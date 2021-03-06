<div class="panel-heading">
    Comer Akuma no Mi
</div>

<style type="text/css">
    .input-text {
        color: #efefef;
        background: transparent !important;
        border: none !important;
    }

    .skill-details {
        display: none;
    }
</style>

<div class="panel-body">
    <?php
    if (!isset($_GET["cod"]) OR
        !isset($_GET["akuma"]) OR
        !isset($_GET["img"])
    ) {
        mysql_close();
        echo "Caracter Inválido";
        exit();
    }
    if (!validate_number($_GET["cod"])
        OR !validate_number($_GET["akuma"])
        OR !validate_number($_GET["img"])
    ) {
        mysql_close();
        echo "Caracter Inválido";
        exit();
    }
    $cod_pers = $_GET["cod"];

    $pers = FALSE;
    foreach ($userDetails->personagens as $personagem) {
        if ($personagem["cod"] == $cod_pers) {
            $pers = $personagem;
            break;
        }
    }

    if (!$pers) {
        echo "Caracter Inválido";
        exit();
    }

    $tipoakuma = $_GET["akuma"];
    $img_akuma = $_GET["img"];

    $result = $connection->run("SELECT * FROM tb_usuario_itens WHERE tipo_item = ? AND id = ?",
        "ii", array($tipoakuma, $userDetails->tripulacao["id"]));

    if (!$result->count()) {
        echo "Caracter Inválido";
        exit();
    }
    ?>

    <?= ajuda("Comer Akuma no Mi", "Agora você pode comer sua Akuma no Mi! A Akuma no Mi libera uma hárvore de 
    habilidades exclusiva do seu personagem, escolha com sabedoria as habilidades que você irá aprender!") ?>

    <script type="text/javascript">
        <?php include "JS/akumaComer.js";?>
    </script>

    <?php render_status_akuma($pers, $img_akuma, $tipoakuma); ?>

    <form action="Akuma/criar_akuma" method="POST"
          id="formulario_comer_akuma" name="formulario_comer_akuma" class="text-left">
        <?php render_criador_akuma($pers, $img_akuma, $tipoakuma); ?>
        <button class="btn btn-success" type="submit">Comer</button>
    </form>
</div>