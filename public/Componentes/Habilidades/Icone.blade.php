{{-- $habilidade, $vontade, $espera --}}
@php
    $habilidade = \Regras\Habilidades::habilidade_default_values($habilidade);
    $vontade = $vontade ?: $habilidade['vontade'];
    $espera = $espera ?: 0;
@endphp

<a class="noHref habilidade-icone"
    href="#"
    data-toggle="popover"
    data-html="true"
    data-placement="bottom"
    data-container="#tudo"
    data-placement="right"
    data-trigger="focus"
    data-content='{{ Componentes::render('Habilidades.Descricao', ['habilidade' => $habilidade, 'vontade' => $vontade]) }}'>
    <img width="50px"
        height="50px"
        alt=""
        src="Imagens/Skils/{{ $habilidade['icone'] }}.jpg" />
    @if ($vontade > 1)
        <span class="habilidade-vontade">
            {{ $vontade }}
        </span>
    @endif
    @if ($vontade && $habilidade['dano'])
        <span class="habilidade-dano">
            {{ round(\Regras\Combate\Formulas\Ataque::calc_dano_vontade($vontade, $habilidade['dano'])) }}
        </span>
    @endif
    @if ($habilidade['dano'] && $habilidade['alcance'])
        <span class="habilidade-alcance">
            {{ $habilidade['alcance'] }}
        </span>
    @endif
    @if ($habilidade['dano'] && $habilidade['area'])
        <span class="habilidade-area">
            {{ $habilidade['area'] }}
        </span>
    @endif
    @if ($habilidade['recarga'])
        @if ($espera)
            <span class="habilidade-espera">
                {{ $espera }}
            </span>
        @else
            <span class="habilidade-recarga">
                {{ $habilidade['recarga'] }}
            </span>
        @endif
    @endif
    @if (isset($habilidade['etiquetas']))
        <span class="habilidade-etiquetas">
            @foreach ($habilidade['etiquetas'] as $etiqueta)
                <span class="habilidade-etiqueta"
                    style="background-image: url({{ $etiqueta }});">
                </span>
            @endforeach ($espera)
        </span>
    @endif
    @if (isset($habilidade['quantidade']))
        <span class="habilidade-quantidade">
            {{ $habilidade['quantidade'] }}
        </span>
    @endif
</a>
