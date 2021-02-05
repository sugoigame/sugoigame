<?php

require __DIR__ . "/../../../../../game/Classes/DataLoader.php";
require __DIR__ . "/../../../../../game/Classes/MapLoader.php";

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Welcome
 *
 * @author Ivan
 */
class Welcome extends CI_Controller {

    function index() {
        $min_x = $this->input->get('minX');
        $min_y = $this->input->get('minY');
        $max_x = $this->input->get('maxX');
        $max_y = $this->input->get('maxY');

        $tipo = $this->input->get('tipo');

        if ($tipo == "ilha") {
            $ilhas = MapLoader::load("mapa_ilhas");

            echo json_encode($ilhas);
        } else if ($tipo == 'nao_navegavel') {
            $areas = MapLoader::load("mapa_nao_navegavel");

            echo json_encode($this->format_for_api($areas, $min_x, $min_y, $max_x, $max_y));
        } else if ($tipo == 'nevoa') {
            $areas = MapLoader::load("mapa_nevoa");

            echo json_encode($this->format_for_api($areas, $min_x, $min_y, $max_x, $max_y));
        } else if ($tipo == 'corrente') {
            $areas = MapLoader::load("mapa_corrente");

            echo json_encode($this->format_for_api($areas, $min_x, $min_y, $max_x, $max_y));
        } else if ($tipo == 'redemoinho') {
            $areas = MapLoader::load("mapa_redemoinho");

            echo json_encode($this->format_for_api($areas, $min_x, $min_y, $max_x, $max_y));
        } else if ($tipo == 'mergulho') {
            $areas = MapLoader::load("mapa_zona_mergulho");

            echo json_encode($areas);
        } else if ($tipo == 'exploracao') {
            $areas = MapLoader::load("mapa_zona_exploracao");

            echo json_encode($areas);
        } else if ($tipo == 'rdm') {
            $areas = MapLoader::load("mapa_zona_rdm");

            echo json_encode($areas);
        }
    }

    private function format_for_api($data, $min_x, $min_y, $max_x, $max_y) {
        $retorno = [];
        foreach ($data as $x => $d) {
            foreach ($d as $y => $info) {
                if ($x >= $min_x && $x <= $max_x && $y >= $min_y && $y <= $max_y) {
                    $r = array("x" => $x, "y" => $y);
                    if (!is_array($info)) {
                        $info = array("info" => $info);
                    }
                    $retorno[] = array_merge($info, $r);
                }
            }
        }
        return $retorno;
    }

    function get_rdm_zona() {
        $this->db->select("*");
        $this->db->from("tb_mapa_rdm_zona zona");
        $this->db->join("tb_mapa_rdm rdm", "zona.rdm_id = rdm.rdm_id");
        $this->db->where("zona.zona", $this->input->get("zona"));

        $rdms = $this->db->get();

        echo json_encode($rdms->result_array());
    }

    function get_rdms() {
        $rdms = $this->db->get("tb_mapa_rdm");

        echo json_encode($rdms->result_array());
    }

    function add_ilha() {
        $id = $this->input->get('id');
        $nome = $this->input->get('nome');
        $x = $this->input->get('x');
        $y = $this->input->get('y');

        $this->load->database();

        if ($id === 'novo') {
            $this->db->insert("tb_ilha_ilhas", array(
                "nome" => $nome,
                "x" => $x,
                "y" => $y
            ));
        } else {
            $this->db->update("tb_ilha_ilhas", array(
                "nome" => $nome,
                "x" => $x,
                "y" => $y
            ), array("ilha_id" => $id));
        }
    }

    function remove_ilha() {
        $id = $this->input->get('id');

        $this->load->database();

        $this->db->delete("tb_ilha_ilhas", array("ilha_id" => $id));
    }

    function add_nao_navegavel() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');

        $areas = MapLoader::load("mapa_nao_navegavel");
        $areas[$x][$y] = true;
        MapLoader::save($areas, "mapa_nao_navegavel");
    }

    function remove_nao_navegavel() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');

        $areas = MapLoader::load("mapa_nao_navegavel");
        if (isset($areas[$x]) && isset($areas[$x][$y])) {
            unset($areas[$x][$y]);
            MapLoader::save($areas, "mapa_nao_navegavel");
        }
    }

    function add_nevoa() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $intensidade = $this->input->get('intensidade');
        $areas = MapLoader::load("mapa_nevoa");
        $areas[$x][$y] = $intensidade;
        MapLoader::save($areas, "mapa_nevoa");
    }

    function remove_nevoa() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $areas = MapLoader::load("mapa_nevoa");
        if (isset($areas[$x]) && isset($areas[$x][$y])) {
            unset($areas[$x][$y]);
            MapLoader::save($areas, "mapa_nevoa");
        }
    }

    function add_corrente() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $intensidade = $this->input->get('intensidade');
        $direcao = $this->input->get('direcao');
        $areas = MapLoader::load("mapa_corrente");
        $areas[$x][$y] = array("intensidade" => $intensidade, "direcao" => $direcao);
        MapLoader::save($areas, "mapa_corrente");
    }

    function remove_corrente() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $areas = MapLoader::load("mapa_corrente");
        if (isset($areas[$x]) && isset($areas[$x][$y])) {
            unset($areas[$x][$y]);
            MapLoader::save($areas, "mapa_corrente");
        }
    }

    function add_redemoinho() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $areas = MapLoader::load("mapa_redemoinho");
        $areas[$x][$y] = true;
        MapLoader::save($areas, "mapa_redemoinho");
    }

    function remove_redemoinho() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $areas = MapLoader::load("mapa_redemoinho");
        if (isset($areas[$x]) && isset($areas[$x][$y])) {
            unset($areas[$x][$y]);
            MapLoader::save($areas, "mapa_redemoinho");
        }
    }

    function add_mergulho() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $zona = $this->input->get('zona');
        $this->db->insert("tb_mapa_zona_mergulho", array(
            "x" => $x,
            "y" => $y,
            "zona" => $zona
        ));
    }

    function remove_mergulho() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $this->db->delete("tb_mapa_zona_mergulho", array(
            "x" => $x,
            "y" => $y
        ));
    }

    function add_exploracao() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $zona = $this->input->get('zona');
        $this->db->insert("tb_mapa_zona_exploracao", array(
            "x" => $x,
            "y" => $y,
            "zona" => $zona
        ));
    }

    function remove_exploracao() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $this->db->delete("tb_mapa_zona_exploracao", array(
            "x" => $x,
            "y" => $y
        ));
    }

    function add_zona_rdm() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $zona = $this->input->get('zona');
        $this->db->insert("tb_mapa_zona_rdm", array(
            "x" => $x,
            "y" => $y,
            "zona" => $zona
        ));
    }

    function remove_zona_rdm() {
        $x = $this->input->get('x');
        $y = $this->input->get('y');
        $this->db->delete("tb_mapa_zona_rdm", array(
            "x" => $x,
            "y" => $y
        ));
    }

    function add_rdm_a_zona() {
        $zona = $this->input->get('zona');
        $rdm = $this->input->get('rdm_id');
        $chance = $this->input->get('chance');
        $this->db->insert("tb_mapa_rdm_zona", array(
            "zona" => $zona,
            "rdm_id" => $rdm,
            "chance" => $chance
        ));
    }

    function remove_rdm_da_zona() {
        $zona = $this->input->get('zona');
        $rdm = $this->input->get('rdm_id');
        $this->db->delete("tb_mapa_rdm_zona", array(
            "zona" => $zona,
            "rdm_id" => $rdm
        ));
    }

    function create_rdm() {
        $this->db->delete("tb_mapa_rdm", array(
            "nome" => $this->input->get('nome'),
            "img" => $this->input->get('img'),
            "hp" => $this->input->get('hp'),
            "hp_max" => $this->input->get('hp_max'),
            "atk" => $this->input->get('atk'),
            "def" => $this->input->get('def'),
            "agl" => $this->input->get('agl'),
            "res" => $this->input->get('res'),
            "pre" => $this->input->get('pre'),
            "dex" => $this->input->get('dex'),
            "con" => $this->input->get('con'),
            "dano" => $this->input->get('dano'),
            "armadura" => $this->input->get('armadura'),
            "xp" => $this->input->get('xp')
        ));
    }

}
