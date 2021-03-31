<?php
function isset_and_true($var, $key) {
    return isset($var[$key]) && $var[$key];
}

function get_super_menu($sess = null) {
    if (!$sess) {
        $sess = $_GET["sessao"];
    }
    switch ($sess) {
        case "era1":
            return "era";
        case "home":
        case "noticias":
        case "noticia":
        case "akumaBook":
        case "ranking":
        case "conta":
        case "kanban":
        case "calculadoras":
        case "vipLoja":
        case "vipComprar":
        case "cadastro":
        case "cadastrosuccess":
        case "seltrip":
        case "calendario":
        case "recrutamento":
        case "torneio":
        case "recuperarSenha":
        case "politica":
        case "regras":
            return "principal";
        case "campanhaImpelDown":
        case "campanhaEniesLobby":
            return "campanha";
        case "status":
        case "habilidades":
        case "equipamentos":
        case "profissoes":
        case "akuma":
        case "haki":
        case "realizacoes":
        case "listaNegra":
        case "tatics":
        case "combateLog":
        case "wantedLog":
        case "karma":
        case "skins":
            return "tripulacao";
        case "statusNavio":
        case "navioSkin":
        case "obstaculos":
        case "quartos":
        case "forja":
        case "oficina":
            return "navio";
        case "missoes":
        case "recrutar":
        case "expulsar":
        case "tripulantesInativos":
        case "mercado":
        case "equipShop":
        case "upgrader":
        case "materiais":
        case "restaurante":
        case "estaleiro":
        case "hospital":
        case "pousada":
        case "academia":
        case "profissoesAprender":
        case "missoesCaca":
        case "missoesR":
        case "arvoreAnM":
        case "coliseu":
        case "leiloes":
        case "incursao":
        case "politicaIlha":
            return "ilha";
        case "oceano":
        case "amigaveis":
        case "servicoDenDen":
        case "transporte":
        case "respawn":
            return "oceano";
        case "combate":
            return "combate";
        case "aliancaCriar":
        case "alianca":
        case "aliancaDiplomacia":
        case "aliancaCooperacao":
        case "aliancaMissoes":
        case "aliancaBanco":
        case "aliancaLista":
            return "alianca";
        case "lojaEvento":
        case "eventoAnoNovo":
        case "eventoNatal":
        case "eventoHalloween":
        case "eventoCriancas":
        case "eventoIndependencia":
        case "eventoDiaPais":
        case "eventoLadroesTesouro":
        case "eventoChefesIlhas":
        case "boss":
        case "eventoPirata":
            return "eventos";
        // case "faq":
        case "suporte":
            return "ajuda";
        case "faq":
        case "forum":
        case "forumNewTopic":
        case "forumPosts":
        case "forumTopics":
            return "forum";
        case "admin-news":
        case "admin-mails":
        case "admin-estatisticas":
        case "admin-combinacaoferreiro":
        case "admin-combinacaoartesao":
        case "admin-combinacaocarpinteiro":
        case "admin-combinacaoequips":
        case "admin-batalhas":
            return "admin";
        default:
            return false;
    }
}