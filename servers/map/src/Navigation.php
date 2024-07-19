<?php
/**
 * Created by PhpStorm.
 * User: ivan.miranda
 * Date: 01/11/2017
 * Time: 08:44
 */

define('SEA_MAX_X', 459);
define('SEA_MAX_Y', 359);

class Navigation
{

    public $no_navigable;
    public $fog;
    public $last_fog_update = 0;
    public $wind;
    public $last_wind_update = 0;
    public $fixed_chains;
    public $variable_chains;
    public $last_chain_update = 0;
    public $rdms;
    public $last_rdm_update = 0;
    public $fixed_swirls;
    public $variable_swirls;
    public $last_swirls_update = 0;
    public $nps = array();
    public $last_nps_update = 0;
    public $last_nps_respawn = 0;
    public $islands = array();
    public $last_hide_players = 0;

    /**
     * @var mywrap_con
     */
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;

        $this->no_navigable = MapLoader::load("mapa_nao_navegavel");
        $this->fog = MapLoader::load("mapa_nevoa");
        $this->fixed_chains = MapLoader::load("mapa_corrente");
        $this->fixed_swirls = MapLoader::load("mapa_redemoinho");
        $islands = \Utils\Data::load("mundo")["ilhas"];

        foreach ($islands as $island) {
            $this->islands[$island["x"]][$island["y"]] = $island["ilha"];

            if (isset($this->no_navigable[$island["x"]]) && isset($this->no_navigable[$island["x"]][$island["y"]])) {
                unset($this->no_navigable[$island["x"]][$island["y"]]);
            }
        }
    }

    public function collide($point, $lvl = 15)
    {
        return (isset($this->no_navigable[$point["x"]])
            && isset($this->no_navigable[$point["x"]][$point["y"]]))
            || ($lvl < 15
                && isset($this->fixed_chains[$point["x"]])
                && isset($this->fixed_chains[$point["x"]][$point["y"]]));
    }

    function distance($origem, $destino)
    {
        return sqrt(pow($origem["x"] - $destino["x"], 2) + pow($origem["y"] - $destino["y"], 2));
    }

    function get_fog($x, $y)
    {
        return isset($this->fog[$x]) && isset($this->fog[$x][$y]) ? $this->fog[$x][$y] : 0;
    }

    function update()
    {
        $this->update_wind();
        $this->update_chain();
        $this->update_swirl();
        $this->update_rdm();
        $this->update_fog();
        $this->update_nps();
        $this->respawn_nps();
        $this->hide_inative_players();
    }

    function hide_inative_players()
    {
        // if ($this->last_hide_players > (atual_segundo() - 60 * 60)) {
        //     return;
        // }
        // $this->last_hide_players = atual_segundo();
        // $this->connection->run("UPDATE tb_usuarios SET mar_visivel = 0, navegacao_destino = null, navegacao_inicio = null, navegacao_fim = null WHERE ultimo_logon < ?", "i", [
        //     atual_segundo() - (24 * 60 * 60)
        // ]);
    }

    function update_nps()
    {
        if ($this->last_nps_update > (atual_segundo() - 10)) {
            return;
        }
        $this->last_nps_update = atual_segundo();

        $mercadores = $this->connection->run(
            "SELECT c.mercador_id, c.x, c.y, m.ilha_destino
             FROM tb_mapa_contem c
             INNER JOIN tb_ilha_mercador m ON c.mercador_id = m.id
             WHERE c.mercador_id IS NOT null "
        )->fetch_all_array();

        foreach ($mercadores as $mercador) {
            $ilha_destino = \Regras\Ilhas::get_ilha($mercador["ilha_destino"]);
            $mercador["destino_x"] = $ilha_destino["x"];
            $mercador["destino_y"] = $ilha_destino["y"];
            $nps = array(
                "id" => 100000 + $mercador["mercador_id"],
                "x" => $mercador["x"],
                "y" => $mercador["y"],
                "icon" => 4,
                "rdm_id" => 90,
                "move_x" => $mercador["x"] > $mercador["destino_x"] ? -1 : ($mercador["x"] < $mercador["destino_x"] ? 1 : 0),
                "move_y" => $mercador["y"] > $mercador["destino_y"] ? -1 : ($mercador["y"] < $mercador["destino_y"] ? 1 : 0),
                "nome" => "Navio Mercador"
            );
            if (! $nps["move_x"] && ! $nps["move_y"]) {
                unset($this->nps[$mercador["x"]][$mercador["y"]]);
                $this->connection->run("DELETE FROM tb_mapa_contem WHERE mercador_id = ?",
                    "i", array($mercador["mercador_id"]));
                $this->connection->run("UPDATE tb_ilha_mercador SET finalizou = 1 WHERE id = ?",
                    "i", array($mercador["mercador_id"]));
            } else {
                $this->nps[$mercador["x"]][$mercador["y"]] = $nps;
                $this->connection->run("UPDATE tb_mapa_contem SET x = ?, y = ? WHERE mercador_id = ?",
                    "iii", array($nps["x"] + $nps["move_x"], $nps["y"] + $nps["move_y"], $mercador["mercador_id"]));
            }
        }

        foreach ($this->nps as $x => $y_quad) {
            foreach ($y_quad as $y => $nps) {
                if (! $this->collide(array("x" => $nps["x"] + $nps["move_x"], "y" => $nps["y"] + $nps["move_y"]))) {
                    $nps["x"] += $nps["move_x"];
                    $nps["y"] += $nps["move_y"];
                    unset($this->nps[$x][$y]);
                    $this->nps[$nps["x"]][$nps["y"]] = $nps;
                }
                $this->nps[$nps["x"]][$nps["y"]]["move_x"] = rand(-1, 1);
                $this->nps[$nps["x"]][$nps["y"]]["move_y"] = rand(-1, 1);
            }
        }
    }

    function respawn_nps()
    {
        if ($this->last_nps_respawn > (atual_segundo() - 5 * 60)) {
            return;
        }
        $this->last_nps_respawn = atual_segundo();

        $nps_data = DataLoader::load("nps");
        $rdm_data = DataLoader::load("rdm");

        for ($mar = 1; $mar <= 6; $mar++) {
            $nps_count = 0;
            foreach ($this->nps as $x => $y_quad) {
                foreach ($y_quad as $y => $nps) {
                    if (get_mar($x, $y) == $mar) {
                        $nps_count++;
                    }
                }
            }

            $total = 200;
            for ($i = 0; $i < $total - $nps_count; $i++) {
                if ($mar == 1) {
                    $x = rand(290, 430);
                    $y = rand(20, 95);
                    $npss = [1, 2, 3, 8];
                } elseif ($mar == 2) {
                    $x = rand(30, 165);
                    $y = rand(20, 95);
                    $npss = [1, 2, 3, 8];
                } elseif ($mar == 3) {
                    $x = rand(288, 430);
                    $y = rand(266, 345);
                    $npss = [1, 2, 3, 8];
                } elseif ($mar == 4) {
                    $x = rand(30, 165);
                    $y = rand(266, 345);
                    $npss = [1, 2, 3, 8];
                } elseif ($mar == 5) {
                    $x = rand(291, 430);
                    $y = rand(106, 254);
                    $npss = [3, 4, 5, 8];
                } else {
                    $x = rand(30, 165);
                    $y = rand(106, 254);
                    $npss = [5, 6, 7, 8];
                }

                if (! $this->collide(array("x" => $x, "y" => $y))) {
                    $nps = $nps_data[$npss[array_rand($npss)]];
                    $this->nps[$x][$y] = array(
                        "id" => (atual_segundo() * 1000) + $i,
                        "x" => $x,
                        "y" => $y,
                        "icon" => $nps["icon"],
                        "rdm_id" => $nps["rdm_id"],
                        "move_x" => rand(-1, 1),
                        "move_y" => rand(-1, 1),
                        "nome" => $rdm_data[$nps["rdm_id"]]["nome"]
                    );
                }
            }
        }
    }

    function update_fog()
    {
        if ($this->last_fog_update > (atual_segundo() - 5 * 60)) {
            return;
        }
        $this->last_fog_update = atual_segundo();

        $this->fog = MapLoader::load("mapa_nevoa");

        for ($i = 0; $i < 30; $i++) {
            $fog_x = rand(0, SEA_MAX_X);
            $fog_y = rand(0, SEA_MAX_Y);

            $fog_range = rand(1, 5);
            for ($x = $fog_x - $fog_range; $x <= $fog_x + $fog_range; $x++) {
                for ($y = $fog_y - $fog_range; $y <= $fog_y + $fog_range; $y++) {
                    if (! $this->get_fog($x, $y)) {
                        $this->fog[$x][$y] = max(10, (1 - ($this->distance(array("x" => $fog_x, "y" => $fog_y), array("x" => $x, "y" => $y)) / $fog_range)) * 100);
                    }
                }
            }
        }
    }

    function clear_rdm($x, $y)
    {
        $rdm = $this->get_rdm($x, $y);
        if ($rdm && $rdm["removivel"]) {
            unset($this->rdms[$x][$y]);
        }
    }

    function update_rdm()
    {
        if ($this->last_rdm_update > (atual_segundo() - 60)) {
            return;
        }
        $this->last_rdm_update = atual_segundo();

        $this->rdms = array();

        for ($x = 0; $x < SEA_MAX_X; $x++) {
            for ($y = 0; $y < SEA_MAX_Y; $y++) {
                if (($x <= 176 && $y >= 96 && $y <= 104)
                    || $x >= 283 && $y >= 96 && $y <= 104
                    || ($x <= 176 && $y >= 256 && $y <= 264)
                    || $x >= 283 && $y >= 256 && $y <= 264
                ) {
                    $this->rdms[$x][$y] = array(
                        "id" => 0,
                        "chance" => 100,
                        "ameaca" => 2,
                        "removivel" => false
                    );
                } elseif (
                    ! $this->collide(array("x" => $x, "y" => $y))
                    && ! $this->get_chain($x, $y)
                    && rand(0, 100) <= 5
                ) {
                    $mar = get_mar($x, $y);
                    $this->rdms[$x][$y] = array(
                        "id" => $mar <= 4 ? rand(1, 3) : ($mar == 5 ? rand(1, 6) : rand(4, 8)),
                        "chance" => 25,
                        "ameaca" => 0,
                        "removivel" => true
                    );
                }
            }
        }

        $special_rdm = $this->connection->run("SELECT * FROM tb_mapa_rdm")->fetch_all_array();

        foreach ($special_rdm as $rdm) {
            $this->rdms[$rdm["x"]][$rdm["y"]] = array(
                "id" => $rdm["rdm_id"],
                "chance" => 100,
                "ameaca" => $rdm["ameaca"],
                "removivel" => false
            );
        }
    }

    private function _get_continous_effects($min, $max)
    {
        $effects = [];
        $amount = rand($min, $max);

        $direction_increment = [
            0 => ["x" => 0, "y" => -1],
            1 => ["x" => 1, "y" => -1],
            2 => ["x" => 1, "y" => 0],
            3 => ["x" => 1, "y" => 1],
            4 => ["x" => 0, "y" => 1],
            5 => ["x" => -1, "y" => 1],
            6 => ["x" => -1, "y" => 0],
            7 => ["x" => -1, "y" => -1],
        ];

        $turned = 0;

        for ($i = 0; $i < $amount; $i++) {
            $size = rand(5, 30);

            $power = rand(0, 100) / 100.0;

            $x = rand(0, SEA_MAX_X);
            $y = rand(0, SEA_MAX_Y);
            $direction = rand(0, 7);
            for ($j = 0; $j < $size; $j++) {
                if (! $this->collide(array("x" => $x, "y" => $y))) {
                    $power = min(max($power + (rand(-10, 10) / 100.0), 0.2), 1.0);
                    $effects[$x][$y] = array(
                        "direcao" => $direction,
                        "power" => $power
                    );
                }
                $x += $direction_increment[$direction]["x"];
                $y += $direction_increment[$direction]["y"];

                if ($x < 0) {
                    $x += SEA_MAX_X;
                } elseif ($x > SEA_MAX_X) {
                    $x -= SEA_MAX_X;
                }

                if ($y < 0) {
                    $y += SEA_MAX_Y;
                } elseif ($y > SEA_MAX_Y) {
                    $y -= SEA_MAX_Y;
                }

                $turn_rand = rand(0, 100);
                if ($turn_rand < ($turned < 0 ? 40 : 20)) {
                    $turned = -1;
                } elseif ($turn_rand > ($turned > 0 ? 60 : 80)) {
                    $turned = 1;
                } else {
                    $turned = 0;
                }
                $direction += $turned;

                if ($direction < 0) {
                    $direction = 8 + $direction;
                } elseif ($direction > 7) {
                    $direction = $direction - 8;
                }
            }
        }

        return $effects;
    }
    private function _get_effect_block($min, $max)
    {
        $effects = [];
        $amount = rand($min, $max);

        for ($a = 0; $a < $amount; $a++) {
            $size_x = rand(5, 30);
            $size_y = rand(5, 30);

            $x_start = rand(0, SEA_MAX_X);
            $y_start = rand(0, SEA_MAX_Y);

            $power = rand(0, 100) / 100.0;

            $direction = rand(0, 7);
            for ($i = 0; $i < $size_x; $i++) {
                for ($j = 0; $j < $size_y; $j++) {
                    $x = $x_start + $i;
                    $y = $y_start + $j;
                    if ($x > SEA_MAX_X) {
                        $x -= SEA_MAX_X;
                    }
                    if ($y > SEA_MAX_Y) {
                        $y -= SEA_MAX_Y;
                    }

                    if (! $this->collide(array("x" => $x, "y" => $y))) {
                        $power = min(max($power + (rand(-10, 10) / 100.0), 0.2), 1.0);
                        $effects[$x][$y] = array(
                            "direcao" => $direction,
                            "power" => $power
                        );
                    }

                }
            }
        }

        return $effects;
    }

    function update_chain()
    {
        if ($this->last_chain_update > (atual_segundo() - 5 * 60)) {
            return;
        }
        $this->last_chain_update = atual_segundo();

        $this->variable_chains = $this->_get_continous_effects(2000, 5000);
    }

    function update_wind()
    {
        if ($this->last_wind_update > (atual_segundo() - 5 * 60)) {
            return;
        }
        $this->last_wind_update = atual_segundo();

        $this->wind = $this->_get_effect_block(200, 500);
    }

    function update_swirl()
    {
        if ($this->last_swirls_update > (atual_segundo() - 5 * 60)) {
            return;
        }
        $this->last_swirls_update = atual_segundo();

        $this->variable_swirls = array();

        for ($x = 0; $x < SEA_MAX_X; $x++) {
            for ($y = 0; $y < SEA_MAX_Y; $y++) {
                if (! $this->collide(array("x" => $x, "y" => $y))
                    && ! $this->get_chain($x, $y)
                    && rand(0, 100) <= 2
                ) {
                    $this->variable_swirls[$x][$y] = true;
                }
            }
        }
    }

    function get_wind($x, $y)
    {
        return isset($this->wind[$x]) && isset($this->wind[$x][$y]) ? $this->wind[$x][$y] : null;
    }

    function get_navigator_wind($x, $y, $navegador_lvl = false)
    {
        if (! $navegador_lvl) {
            $navegador_lvl = 1;
        }

        $wind = isset($this->wind[$x]) && isset($this->wind[$x][$y]) ? $this->wind[$x][$y] : null;
        if ($wind && ($wind["power"] * 10) <= $navegador_lvl) {
            return $wind;
        }

        return null;
    }

    function get_chains($x, $y, $distance_x, $distance_y, $navegador_lvl = false)
    {
        $chains = array();
        $dx = round($distance_x / 2);
        $dy = round($distance_y / 2);

        if (! $navegador_lvl) {
            $navegador_lvl = 1;
        }

        for ($px = max(0, $x - $dx); $px <= min(SEA_MAX_X, $x + $dx); $px++) {
            for ($py = max(0, $y - $dy); $py <= min(SEA_MAX_Y, $y + $dy); $py++) {
                if (isset($this->fixed_chains[$px]) && isset($this->fixed_chains[$px][$py])) {
                    $chains[] = array_merge(array(
                        "x" => $px,
                        "y" => $py
                    ), $this->fixed_chains[$px][$py]);
                } elseif (isset($this->variable_chains[$px]) && isset($this->variable_chains[$px][$py])) {
                    if (($this->variable_chains[$px][$py]["power"] * 10) <= $navegador_lvl) {
                        $chains[] = array_merge(array(
                            "x" => $px,
                            "y" => $py
                        ), $this->variable_chains[$px][$py]);
                    }
                }
            }
        }
        return $chains;
    }

    function get_chain($x, $y)
    {
        return isset($this->fixed_chains[$x]) && isset($this->fixed_chains[$x][$y]) ? $this->fixed_chains[$x][$y] :
            (isset($this->variable_chains[$x]) && isset($this->variable_chains[$x][$y]) ? $this->variable_chains[$x][$y] : null);
    }

    function get_swirls($x, $y, $distance_x, $distance_y)
    {
        $swirls = array();
        $dx = round($distance_x / 2);
        $dy = round($distance_y / 2);

        for ($px = max(0, $x - $dx); $px <= min(SEA_MAX_X, $x + $dx); $px++) {
            for ($py = max(0, $y - $dy); $py <= min(SEA_MAX_Y, $y + $dy); $py++) {
                if (isset($this->fixed_swirls[$px]) && isset($this->fixed_swirls[$px][$py])) {
                    $swirls[] = array(
                        "x" => $px,
                        "y" => $py
                    );
                } elseif (isset($this->variable_swirls[$px]) && isset($this->variable_swirls[$px][$py])) {
                    $swirls[] = array(
                        "x" => $px,
                        "y" => $py
                    );
                }
            }
        }
        return $swirls;
    }

    function get_swirl($x, $y)
    {
        return isset($this->fixed_swirls[$x]) && isset($this->fixed_swirls[$x][$y]) ? $this->fixed_swirls[$x][$y] :
            (isset($this->variable_swirls[$x]) && isset($this->variable_swirls[$x][$y]) ? $this->variable_swirls[$x][$y] : null);
    }

    function get_rdms($x, $y, $distance_x, $distance_y)
    {
        $rdms = array();
        $dx = round($distance_x / 2);
        $dy = round($distance_y / 2);

        for ($px = max(0, $x - $dx); $px <= min(SEA_MAX_X, $x + $dx); $px++) {
            for ($py = max(0, $y - $dy); $py <= min(SEA_MAX_Y, $y + $dy); $py++) {
                if (isset($this->rdms[$px]) && isset($this->rdms[$px][$py])) {
                    $rdms[] = array(
                        "x" => $px,
                        "y" => $py,
                        "ameaca" => $this->rdms[$px][$py]["ameaca"]
                    );
                }
            }
        }
        return $rdms;
    }

    function get_rdm($x, $y)
    {
        return isset($this->rdms[$x]) && isset($this->rdms[$x][$y]) ? $this->rdms[$x][$y] : null;
    }

    function get_npss($x, $y, $distance_x, $distance_y)
    {
        $nps = array();
        $dx = $distance_x;
        $dy = $distance_y;

        for ($px = max(0, $x - $dx); $px <= min(SEA_MAX_X, $x + $dx); $px++) {
            for ($py = max(0, $y - $dy); $py <= min(SEA_MAX_Y, $y + $dy); $py++) {
                $np = $this->get_nps($px, $py);
                if ($np) {
                    $nps[] = array_merge(array(
                        "x" => $px,
                        "y" => $py
                    ), $np);
                }
            }
        }
        return $nps;
    }

    function get_nps($x, $y)
    {
        return isset($this->nps[$x]) && isset($this->nps[$x][$y]) ? $this->nps[$x][$y] : null;
    }

    function clear_nps($x, $y)
    {
        if (isset($this->nps[$x]) && isset($this->nps[$x][$y])) {
            unset($this->nps[$x][$y]);
        }
    }

    function get_islands($x, $y, $distance_x, $distance_y, $connection)
    {
        $islands = array();
        $dx = $distance_x;
        $dy = $distance_y;

        for ($px = max(0, $x - $dx); $px <= min(SEA_MAX_X, $x + $dx); $px++) {
            for ($py = max(0, $y - $dy); $py <= min(SEA_MAX_Y, $y + $dy); $py++) {
                $island = $this->get_island($px, $py);
                if ($island) {
                    $island_govern = $connection->run(
                        "SELECT u.tripulacao, u.faccao, u.bandeira
                         FROM tb_mapa m
                         LEFT JOIN tb_usuarios u ON m.ilha_dono = u.id
                         WHERE m.ilha = ?",
                        "i", array($island)
                    )->fetch_array();
                    $islands[] = array(
                        "x" => $px,
                        "y" => $py,
                        "island" => $island,
                        "island_name" => nome_ilha($island),
                        "govern" => $island_govern
                    );
                }
            }
        }
        return $islands;
    }

    function get_island($x, $y)
    {
        return isset($this->islands[$x]) && isset($this->islands[$x][$y]) ? $this->islands[$x][$y] : null;
    }
}
