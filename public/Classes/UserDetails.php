<?php

/**
 * Class UserDetails
 *
 * @property array tripulacao
 * @property array personagens
 * @property array rotas
 * @property array vip
 * @property array conta
 * @property array tripulacoes
 * @property array capitao
 * @property int lvl_mais_forte
 * @property int fa_mais_alta
 * @property array lvl_medico
 * @property array medicos
 * @property array navegadores
 * @property int lvl_navegador
 * @property array carpinteiros
 * @property int lvl_carpinteiro
 * @property array artesoes
 * @property array ferreiros
 * @property array mergulhadores
 * @property int lvl_mergulhador
 * @property array cartografos
 * @property int lvl_cartografo
 * @property array arqueologos
 * @property int lvl_arqueologo
 * @property boolean in_ilha
 * @property array ilha
 * @property array navio
 * @property array ally
 * @property array combate_pvp
 * @property array tripulacoes_pvp
 * @property array combate_pve
 * @property array combate_bot
 * @property boolean in_combate
 * @property array missao
 * @property array missao_r
 * @property boolean is_visivel
 * @property boolean has_ilha_envolta_me
 * @property boolean has_ilha_or_terra_envolta_me
 * @property boolean tripulacao_alive
 * @property array fila_coliseu
 * @property number lvl_coliseu
 * @property array alerts_data
 * @property array super_alerts_data
 */
class UserDetails
{
    /**
     * @var mywrap_con
     */
    private $connection;

    /**
     * @var BuffTripulacao
     */
    public $buffs;

    /**
     * @var Equipamentos
     */
    public $equipamentos;

    /**
     * @var Alerts
     */
    public $alerts;

    public function __construct($connection)
    {
        $this->connection = $connection;

        $this->_update_last_logon();
        $this->_update_vip();

        $this->buffs = new BuffTripulacao($this, $connection);
        $this->equipamentos = new Equipamentos($connection);
        $this->alerts = new Alerts($this, $connection);
    }

    public function get_time_now()
    {
        $ano = date("Y", time());
        $mes = date("m", time());
        $dia = date("d", time());
        $hora = date("H", time());
        $min = date("i", time());
        $sec = date("s", time());

        return mktime($hora, $min, $sec, $mes, $dia, $ano);
    }

    public function get_user_ip()
    {
        //Just get the headers if we can or else use the SERVER global
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }
        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['X-Forwarded-For'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {
            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
        return $the_ip;
    }

    protected function _update_last_logon()
    {
        global $_SERVER;
        if (! $this->tripulacao) {
            return;
        }

        $this->connection->run("UPDATE tb_usuarios SET ip = ?, ultimo_logon = ?, ultima_pagina = ? WHERE id=?",
            "sssi", array($this->get_user_ip(), $this->get_time_now(), $_SERVER["REQUEST_URI"], $this->tripulacao["id"]));

    }

    private function _update_vip()
    {
        if (! $this->vip) {
            return;
        }
        $tempo = $this->get_time_now();
        if ($this->vip["luneta_duracao"] < $tempo and $this->vip["luneta_duracao"] != 0) {
            $this->connection->run("UPDATE tb_vip SET luneta = '0', luneta_duracao = '0' WHERE id= ?",
                "i", $this->tripulacao["id"]);
        }
        if ($this->vip["sense_duracao"] < $tempo and $this->vip["sense_duracao"] != 0) {
            $this->connection->run("UPDATE tb_vip SET sense = '0', sense_duracao = '0' WHERE id= ?",
                "i", $this->tripulacao["id"]);
        }
        if ($this->vip["tatic_duracao"] < $tempo and $this->vip["tatic_duracao"] != 0) {
            $this->connection->run("UPDATE tb_vip SET tatic = '0', tatic_duracao = '0' WHERE id= ?",
                "i", $this->tripulacao["id"]);
        }
        if ($this->vip["conhecimento_duracao"] < $tempo and $this->vip["conhecimento_duracao"] != 0) {
            $this->connection->run("UPDATE tb_vip SET conhecimento = '0', conhecimento_duracao = '0' WHERE id= ?",
                "i", $this->tripulacao["id"]);
        }
        if ($this->vip["coup_de_burst_duracao"] < $tempo and $this->vip["coup_de_burst_duracao"] != 0) {
            $this->connection->run("UPDATE tb_vip SET coup_de_burst = '0', coup_de_burst_duracao = '0' WHERE id= ?",
                "i", $this->tripulacao["id"]);
        }
        if ($this->vip["formacoes_duracao"] < $tempo and $this->vip["formacoes_duracao"] != 0) {
            $this->connection->run("UPDATE tb_vip SET formacoes = '0', formacoes_duracao = '0' WHERE id= ?",
                "i", $this->tripulacao["id"]);
        }
    }

    public function __get($property)
    {
        $load_method = "_load_$property";
        if (property_exists($this, $property)) {
            return $this->$property;
        } else if (method_exists($this, $load_method)) {
            return $this->$load_method();
        } else {
            return null;
        }
    }

    public function start_session()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    protected function _get_token()
    {
        $this->start_session();

        global $_SESSION;
        global $_COOKIE;

        if (isset($_COOKIE["sg_c"]) and isset($_COOKIE["sg_k"])) {
            if (! validate_alphanumeric($_COOKIE["sg_c"]) || ! validate_alphanumeric($_COOKIE["sg_k"])) {
                return false;
            }
            $id_encrip = $_COOKIE["sg_c"];
            $cookie = $_COOKIE["sg_k"];

            if (! isset($_SESSION["sg_c"]) or ! isset($_SESSION["sg_k"])) {
                $_SESSION["sg_c"] = $_COOKIE["sg_c"];
                $_SESSION["sg_k"] = $_COOKIE["sg_k"];
            }
        } else if (isset($_SESSION["sg_c"]) and isset($_SESSION["sg_k"])) {
            if (! validate_alphanumeric($_SESSION["sg_c"]) || ! validate_alphanumeric($_SESSION["sg_k"])) {
                return false;
            }
            $id_encrip = $_SESSION["sg_c"];
            $cookie = $_SESSION["sg_k"];
        } else {
            return false;
        }
        return array(
            "id_encrypted" => $id_encrip,
            "token" => $cookie
        );
    }

    public function set_authentication($conta_id)
    {
        $cookie = md5(uniqid(time()));

        //inicia sessao
        $this->start_session();

        $_SESSION["sg_c"] = $conta_id;
        $_SESSION["sg_k"] = $cookie;

        setcookie("chat", "0", time() + 80000, '/', FALSE, TRUE);
        setcookie("sg_c", $conta_id, time() + 80000, '/', FALSE, TRUE);
        setcookie("sg_k", $cookie, time() + 80000, '/', FALSE, TRUE);

        //atualiza o cookie do bd
        $this->connection->run("UPDATE tb_conta SET cookie = ? WHERE conta_id = ?", "si", array($cookie, $conta_id));
    }

    private function _load_conta()
    {
        $token = $this->_get_token();

        if (! $token) {
            return ($this->conta = false);
        }

        $result = $this->connection->run("SELECT * FROM tb_conta WHERE conta_id = ? LIMIT 1", "s", array($token["id_encrypted"]));

        if (! $result->count()) {
            return ($this->conta = false);
        }

        $conta = $result->fetch_array();

        if (! $this->_token_matches($conta["cookie"], $token["token"])) {
            return ($this->conta = false);
        }

        //$this->connection->run("INSERT INTO tb_log_acesso (conta_id, tripulacao_id, url) VALUES (?, ?, ?)",
        //   "iis", array($conta["conta_id"], $conta["tripulacao_id"], $_SERVER["REQUEST_URI"]));

        return ($this->conta = $conta);
    }

    private function _token_matches($saved_token, $request_token)
    {
        return $saved_token == $request_token;
    }

    private function _load_tripulacao()
    {
        if (! $this->conta) {
            return ($this->tripulacao = false);
        }
        if (! $this->conta["tripulacao_id"]) {
            return ($this->tripulacao = false);
        }

        $result = $this->connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", array($this->conta["tripulacao_id"]));

        $tripulacao = $result->fetch_array();
        $tripulacao["gold"] = $this->conta["gold"];

        $global = $this->connection->run("SELECT valor_int FROM tb_variavel_global WHERE variavel = ? LIMIT 1", 's', [
            VARIAVEL_TOTAL_HAKI_TREINOS
        ])->fetch_array();
        $tripulacao["treinos_haki_disponiveis"] = (int) $global['valor_int'];

        // $attrs = [
        // 	'atk'		=> 0,
        // 	'def'		=> 0,
        // 	'agl'		=> 0,
        // 	'res'		=> 0,
        // 	'pre'		=> 0,
        // 	'dex'		=> 0,
        // 	'con'		=> 0,
        // 	'vit'		=> 0,
        // 	'haki_esq'	=> 0,
        // 	'haki_blo'	=> 0,
        // 	'haki_cri'	=> 0,
        // 	'haki_hdr'	=> 0,
        // ];

        // $result = $this->connection->run("SELECT * FROM tb_personagens WHERE id = ? AND ativo = 1", "i", array($tripulacao['id']));
        // while ($perso = $result->fetch_array()) {
        // 	$bonus = calc_bonus($perso);
        // 	for ($i = 1; $i <= 7; $i++) {
        // 		$attrs[nome_atributo_tabela($i)] += $perso[nome_atributo_tabela($i)];
        // 		$attrs[nome_atributo_tabela($i)] += $bonus[nome_atributo_tabela($i)];
        // 	}

        // 	$attrs['haki_esq']	+= $perso['haki_hdr'];
        // 	$attrs['haki_blo']	+= $perso['haki_hdr'];
        // 	$attrs['haki_cri']	+= $perso['haki_hdr'];
        // 	$attrs['haki_hdr']	+= $perso['haki_hdr'];
        // }

        // $fight_power = 0;
        // $fight_power += ($attrs['vit']) * 200;
        // $fight_power += ($attrs['atk'] + $attrs['con'] + $attrs['def'] + $attrs['res']) * 150;
        // $fight_power += ($attrs['agl'] + $attrs['dex'] + $attrs['pre']) * 100;
        // $fight_power += ($attrs['haki_hdr']) * 500;
        // $fight_power += ($attrs['haki_esq'] + $attrs['haki_blo'] + $attrs['haki_cri']) * 125;

        $tripulacao["poder"] = 0;

        return ($this->tripulacao = $tripulacao);
    }

    private function _load_tripulacoes()
    {
        if (! $this->conta) {
            return ($this->tripulacoes = false);
        }

        $result = $this->connection->run("SELECT * FROM tb_usuarios WHERE conta_id = ?", "i", array($this->conta["conta_id"]));

        return ($this->tripulacoes = $result->fetch_all_array());
    }

    private function _load_personagens()
    {
        if (! $this->tripulacao) {
            return ($this->personagens = false);
        }

        $personagens = [];
        $result = $this->connection->run("SELECT * FROM tb_personagens WHERE id = ? AND ativo = 1", "i", array($this->tripulacao["id"]));
        while ($row = $result->fetch_array()) {
            $level = $row['lvl'];
            $xp = $row['xp'];
            $xp_max = $row['xp_max'];
            $haki_xp = $row['haki_xp'];
            $haki_xp_max = $row['haki_xp_max'];
            $haki_lvl = $row['haki_lvl'];
            if (($xp >= $xp_max && $level < 50) || ($haki_xp > $haki_xp_max && $haki_lvl < HAKI_LVL_MAX)) {
                $runs = 0;
                while ($xp >= $xp_max) {
                    if ($level < 50) {
                        $xp -= $xp_max;
                        $xp_max = formulaExp($level++);

                        ++$runs;
                    } else {
                        break;
                    }
                }
                $row['lvl'] = $level;
                $row['xp'] = $xp;
                $row['xp_max'] = $xp_max;
                $row['pts'] += PONTOS_POR_NIVEL * $runs;
                $row['hp_max'] += HP_POR_NIVEL * $runs;
                $row['hp'] = $row['hp_max'];
                $row['fama_ameaca'] += 20000 * $runs;

                $this->connection->run("UPDATE tb_personagens
					SET lvl         = ?,
						xp      	= ?,
						xp_max      = ?,
						pts         = ?,
						hp_max      = ?,
						hp          = ?,
						mp_max      = ?,
						mp          = ?,
						fama_ameaca = ?
					WHERE id = ? AND cod = ?", 'iiiiiiiiiii', [
                    $row['lvl'],
                    $row['xp'],
                    $row['xp_max'],
                    $row['pts'],
                    $row['hp_max'],
                    $row['hp'],
                    $row['mp_max'],
                    $row['mp'],
                    $row['fama_ameaca'],
                    $this->tripulacao['id'],
                    $row['cod']
                ]);
            }

            $personagens[] = $row;
        }

        // return ($this->personagens = $result->fetch_all_array());
        return ($this->personagens = $personagens);
    }

    private function _load_capitao()
    {
        if (! $this->personagens) {
            return ($this->capitao = false);
        }

        return ($this->capitao = $this->personagens[0]);
    }

    private function _load_lvl_mais_forte()
    {
        if (! $this->personagens) {
            return ($this->lvl_mais_forte = false);
        }
        $lvl_max = 0;
        foreach ($this->personagens as $pers) {
            if ($pers["lvl"] > $lvl_max) {
                $lvl_max = $pers["lvl"];
            }
        }

        return ($this->lvl_mais_forte = $lvl_max);
    }

    private function _load_fa_mais_alta()
    {
        if (! $this->personagens) {
            return ($this->fa_mais_alta = false);
        }
        $fa_max = 0;
        foreach ($this->personagens as $pers) {
            if ($pers["fama_ameaca"] > $fa_max) {
                $fa_max = $pers["fama_ameaca"];
            }
        }

        return ($this->fa_mais_alta = $fa_max);
    }

    private function _load_medicos()
    {
        if (! $this->personagens) {
            return ($this->medicos = false);
        }

        return ($this->medicos = $this->get_personagens_by_profissao(PROFISSAO_MEDICO));
    }

    private function _load_lvl_medico()
    {
        if (! $this->medicos) {
            return ($this->lvl_medico = false);
        }

        return ($this->lvl_medico = $this->_load_lvl_prof($this->medicos));
    }

    private function _load_navegadores()
    {
        if (! $this->personagens) {
            return ($this->navegadores = false);
        }

        return ($this->navegadores = $this->get_personagens_by_profissao(PROFISSAO_NAVEGADOR));
    }

    private function _load_lvl_navegador()
    {
        if (! $this->navegadores) {
            return ($this->lvl_navegador = false);
        }

        return ($this->lvl_navegador = $this->_load_lvl_prof($this->navegadores));
    }

    private function _load_lvl_prof($profissionais)
    {
        $lvl = 0;
        foreach ($profissionais as $prof) {
            if ($prof["profissao_lvl"] > $lvl) {
                $lvl = $prof["profissao_lvl"];
            }
        }
        return $lvl;
    }

    private function _load_lvl_min_prof($profissionais)
    {
        $lvl = 1000;
        foreach ($profissionais as $prof) {
            if ($prof["profissao_lvl"] < $lvl) {
                $lvl = $prof["profissao_lvl"];
            }
        }
        return $lvl;
    }

    private function _load_carpinteiros()
    {
        if (! $this->personagens) {
            return ($this->carpinteiros = false);
        }

        return ($this->carpinteiros = $this->get_personagens_by_profissao(PROFISSAO_CARPINTEIRO));
    }

    private function _load_lvl_carpinteiro()
    {
        if (! $this->carpinteiros) {
            return ($this->lvl_carpinteiro = false);
        }

        return ($this->lvl_carpinteiro = $this->_load_lvl_prof($this->carpinteiros));
    }

    private function _load_ferreiros()
    {
        if (! $this->personagens) {
            return ($this->ferreiros = false);
        }

        return ($this->ferreiros = $this->get_personagens_by_profissao(PROFISSAO_FERREIRO));
    }

    private function _load_artesoes()
    {
        if (! $this->personagens) {
            return ($this->artesoes = false);
        }

        return ($this->artesoes = $this->get_personagens_by_profissao(PROFISSAO_ARTESAO));
    }

    private function _load_mergulhadores()
    {
        if (! $this->personagens) {
            return ($this->mergulhadores = false);
        }

        return ($this->mergulhadores = $this->get_personagens_by_profissao(PROFISSAO_MERGULHADOR));
    }

    private function _load_lvl_mergulhador()
    {
        if (! $this->mergulhadores) {
            return ($this->lvl_mergulhador = false);
        }

        return ($this->lvl_mergulhador = $this->_load_lvl_min_prof($this->mergulhadores));
    }

    private function _load_arqueologos()
    {
        if (! $this->personagens) {
            return ($this->arqueologos = false);
        }

        return ($this->arqueologos = $this->get_personagens_by_profissao(PROFISSAO_ARQUEOLOGO));
    }

    private function _load_lvl_arqueologo()
    {
        if (! $this->arqueologos) {
            return ($this->lvl_arqueologo = false);
        }

        return ($this->lvl_arqueologo = $this->_load_lvl_min_prof($this->arqueologos));
    }

    private function _load_cartografos()
    {
        if (! $this->personagens) {
            return ($this->cartografos = false);
        }

        return ($this->cartografos = $this->get_personagens_by_profissao(PROFISSAO_CARTOGRAFO));
    }

    private function _load_lvl_cartografo()
    {
        if (! $this->cartografos) {
            return ($this->lvl_cartografo = false);
        }

        return ($this->lvl_cartografo = $this->_load_lvl_min_prof($this->cartografos));
    }

    public function get_personagens_by_profissao($prof)
    {
        $filter = function ($pers) use ($prof) {
            return $pers["profissao"] == $prof;
        };

        $array = array_values(array_filter($this->personagens, $filter));
        return count($array) ? $array : FALSE;
    }

    private function _load_rotas()
    {
        if (! $this->tripulacao) {
            return ($this->rotas = false);
        }

        $result = $this->connection->run("SELECT * FROM tb_rotas WHERE id = ? ORDER BY indice", "i", $this->tripulacao["id"]);

        if (! $result->count()) {
            return ($this->rotas = false);
        }

        return ($this->rotas = $result->fetch_all_array());
    }

    public function can_access_inpel_down()
    {
        if ($impel_down_access = get_value_variavel_global(VARIAVEL_IDS_ACESSO_IMPEL_DOWN)) {
            return in_array($this->tripulacao["id"], explode(",", $impel_down_access["valor_varchar"]));
        }
        return false;
    }

    public function can_access_enies_lobby()
    {
        if ($access = get_value_variavel_global(VARIAVEL_IDS_ACESSO_ENIES_LOBBY)) {
            return in_array($this->tripulacao["id"], explode(",", $access["valor_varchar"]));
        }
        return false;
    }

    private function _verify_in_ilha($ilha)
    {
        if ($ilha == 0) {
            return FALSE;
        } else if ($ilha == 47) {
            if ($this->tripulacao["faccao"] == FACCAO_PIRATA) {
                $rdp = get_value_varchar_variavel_global(VARIAVEL_VENCEDORES_ERA_PIRATA);
                return ($rdp == $this->tripulacao["id"]);
            } else if ($this->tripulacao["faccao"] == FACCAO_MARINHA) {
                $adf = get_value_varchar_variavel_global(VARIAVEL_VENCEDORES_ERA_MARINHA);
                return ($adf == $this->tripulacao["id"]);
            }
        } else if ($ilha == 101) {
            if ($this->can_access_inpel_down()) {
                return TRUE;
            }
        } else if ($ilha == 102) {
            if ($this->can_access_enies_lobby()) {
                return TRUE;
            }
        } else {
            return TRUE;
        }

        return FALSE;
    }

    private function _load_in_ilha()
    {
        if (! $this->tripulacao) {
            return ($this->in_ilha = false);
        }

        return $this->in_ilha = $this->_verify_in_ilha($this->ilha["ilha"]);
    }

    private function _load_ilha()
    {
        if (! $this->tripulacao) {
            return ($this->ilha = false);
        }

        $result = $this->connection->run("SELECT * FROM tb_mapa WHERE x = ? AND y = ? LIMIT 1",
            "ii", array($this->tripulacao["x"], $this->tripulacao["y"]));

        if ($result->count()) {
            $this->ilha = $result->fetch_array();
        } else {
            $this->ilha = [
                "ilha" => 0,
                "x" => $this->tripulacao["x"],
                "y" => $this->tripulacao["y"],
                "mar" => get_mar($this->tripulacao["x"], $this->tripulacao["y"])
            ];
        }

        if ($this->ilha["ilha"] == 47 && ! $this->_verify_in_ilha($this->ilha["ilha"])) {
            $this->ilha["ilha"] = 0;
        }

        return $this->ilha;
    }

    private function _load_navio()
    {
        if (! $this->tripulacao) {
            return ($this->navio = false);
        }
        $result = $this->connection->run("SELECT
			*,
			(SELECT quant FROM tb_usuario_itens WHERE id = usrnav.id AND tipo_item = 13) AS canhao_balas
			FROM tb_usuario_navio usrnav
			INNER JOIN tb_navio nav
			ON usrnav.cod_navio = nav.cod_navio
			 WHERE usrnav.id = ?", "i", $this->tripulacao["id"]);

        if (! $result->count()) {
            return ($this->navio = false);
        }
        return ($this->navio = $result->fetch_array());
    }

    private function _load_vip()
    {
        if (! $this->tripulacao) {
            return ($this->vip = false);
        }

        $result = $this->connection->run("SELECT * FROM tb_vip WHERE id = ?", "i", $this->tripulacao["id"]);
        return ($this->vip = $result->fetch_array());
    }

    private function _load_ally()
    {
        if (! $this->tripulacao) {
            return ($this->ally = false);
        }

        $result = $this->connection->run("SELECT * FROM tb_alianca_membros WHERE id = ?", "i", $this->tripulacao["id"]);

        if (! $result->count()) {
            return ($this->ally = false);
        }

        $ally = $result->fetch_array();
        $result = $this->connection->run("SELECT * FROM tb_alianca WHERE cod_alianca=?", "i", $ally["cod_alianca"]);

        $this->ally = $result->fetch_array();
        $this->ally["autoridade"] = $ally["autoridade"];
        $this->ally["cooperacao"] = $ally["cooperacao"];

        return $this->ally;
    }

    private function _load_in_combate()
    {
        return $this->combate_pve || $this->combate_pvp || $this->combate_bot;
    }

    private function _load_combate_pvp()
    {
        if (! $this->tripulacao) {
            return ($this->combate_pvp = false);
        }
        $result = $this->connection->run("SELECT * FROM tb_combate WHERE id_1 =  ? OR id_2 = ?",
            "ii", array($this->tripulacao["id"], $this->tripulacao["id"]));

        if (! $result->count()) {
            return ($this->combate_pvp = false);
        }

        $this->connection->run("DELETE FROM tb_combate_npc WHERE id = ?", "i", $this->tripulacao["id"]);

        return ($this->combate_pvp = $result->fetch_array());
    }

    private function _load_tripulacoes_pvp()
    {
        if (! $this->combate_pvp) {
            return ($this->tripulacoes_pvp = false);
        }
        $tripulacao["1"] = $this->connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", $this->combate_pvp["id_1"])
            ->fetch_array();
        $tripulacao["2"] = $this->connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", $this->combate_pvp["id_2"])
            ->fetch_array();

        return ($this->tripulacoes_pvp = $tripulacao);
    }

    private function _load_combate_pve()
    {
        if (! $this->tripulacao) {
            return ($this->combate_pve = false);
        }
        $result = $this->connection->run("SELECT * FROM tb_combate_npc WHERE id = ?", "i", $this->tripulacao["id"]);

        if (! $result->count()) {
            return ($this->combate_pve = false);
        }
        $combate = $result->fetch_array();
        if ($combate["boss_id"]) {
            $boss = $this->connection->run("SELECT * FROM tb_boss WHERE id = ?", "i", $combate["boss_id"])->fetch_array();
            $combate["hp_npc"] = $boss["hp"];
            $combate["real_boss_id"] = $boss["real_boss_id"];
        }

        return ($this->combate_pve = $combate);
    }

    private function _load_combate_bot()
    {
        if (! $this->tripulacao) {
            return ($this->combate_bot = false);
        }
        $result = $this->connection->run("SELECT * FROM tb_combate_bot WHERE tripulacao_id = ?", "i", array($this->tripulacao["id"]));

        if (! $result->count()) {
            return ($this->combate_bot = false);
        }

        return ($this->combate_bot = $result->fetch_array());
    }

    private function _load_missao()
    {
        if (! $this->tripulacao) {
            return ($this->missao = false);
        }
        $result = $this->connection->run(
            "SELECT * FROM tb_missoes_iniciadas misini WHERE misini.id = ?",
            "i", $this->tripulacao["id"]
        );

        if (! $result->count()) {
            return ($this->missao = false);
        }

        $missao_iniciada = $result->fetch_array();

        $missoes = DataLoader::load("missoes");

        return ($this->missao = array_merge($missao_iniciada, $missoes[$missao_iniciada["cod_missao"]]));
    }

    private function _load_missao_r()
    {
        if (! $this->tripulacao) {
            return ($this->missao_r = false);
        }
        $result = $this->connection->run("SELECT * FROM tb_missoes_r WHERE id = ?", "i", $this->tripulacao["id"]);

        if (! $result->count()) {
            return ($this->missao_r = false);
        }
        return ($this->missao_r = $result->fetch_array());
    }

    private function _load_is_visivel()
    {
        if (! $this->tripulacao) {
            return ($this->is_visivel = false);
        }

        return ($this->is_visivel = $this->tripulacao["mar_visivel"]);
    }

    private function _load_has_ilha_envolta_me()
    {
        if (! $this->tripulacao) {
            return ($this->has_ilha_envolta_me = false);
        }

        $my_x = $this->tripulacao["x"];
        $my_y = $this->tripulacao["y"];
        $result = $this->connection->run(
            "SELECT * FROM tb_mapa WHERE x >= ? AND x <= ? AND y >= ? AND y <= ? AND ilha <> 0",
            "iiii", array($my_x - 2, $my_x + 2, $my_y - 2, $my_y + 2)
        );

        return ($this->has_ilha_envolta_me = ! ! $result->count());
    }

    private function _load_has_ilha_or_terra_envolta_me()
    {
        if ($this->has_ilha_envolta_me) {
            return ($this->has_ilha_or_terra_envolta_me = true);
        } else {
            $ilha_proxima = $this->connection->run("SELECT * FROM tb_mapa WHERE x >= ? AND x <= ? AND y >= ? AND y <= ? AND (navegavel = 0 OR ilha <> 0)",
                "iiii", array(
                    $this->tripulacao["x"] - 1,
                    $this->tripulacao["x"] + 1,
                    $this->tripulacao["y"] - 1,
                    $this->tripulacao["y"] + 1
                ));
            return ($this->has_ilha_or_terra_envolta_me = ! ! $ilha_proxima->count());
        }
    }

    private function _load_tripulacao_alive()
    {
        if (! $this->tripulacao) {
            return ($this->tripulacao_alive = false);
        }

        $this->tripulacao_alive = false;
        foreach ($this->personagens as $pers) {
            if ($pers["hp"] > 0) {
                $this->tripulacao_alive = true;
                break;
            }
        }

        return $this->tripulacao_alive;
    }

    private function _load_fila_coliseu()
    {
        if (! $this->tripulacao) {
            return ($this->fila_coliseu = false);
        }

        $result = $this->connection->run("SELECT * FROM tb_coliseu_fila WHERE id = ?", "i", array($this->tripulacao["id"]));

        return $this->fila_coliseu = $result->count() ? $result->fetch_array() : null;
    }

    private function _load_lvl_coliseu()
    {
        if (! $this->tripulacao) {
            return ($this->lvl_coliseu = false);
        }

        return $this->lvl_coliseu = $this->connection->run("SELECT MAX(lvl) AS lvl FROM tb_personagens WHERE id = ? AND time_coliseu = 1",
            "i", array($this->tripulacao["id"]))->fetch_array()["lvl"];
    }

    private function _load_alerts_data()
    {
        if (! $this->tripulacao) {
            return ($this->alerts_data = array());
        }

        $alerts = array();

        foreach ($this->personagens as $pers) {
            if ($this->alerts->has_alert_trip_sem_distribuir_atributo($pers)) {
                $alerts["status"] = true;
                $alerts["status." . $pers["cod"]] = true;
                $alerts["status.status." . $pers["cod"]] = true;
                $alerts["trip_sem_distribuir_atributo." . $pers["cod"]] = true;
            }
            if ($this->is_sistema_desbloqueado(SISTEMA_ACADEMIA)
                && $this->alerts->has_alert_trip_sem_classe($pers)) {
                $alerts["status"] = true;
                $alerts["status." . $pers["cod"]] = true;
                $alerts["status.classe." . $pers["cod"]] = true;
                $alerts["trip_sem_classe." . $pers["cod"]] = true;
            }
            if ($this->is_sistema_desbloqueado(SISTEMA_PROFISSOES)
                && $this->alerts->has_alert_trip_sem_profissao($pers)) {
                $alerts["status"] = true;
                $alerts["status&nav=profissao"] = true;
                $alerts["status." . $pers["cod"]] = true;
                $alerts["status.profissao." . $pers["cod"]] = true;
                $alerts["trip_sem_profissao." . $pers["cod"]] = true;
            }
            if ($this->is_sistema_desbloqueado(SISTEMA_HAKI)
                && $this->alerts->has_alert_trip_sem_distribuir_haki($pers)) {
                $alerts["status"] = true;
                $alerts["status&nav=haki"] = true;
                $alerts["status." . $pers["cod"]] = true;
                $alerts["status.haki." . $pers["cod"]] = true;
                $alerts["trip_sem_distribuir_haki." . $pers["cod"]] = true;
            }
            if ($this->is_sistema_desbloqueado(SISTEMA_ACADEMIA)
                && $this->alerts->has_alert_nova_habilidade_classe($pers)) {
                $alerts["status"] = true;
                $alerts["status." . $pers["cod"]] = true;
                $alerts["status.classe." . $pers["cod"]] = true;
                $alerts["nova_habilidade_classe." . $pers["cod"]] = true;
            }
            if ($this->alerts->has_alert_nova_habilidade_akuma($pers)) {
                $alerts["status"] = true;
                $alerts["status&nav=akuma"] = true;
                $alerts["status." . $pers["cod"]] = true;
                $alerts["status.akuma." . $pers["cod"]] = true;
                $alerts["nova_habilidade_akuma." . $pers["cod"]] = true;
            }
            if ($this->is_sistema_desbloqueado(SISTEMA_PROFISSOES)
                && $this->alerts->has_alert_nova_habilidade_profissao($pers)) {
                $alerts["status"] = true;
                $alerts["status&nav=profissao"] = true;
                $alerts["status." . $pers["cod"]] = true;
                $alerts["status.profissao." . $pers["cod"]] = true;
                $alerts["nova_habilidade_profissao." . $pers["cod"]] = true;
            }
            if ($this->is_sistema_desbloqueado(SISTEMA_EQUIPAMENTOS)
                && $this->alerts->has_alert_sem_equipamento($pers)) {
                $alerts["status"] = true;
                $alerts["status&nav=equipamentos"] = true;
                $alerts["equipamentos." . $pers["cod"]] = true;
                $alerts["status.equipamentos." . $pers["cod"]] = true;
                $alerts["sem_equipamento." . $pers["cod"]] = true;
            }
        }
        if ($this->tripulacao["battle_points"] > PONTOS_POR_NIVEL_BATALHA) {
            $alerts["tripulacao"] = true;
        }

        return $this->alerts_data = $alerts;
    }

    private function _load_super_alerts_data()
    {
        if (! $this->tripulacao) {
            return ($this->super_alerts_data = array());
        }

        $alerts = array();

        foreach ($this->alerts_data as $alert => $bool) {
            $super_menu = get_super_menu($alert);
            if ($super_menu) {
                $alerts[get_super_menu($alert)] = true;
            }
        }

        return $this->super_alerts_data = $alerts;
    }

    public function add_effect($effect, $quant = 1)
    {
        $animacao = $this->connection->run("SELECT * FROM tb_tripulacao_animacoes_skills WHERE tripulacao_id = ? AND effect = ?",
            "is", array($this->tripulacao["id"], $effect));

        if ($animacao->count()) {
            $this->connection->run("UPDATE tb_tripulacao_animacoes_skills SET quant = quant + ? WHERE tripulacao_id = ? AND effect = ?",
                "iis", array($quant, $this->tripulacao["id"], $effect));
        } else {
            $this->connection->run("INSERT INTO tb_tripulacao_animacoes_skills (tripulacao_id, effect, quant) VALUE (?,?,?)",
                "isi", array($this->tripulacao["id"], $effect, $quant));
        }
    }

    public function remove_skills_classe($pers)
    {
        global $COD_HAOSHOKU_LVL;
        $skils_nao_resetaveis = array_merge($COD_HAOSHOKU_LVL, array(1, 2));

        $this->restaura_effects($pers, "((tipo='1' AND cod_skil NOT IN (" . implode(",", $skils_nao_resetaveis) . ")) OR tipo='2' OR tipo='3')");

        $this->connection->run(
            "DELETE FROM tb_personagens_skil
			WHERE cod= ? AND  ((tipo='1' AND cod_skil NOT IN (" . implode(",", $skils_nao_resetaveis) . ")) OR tipo='2' OR tipo='3')",
            "i", array($pers["cod"])
        );
    }

    public function remove_hdr($pers)
    {
        global $COD_HAOSHOKU_LVL;
        $this->restaura_effects($pers, "(tipo = 1 AND cod_skil IN (" . implode(',', $COD_HAOSHOKU_LVL) . "))");

        $this->connection->run("DELETE FROM tb_personagens_skil WHERE cod = ? AND tipo = ? AND cod_skil IN (" . implode(',', $COD_HAOSHOKU_LVL) . ")",
            "ii", array($pers["cod"], TIPO_SKILL_ATAQUE_CLASSE));
    }

    public function remove_skills_profissao($pers)
    {
        $this->restaura_effects($pers, "(tipo IN (4,5,6))");

        $this->connection->run(
            "DELETE FROM tb_personagens_skil
			WHERE cod=? AND tipo IN (4,5,6)",
            "i", array($pers["cod"])
        );
    }

    public function restaura_effects($pers, $where)
    {
        $effects = $this->connection->run(
            "SELECT effect, count(*) AS quant FROM tb_personagens_skil
			WHERE cod = ? AND effect <> 'Atingir fisicamente' AND $where
			GROUP BY effect",
            "i", array($pers["cod"]));

        while ($effect = $effects->fetch_array()) {
            $this->add_effect($effect["effect"], $effect["quant"]);
        }
    }

    private function get_all_progress_info()
    {
        global $connection;
        $ilhas = $connection->run("SELECT ilha, x, y FROM tb_mapa WHERE ilha <> 0")->fetch_all_array();

        $mar = $this->ilha["mar"] == 4 ? 3 : ($this->ilha["mar"] == 3 ? 4 : $this->ilha["mar"]);
        $primeira_ilha = ($mar - 1) * 7 + 1;
        $segunda_ilha = ($mar - 1) * 7 + 2;
        $segunda_ilha_coord = get_coord_ilha_from_cache($segunda_ilha, $ilhas);
        $terceira_ilha = ($mar - 1) * 7 + 3;
        $terceira_ilha_coord = get_coord_ilha_from_cache($terceira_ilha, $ilhas);
        $quarta_ilha = ($mar - 1) * 7 + 4;
        $quarta_ilha_coord = get_coord_ilha_from_cache($quarta_ilha, $ilhas);
        $quinta_ilha = ($mar - 1) * 7 + 5;
        $quinta_ilha_coord = get_coord_ilha_from_cache($quinta_ilha, $ilhas);
        $sexta_ilha = ($mar - 1) * 7 + 6;
        $sexta_ilha_coord = get_coord_ilha_from_cache($sexta_ilha, $ilhas);
        $setima_ilha = ($mar - 1) * 7 + 7;
        $setima_ilha_coord = get_coord_ilha_from_cache($setima_ilha, $ilhas);

        $coord_sabaody = get_coord_ilha_from_cache(42, $ilhas);
        $coord_mariejois = get_coord_ilha_from_cache(43, $ilhas);

        return array(
            0 => array(
                "goal" => "Complete uma missão na ilha",
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_HOSPITAL],
                "next" => 1,
                "check_progress" => function () use ($primeira_ilha) {
                    return check_progress_missoes_realizadas($primeira_ilha, 1);
                }
            ),
            1 => array(
                "goal" => "Se recupere no hospital",
                "link" => "hospital",
                "unlock" => [],
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "next" => 2,
                "check_progress" => function () {
                    return check_progress_personagens_recuperados();
                }
            ),
            2 => array(
                "goal" => "Complete mais uma missão na ilha",
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_ACADEMIA],
                "next" => 3,
                "check_progress" => function () use ($primeira_ilha) {
                    return check_progress_missoes_realizadas($primeira_ilha, 2);
                }
            ),
            3 => array(
                "goal" => "Escolha a classe do capitão",
                "link" => "academia",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_VISAO_GERAL_TRIPULACAO],
                "next" => 4,
                "check_progress" => function () {
                    return check_progress_personagem_com_classe($this->capitao);
                }
            ),
            4 => array(
                "goal" => "Escolha os atributos do capitão",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 5,
                "check_progress" => function () {
                    return check_progress_personagem_com_atributos($this->capitao);
                }
            ),
            5 => array(
                "goal" => "Escolha uma habilidade para o capitão",
                "link" => "academia",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 6,
                "check_progress" => function () {
                    return check_progress_personagem_com_habilidade($this->capitao["cod"]);
                }
            ),
            6 => array(
                "goal" => "Complete mais uma missão na ilha",
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_ESTALEIRO],
                "next" => 7,
                "check_progress" => function () use ($primeira_ilha) {
                    return check_progress_missoes_realizadas($primeira_ilha, 3);
                }
            ),
            7 => array(
                "goal" => "Compre um barco",
                "link" => "estaleiro",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_BANDEIRA],
                "next" => 8,
                "check_progress" => function () {
                    return check_progress_barco_comprado();
                }
            ),
            8 => array(
                "goal" => "Personalize sua bandeira",
                "link" => "bandeira",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_RECRUTAR_TRIPULANTE],
                "next" => 9,
                "check_progress" => function () {
                    return check_progress_bandeira_trocada();
                }
            ),
            9 => array(
                "goal" => "Recrute um tripulante",
                "link" => "recrutar",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 11,
                "check_progress" => function () {
                    return check_progress_tripulantes_recrutados(2);
                }
            ),
            11 => array(
                "goal" => "Escolha a classe do novo tripulante",
                "link" => "academia",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 12,
                "check_progress" => function () {
                    return check_progress_personagem_com_classe($this->personagens[1]);
                }
            ),
            12 => array(
                "goal" => "Escolha os atributos do novo tripulante",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 13,
                "check_progress" => function () {
                    return check_progress_personagem_com_atributos($this->personagens[1]);
                }
            ),
            13 => array(
                "goal" => "Escolha uma habilidade para o novo tripulante",
                "link" => "academia",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 14,
                "check_progress" => function () {
                    return check_progress_personagem_com_habilidade($this->personagens[1]["cod"]);
                }
            ),
            14 => array(
                "goal" => "Recrute mais um tripulante",
                "link" => "recrutar",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 15,
                "check_progress" => function () {
                    return check_progress_tripulantes_recrutados(3);
                }
            ),
            15 => array(
                "goal" => "Escolha a classe do novo tripulante",
                "link" => "academia",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 16,
                "check_progress" => function () {
                    return check_progress_personagem_com_classe($this->personagens[2]);
                }
            ),
            16 => array(
                "goal" => "Escolha os atributos do novo tripulante",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 17,
                "check_progress" => function () {
                    return check_progress_personagem_com_atributos($this->personagens[2]);
                }
            ),
            17 => array(
                "goal" => "Escolha uma habilidade para o novo tripulante",
                "link" => "academia",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 18,
                "check_progress" => function () {
                    return check_progress_personagem_com_habilidade($this->personagens[2]["cod"]);
                }
            ),
            18 => array(
                "goal" => "Complete todas as missões da ilha",
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 19,
                "check_progress" => function () use ($primeira_ilha) {
                    return check_progress_missoes_realizadas($primeira_ilha, -1);
                }
            ),
            19 => array(
                "goal" => "Derrote o chefe da ilha",
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_INCURSOES],
                "next" => 20,
                "check_progress" => function () use ($primeira_ilha) {
                    return check_progress_chefe_ilha_derrotado($primeira_ilha);
                }
            ),
            20 => array(
                "goal" => "Complete a incursão da ilha",
                "link" => "incursao",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_PESQUISAS],
                "next" => 21,
                "check_progress" => function () use ($primeira_ilha) {
                    return check_progress_incursao_realizada($primeira_ilha);
                }
            ),
            21 => array(
                "goal" => "Inicie uma pesquisa",
                "link" => "missoesR",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_PROFISSOES],
                "next" => 22,
                "check_progress" => function () {
                    return check_progress_pesquisa_iniciada();
                }
            ),
            22 => array(
                "goal" => "Escolha a profissão do capitão",
                "link" => "profissoesAprender",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 23,
                "check_progress" => function () {
                    return check_progress_personagem_com_profissao($this->capitao);
                }
            ),
            23 => array(
                "goal" => "Escolha a profissão do resto da tripulação",
                "link" => "profissoesAprender",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_RESTAURANTE],
                "next" => 24,
                "check_progress" => function () {
                    return check_progress_personagem_com_profissao($this->personagens[1])
                        && check_progress_personagem_com_profissao($this->personagens[2]);
                }
            ),
            24 => array(
                "goal" => "Compre comida no restaurante",
                "link" => "restaurante",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_CACA],
                "next" => 25,
                "check_progress" => function () {
                    return check_progress_comida_comprada();
                }
            ),
            25 => array(
                "goal" => "Inicie uma missão de caça",
                "link" => "missoesCaca",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_OCEANO],
                "next" => 26,
                "check_progress" => function () {
                    return $this->tripulacao["missao_caca"];
                }
            ),
            26 => array(
                "goal" => "Navegue em alto mar",
                "link" => "oceano",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [SISTEMA_OCEANO],
                "next" => 27,
                "check_progress" => function () {
                    return ! $this->in_ilha;
                }
            ),
            27 => array(
                "goal" => "Derrote uma criatura maritma",
                "link" => "oceano",
                "rewards" => array("xp" => 0, "berries" => 1000, "dobroes" => 0),
                "unlock" => [],
                "next" => 28,
                "check_progress" => function () {
                    return check_progress_criatura_derrotada();
                }
            ),
            // segunda ilha
            28 => array(
                "goal" => "Viaje até " . nome_ilha($segunda_ilha) . " em " . get_human_location($segunda_ilha_coord["x"], $segunda_ilha_coord["y"]),
                "link" => "oceano",
                "rewards" => array("xp" => 0, "berries" => 2000, "dobroes" => 0),
                "unlock" => [],
                "next" => 29,
                "check_progress" => function () use ($segunda_ilha) {
                    return check_progress_in_ilha($segunda_ilha);
                }
            ),
            29 => array(
                "goal" => "Complete todas as missões de " . nome_ilha($segunda_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 2000, "dobroes" => 0),
                "unlock" => [],
                "next" => 30,
                "check_progress" => function () use ($segunda_ilha) {
                    return check_progress_missoes_realizadas($segunda_ilha, -1);
                }
            ),
            30 => array(
                "goal" => "Derrote o chefe de " . nome_ilha($segunda_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 2000, "dobroes" => 0),
                "unlock" => [],
                "next" => 31,
                "check_progress" => function () use ($segunda_ilha) {
                    return check_progress_chefe_ilha_derrotado($segunda_ilha);
                }
            ),
            31 => array(
                "goal" => "Complete a incursão de " . nome_ilha($segunda_ilha),
                "link" => "incursao",
                "rewards" => array("xp" => 0, "berries" => 2000, "dobroes" => 0),
                "unlock" => [SISTEMA_CALENDARIO, SISTEMA_EVENTOS],
                "next" => 32,
                "check_progress" => function () use ($segunda_ilha) {
                    return check_progress_incursao_realizada($segunda_ilha);
                }
            ),
            // terceira ilha
            32 => array(
                "goal" => "Viaje até " . nome_ilha($terceira_ilha) . " em " . get_human_location($terceira_ilha_coord["x"], $terceira_ilha_coord["y"]),
                "link" => "oceano",
                "rewards" => array("xp" => 0, "berries" => 3000, "dobroes" => 0),
                "unlock" => [],
                "next" => 33,
                "check_progress" => function () use ($terceira_ilha) {
                    return check_progress_in_ilha($terceira_ilha);
                }
            ),
            33 => array(
                "goal" => "Complete todas as missões de " . nome_ilha($terceira_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 3000, "dobroes" => 0),
                "unlock" => [],
                "next" => 34,
                "check_progress" => function () use ($terceira_ilha) {
                    return check_progress_missoes_realizadas($terceira_ilha, -1);
                }
            ),
            34 => array(
                "goal" => "Derrote o chefe de " . nome_ilha($terceira_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 3000, "dobroes" => 0),
                "unlock" => [],
                "next" => 35,
                "check_progress" => function () use ($terceira_ilha) {
                    return check_progress_chefe_ilha_derrotado($terceira_ilha);
                }
            ),
            35 => array(
                "goal" => "Complete a incursão de " . nome_ilha($terceira_ilha),
                "link" => "incursao",
                "rewards" => array("xp" => 0, "berries" => 3000, "dobroes" => 0),
                "unlock" => [],
                "next" => 36,
                "check_progress" => function () use ($terceira_ilha) {
                    return check_progress_incursao_realizada($terceira_ilha);
                }
            ),
            // quarta ilha
            36 => array(
                "goal" => "Viaje até " . nome_ilha($quarta_ilha) . " em " . get_human_location($quarta_ilha_coord["x"], $quarta_ilha_coord["y"]),
                "link" => "oceano",
                "rewards" => array("xp" => 0, "berries" => 4000, "dobroes" => 0),
                "unlock" => [],
                "next" => 37,
                "check_progress" => function () use ($quarta_ilha) {
                    return check_progress_in_ilha($quarta_ilha);
                }
            ),
            37 => array(
                "goal" => "Complete todas as missões de " . nome_ilha($quarta_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 4000, "dobroes" => 0),
                "unlock" => [],
                "next" => 38,
                "check_progress" => function () use ($quarta_ilha) {
                    return check_progress_missoes_realizadas($quarta_ilha, -1);
                }
            ),
            38 => array(
                "goal" => "Derrote o chefe de " . nome_ilha($quarta_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 4000, "dobroes" => 0),
                "unlock" => [],
                "next" => 39,
                "check_progress" => function () use ($quarta_ilha) {
                    return check_progress_chefe_ilha_derrotado($quarta_ilha);
                }
            ),
            39 => array(
                "goal" => "Complete a incursão de " . nome_ilha($quarta_ilha),
                "link" => "incursao",
                "rewards" => array("xp" => 0, "berries" => 4000, "dobroes" => 0),
                "unlock" => [],
                "next" => 40,
                "check_progress" => function () use ($quarta_ilha) {
                    return check_progress_incursao_realizada($quarta_ilha);
                }
            ),
            // quinta ilha
            40 => array(
                "goal" => "Viaje até " . nome_ilha($quinta_ilha) . " em " . get_human_location($quinta_ilha_coord["x"], $quinta_ilha_coord["y"]),
                "link" => "oceano",
                "rewards" => array("xp" => 0, "berries" => 5000, "dobroes" => 0),
                "unlock" => [],
                "next" => 41,
                "check_progress" => function () use ($quinta_ilha) {
                    return check_progress_in_ilha($quinta_ilha);
                }
            ),
            41 => array(
                "goal" => "Complete todas as missões de " . nome_ilha($quinta_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 5000, "dobroes" => 0),
                "unlock" => [],
                "next" => 42,
                "check_progress" => function () use ($quinta_ilha) {
                    return check_progress_missoes_realizadas($quinta_ilha, -1);
                }
            ),
            42 => array(
                "goal" => "Derrote o chefe de " . nome_ilha($quinta_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 5000, "dobroes" => 0),
                "unlock" => [],
                "next" => 43,
                "check_progress" => function () use ($quinta_ilha) {
                    return check_progress_chefe_ilha_derrotado($quinta_ilha);
                }
            ),
            43 => array(
                "goal" => "Complete a incursão de " . nome_ilha($quinta_ilha),
                "link" => "incursao",
                "rewards" => array("xp" => 0, "berries" => 5000, "dobroes" => 0),
                "unlock" => [],
                "next" => 44,
                "check_progress" => function () use ($quinta_ilha) {
                    return check_progress_incursao_realizada($quinta_ilha);
                }
            ),
            // sexta ilha
            44 => array(
                "goal" => "Viaje até " . nome_ilha($sexta_ilha) . " em " . get_human_location($sexta_ilha_coord["x"], $sexta_ilha_coord["y"]),
                "link" => "oceano",
                "rewards" => array("xp" => 0, "berries" => 6000, "dobroes" => 0),
                "unlock" => [],
                "next" => 45,
                "check_progress" => function () use ($sexta_ilha) {
                    return check_progress_in_ilha($sexta_ilha);
                }
            ),
            45 => array(
                "goal" => "Complete todas as missões de " . nome_ilha($sexta_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 6000, "dobroes" => 0),
                "unlock" => [],
                "next" => 46,
                "check_progress" => function () use ($sexta_ilha) {
                    return check_progress_missoes_realizadas($sexta_ilha, -1);
                }
            ),
            46 => array(
                "goal" => "Derrote o chefe de " . nome_ilha($sexta_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 6000, "dobroes" => 0),
                "unlock" => [],
                "next" => 47,
                "check_progress" => function () use ($sexta_ilha) {
                    return check_progress_chefe_ilha_derrotado($sexta_ilha);
                }
            ),
            47 => array(
                "goal" => "Complete a incursão de " . nome_ilha($sexta_ilha),
                "link" => "incursao",
                "rewards" => array("xp" => 0, "berries" => 6000, "dobroes" => 0),
                "unlock" => [],
                "next" => 48,
                "check_progress" => function () use ($sexta_ilha) {
                    return check_progress_incursao_realizada($sexta_ilha);
                }
            ),
            // setima ilha
            48 => array(
                "goal" => "Viaje até " . nome_ilha($setima_ilha) . " em " . get_human_location($setima_ilha_coord["x"], $setima_ilha_coord["y"]),
                "link" => "oceano",
                "rewards" => array("xp" => 0, "berries" => 7000, "dobroes" => 0),
                "unlock" => [SISTEMA_SERVICO_TRANSPORTE],
                "next" => 49,
                "check_progress" => function () use ($setima_ilha) {
                    return check_progress_in_ilha($setima_ilha);
                }
            ),
            49 => array(
                "goal" => "Compre um barco maior",
                "link" => "estaleiro",
                "rewards" => array("xp" => 0, "berries" => 7000, "dobroes" => 0),
                "unlock" => [],
                "next" => 50,
                "check_progress" => function () {
                    return $this->navio["cod_navio"] > 1;
                }
            ),
            50 => array(
                "goal" => "Consiga 5 tripulantes",
                "link" => "recrutar",
                "rewards" => array("xp" => 0, "berries" => 7000, "dobroes" => 0),
                "unlock" => [],
                "next" => 51,
                "check_progress" => function () {
                    return check_progress_tripulantes_recrutados(5);
                }
            ),
            51 => array(
                "goal" => "Compre um novo casco para o navio",
                "link" => "estaleiro",
                "rewards" => array("xp" => 0, "berries" => 7000, "dobroes" => 0),
                "unlock" => [],
                "next" => 52,
                "check_progress" => function () {
                    return $this->navio["cod_casco"] > 0;
                }
            ),
            52 => array(
                "goal" => "Compre um canhão para o navio",
                "link" => "estaleiro",
                "rewards" => array("xp" => 0, "berries" => 7000, "dobroes" => 0),
                "unlock" => [],
                "next" => 53,
                "check_progress" => function () {
                    return $this->navio["cod_canhao"] > 0;
                }
            ),
            53 => array(
                "goal" => "Complete todas as missões de " . nome_ilha($setima_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 7000, "dobroes" => 0),
                "unlock" => [],
                "next" => 54,
                "check_progress" => function () use ($setima_ilha) {
                    return check_progress_missoes_realizadas($setima_ilha, -1);
                }
            ),
            54 => array(
                "goal" => "Derrote o chefe de " . nome_ilha($setima_ilha),
                "link" => "missoes",
                "rewards" => array("xp" => 0, "berries" => 7000, "dobroes" => 0),
                "unlock" => [],
                "next" => 55,
                "check_progress" => function () use ($setima_ilha) {
                    return check_progress_chefe_ilha_derrotado($setima_ilha);
                }
            ),
            55 => array(
                "goal" => "Complete a incursão de " . nome_ilha($setima_ilha),
                "link" => "incursao",
                "rewards" => array("xp" => 0, "berries" => 7000, "dobroes" => 0),
                "unlock" => [],
                "next" => 56,
                "check_progress" => function () use ($setima_ilha) {
                    return check_progress_incursao_realizada($setima_ilha);
                }
            ),
            56 => array(
                "goal" => "Evolua o capitão até o nível 15",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 15000, "dobroes" => 0),
                "unlock" => [],
                "next" => 57,
                "check_progress" => function () {
                    return $this->capitao["lvl"] >= 15;
                }
            ),
            57 => array(
                "goal" => "Entre na Grand Line",
                "link" => "oceano",
                "rewards" => array("xp" => 0, "berries" => 15000, "dobroes" => 0),
                "unlock" => [SISTEMA_DOMINIO_ILHA, SISTEMA_ALIANCAS, SISTEMA_TRIPULANTES_FORA_BARCO],
                "next" => 58,
                "check_progress" => function () {
                    return check_progress_in_ilha(29);
                }
            ),
            58 => array(
                "goal" => "Evolua o capitão até o nível 20",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 20000, "dobroes" => 0),
                "unlock" => [SISTEMA_HAKI],
                "next" => 59,
                "check_progress" => function () {
                    return $this->capitao["lvl"] >= 20;
                }
            ),
            59 => array(
                "goal" => "Evolua o capitão até o nível 25",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 25000, "dobroes" => 0),
                "unlock" => [],
                "next" => 60,
                "check_progress" => function () {
                    return $this->capitao["lvl"] >= 25;
                }
            ),
            60 => array(
                "goal" => "Evolua o capitão até o nível 30",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 30000, "dobroes" => 0),
                "unlock" => [],
                "next" => 61,
                "check_progress" => function () {
                    return $this->capitao["lvl"] >= 30;
                }
            ),
            61 => array(
                "goal" => "Evolua o capitão até o nível 35",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 35000, "dobroes" => 0),
                "unlock" => [],
                "next" => 62,
                "check_progress" => function () {
                    return $this->capitao["lvl"] >= 35;
                }
            ),
            62 => array(
                "goal" => "Evolua o capitão até o nível 40",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 40000, "dobroes" => 0),
                "unlock" => [],
                "next" => 63,
                "check_progress" => function () {
                    return $this->capitao["lvl"] >= 40;
                }
            ),
            63 => array(
                "goal" => "Evolua o capitão até o nível 45",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 45000, "dobroes" => 0),
                "unlock" => [],
                "next" => 64,
                "check_progress" => function () {
                    return $this->capitao["lvl"] >= 45;
                }
            ),
            64 => array(
                "goal" => "Entre no Novo Mundo",
                "link" => "oceano",
                "rewards" => array("xp" => 0, "berries" => 1000000, "dobroes" => 0),
                "unlock" => [],
                "next" => 65,
                "check_progress" => function () {
                    return check_progress_in_ilha(44);
                }
            ),
            65 => array(
                "goal" => "Evolua o capitão até o nível 50",
                "link" => "status",
                "rewards" => array("xp" => 0, "berries" => 1000000, "dobroes" => 0),
                "unlock" => [],
                "next" => 66,
                "check_progress" => function () {
                    return $this->capitao["lvl"] >= 50;
                }
            ),
            66 => array(
                "goal" => "Chegue em Laftel",
                "link" => "ranking",
                "rewards" => array("xp" => 0, "berries" => 0, "dobroes" => 0),
                "unlock" => [],
                "next" => 66,
                "check_progress" => function () {
                    return check_progress_in_ilha(47);
                }
            ),
        );
    }

    public function get_progress_info()
    {
        if (! $this->tripulacao) {
            return NULL;
        }

        $all_progress_info = $this->get_all_progress_info();
        if (isset($all_progress_info[$this->tripulacao["progress"]])) {
            return $all_progress_info[$this->tripulacao["progress"]];
        } else {
            return NULL;
        }
    }

    public function get_progress_reward()
    {
        $all_progress_info = $this->get_all_progress_info();
        if (isset($all_progress_info[$this->tripulacao["progress"]])) {
            return $all_progress_info[$this->tripulacao["progress"]]["rewards"];
        } else {
            return NULL;
        }
    }

    public function is_progress_finished()
    {
        if (! $this->tripulacao) {
            return false;
        }
        $progress_info = $this->get_progress_info();

        if (! isset($progress_info["check_progress"])) {
            return false;
        }
        return $progress_info["check_progress"]();
    }

    public function get_sistemas_desbloqueados()
    {
        $progresses_info = $this->get_all_progress_info();
        $sistemas = [];
        $progress = 0;

        while ($progress != $this->tripulacao["progress"]) {
            foreach ($progresses_info[$progress]["unlock"] as $sistema) {
                $sistemas[] = $sistema;
            }
            $progress = $progresses_info[$progress]["next"];
        }

        return $sistemas;
    }

    public function is_sistema_desbloqueado($sistema)
    {
        $sistemas = $this->get_sistemas_desbloqueados();
        foreach ($sistemas as $sistema_desbloqueado) {
            if ($sistema_desbloqueado == $sistema) {
                return true;
            }
        }
        return false;
    }

    public function get_next_progress()
    {
        if (! $this->tripulacao) {
            return NULL;
        }

        $all_progress_info = $this->get_all_progress_info();
        if (isset($all_progress_info[$this->tripulacao["progress"]])) {
            return $all_progress_info[$this->tripulacao["progress"]]["next"];
        } else {
            return NULL;
        }
    }

    public function get_pers_by_cod($cod, $fora_barco = false)
    {
        if (! $fora_barco) {
            foreach ($this->personagens as $pers) {
                if ($pers["cod"] == $cod) {
                    return $pers;
                }
            }
        } else {
            $result = $this->connection->run("SELECT * FROM tb_personagens WHERE cod = ? AND id = ?",
                "ii", array($cod, $this->tripulacao["id"]));

            return $result->count() ? $result->fetch_array() : null;
        }
        return NULL;
    }

    public function xp_for_all($quant)
    {
        if ($bonus = $this->buffs->get_efeito("bonus_xp")) {
            $quant += round($bonus * $quant);
        }

        $quant_lvl_max = $quant;
        if ($bonus = $this->buffs->get_efeito("multiplicador_xp_lvl_max")) {
            $quant_lvl_max *= $bonus;
        }

        $this->connection->run("UPDATE tb_personagens SET xp = xp + ? WHERE id = ? AND lvl < 50 AND ativo = 1",
            "ii", array($quant, $this->tripulacao["id"]));

        $this->connection->run("UPDATE tb_personagens SET xp = xp + ? WHERE id = ? AND lvl >= 50 AND ativo = 1",
            "ii", array($quant_lvl_max, $this->tripulacao["id"]));
    }
    public function remove_xp_personagem($quant, $pers)
    {
        $this->connection->run("UPDATE tb_personagens SET xp = xp - ? WHERE cod = ?",
            "ii", array($quant, $pers["cod"]));
    }

    public function xp_for_profissao($quant, $prof)
    {
        $this->connection->run("UPDATE tb_personagens SET profissao_xp = LEAST(profissao_xp + ?, profissao_xp_max) WHERE id = ? AND profissao = ? AND ativo = 1",
            "iii", array($quant, $this->tripulacao["id"], $prof));
    }

    public function haki_for_all($quant)
    {
        foreach ($this->personagens as $pers) {
            $this->add_haki($pers, $quant);
        }
    }

    public function add_berries($quant)
    {
        $this->connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
            "ii", array($quant, $this->tripulacao["id"]));
    }

    public function add_haki($pers, $quant)
    {
        if ($pers["haki_lvl"] >= HAKI_LVL_MAX) {
            return;
        }
        if ($bonus = $this->buffs->get_efeito("bonus_haki")) {
            $quant += round($bonus * $quant);
        }

        $haki_xp = $pers["haki_xp"] + $quant;
        if ($haki_xp >= $pers["haki_xp_max"]) {
            $haki_xp = $haki_xp - $pers["haki_xp_max"];
            $lvl = $pers["haki_lvl"] + 1;
            $pts = $pers["haki_pts"] + 1;
            $haki_max = $pers["haki_xp_max"] + 1000;
        } else {
            $lvl = $pers["haki_lvl"];
            $pts = $pers["haki_pts"];
            $haki_max = $pers["haki_xp_max"];
        }

        $this->connection->run(
            "UPDATE tb_personagens
			SET haki_xp='$haki_xp', haki_lvl='$lvl', haki_pts='$pts', haki_xp_max='$haki_max'
			WHERE  cod = ?",
            "i", $pers["cod"]
        );
    }

    public function can_add_item($quant = 1, $id = null)
    {
        if (! $id) {
            $id = $this->tripulacao["id"];
        }
        $item_count = $this->connection->run("SELECT count(id) AS total FROM tb_usuario_itens WHERE id = ?",
            "i", array($id))->fetch_array()["total"];
        $navio = $this->connection->run("SELECT * FROM tb_usuario_navio WHERE id = ?", "i", array($id))->fetch_array();
        return ($item_count + $quant) <= $navio["capacidade_inventario"];
    }

    public function add_equipamento($equipamento)
    {
        $id = $this->equipamentos->create_equipamento($equipamento);

        return $this->add_item($id, TIPO_ITEM_EQUIPAMENTO, 1, true);
    }

    public function add_equipamento_by_cod($cod_equipamento)
    {
        $result = $this->connection->run("SELECT * FROM tb_equipamentos WHERE item = ?",
            "i", array($cod_equipamento));

        if (! $result->count()) {
            return false;
        }

        $equipamento = $result->fetch_array();

        return $this->add_equipamento($equipamento);
    }

    public function get_item($cod_item, $tipo_item, $id = null)
    {
        if (! $id) {
            $id = $this->tripulacao["id"];
        }
        $exist = $this->connection->run("SELECT * FROM tb_usuario_itens WHERE tipo_item = ? AND cod_item = ? AND id = ?",
            "iii", array($tipo_item, $cod_item, $id));
        return $exist->count() ? $exist->fetch_array() : NULL;
    }

    public function add_item($cod_item, $tipo_item, $quant, $unique = false, $id = null)
    {
        if (! $id) {
            $id = $this->tripulacao["id"];
        }
        if (! $this->can_add_item(1, $id)) {
            return false;
        }

        $item = $this->get_item($cod_item, $tipo_item, $id);

        if ($unique || ! $item) {
            $this->connection->run("INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant) VALUES (?, ?, ?, ?)",
                "iiii", array($id, $cod_item, $tipo_item, $quant));
        } else {
            $quant += $item["quant"];

            $this->connection->run("UPDATE tb_usuario_itens SET quant = ?, novo = 1 WHERE cod_item = ? AND tipo_item = ? AND id = ?",
                "iiii", array($quant, $cod_item, $tipo_item, $id));
        }

        return true;
    }

    public function reduz_item($cod_item, $tipo_item, $quant, $unique = false)
    {
        if ($unique) {
            $this->connection->run("DELETE FROM tb_usuario_itens WHERE cod_item = ? AND tipo_item = ? AND id = ? LIMIT 1",
                "iii", array($cod_item, $tipo_item, $this->tripulacao["id"]));
        } else {
            $item = $this->get_item($cod_item, $tipo_item);

            if ($item) {
                $nQuant = $item["quant"] - $quant;

                if ($nQuant <= 0) {
                    $this->connection->run("DELETE FROM tb_usuario_itens WHERE cod_item = ? AND tipo_item = ? AND id = ?",
                        "iii", array($cod_item, $tipo_item, $this->tripulacao["id"]));
                } else {
                    $this->connection->run("UPDATE tb_usuario_itens SET quant = ? WHERE cod_item = ? AND tipo_item = ? AND id = ?",
                        "iiii", array($nQuant, $cod_item, $tipo_item, $this->tripulacao["id"]));
                }
            }
        }
    }


    public function reduz_gold_or_dobrao($tipo, $quant_gold, $quant_dobrao, $scrit)
    {
        if ($tipo == "gold") {
            $this->reduz_gold($quant_gold, $scrit);
        } else if ($tipo == "dobrao") {
            $this->reduz_dobrao($quant_dobrao, $scrit);
        }
    }

    public function reduz_gold($quant, $script)
    {
        if ($this->conta["gold"] < $quant) {
            return false;
        }

        $this->connection->run(
            "UPDATE tb_conta SET gold = gold - ? WHERE conta_id = ?",
            "ii", array($quant, $this->conta["conta_id"])
        );

        //        $gasto = $this->connection->run("SELECT sum(quant) AS total FROM tb_gold_log WHERE user_id = ? AND quando > '2017-11-23 00:00:00' AND quando <'2017-11-25 00:00:00'",
//            "i", array($this->tripulacao["id"]))->fetch_array()["total"];
//
//        $gasto = $gasto % 50;
//
//        $bonus = floor(($gasto + $quant) / 50);
//
//        if ($bonus) {
//            $this->connection->run("UPDATE tb_usuarios SET free_reset_atributos = free_reset_atributos + ? WHERE id = ?",
//                "ii", array($bonus, $this->tripulacao["id"]));
//        }

        $this->connection->run(
            "INSERT INTO tb_gold_log (user_id, quant, script) VALUES (?, ? ,?)",
            "iis", array($this->tripulacao["id"], $quant, $script)
        );

        return true;
    }

    public function reduz_dobrao_criado($quant, $script)
    {
        if ($this->conta["dobroes_criados"] < $quant) {
            return false;
        }

        $this->connection->run(
            "UPDATE tb_conta SET dobroes_criados = dobroes_criados - ? WHERE conta_id = ?",
            "ii", array($quant, $this->conta["conta_id"])
        );

        $this->connection->run(
            "INSERT INTO tb_dobroes_log (conta_id, tripulacao_id, quant, script) VALUES (?, ?, ? ,?)",
            "iiis", array($this->conta["conta_id"], $this->tripulacao["id"], $quant, $script)
        );

        return true;
    }

    public function reduz_dobrao($quant, $script)
    {
        if ($this->conta["dobroes"] < $quant) {
            return false;
        }

        $this->connection->run(
            "UPDATE tb_conta SET dobroes = dobroes - ? WHERE conta_id = ?",
            "ii", array($quant, $this->conta["conta_id"])
        );

        $this->connection->run(
            "INSERT INTO tb_dobroes_log (conta_id, tripulacao_id, quant, script) VALUES (?, ?, ? ,?)",
            "iiis", array($this->conta["conta_id"], $this->tripulacao["id"], $quant, $script)
        );

        return true;
    }

    public function reduz_berries($quant)
    {
        if ($this->tripulacao["berries"] < $quant) {
            return false;
        }
        $this->connection->run("UPDATE tb_usuarios SET berries = berries - ? WHERE id = ?",
            "ii", array($quant, $this->tripulacao["id"]));

        return true;
    }

    public function has_alert($menu)
    {
        return isset($this->alerts_data[$menu]);
    }

    public function render_alert($menu, $classe = null)
    {
        if ($this->has_alert($menu)) {
            echo $this->alerts->get_alert($classe);
        }
    }

    public function has_super_alert($menu)
    {
        return isset($this->super_alerts_data[$menu]);
    }
}
