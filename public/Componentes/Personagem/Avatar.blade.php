{{-- $pers, $tripulacao, $class, $attributes, $style --}}
<div class="big-pers-avatar {{ $tripulacao['faccao'] ? 'pirate' : 'marine' }} {{ $class }}"
    style="{{ $style }}">
    <div class="big-pers-skin-bandeira d-flex">
        <div style="width: 25%">
            @component('Tripulacao.Bandeira', ['tripulacao' => $tripulacao])
            @endcomponent
        </div>
        <div class="nome-tripulacao flex-grow d-flex flex-column justify-content-center align-items-center">
            {{ $tripulacao['tripulacao'] }}
        </div>
    </div>
    <div class="big-pers-skin">
        <div class="big-pers-skin-cartaz">
            @component('Personagem.Cartaz', ['pers' => $pers, 'faccao' => $tripulacao['faccao']])
            @endcomponent
        </div>

        @if ($pers['borda'])
            <img class="big-pers-borda"
                src="Imagens/Personagens/Bordas/{{ $pers['borda'] }}.png"
                {{ $attributes }}
                alt="" />
        @endif
        <img class="big-pers-skin-img {{ $class }}"
            src="Imagens/Personagens/Big/{{ sprintf('%04d', $pers['img']) }}({{ $pers['skin_c'] }}).jpg"
            {{ $attributes }}
            alt="" />
    </div>

    <div class="big-pers-detail d-flex">
        <div class="d-flex flex-column justify-content-center align-items-center"
            style="max-width: 100px; margin: auto;">
            @if ($pers['titulo'])
                @php
                    $titulo = \Utils\Data::find('titulos', ['cod_titulo' => $pers['titulo']]);
                    $sexo = $pers['sexo'] ? '_f' : '';
                @endphp
                {{ $titulo["nome$sexo"] }}
            @endif
        </div>
    </div>
</div>
