<?php
// Função para exibir a imagem do reagente
function get_image($material) {
    $extensao = $material["img_format"] == "jpg" ? "jpg" : "png";
    $imagem = "Imagens/Itens/" . $material["img"] . "." . $extensao;
    echo file_exists($imagem) ? '<img src="' . $imagem . '"/>' : 'Imagem não encontrada';
}
?>
