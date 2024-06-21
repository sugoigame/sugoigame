<?php
function get_random_akuma()
{
    $chance_paramecia = 45;
    $chance_zoan = 35;

    $rand = rand(0, 100);
    if ($rand < $chance_paramecia) {
        $find = "paramecia";
    } elseif ($rand < ($chance_paramecia + $chance_zoan)) {
        $find = "zoan";
    } else {
        $find = "logia";
    }
    $akumas = DataLoader::filter("akumas", function ($akuma) use ($find) {
        return $akuma["tipo"] == $find;
    });

    return $akumas[array_rand($akumas)];
}

function nome_categoria_akuma($categoria)
{
    switch ($categoria) {
        case 1:
            return "A";
        case 2:
            return "B";
        case 3:
            return "C";
        case 4:
            return "D";
        case 5:
            return "E";
        case 6:
            return "F";
        case 7:
            return "Mística";
        case 8:
            return "Neutra";
        case 9:
            return "Ineficaz";
        case "ofensiva":
            return "Ofensiva";
        case "tatica":
            return "Tática";
        case "defensiva":
            return "Defensiva";
        case "ancestral":
            return "Ancestral";
        case "mitica":
            return "Mítica";
        default:
            return "Inexistente";
    }
}
function label_categoria_akuma($categoria)
{
    switch ($categoria) {
        case "ofensiva":
            return "danger";
        case "tatica":
            return "success";
        case "defensiva":
            return "info";
        case "ancestral":
            return "warning";
        case "mitica":
            return "default";
        default:
            return "Inexistente";
    }
}

function vantagem_categoria_akuma($categoria)
{
    switch ($categoria) {
        case "ofensiva":
            return "tatica";
        case "tatica":
            return "defensiva";
        case "defensiva":
            return "ofensiva";
        case "ancestral":
            return "mitica";
        case "mitica":
            return "ancestral";
        default:
            return "Inexistente";
    }
}

function nome_tipo_akuma($tipo)
{
    switch ($tipo) {
        case 8:
        case "logia":
            return "Logia";
        case 9:
        case "paramecia":
            return "Paramecia";
        case 10:
        case "zoan":
            return "Zoan";
        default:
            return "Inexistente";
    }
}

function label_tipo_akuma($tipo)
{
    switch ($tipo) {
        case 8:
        case "logia":
            return "danger";
        case 9:
        case "paramecia":
            return "success";
        case 10:
        case "zoan":
            return "info";
        default:
            return "default";
    }
}

function render_vantagens_akuma($akuma)
{
    ?>
    <div>
        Causa mais dano contra Akumas
        <?= nome_categoria_akuma(vantagem_categoria_akuma($akuma["categoria"])) ?>s
    </div>
    <?php if (isset($akuma["vantagens"])) : ?>
        <?php foreach ($akuma["vantagens"] as $vantagem) : ?>
            <?php $akuma_vantagem = DataLoader::find("akumas", ["cod_akuma" => $vantagem]); ?>
            <div>
                Causa mais dano contra
                <?= $akuma_vantagem["nome"] ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php } ?>

