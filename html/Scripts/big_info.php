<?php
$valida = "EquipeSugoiGame2012";
require "../Includes/conectdb.php";

if (!isset($_GET["cod"])) {
    mysql_close();
    exit();
}

$cod = mysql_real_escape_string(substr($_GET["cod"], 0, 6));

if (!preg_match("/^[\d]+$/", $cod)) {
    mysql_close();
    exit();
}

$query = "SELECT * FROM tb_usuarios INNER JOIN tb_personagens ON tb_usuarios.cod_personagem=tb_personagens.cod 
	WHERE tb_usuarios.id='$cod'";
$result = mysql_query($query);
$personagem = mysql_fetch_array($result);

$query = "SELECT * FROM tb_alianca INNER JOIN tb_alianca_membros ON tb_alianca.cod_alianca=tb_alianca_membros.cod_alianca 
	WHERE tb_alianca_membros.id='$cod'";
$result = mysql_query($query);
if (mysql_num_rows($result) != 0)
    $ally = mysql_fetch_array($result);
?>
<style type="text/css">
    .mini_selected {
        border-radius: 5px;
        padding: 10px;
        top: 0px;
        text-align: left;
        font-size: 14px;
    }

    body {
        background: url('../Imagens/<? echo $personagem["faccao"] ?>/Cabecalho/rodape.jpg');
    }
</style>
<body>
<div class="mini_selected"
     style="background: url('../Imagens/<? echo $personagem["faccao"] ?>/Conteudo/back.jpg'); border: 1px solid #<? if ($personagem["faccao"] == 1) echo "9d6f31"; else echo "50816b"; ?>;">
    <img src="../Imagens/Personagens/Icons/<?php echo $personagem["img"] ?>.jpg" style=" margin: 5px"/>
    <img src="../Imagens/Bandeiras/img.php?cod=<?php echo $personagem["bandeira"] ?>&f=<?php echo $personagem["faccao"] ?>">
    <br>
    <?php echo $personagem["nome"] ?> - <b><?php echo $personagem["tripulacao"] ?></b><br>
    <?php
    if (isset($ally["nome"])) {
        ?>
        <b><?php echo $ally["nome"] ?></b><br>
    <?php } ?>
    Nível <?php echo $personagem["lvl"] ?><br>
    Tripulantes:
    <?php
    $query = "SELECT * FROM tb_personagens WHERE id='" . $personagem["id"] . "' AND ativo = 1";
    $result = mysql_query($query);
    echo mysql_num_rows($result);
    $rec = 0;
    while ($personagem_ = mysql_fetch_array($result)) {
        $rec += $personagem_["fama_ameaca"] * 20000;
    }
    ?><br>
    Reputação:
    <?php echo $personagem["reputacao"] ?><br>
    <?php
    include_once "../Funcoes/mascara_string.php";
    if ($personagem["faccao"] == 0) echo "Gratificação: ";
    else echo "Recompensa: ";
    $tam = strlen($rec);
    if ($tam == 4) {
        $reco = mascara_string("#.###", $rec);
    } else if ($tam == 5) {
        $reco = mascara_string("##.###", $rec);
    } else if ($tam == 6) {
        $reco = mascara_string("###.###", $rec);
    } else if ($tam == 7) {
        $reco = mascara_string("#.###.###", $rec);
    } else if ($tam == 8) {
        $reco = mascara_string("##.###.###", $rec);
    } else if ($tam == 9) {
        $reco = mascara_string("###.###.###", $rec);
    } else if ($tam == 10) {
        $reco = mascara_string("#.###.###.###", $rec);
    } else {
        $reco = $rec;
    }
    echo "$ " . $reco;
    mysql_close();
    ?>
</div>
</body>