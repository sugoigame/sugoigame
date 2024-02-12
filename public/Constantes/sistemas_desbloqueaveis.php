<?php
define('SISTEMA_HOSPITAL', 'SISTEMA_HOSPITAL');
define('SISTEMA_ACADEMIA', 'SISTEMA_ACADEMIA');
define('SISTEMA_VISAO_GERAL_TRIPULACAO', 'SISTEMA_VISAO_GERAL_TRIPULACAO');
define('SISTEMA_ESTALEIRO', 'SISTEMA_ESTALEIRO');
define('SISTEMA_BANDEIRA', 'SISTEMA_BANDEIRA');
define('SISTEMA_RECRUTAR_TRIPULANTE', 'SISTEMA_RECRUTAR_TRIPULANTE');
define('SISTEMA_INCURSOES', 'SISTEMA_INCURSOES');
define('SISTEMA_TESOUROS', 'SISTEMA_TESOUROS');
define('SISTEMA_PROFISSOES', 'SISTEMA_PROFISSOES');
define('SISTEMA_PESQUISAS', 'SISTEMA_PESQUISAS');
define('SISTEMA_RESTAURANTE', 'SISTEMA_RESTAURANTE');
define('SISTEMA_CACA', 'SISTEMA_CACA');
define('SISTEMA_OCEANO', 'SISTEMA_OCEANO');
define('SISTEMA_CALENDARIO', 'SISTEMA_CALENDARIO');
define('SISTEMA_SERVICO_TRANSPORTE', 'SISTEMA_SERVICO_TRANSPORTE');
define('SISTEMA_DOMINIO_ILHA', 'SISTEMA_DOMINIO_ILHA');
define('SISTEMA_ALIANCAS', 'SISTEMA_ALIANCAS');
define('SISTEMA_EVENTOS', 'SISTEMA_EVENTOS');
define('SISTEMA_TRIPULANTES_FORA_BARCO', 'SISTEMA_TRIPULANTES_FORA_BARCO');
define('SISTEMA_HAKI', 'SISTEMA_HAKI');
define('SISTEMA_EQUIPAMENTOS', 'SISTEMA_EQUIPAMENTOS');
define('SISTEMA_COLISEU', 'SISTEMA_COLISEU');
define('SISTEMA_MAESTRIA', 'SISTEMA_MAESTRIA');

$sessoes_por_sistema = array(
    SISTEMA_ACADEMIA => ["academia"],
    SISTEMA_EQUIPAMENTOS => ["equipShop", "mercado", "materiais", "upgrader", "forja", "oficina", "equipamentos"],
    SISTEMA_ESTALEIRO => ["estaleiro"],
    SISTEMA_RESTAURANTE => ["restaurante"],
    SISTEMA_HOSPITAL => ["hospital"],
    SISTEMA_PROFISSOES => ["profissoesAprender"],
    SISTEMA_TRIPULANTES_FORA_BARCO => ["tripulantesInativos"],
    SISTEMA_DOMINIO_ILHA => ["politicaIlha"],
    SISTEMA_COLISEU => ["coliseu", "localizadorCasual", "localizadorCompetitivo"],
    SISTEMA_INCURSOES => ["incursao"],
    SISTEMA_PESQUISAS => ["missoesR"],
    SISTEMA_RECRUTAR_TRIPULANTE => ["recrutar", "expulsar"],
    SISTEMA_VISAO_GERAL_TRIPULACAO => ["status"],
    SISTEMA_HAKI => ["haki"],
    SISTEMA_EVENTOS => ["lojaEvento"],
    SISTEMA_CACA => ["missoesCaca"],
    SISTEMA_ALIANCAS => ["aliancaLista", "aliancaCriar", "aliancaBanco", "alianca", "aliancaDiplomacia", "aliancaCooperacao", "aliancaMissoes"],
    SISTEMA_BANDEIRA => ["bandeira"],
    SISTEMA_SERVICO_TRANSPORTE => ["transporte"],
    SISTEMA_OCEANO => ["amigaveis", "servicoDenDen"]
);

$sistemas_por_sessao = [];
foreach ($sessoes_por_sistema as $sistema => $sessoes) {
    foreach ($sessoes as $sessao) {
        $sistemas_por_sessao[$sessao] = $sistema;
    }
}
