<?php
define('VARIAVEL_BALANCO_VENDA_DOBRAO',     'BALANCO_VENDA_DOBRAO');
define('VARIAVEL_VENCEDORES_ERA_PIRATA',    'VENCEDORES_ERA_PIRATA');
define('VARIAVEL_VENCEDORES_ERA_MARINHA',   'VENCEDORES_ERA_MARINHA');
define('VARIAVEL_IDS_ACESSO_IMPEL_DOWN',    'IDS_ACESSO_IMPEL_DOWN');
define('VARIAVEL_IDS_ACESSO_ENIES_LOBBY',   'IDS_ACESSO_ENIES_LOBBY');
define('VARIAVEL_ALMIRANTES',               'ALMIRANTES');
define('VARIAVEL_YONKOUS',                  'YONKOUS');
define('VARIAVEL_EVENTO_PERIODICO_ATIVO',   'EVENTO_PERIODICO_ATIVO');
define('VARIAVEL_VENCEDORES_INCURSAO',      'VENCEDORES_INCURSAO');
define('VARIAVEL_TOTAL_HAKI_TREINOS',       'TOTAL_HAKI_TREINOS');
define('VARIAVEL_COLISEU',                  'STATUS_COLISEU');

function get_value_variavel_global($variavel) {
    global $connection;

    $result = $connection->run("SELECT * FROM tb_variavel_global WHERE variavel = ? LIMIT 1", "s", [
        $variavel
    ]);
    return $result->count() == 1 ? $result->fetch_array() : FALSE;
}

function get_value_varchar_variavel_global($variavel) {
    $value = get_value_variavel_global($variavel);

    return $value ? $value["valor_varchar"] : FALSE;
}