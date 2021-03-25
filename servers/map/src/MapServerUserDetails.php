<?php

/**
 * Created by PhpStorm.
 * User: ivan.miranda
 * Date: 20/10/2017
 * Time: 08:22
 */
class MapServerUserDetails extends UserDetails {
    /**
     * @var mywrap_con
     */
    private $connection;

    private $sg_c;
    private $sg_k;

    private $distancia_visao = array(
        "x" => 17,
        "y" => 10
    );

    /**
     * MapServerUserDetails constructor.
     * @param $connection
     * @param $sg_c
     * @param $sg_k
     */
    public function __construct($connection, $sg_c, $sg_k) {
        $this->connection = $connection;
        $this->sg_c = $sg_c;
        $this->sg_k = $sg_k;

        parent::__construct($connection);
    }

    protected function _get_token() {
        return array(
            "id_encrypted" => $this->sg_c,
            "token" => $this->sg_k
        );
    }

    protected function _update_last_logon() {
        //np
    }

    public function get_public_data($id = null) {
        if (!$id) {
            $id = $this->tripulacao["id"];
        }

        $me = $this->connection->run(
            "SELECT 
            u.id,
            u.tripulacao,
            u.bandeira,
            u.faccao,
            u.skin_navio,
            u.direcao_navio,
            u.reputacao,
            u.reputacao_mensal,
            u.x,
            u.y,
            u.mar_visivel,
            u.adm,
            u.navegacao_destino AS navegando,
            u.coup_de_burst_usado,
            (unix_timestamp(current_timestamp(3)) - u.navegacao_inicio) / (u.navegacao_fim - u.navegacao_inicio) AS navegacao_progresso,
            u.navegacao_fim - unix_timestamp(current_timestamp(3)) AS navegacao_restante, 
            p.nome AS capitao_nome,
            p.img AS capitao_img,
            p.skin_r AS capitao_skin_r,
            p.fama_ameaca AS capitao_wanted,
            IF(p.sexo = 0, t.nome, t.nome_f) AS capitao_titulo,
            (SELECT max(lvl) FROM tb_personagens WHERE id = u.id AND p.ativo = 1) AS lvl_mais_forte,
            n.cod_canhao AS canhao,
            (SELECT quant FROM tb_usuario_itens WHERE id = u.id AND tipo_item = 13) AS canhao_balas,
            (n.hp_teste / n.hp_max) AS hp_navio,
            v.luneta,
            v.coup_de_burst,
            u.kai AS kairouseki_ativo,
            c.kairouseki AS has_kairouseki,
            m.ilha
            FROM tb_usuarios u
            INNER JOIN tb_personagens p ON u.cod_personagem = p.cod
            LEFT JOIN tb_titulos t ON p.titulo=t.cod_titulo
            INNER JOIN tb_usuario_navio n ON n.id = u.id
            INNER JOIN tb_vip v ON u.id = v.id
            LEFT JOIN tb_item_navio_casco c ON n.cod_casco = c.cod_casco
            LEFT JOIN tb_mapa m ON u.x = m.x AND u.y = m.y
            WHERE u.id = ?",
            "i", array($id)
        )->fetch_array();

        $me['is_adm']           = $me['adm'] > 0;
        $me["location"]         = get_human_location($me["x"], $me["y"]);
        $me["destino_mar"]      = nome_mar(get_mar($me["x"], $me["y"]));
        $me["destino_ilha"]     = nome_ilha($me["ilha"]);
        $me['coup_de_burst']    = $me['coup_de_burst'] < 0 ? 0 : $me['coup_de_burst'];
        $me['poder_batalha']    = 0;

        unset($me['adm']);

        return $me;
    }

    public function get_field_of_view(Navigation $navigation) {
        $data = array(
            "me" => $this->get_public_data(),
            "players" => $this->connection->run(
                "SELECT 
                u.id,
                u.tripulacao,
                u.bandeira,
                u.faccao,
                u.skin_navio,
                u.direcao_navio,
                u.reputacao,
                u.reputacao_mensal,
                u.x,
                u.y,
                u.adm,
                u.navegacao_destino AS navegando,
                u.coup_de_burst_usado,
                (unix_timestamp(current_timestamp(3)) - u.navegacao_inicio) / (u.navegacao_fim - u.navegacao_inicio) AS navegacao_progresso,
                u.navegacao_fim - unix_timestamp(current_timestamp(3)) AS navegacao_restante, 
                p.nome AS capitao_nome,
                p.img AS capitao_img,
                p.skin_r AS capitao_skin_r,
                p.fama_ameaca AS capitao_wanted,
                IF(p.sexo = 0, t.nome, t.nome_f) AS capitao_titulo,
                (SELECT max(lvl) FROM tb_personagens WHERE id = u.id AND p.ativo = 1) AS lvl_mais_forte,
                (n.hp_teste / n.hp_max) AS hp_navio
                FROM tb_usuarios u
                INNER JOIN tb_personagens p ON u.cod_personagem = p.cod
                LEFT JOIN tb_titulos t ON p.titulo=t.cod_titulo
                INNER JOIN tb_usuario_navio n ON n.id = u.id
                WHERE u.x >= ? AND u.x <= ? AND u.y >= ? AND u.y <= ? AND u.mar_visivel = 1 AND u.id <> ?",
                "iiiii", array(
                    $this->tripulacao["x"] - $this->distancia_visao["x"],
                    $this->tripulacao["x"] + $this->distancia_visao["x"],
                    $this->tripulacao["y"] - $this->distancia_visao["y"],
                    $this->tripulacao["y"] + $this->distancia_visao["y"],
                    $this->tripulacao["id"]
                )
            )->fetch_all_array(),
            "map" => array(
                "fog" => $navigation->get_fog($this->tripulacao["x"], $this->tripulacao["y"]),
                "chains" => $navigation->get_chains($this->tripulacao["x"], $this->tripulacao["y"], $this->distancia_visao["x"], $this->distancia_visao["y"]),
                "rdms" => $navigation->get_rdms($this->tripulacao["x"], $this->tripulacao["y"], $this->distancia_visao["x"], $this->distancia_visao["y"]),
                "swirls" => $navigation->get_swirls($this->tripulacao["x"], $this->tripulacao["y"], $this->distancia_visao["x"], $this->distancia_visao["y"]),
                "wind" => $navigation->get_wind($this->tripulacao["x"], $this->tripulacao["y"]),
                "islands" => $navigation->get_islands($this->tripulacao["x"], $this->tripulacao["y"], $this->distancia_visao["x"], $this->distancia_visao["y"], $this->connection)
            ),
            "nps" => $navigation->get_npss($this->tripulacao["x"], $this->tripulacao["y"], $this->distancia_visao["x"], $this->distancia_visao["y"])
        );

        for ($x = 0; $x < count($data["players"]); $x++) {
            $data["players"][$x]['is_adm']                      = $data["players"][$x]['adm'] > 0;
            $data["players"][$x]["reputacao_vitoria"]           = calc_reputacao($data["me"]["reputacao"], $data["players"][$x]["reputacao"], $data["me"]["lvl_mais_forte"], $data["players"][$x]["lvl_mais_forte"]);
            $data["players"][$x]["reputacao_mensal_vitoria"]    = calc_reputacao($data["me"]["reputacao_mensal"], $data["players"][$x]["reputacao_mensal"], $data["me"]["lvl_mais_forte"], $data["players"][$x]["lvl_mais_forte"]);
            $data["players"][$x]["poder_batalha"]               = 0;

            unset($data["players"][$x]['adm']);
        }

        return $data;
    }

    public function travel($destino, Navigation $navigation) {
        unset($this->combate_pvp);
        unset($this->combate_bot);
        unset($this->combate_pve);

        if ($navigation->collide(
            $this->project_next_position(
                $this->get_direction($this->tripulacao, $destino)
            ), $this->capitao["lvl"]) || $this->in_combate) {
            return false;
        }

        $ja_navegando = !!$this->tripulacao["navegacao_destino"];
        $tempo_calculado = !!$this->tripulacao["navegacao_fim"];

        if ($ja_navegando && $tempo_calculado) {
            $this->connection->run("UPDATE tb_usuarios SET navegacao_destino = ? WHERE id = ?",
                "si", array($destino["x"] . "_" . $destino["y"], $this->tripulacao["id"]));
        } else {
            $chain = $navigation->get_chain($this->tripulacao["x"], $this->tripulacao["y"]);
            // corrente automatica onde o jogador esta
            if ($chain && isset($chain["intensidade"])) {
                $future_position = $this->project_next_position($chain["direcao"], $this->tripulacao);

                $this->connection->run("UPDATE tb_usuarios SET navegacao_destino = ?, direcao_navio = ?, navegacao_inicio = unix_timestamp(current_timestamp(3)), navegacao_fim = unix_timestamp(current_timestamp(3)) + ? WHERE id = ? AND navegacao_fim < unix_timestamp(current_timestamp(3))",
                    "sidi", array($future_position["x"] . "_" . $future_position["y"], $chain["direcao"], $chain["intensidade"], $this->tripulacao["id"]));
            } else {
                $direction = $this->get_direction($this->tripulacao, $destino);
                $this->connection->run("UPDATE tb_usuarios SET navegacao_destino = ?, direcao_navio=?, navegacao_inicio = unix_timestamp(current_timestamp(3)), navegacao_fim = unix_timestamp(current_timestamp(3)) + ? WHERE id = ?",
                    "sidi", array($destino["x"] . "_" . $destino["y"], $direction, $this->calc_travel_speed($direction, $navigation), $this->tripulacao["id"]));
                $this->update_coup_de_burst();

            }
        }
        unset($this->tripulacao);
        return !$ja_navegando;
    }

    public function update_coup_de_burst() {
        if ($this->tripulacao["coup_de_burst_usado"]) {
            $this->connection->run("UPDATE tb_usuarios SET coup_de_burst_usado = coup_de_burst_usado - 1 WHERE id = ?",
                "i", array($this->tripulacao["id"]));
            $this->tripulacao["coup_de_burst_usado"] -= 1;
        }
    }

    public function in_field_of_view($tripulacao) {
        return abs($tripulacao["x"] - $this->tripulacao["x"] < $this->distancia_visao["x"])
            && abs($tripulacao["y"] - $this->tripulacao["y"] < $this->distancia_visao["y"]);
    }

    private function get_direction($ship, $destination) {
        if ($destination["x"] < $ship["x"] && $destination["y"] < $ship["y"]) {
            return 7;
        } else if ($destination["x"] < $ship["x"] && $destination["y"] == $ship["y"]) {
            return 6;
        } else if ($destination["x"] < $ship["x"] && $destination["y"] > $ship["y"]) {
            return 5;
        } else if ($destination["x"] == $ship["x"] && $destination["y"] < $ship["y"]) {
            return 0;
        } else if ($destination["x"] == $ship["x"] && $destination["y"] > $ship["y"]) {
            return 4;
        } else if ($destination["x"] > $ship["x"] && $destination["y"] < $ship["y"]) {
            return 1;
        } else if ($destination["x"] > $ship["x"] && $destination["y"] == $ship["y"]) {
            return 2;
        } else if ($destination["x"] > $ship["x"] && $destination["y"] > $ship["y"]) {
            return 3;
        }
        return 0;
    }

    public function update_position(Navigation $navigation) {
        unset($this->combate_pvp);
        unset($this->combate_bot);
        unset($this->combate_pve);
        unset($this->in_combate);

        if ($this->in_combate) {
            $this->connection->run("UPDATE tb_usuarios SET mar_visivel = 0, navegacao_destino = NULL, navegacao_inicio = NULL, navegacao_fim = NULL WHERE id = ? ",
                "i", array($this->tripulacao["id"]));

            unset($this->tripulacao);
            return;
        }

        $next_position = $this->project_next_position();
        if ($next_position["x"] == 278 && $next_position["y"] == 181) {
            $this->connection->run("UPDATE tb_usuarios SET res_x = ?, res_y = ? WHERE id = ?", 'iii', [
                281,    // X
                177,    // Y
                $this->tripulacao['id']
            ]);
        }
        $rdm = $navigation->get_rdm($next_position["x"], $next_position["y"]);

        if (!$this->tripulacao["kai"] && $rdm && rand(1, 100) < $rdm["chance"]) {
            $this->connection->run("UPDATE tb_usuarios SET mar_visivel = 0, navegacao_destino = NULL, navegacao_inicio = NULL, navegacao_fim = NULL WHERE id = ?",
                "i", array($this->tripulacao["id"]));

            atacar_rdm($rdm["id"], $this, $this->connection);
            $navigation->clear_rdm($next_position["x"], $next_position["y"]);
        } else {
            $chain = $navigation->get_chain($next_position["x"], $next_position["y"]);
            $swirl = $navigation->get_swirl($next_position["x"], $next_position["y"]);

            if ($swirl && !$this->tripulacao['adm']) {
                $swirl_demage = rand(0, 25) / 100;
                $this->connection->run("UPDATE tb_usuario_navio SET hp_teste = GREATEST(0, hp_teste - (hp_max * {$swirl_demage})) WHERE id = ?",
                    "i", array($this->tripulacao["id"]));
                unset($this->navio);
            }

            if ($chain && isset($chain["intensidade"])) {
                $future_position = $this->project_next_position($chain["direcao"], $next_position);

                $this->connection->run("UPDATE tb_usuarios SET mar_visivel = 1, navegacao_destino = ?, x = ?, y = ?, direcao_navio = ?, navegacao_inicio = unix_timestamp(current_timestamp(3)), navegacao_fim = unix_timestamp(current_timestamp(3)) + ? WHERE id = ? AND navegacao_fim < unix_timestamp(current_timestamp(3))",
                    "siiidi", array($future_position["x"] . "_" . $future_position["y"], $next_position["x"], $next_position["y"], $chain["direcao"], $chain["intensidade"], $this->tripulacao["id"]));
            } else {
                $d = explode("_", $this->tripulacao["navegacao_destino"]);
                $destino = array("x" => $d[0], "y" => $d[1]);

                $this->tripulacao["x"] = $next_position["x"];
                $this->tripulacao["y"] = $next_position["y"];
                $future_position = $this->project_next_position($this->get_direction($next_position, $destino), $next_position);

                if ($navigation->collide($future_position, $this->capitao["lvl"]) || $next_position["x"] . "_" . $next_position["y"] == $this->tripulacao["navegacao_destino"]) {
                    $this->connection->run("UPDATE tb_usuarios SET mar_visivel = 1, x = ?, y = ?, navegacao_destino = NULL, navegacao_inicio = NULL, navegacao_fim = NULL WHERE id = ? AND navegacao_fim < unix_timestamp(current_timestamp(3)) ",
                        "iii", array($next_position["x"], $next_position["y"], $this->tripulacao["id"]));
                } else {
                    $direction = $this->get_direction($next_position, $destino);
                    $this->connection->run("UPDATE tb_usuarios SET mar_visivel = 1, x = ?, y = ?, direcao_navio = ?, navegacao_inicio = unix_timestamp(current_timestamp(3)), navegacao_fim = unix_timestamp(current_timestamp(3)) + ? WHERE id = ? AND navegacao_fim < unix_timestamp(current_timestamp(3))",
                        "iiidi", array($next_position["x"], $next_position["y"], $direction, $this->calc_travel_speed($direction, $navigation), $this->tripulacao["id"]));
                    $this->update_coup_de_burst();
                }
                $this->add_xp_navegacao();

                if (rand(1, 100) <= 2) {
                    if ($this->can_add_item()) {
                        $this->add_item(133, TIPO_ITEM_REAGENT, 1);
                    }
                }
            }
        }

        unset($this->tripulacao);
        unset($this->combate_pve);
    }

    public function add_xp_navegacao() {
        unset($this->navio);
        if ($this->navio["lvl"] < 10) {
            $this->navio["xp"] += 1;
            if ($this->navio["xp"] >= $this->navio["xp_max"]) {
                $this->navio["hp_max"] += 10;
                $this->navio["hp_teste"] = $this->navio["hp_max"];
                $this->navio["xp"] = 0;
                $this->navio["xp_max"] += 250;
                $this->navio["lvl"] += 1;
            }

            $this->connection->run("UPDATE tb_usuario_navio SET xp = ?, xp_max = ?, hp_teste = ?, hp_max = ?, lvl = ? WHERE id = ?",
                "iiiiii", array($this->navio["xp"], $this->navio["xp_max"], $this->navio["hp_teste"], $this->navio["hp_max"], $this->navio["lvl"], $this->tripulacao["id"]));
        }

        $this->xp_for_profissao(1, PROFISSAO_NAVEGADOR);
    }

    public function project_next_position($direction = null, $base_point = null) {
        $direction = $direction ? $direction : $this->tripulacao["direcao_navio"];
        $base_point = $base_point ? $base_point : $this->tripulacao;

        return array(
            "x" => $base_point["x"] + ($direction >= 5 && $direction <= 7 ? -1 : ($direction >= 1 && $direction <= 3 ? 1 : 0)),
            "y" => $base_point["y"] + ($direction >= 7 || $direction <= 1 ? -1 : ($direction >= 3 && $direction <= 5 ? 1 : 0))
        );
    }

    public function calc_travel_speed($direction, Navigation $navigation) {
        $base_speed = $direction % 2 == 0 ? 1.5 : 2.25;

        $most_slow_possible = 3;
        $base_speed += (1 - ($this->navio["hp_teste"] / $this->navio["hp_max"])) * $most_slow_possible;

        $wind = $navigation->get_wind($this->tripulacao["x"], $this->tripulacao["y"]);
        $wind_modifier = 0;
        if ($wind && $wind["direction"] == $direction) {
            if ($this->navio["cod_velas"]) {
                $wind["power"] += 0.1;
            }
            $wind_modifier = $base_speed * ($wind["power"] * 0.5 + 0.2);
        }

        $chain = $navigation->get_chain($this->tripulacao["x"], $this->tripulacao["y"]);
        $chain_modifier = 0;
        if ($chain && isset($chain["power"]) && $chain["direcao"] == $direction) {
            if ($this->navio["cod_leme"]) {
                $chain["power"] += 0.1;
            }
            $chain_modifier = $base_speed * ($chain["power"] * 0.5 + 0.2);
        }

        $base_speed = max(0.1, $base_speed - $chain_modifier - $wind_modifier);

        if ($reduction = $this->buffs->get_efeito("aumento_velocidade_barco")) {
            $base_speed -= $reduction * $base_speed;
        }

        if ($this->tripulacao["coup_de_burst_usado"]) {
            $base_speed /= 2;
        }

        if ($this->tripulacao['adm'] > 0) {
            $base_speed /= 2;
        }

        return $base_speed;
    }

    public function heal_ship() {
        if (atual_segundo() < $this->navio["ultima_cura"] + 5) {
            return 0;
        }

        unset($this->navio);

        $percent = (rand(5, 10) + $this->lvl_carpinteiro) / 100;
        if ($this->tripulacao['adm'] > 0) {
            $percent = 1;
        }

        $cura = round($percent * $this->navio["hp_max"]);
        $this->navio["hp_teste"] = min($this->navio["hp_max"], $this->navio["hp_teste"] + $cura);

        $this->connection->run("UPDATE tb_usuario_navio SET hp_teste = ?, ultima_cura = ? WHERE id = ?",
            "iii", array($this->navio["hp_teste"], atual_segundo(), $this->tripulacao["id"]));
        $this->navio["ultima_cura"] = atual_segundo();

        unset($this->tripulacao);
        return $cura;
    }

    public function dispara($target, Navigation $navigation) {
        unset($this->has_ilha_envolta_me);
        if (atual_segundo() < $this->navio["ultimo_disparo"] + 5 || $this->has_ilha_envolta_me) {
            return NULL;
        }

        if (!$this->navio['cod_canhao'])
            return NULL;

        if (abs($target->x - $this->tripulacao["x"] > $this->distancia_visao) ||
            abs($target->y - $this->tripulacao["y"]) > $this->distancia_visao
        ) {
            return NULL;
        }

        $alvos = $this->connection->run(
            "SELECT u.x, u.y, n.* 
             FROM tb_usuarios u
             INNER JOIN tb_usuario_navio n ON u.id = n.id
             WHERE u.x = ? AND u.y = ? AND u.id <> ? AND u.mar_visivel = 1",
            "iii", array(
                $target->x,
                $target->y,
                $this->tripulacao["id"]
            )
        )->fetch_all_array();

        $damages = array();
        foreach ($alvos as $alvo) {
            $damage = $this->_apply_damage_canhao($alvo, $navigation);
            if ($damage) {
                $damages[] = $damage;
            }
        }

        $this->_update_ultimo_disparo();

        return $damages;
    }

    public function dispara_target($target_id, Navigation $navigation) {
        unset($this->has_ilha_envolta_me);
        if (atual_segundo() < $this->navio["ultimo_disparo"] + 5)   return NULL;
        if ($this->has_ilha_envolta_me)                             return NULL;
        if ($target_id == $this->tripulacao["id"])                  return NULL;

        $alvo = $this->connection->run(
            "SELECT u.x, u.y, n.* 
             FROM tb_usuarios u
             INNER JOIN tb_usuario_navio n ON u.id = n.id
             WHERE u.id = ? AND u.mar_visivel = 1",
            "i", array($target_id)
        );

        if (!$alvo->count()) {
            return NULL;
        }

        $alvo = $alvo->fetch_array();

        if (abs($alvo["x"] - $this->tripulacao["x"] > $this->distancia_visao) ||
            abs($alvo["y"] - $this->tripulacao["y"]) > $this->distancia_visao
        ) {
            return NULL;
        }

        $damages = array();
        $damage = $this->_apply_damage_canhao($alvo, $navigation);
        if ($damage) {
            $damages[] = $damage;
        }

        $this->_update_ultimo_disparo();

        return array("damages" => $damages, "destination" => array(
            "x" => $alvo["x"],
            "y" => $alvo["y"]
        ));
    }

    private function _update_ultimo_disparo() {
        $this->connection->run("UPDATE tb_usuarios SET mar_visivel = '1' WHERE id = ?", "i", [
            $this->tripulacao["id"]
        ]);

        $this->connection->run("UPDATE tb_usuario_navio SET ultimo_disparo = ? WHERE id = ?", "ii", [
            atual_segundo(),
            $this->tripulacao["id"]
        ]);


        --$this->navio["canhao_balas"];
        if ($this->navio["canhao_balas"] > 0) {
            $this->connection->run("UPDATE tb_usuario_itens SET quant = quant - 1 WHERE id = ? AND tipo_item = 13", "i", [
                $this->tripulacao["id"]
            ]);
        } else {
            $this->connection->run("DELETE FROM tb_usuario_itens WHERE id = ? AND tipo_item = 13", "i", [
                $this->tripulacao["id"]
            ]);
            $this->navio["canhao_balas"] = 0;
        }

        $this->navio["ultimo_disparo"] = atual_segundo();

        unset($this->tripulacao);
    }

    private function _apply_damage_canhao($alvo, Navigation $navigation) {
        $damages = null;
        if (!count($navigation->get_islands($alvo["x"], $alvo["y"], 2, 2, $this->connection)) && rand(1, 100) > 30) {
            $damage = rand(15, 35);
            $new_hp = max(0, $alvo["hp_teste"] - $damage);

            $this->connection->run("UPDATE tb_usuario_navio SET hp_teste = ?, ultimo_disparo_sofrido = ? WHERE id = ?",
                "iii", array($new_hp, atual_segundo(), $alvo["id"]));

            $damages = array(
                "id" => $alvo["id"],
                "hp_navio" => $new_hp / $alvo["hp_max"],
                "hp_reduzida" => $damage
            );
        }

        return $damages;
    }

    function attack_target($target_id, $type, Navigation $navigation) {
        $result = $this->connection->run("SELECT * FROM tb_usuarios WHERE id = ? AND mar_visivel = 1", "i", $target_id);
        if (!$result->count()) {
            return NULL;
        }

        $target = $result->fetch_array();
        if (distancia($this->tripulacao, [
            "x" => $target["x"],
            "y" => $target["y"]
        ]) > 2) {
            return FALSE;
        }

        if (count($navigation->get_islands($target['x'], $target['y'], 2, 2, $this->connection)) > 0) {
            return FALSE;
        }

        $capitaoAlvo = $this->connection->run("SELECT * FROM tb_personagens WHERE cod = ? LIMIT 1", "i", $target['cod_personagem'])->fetch_array();
        if ($capitaoAlvo['lvl'] < 10) {
            return FALSE;
        }

        if ($this->connection->run("SELECT * FROM tb_combate WHERE id_1 = ? OR id_2 = ?", "ii", [
            $target_id,
            $target_id
        ])->count()) {
            return FALSE;
        }
        if ($this->connection->run("SELECT * FROM tb_combate_npc WHERE id = ?", "i", $target_id)->count()) {
            return FALSE;
        }
        if ($this->connection->run("SELECT * FROM tb_combate_bot WHERE tripulacao_id = ?", "i", $target_id)->count()) {
            return FALSE;
        }
        if ($target["adm"]) {
            return FALSE;
        }
        if (!$target["mar_visivel"]) {
            return FALSE;
        }
        if (!can_attack($target)) {
            return FALSE;
        }

        print_r($target);

        return TRUE;
    }

    function atacar_nps($target, Navigation $navigation) {
        if (distancia($this->tripulacao, array("x" => $target->x, "y" => $target->y)) > 2) {
            return FALSE;
        }

        if ($this->navio['ultimo_disparo_sofrido'] + 30 > atual_segundo()) {
            return FALSE;
        }
        if ($this->navio['ultimo_disparo'] + 30 > atual_segundo()) {
            return FALSE;
        }

        $nps = $navigation->get_nps($target->x, $target->y);

        if (!$nps) {
            return FALSE;
        }

        atacar_rdm($nps["rdm_id"], $this, $this->connection);
        $navigation->clear_nps($target->x, $target->y);

        unset($this->tripulacao);
        unset($this->combate_bot);
        unset($this->combate_pve);
        unset($this->combate_pvp);
        unset($this->in_combate);
        return true;
    }

    function toggle_kairouseki() {
        $data = $this->get_public_data();

        if (!$data["has_kairouseki"]) {
            return;
        }

        $this->connection->run("UPDATE tb_usuarios SET kai = ? WHERE id = ?",
            "ii", array($this->tripulacao["kai"] ? 0 : 1, $this->tripulacao["id"]));
        unset($this->tripulacao);
    }

    function ativa_coup_de_burst() {
        if ($this->vip["coup_de_burst"] <= 0) {
            return false;
        }

        $this->connection->run("UPDATE tb_usuarios SET coup_de_burst_usado = coup_de_burst_usado + 5 WHERE id = ?",
            "i", array($this->tripulacao["id"]));

        $this->connection->run("UPDATE tb_vip SET coup_de_burst = coup_de_burst -1 WHERE id = ?",
            "i", array($this->tripulacao["id"]));
        return true;
    }

    function destroy() {
        $this->buffs->destroy();
        $this->alerts->destroy();
        $this->equipamentos->destroy();
    }
}