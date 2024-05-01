<?php
$valida = "EquipeSugoiGame2012";
require "../Includes/conectdb.php";

$cod = $protector->get_number_or_exit("cod");

$query = "SELECT * FROM tb_usuarios INNER JOIN tb_personagens ON tb_usuarios.cod_personagem=tb_personagens.cod
	WHERE tb_usuarios.id= ?";
$result = $connection->run($query, "i", $cod);
$personagem = $result->fetch_array();

$query = "SELECT * FROM tb_alianca INNER JOIN tb_alianca_membros ON tb_alianca.cod_alianca=tb_alianca_membros.cod_alianca
	WHERE tb_alianca_membros.id= ?";
$result = $connection->run($query, "i", $cod);
if ($result->count() != 0)
    $ally = $result->fetch_array();
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
    <div class="mini_selected" style="background: url('../Imagens/<? echo $personagem["faccao"] ?>/Conteudo/back.jpg'); border: 1px solid #<? if ($personagem["faccao"] == 1)
           echo "9d6f31";
       else
           echo "50816b"; ?>;">
        <img src="../Imagens/Personagens/Icons/<?php echo $personagem["img"] ?>.jpg" style=" margin: 5px" />
        <img
            src="../Imagens/Bandeiras/img.php?cod=<?php echo $personagem["bandeira"] ?>&f=<?php echo $personagem["faccao"] ?>">
        <br>
        <?php echo $personagem["nome"] ?> - <b>
            <?php echo $personagem["tripulacao"] ?>
        </b><br>
        <?php
        if (isset($ally["nome"])) {
            ?>
            <b>
                <?php echo $ally["nome"] ?>
            </b><br>
        <?php } ?>
        Nível
        <?php echo $personagem["lvl"] ?><br>
        Tripulantes:
        <?php
        $query = "SELECT * FROM tb_personagens WHERE id=? AND ativo = 1";
        $result = $connection->run($query, "i", [$personagem["id"]]);
        echo $result->count();
        $rec = 0;
        while ($personagem_ = $result->fetch_array()) {
            $rec += $personagem_["fama_ameaca"] * 20000;
        }
        ?><br>
        Reputação:
        <?php echo $personagem["reputacao"] ?><br>
        <?php
        include_once "../Funcoes/mascara_string.php";
        if ($personagem["faccao"] == 0)
            echo "Gratificação: ";
        else
            echo "Recompensa: ";
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
        ?>
    </div>
</body>
