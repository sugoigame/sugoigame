<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (! $pers) {
    $protector->exit_error("Personagem inválido");
}
?>
<?php render_personagem_panel_top($pers, 0) ?>
<?php $habilidades = DataLoader::load("habilidades"); ?>
<?php $aprendidas_db = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod_pers = ?", "i", array($pers["cod"]))->fetch_all_array(); ?>

<?php $animacoes = $connection->run(
    "SELECT * FROM tb_tripulacao_animacoes_skills WHERE tripulacao_id = ?",
    "i", array($userDetails->tripulacao["id"])
)->fetch_all_array() ?>

<?php $aprendidas = []; ?>
<?php foreach ($aprendidas_db as $aprendida) : ?>
    <?php $aprendidas[$aprendida["cod_skil"]] = $aprendida; ?>
<?php endforeach; ?>

<div class="table-responsive mb4">
    <table class="table text-center table-condensed">
        <thead>
            <tr>
                <?php foreach ($habilidades["classes"] as $cod_classe => $classe) : ?>
                    <th>
                        <div>
                            <?= $classe["nome"]; ?>
                        </div>
                        <?php if ($pers["classe"] == $cod_classe) : ?>
                            <button class="btn btn-primary" disabled>
                                Ativa
                            </button>
                        <?php else : ?>
                            <button href='Academia/academia_aprender.php?cod=<?= $pers["cod"]; ?>&class=<?= $cod_classe; ?>'
                                class="btn btn-<?= $pers["classe"] ? "info" : "success"; ?> link_confirm"
                                data-question="Deseja escolher essa classe?">
                                <?= $pers["classe"] ? "Trocar" : "Escolher"; ?>
                            </button>
                        <?php endif; ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php $lvls = [1, 5, 10, 20, 30, 40, 50] ?>
            <?php foreach ($lvls as $key => $lvl) : ?>
                <tr>
                    <?php foreach ($habilidades["classes"] as $cod_classe => $classe) : ?>
                        <?php $habilidades_classe = \Regras\Habilidades::habilidades_default_values($classe["habilidades"]) ?>
                        <?php $habilidades_lvl = array_filter($habilidades_classe, function ($habilidade) use ($lvl) {
                            return ($habilidade["requisito_lvl"] ?: 1) == $lvl;
                        }); ?>
                        <td class="border-none">
                            <?php foreach ($habilidades_lvl as $index => $habilidade) : ?>
                                <?php $habilidade = array_merge($habilidade, $aprendidas[$habilidade["cod"]] ?: []) ?>
                                <div>
                                    <?= Componentes::render("Habilidades.Icone", ["habilidade" => $habilidade, "vontade" => $habilidade["vontade"]]) ?>
                                </div>
                                <?php if ($pers["classe"] == $cod_classe) : ?>
                                    <?php if ($habilidade["requisito_lvl"] <= $pers["lvl"]) : ?>
                                        <?= Componentes::render("Habilidades.BotaoCustomizar", ["pers" => $pers, "habilidade" => $habilidade, "animacoes" => $animacoes]); ?>
                                    <?php else : ?>
                                        <button class="btn btn-sm btn-primary" disabled>
                                            Nível <?= $habilidade["requisito_lvl"]; ?>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php render_personagem_panel_bottom() ?>

