{{-- $personagem, $id_azul, $height = '55px' --}}
@php
    $classe = $personagem['tripulacao_id'] == $id_azul ? 'personagem-aliado' : 'personagem-inimigo';
    $url =
        $personagem['cod'] === 'npc'
            ? 'Imagens/Batalha/Npc/' . $personagem['img'] . '.png'
            : 'Imagens/Personagens/Icons/' . get_img($personagem, 'r') . '.jpg';
@endphp
<img src="{{ $url }}"
    height="{{ $height ?: '55px' }}"
    alt=""
    class="{{ $classe }}" />
