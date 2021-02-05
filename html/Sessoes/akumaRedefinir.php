<div class="panel-heading">
    Redefinir Akuma no Mi
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
    $cod_pers = $protector->get_number_or_exit("cod");
    $pers = $userDetails->get_pers_by_cod($cod_pers);

    if (!$pers) {
        echo "Caracter Inválido";
        exit();
    }

    $cod_akuma = $pers["akuma"];
    if (!$cod_akuma) {
        echo "Personagem sem akuma";
        exit();
    }

    $akuma = $connection->run("SELECT * FROM tb_akuma WHERE cod_akuma = ?",
        "i", array($cod_akuma))->fetch_array();

    $tipoakuma = $akuma["tipo"];
    $img_akuma = $akuma["img"];
    ?>

    <?= ajuda("Redefinir Akuma no Mi", "A Akuma no Mi libera uma hárvore de habilidades exclusiva do seu personagem, 
    escolha com sabedoria as habilidades que você irá aprender!") ?>

    <script type="text/javascript">
        <?php include "JS/akumaComer.js";?>
    </script>

    <?php render_status_akuma($pers, $img_akuma, $tipoakuma); ?>

    <form action="Akuma/redefinir_akuma" method="POST"
          id="formulario_comer_akuma" name="formulario_comer_akuma" class="text-left"
          data-question="Deseja redefinir as habilidades dessa Akuma no Mi?">
        <?php render_criador_akuma($pers, $img_akuma, $tipoakuma, $akuma["nome"], $akuma["descricao"]); ?>

        <p>
            <label>
                <input required type="radio" name="tipo_reset" value="gold"> <?= PRECO_GOLD_REDEFINE_AKUMA ?> <img
                        src="Imagens/Icones/Gold.png"/>
            </label>
        </p>
        <p>
            <label>
                <input required type="radio" name="tipo_reset" value="dobrao"> <?= PRECO_DOBRAO_REDEFINE_AKUMA ?> <img
                        src="Imagens/Icones/Dobrao.png"/>
            </label>
        </p>

        <button class="btn btn-success" type="submit">Redefinir</button>
    </form>
</div>