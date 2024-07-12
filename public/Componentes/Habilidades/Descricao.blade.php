{{-- $habilidade, $vontade --}}
@php
    $vontade = $vontade ?: $habilidade['vontade'];
@endphp

<div class="habilidade-descricao">
    <div>
        <strong>{{ $habilidade['nome'] }}</strong>
    </div>
    <div>
        <span>{{ $habilidade['descricao'] }}</span>
    </div>
    @isset($habilidade['explicacao'])
        <div>
            <span>
                @component('Habilidades.Explicacao', [
                    'explicacao' => $habilidade['explicacao'],
                    'vontade' => $vontade,
                    'dano' => $habilidade['dano'],
                ])
                @endcomponent
        </div>
    @endisset
    <div>
        <span>Nível:</span>
        <span>{{ $habilidade['requisito_lvl'] }}</span>
    </div>
    @if ($habilidade['vontade'] > 1)
        <div>
            <span>Vontade necessária:</span>
            <span>{{ $habilidade['vontade'] }}</span>
            <img src="Imagens/Icones/vontade.png"
                height="20rem" />
        </div>
    @endif
    @if (!isset($habilidade['efeitos']) || !isset($habilidade['efeitos']['passivos']))
        <div>
            <span>Alcance:</span>
            <span>{{ $habilidade['alcance'] }}</span>
        </div>
        <div>
            <span>Área:</span>
            <span>{{ $habilidade['area'] }}</span>
        </div>
    @endif
    @if ($habilidade['recarga'])
        <div>
            <span>Recarga:</span>
            <span>{{ $habilidade['recarga'] }} turno(s)</span>
            @if ($habilidade['recarga_universal'])
                <span>Universal</span>
            @endif
        </div>
    @endif

</div>
