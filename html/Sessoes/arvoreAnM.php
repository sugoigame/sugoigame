<div class="panel-heading">
    Jardim de Laftel
</div>

<div class="panel-body">
    <?= ajuda("Jardim de Laftel", "Esse é o único lugar no mundo onde nascem essas árvores estranhas, cujo fruto são as 
    tão procuradas Frutas do diabo.<br/> Você só pode coletar uma fruta a cada 7 dias.") ?>

    <h3>Árvore de Akuma no Mi</h3>
    <?
    $query = "SELECT * FROM tb_jardim_laftel WHERE id='" . $usuario["id"] . "'";
    $result = mysql_query($query);

    $possivel = FALSE;
    if (mysql_num_rows($result) == 0)
        $possivel = TRUE;
    else {
        $tempo = mysql_fetch_array($result);

        if ($tempo["tempo"] < atual_segundo()) $possivel = TRUE;
    }

    if ($possivel) { ?>
        <button href="link_Especiais/jardim_laftel.php" class="link_send btn btn-success">Colher uma Akuma no Mi
        </button>
    <? } else { ?>
        <? echo transforma_tempo_min($tempo["tempo"] - atual_segundo()) ?> para colher uma fruta.
    <? } ?>
</div>