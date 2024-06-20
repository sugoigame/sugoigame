<?php
namespace Componentes\Habilidades;


class HabilidadeExplicacao
{

    public static function render($descricao)
    {
        $descricao = str_replace("{ATK}", HabilidadeExplicacao::icone_atr("atk"), $descricao);
        $descricao = str_replace("{DEF}", HabilidadeExplicacao::icone_atr("def"), $descricao);
        $descricao = str_replace("{AGL}", HabilidadeExplicacao::icone_atr("agl"), $descricao);
        $descricao = str_replace("{PRE}", HabilidadeExplicacao::icone_atr("pre"), $descricao);
        $descricao = str_replace("{RES}", HabilidadeExplicacao::icone_atr("res"), $descricao);
        $descricao = str_replace("{DEX}", HabilidadeExplicacao::icone_atr("dex"), $descricao);
        $descricao = str_replace("{PER}", HabilidadeExplicacao::icone_atr("con"), $descricao);

        return $descricao;
    }
    public static function icone_atr($atr)
    {
        return '<img class="icone-atributo" src="Imagens/Icones/' . $atr . '.png" height="20rem" />';
    }
}
