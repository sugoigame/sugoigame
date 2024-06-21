{{-- $habilidade --}}

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
                @component('Habilidades.Explicacao', ['explicacao' => $habilidade['explicacao']])
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
    @if ($habilidade['recarga'])
        <div>
            <span>Recarga:</span>
            @if ($habilidade['recarga_universal'])
                <span>Universal -</span>
            @endif
            <span>{{ $habilidade['recarga'] }} turno(s)</span>
        </div>
    @endif
    @if (!isset($habilidade['efeitos']) && !isset($habilidade['efeitos']['passivo']))
        <div>
            <span>Área:</span>
            <span>{{ $habilidade['area'] }}</span>
        </div>
        <div>
            <span>Alcance:</span>
            <span>{{ $habilidade['alcance'] }}</span>
        </div>
    @endif
    @if ($habilidade['dano'] && $habilidade['dano'] != 1)
        <div>
            <span>Dano adicional:</span>
            <span>{{ ($habilidade['dano'] - 1) * 100 }}%</span>
        </div>
    @endif

</div>
