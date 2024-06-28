{{-- $personagem, $id_azul --}}
@php
    $classe = $personagem['tripulacao_id'] == $id_azul ? 'personagem-aliado' : 'personagem-inimigo';
    $url =
        $personagem['skin_r'] == 'npc'
            ? 'Imagens/Batalha/Npc/' . $personagem['img'] . '.png'
            : 'Imagens/Personagens/Icons/' . get_img($personagem, 'r') . '.png';
@endphp
<img src="{{ $url }}"
    height="55px"
    alt="Atacante"
    class="{{ $classe }}" />
