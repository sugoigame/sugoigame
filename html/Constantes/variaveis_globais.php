<?php
define('VARIAVEL_BALANCO_VENDA_DOBRAO',     'BALANCO_VENDA_DOBRAO');
define('VARIAVEL_RDP',                      'RDP');
define('VARIAVEL_ADF',                      'ADF');
define('VARIAVEL_VENCEDORES_ERA_PIRATA',    'VENCEDORES_ERA_PIRATA');
define('VARIAVEL_VENCEDORES_ERA_MARINHA',   'VENCEDORES_ERA_MARINHA');
define('VARIAVEL_IDS_ACESSO_IMPEL_DOWN',    'IDS_ACESSO_IMPEL_DOWN');
define('VARIAVEL_IDS_ACESSO_ENIES_LOBBY',   'IDS_ACESSO_ENIES_LOBBY');
define('VARIAVEL_ALMIRANTES',               'ALMIRANTES');
define('VARIAVEL_YONKOUS',                  'YONKOUS');
define('VARIAVEL_EVENTO_PERIODICO_ATIVO',   'EVENTO_PERIODICO_ATIVO');
define('VARIAVEL_VENCEDORES_INCURSAO',      'VENCEDORES_INCURSAO');

function get_value_variavel_global($variavel) {
    global $connection;
    $result = $connection->run("SELECT * FROM tb_variavel_global WHERE variavel = ?", "s", [
        $variavel
    ]);
    return $result->count() ? $result->fetch_array() : NULL;
}

function get_value_varchar_variavel_global($variavel) {
    $value = get_value_variavel_global($variavel);

    return $value["valor_varchar"];
}