<?php

// Permite importar classes automaticamente com uso de namespaces
spl_autoload_register(function ($class) {
    $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $file = str_replace("scripts", "public" . DIRECTORY_SEPARATOR, __DIR__) . $class_path . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

include "../public/Classes/DataLoader.php";
include "../public/Classes/MapLoader.php";
include "../public/Funcoes/atributos.php";
include "../public/Constantes/skills.php";

$akumas = DataLoader::load('akumas');
$animacoes = MapLoader::load_file("../public/Imagens/Skils/Animacoes/Animations.json");

function get_bonus_atr($atrs_list_string, $cod_arquetipo, $index)
{
    $atrs = explode(",", $atrs_list_string);
    if (count($atrs) == 1) {
        return $atrs[0];
    } else {
        if (count($atrs) < 5) {
            for ($i = count($atrs); $i < 5; $i++) {
                $atrs[] = $atrs[$i - 1];
            }
        }
        if ($cod_arquetipo == 0) {
            return $atrs[$index];
        } elseif ($cod_arquetipo == 1) {
            return $atrs[$index / 2];
        } else {
            return $atrs[$index == 1 ? 0 : 1];
        }
    }
}

function get_arquetipo_passiva($akuma, $cod_arquetipo)
{

    $atrs = ["atk", "def", "agl", "pre", "res", "dex", "con"];
    $atr_count = [];
    foreach ($atrs as $atr) {
        $atr_count[$atr] = 0;
        foreach ($akuma["arquetipos"] as $arquetipo) {
            if (str_contains($arquetipo, $atr)) {
                $atr_count[$atr]++;
            }
        }
    }

    $max_qnt_1 = 0;
    $max_atr_1 = $atrs[array_rand($atrs)];
    $max_qnt_2 = 0;
    $max_atr_2 = $atrs[array_rand($atrs)];
    foreach ($atrs as $atr) {
        if ($atr_count[$atr] > $max_qnt_1) {
            $max_atr_2 = $max_atr_1;
            $max_qnt_2 = $max_qnt_1;
            $max_atr_1 = $atr;
            $max_qnt_1 = $atr_count[$atr];
        } elseif ($atr_count[$atr] > $max_qnt_2) {
            $max_atr_2 = $atr;
            $max_qnt_2 = $atr_count[$atr];
        }
    }

    return "passiva_" . ($cod_arquetipo == -1 ? $max_atr_1 : $max_atr_2);
}

function createSkill($akuma, $index, $lvl)
{
    global $animacoes;

    $vontades = [10 => 6, 20 => 10, 30 => 18, 40 => 26, 50 => 36];
    $arquetipos = [10 => 0, 20 => -1, 30 => 1, 40 => -2, 50 => 2];

    $vontade = $vontades[$lvl];
    $cod_arquetipo = $arquetipos[$lvl];
    if ($cod_arquetipo >= 0) {
        $arquetipo = $akuma["arquetipos"][$cod_arquetipo];
    } else {
        $arquetipo = get_arquetipo_passiva($akuma, $cod_arquetipo);
    }

    $cod_skill = 30000 + ($akuma["cod_akuma"] * 100) + $lvl;
    $description = $akuma["nome"] . " - " . $lvl . " " . $arquetipo;

    // "cod_akuma" => $akuma["cod_akuma"],

    $skill = [
        "cod" => $cod_skill,
        "icone" => rand(1, 514),
        "nome" => $akuma["nome"] . " - " . $lvl,
        "animacao" => $animacoes[rand(1, count($animacoes) - 1)]["name"],
        "descricao" => $description,
        "vontade" => $vontade,
        "requisito_lvl" => $lvl,
        "recarga" => ($lvl - 10) / 10,
    ];

    if ($arquetipo == "Dano") {
        $skill["explicacao"] = "Causa {DANO} dividido entre os alvos atingidos. O dano aumenta conforme a vontade da tripulação.";
        $skill["dano"] = 1.2;
        $skill["alcance"] = 1;
        $skill["area"] = 3;
    } elseif ($arquetipo == "Alcance") {
        $skill["explicacao"] = "Causa {DANO} dividido entre os alvos atingidos. O dano aumenta conforme a vontade da tripulação.";
        $skill["alcance"] = 10;
        $skill["area"] = 3;
    } elseif ($arquetipo == "Area") {
        $skill["explicacao"] = "Causa {DANO} dividido entre os alvos atingidos. O dano aumenta conforme a vontade da tripulação.";
        $skill["alcance"] = 2;
        $skill["area"] = 5;
    } elseif ($arquetipo == "Imobilizar") {
        $skill["explicacao"] = "Causa {DANO} dividido entre os alvos atingidos e aplica imobilização por 3 turnos. O dano aumenta conforme a vontade da tripulação. Personagens imobilizados não podem se mover.";
        $skill["alcance"] = 3;
        $skill["area"] = 2;
        $skill["efeitos"] = [
            "acerto" => [
                [
                    "tipo" => "NEGATIVO",
                    "explicacao" => "Imobilização: Não pode se mover",
                    "duracao" => 3,
                    "bonus" => [
                        "atr" => "IMOBILIZACAO",
                        "valor" => 1
                    ]
                ]
            ]
        ];
    } elseif ($arquetipo == "Veneno") {
        $skill["explicacao"] = "Causa {DANO} dividido entre os alvos atingidos e aplica veneno por 6 turnos. O dano aumenta conforme a vontade da tripulação. Personagens envenenados perdem vida a cada turno.";
        $skill["alcance"] = 3;
        $skill["area"] = 2;
        $skill["efeitos"] = [
            "acerto" => [
                [
                    "tipo" => "NEGATIVO",
                    "explicacao" => "Veneno: Perde 250 pontos de vida a cada turno.",
                    "duracao" => 6,
                    "bonus" => [
                        "atr" => "Veneno",
                        "valor" => 250
                    ]
                ]
            ]
        ];
    } elseif ($arquetipo == "Sangramento") {
        $skill["explicacao"] = "Causa {DANO} dividido entre os alvos atingidos e aplica sangramento por 3 turnos. O dano aumenta conforme a vontade da tripulação. Personagens com sangramento perdem vida a cada turno.";
        $skill["alcance"] = 3;
        $skill["area"] = 2;
        $skill["efeitos"] = [
            "acerto" => [
                [
                    "tipo" => "NEGATIVO",
                    "explicacao" => "Sangramento: Perde 500 pontos de vida a cada turno.",
                    "duracao" => 3,
                    "bonus" => [
                        "atr" => "Sangramento",
                        "valor" => 500
                    ]
                ]
            ]
        ];
    } elseif (str_starts_with($arquetipo, "AB")) {
        $bonus_atr = get_bonus_atr(str_replace("AB:", "", $arquetipo), $cod_arquetipo, $index);
        $skill["explicacao"] = "Causa {DANO} com adicional de {" . strtoupper($bonus_atr) . "} +30% dividido entre os alvos atingidos. O dano aumenta conforme a vontade da tripulação.";
        $skill["alcance"] = 3;
        $skill["area"] = 2;
        $skill["efeitos"] = [
            "pre_ataque" => [
                [
                    "tipo_alvo" => "ATACANTE",
                    "duracao" => 0,
                    "bonus" => [
                        "atr" => $bonus_atr . "_porcentagem",
                        "valor" => 0.3
                    ]
                ]
            ]
        ];
    } elseif (str_starts_with($arquetipo, "Buff")) {
        $bonus_atr = get_bonus_atr(str_replace("Buff:", "", $arquetipo), $cod_arquetipo, $index);
        $skill["explicacao"] = "Causa {DANO} dividido entre os inimigos atingidos e aplica um buff de {" . strtoupper($bonus_atr) . "} +30% por 3 turnos nos aliados atingidos. O dano aumenta conforme a vontade da tripulação.";
        $skill["alcance"] = 1;
        $skill["area"] = 2;
        $skill["efeitos"] = [
            "acerto" => [
                [
                    "tipo_alvo" => "ALVO",
                    "filtro_alvo" => "ALIADO",
                    "duracao" => 3,
                    "bonus" => [
                        "atr" => $bonus_atr . "_porcentagem",
                        "valor" => 0.3
                    ]
                ]
            ]
        ];
    } elseif (str_starts_with($arquetipo, "Debuff")) {
        $bonus_atr = get_bonus_atr(str_replace("Debuff:", "", $arquetipo), $cod_arquetipo, $index);
        $skill["explicacao"] = "Causa {DANO} dividido entre os inimigos atingidos e aplica um debuff de {" . strtoupper($bonus_atr) . "} -30% por 3 turnos. O dano aumenta conforme a vontade da tripulação.";
        $skill["alcance"] = 1;
        $skill["area"] = 2;
        $skill["efeitos"] = [
            "acerto" => [
                [
                    "tipo_alvo" => "ALVO",
                    "filtro_alvo" => "INIMIGO",
                    "duracao" => 3,
                    "bonus" => [
                        "atr" => $bonus_atr . "_porcentagem",
                        "valor" => -0.3
                    ]
                ]
            ]
        ];
    } elseif (str_starts_with($arquetipo, "passiva_")) {
        $bonus_atr = str_replace("passiva_", "", $arquetipo);
        $skill["explicacao"] = "Efeito passivo: {" . strtoupper($bonus_atr) . "} +10.";
        $skill["dano"] = 0;
        $skill["alcance"] = 0;
        $skill["efeitos"] = [
            "passivos" => [
                [
                    "duracao" => -1,
                    "bonus" => [
                        "atr" => $bonus_atr,
                        "valor" => 10
                    ]
                ]
            ]
        ];
    } else {
        echo $arquetipo;
        return [];
    }

    return $skill;
}

$skills = ["akumas" => []];
foreach ($akumas as $akuma) {
    $habilidades = [];
    $lvls = [10, 20, 30, 40, 50];
    foreach ($lvls as $index => $lvl) {
        $habilidades[] = createSkill($akuma, $index, $lvl);
    }

    $skills["akumas"][$akuma["cod_akuma"]] = [
        "nome" => $akuma["nome"],
        "habilidades" => $habilidades
    ];
}

$yml = \Classes\Spyc::YAMLDump($skills, );

$file = fopen("../public/Data/skil_akuma.yaml", "w");
fwrite($file, $yml);
fclose($file);
