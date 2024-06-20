<?php
namespace Componentes\Habilidades;


class HabilidadeDescricao
{
    public static function render($habilidade)
    {
        $habilidade = habilidade_default_values($habilidade);
        ?>
        <div class="habilidade-descricao">
            <div>
                <strong><?= $habilidade["nome"]; ?></strong>
            </div>
            <div>
                <span><?= $habilidade["descricao"]; ?></span>
            </div>
            <?php if (isset($habilidade["explicacao"])) : ?>
                <div>
                    <span><?= HabilidadeExplicacao::render($habilidade["explicacao"]) ?></span>
                </div>
            <?php endif; ?>
            <div>
                <span>Nível:</span>
                <span><?= $habilidade["requisito_lvl"]; ?></span>
            </div>
            <?php if ($habilidade["vontade"] > 1) : ?>
                <div>
                    <span>Vontade necessária:</span>
                    <span><?= $habilidade["vontade"] ?></span>
                    <img src="Imagens/Icones/vontade.png" height="20rem" />
                </div>
            <?php endif; ?>
            <?php if ($habilidade["recarga"]) : ?>
                <div>
                    <span>Recarga:</span>
                    <?php if ($habilidade["recarga_universal"]) : ?>
                        <span>Universal -</span>
                    <?php endif; ?>
                    <span><?= $habilidade["recarga"] ?> turno(s)</span>
                </div>
            <?php endif; ?>
            <?php if (! isset($habilidade["efeitos"]) && ! isset($habilidade["efeitos"]["passivo"])) : ?>
                <div>
                    <span>Área:</span>
                    <span><?= $habilidade["area"] ?></span>
                </div>
                <div>
                    <span>Alcance:</span>
                    <span><?= $habilidade["alcance"] ?></span>
                </div>
            <?php endif; ?>
            <?php if ($habilidade["dano"] && $habilidade["dano"] != 1) : ?>
                <div>
                    <span>Dano adicional:</span>
                    <span><?= ($habilidade["dano"] - 1) * 100; ?>%</span>
                </div>
            <?php endif; ?>

        </div>
        <?php
    }
}
