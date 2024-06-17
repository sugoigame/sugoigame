<?php
namespace Componentes\Habilidades;


class HabilidadeIcone
{
    public static function render($habilidade)
    {
        $habilidade = habilidade_default_values($habilidade);
        ?>
        <a class="noHref" href="#" class="habilidade-icone" data-toggle="popover" data-html="true" data-placement="bottom"
            data-container="#tudo" data-placement="right" data-trigger="focus"
            data-content='<?php HabilidadeDescricao::render($habilidade) ?>'>
            <img src="Imagens/Skils/<?= $habilidade["icone"]; ?>.jpg" />
        </a>
        <?php
    }
}
