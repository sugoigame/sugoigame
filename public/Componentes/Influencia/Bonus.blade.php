{{-- $relacoes --}}
@php
    $faccoes = \Utils\Data::load('mundo')['faccoes'];

    $bonus = \Regras\Influencia::get_bonus_todas_faccoes($relacoes);
@endphp
<h4>Bônus em confrontos:</h4>
<div>
    @foreach ($bonus as $atr => $valor)
        <div>
            @component('Habilidades.IconeAtributo', ['atr' => $atr])
            @endcomponent
            +{{ abrevia_numero_grande(round($valor)) }}%
        </div>
    @endforeach
</div>
