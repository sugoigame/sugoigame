{{-- $relacoes --}}
@php
    $faccoes = \Utils\Data::load('mundo')['faccoes'];

    $bonus = [];
    foreach ($relacoes as $relacao) {
        $faccao = array_find($faccoes, ['cod' => $relacao['faccao_id']]);
        foreach ($faccao['bonus'] as $atr) {
            if (!isset($bonus[$atr])) {
                $bonus[$atr] = 0;
            }
            $bonus[$atr] += \Regras\Influencia::get_bonus_faccao($relacao['nivel'] ?: 0);
        }
    }
@endphp
<h4>Bônus em conflitos:</h4>
<div>
    @foreach ($bonus as $atr => $valor)
        <div>
            @component('Habilidades.IconeAtributo', ['atr' => $atr])
            @endcomponent
            +{{ abrevia_numero_grande($valor) }}%
        </div>
    @endforeach
</div>
