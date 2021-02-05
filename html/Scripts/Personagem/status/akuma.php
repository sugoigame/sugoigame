<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}
?>

<?php $akumas = $connection->run("SELECT cod_item, tipo_item FROM tb_usuario_itens 
    WHERE id= ? AND (tipo_item='8' OR tipo_item='9' OR tipo_item='10')", "i", $userDetails->tripulacao["id"])
    ->fetch_all_array(); ?>

<?php render_personagem_panel_top($pers, 0) ?>
<?php render_personagem_sub_panel_with_img_top($pers); ?>
    <div class="panel-body">
        <?php if (!$pers["akuma"]) : ?>
            <h4>Frutas:</h4>
            <?php if (count($akumas)) : ?>
                <div class="row">
                    <?php foreach ($akumas as $item) : ?>
                        <div class="col-md-4">
                            <img src="Imagens/Itens/<?= (substr($item["cod_item"], -3, 3)) ?>.png"/>
                            <div>
                                Tipo: <?= nome_tipo_akuma($item["tipo_item"]) ?>
                            </div>
                            <button href="Link_akumaComer&cod=<?= $pers["cod"] ?>&akuma=<?= $item["tipo_item"] ?>&img=<?= $item["cod_item"] ?>"
                                    class="link_content2 btn btn-success">
                                Comer
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                Você precisa encontrar alguma Akuma no Mi para o personagem comer.<br>
                Há boatos que dizem que se pode encontrar uma Akuma no Mi mergulhando ou fazendo expedições, mas apenas mergulhadores
                e arqueólogos podem realizar essas tarefas...<br>
            <?php endif; ?>
        <?php else : ?>
            <?php $akuma = $connection->run("SELECT * FROM tb_akuma WHERE cod = ?", "i", $pers["cod"])->fetch_array(); ?>
            <img src="Imagens/Itens/<?= $akuma["img"] ?>.png"/>
            <h4><?= $akuma["nome"]; ?></h4>
            <p> <?= $akuma["descricao"]; ?></p>
            <ul class="text-left">
                <li>Tipo: <?= nome_tipo_akuma($akuma["tipo"]); ?>
                <li>Categoria: <?= nome_categoria_akuma($akuma["categoria"]); ?></li>
                <?php if ($akuma["categoria"] < 7): ?>
                    <?php $vantagem = $akuma["categoria"] < 6 ? $akuma["categoria"] + 1 : 1; ?>
                    <?php $desvantagem = $akuma["categoria"] > 1 ? $akuma["categoria"] - 1 : 6; ?>
                    <li>
                        Causa <?= (categoria_akuma($akuma["categoria"], $vantagem) - 1) * 100 ?>% a mais de
                        dano contra a categoria <?= nome_categoria_akuma($vantagem) ?>
                    </li>
                    <li>
                        Recebe <?= (1 - categoria_akuma($akuma["categoria"], $desvantagem)) * 100 ?>% a mais
                        de dano da categoria <?= nome_categoria_akuma($desvantagem) ?>
                    </li>
                <?php elseif ($akuma["categoria"] == 7): ?>
                    <li>
                        Causa <?= (categoria_akuma($akuma["categoria"], 1) - 1) * 100 ?>% a mais de
                        dano contra todas as outras categorias exceto Mística e Neutra
                    </li>
                <?php elseif ($akuma["categoria"] == 8): ?>
                    <li>
                        Não sofre aumento nem redução de dano contra nenhuma outra categoria de Akuma no Mi
                    </li>
                <?php elseif ($akuma["categoria"] == 9): ?>
                    <li>
                        Recebe <?= (categoria_akuma(1, $akuma["categoria"]) - 1) * 100 ?>% a mais
                        de dano de todas as outras categorias exceto Ineficaz e Neutra
                    </li>
                <?php endif; ?>
            </ul>
            <p>
                <button class="link_confirm btn btn-info"
                        data-question="Deseja remover a Akuma no Mi desse personagem?"
                        href="Vip/reset_akuma.php?cod=<?= $pers["cod"] ?>"
                    <?= $userDetails->conta["gold"] < PRECO_GOLD_RESET_AKUMA ? "disabled" : "" ?>>
                    <?= PRECO_GOLD_RESET_AKUMA ?> <img src="Imagens/Icones/Gold.png"/>
                    Remover a Akuma no Mi
                </button>
            </p>
            <p>
                <button class="link_confirm btn btn-info"
                        data-question="Deseja remover a Akuma no Mi desse personagem?"
                        href="VipDobroes/reset_akuma.php?cod=<?= $pers["cod"] ?>"
                    <?= $userDetails->conta["dobroes"] < PRECO_DOBRAO_RESET_AKUMA ? "disabled" : "" ?>>
                    <?= PRECO_DOBRAO_RESET_AKUMA ?> <img src="Imagens/Icones/Dobrao.png"/>
                    Remover a Akuma no Mi
                </button>
            </p>
        <?php endif; ?>
    </div>
<?php render_personagem_sub_panel_with_img_bottom(); ?>

<?php if ($pers["akuma"]) : ?>

    <p>
        <a class="link_content btn btn-info"
           href="./?ses=akumaRedefinir&cod=<?= $pers["cod"] ?>">
            Redefinir as habilidades da Akuma no Mi
        </a>
    </p>

    <p>
        <button class="link_send btn btn-success"
                href="link_Akuma/aprender_todas.php?cod=<?= $pers["cod"] ?>">
            Aprender todas as Habilidades disponíveis
        </button>
    </p>
    <?php
    $skills = [];
    $result = $connection->run("SELECT * FROM tb_akuma_skil_atk WHERE cod_akuma= ? ORDER BY lvl",
        "i", $pers["akuma"]);

    while ($skill = $result->fetch_array()) {
        $skill["tipo"] = "Ataque";
        $skill["tiponum"] = 7;
        $skills[] = $skill;
    }

    $result = $connection->run("SELECT * FROM tb_akuma_skil_buff WHERE cod_akuma= ? ORDER BY lvl",
        "i", $pers["akuma"]);

    while ($skill = $result->fetch_array()) {
        $skill["tipo"] = "Buff";
        $skill["tiponum"] = 8;
        $skills[] = $skill;
    }

    $result = $connection->run("SELECT * FROM tb_akuma_skil_passiva WHERE cod_akuma= ? ORDER BY lvl",
        "i", $pers["akuma"]);

    while ($skill = $result->fetch_array()) {
        $skill["tipo"] = "Passiva";
        $skill["tiponum"] = 9;
        $skills[] = $skill;
    }
    ?>

    <?php render_habilidades_tab($skills, $pers, "Akuma/aprender_akuma_skil.php", function ($pers, $skill) {
        if ($pers["lvl"] >= $skill["lvl"]) {
            echo get_alert();
            return true;
        }
        return false;
    }); ?>
<?php endif; ?>
<?php render_personagem_panel_bottom() ?>