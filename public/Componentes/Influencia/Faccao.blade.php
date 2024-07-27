{{-- $faccao, $relacao, $nivel_base --}}
@php
    global $userDetails;

    $reputacao_necessaria = \Regras\Influencia::get_reputacao_necessaria($relacao['nivel'] ?: 0);
    $reputacao =
        ($relacao['reputacao'] ?: 0) +
        \Regras\Influencia::get_reputacao_produzida(json_decode($relacao['producao'], true) ?: []);

    $nivel = ($relacao['nivel'] ?: 0) + ($nivel_base ?: 0);

    $limite_confrontos = \Regras\Influencia::get_limite_confrontos($userDetails->tripulacao['influencia']);
@endphp

<div class="panel">
    <div class="panel-heading">
        <div>{{ $faccao['nome'] }}</div>
    </div>
    <div class="panel-body">
        <div class="mb">Nível {{ $nivel ?: 0 }}/{{ $userDetails->tripulacao['influencia'] }}</div>
        <div class="progress">
            <div class="progress-bar progress-bar-{{ $faccao['evolui_outros'] ? 'secondary' : 'info' }}"
                style="width: {{ min(1.0, $reputacao / $reputacao_necessaria) * 100 }}%">
                <span>Reputação:
                    {{ mascara_numeros_grandes($reputacao) }} /
                    {{ mascara_numeros_grandes($reputacao_necessaria) }}</span>
            </div>
        </div>
        <div>
            Confrontos:
            {{ $relacao['confrontos'] ?: 0 }}
            @if (!$faccao['evolui_outros'])
                / {{ $limite_confrontos }}
            @endif
            {!! ajuda_tooltip(
                'Você participou de ' .
                    ($relacao['confrontos'] ?: 0) .
                    ' confrontos envolvendo essa facção, a cada hora você ganha ' .
                    ($relacao['confrontos'] ?: 0) * 100 .
                    ' pontos de reputação.',
            ) !!}
        </div>
    </div>
    <div class="panel-footer">
        <div>
            @if ($faccao['evolui_outros'])
                A reputação com {{ $faccao['nome'] }} também aumenta a reputação com todas as outras
                facções.
            @endif
            @if (isset($faccao['bonus']))
                <div>
                    <div>Bônus em confrontos:</div>
                    <div>
                        @foreach ($faccao['bonus'] as $atr)
                            <div>
                                @component('Habilidades.IconeAtributo', ['atr' => $atr])
                                @endcomponent
                                +{{ abrevia_numero_grande(round(\Regras\Influencia::get_bonus_faccao($nivel ?: 0))) }}%
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="panel-footer">
        <button class="btn btn-success link_send"
            href='link_Influencia/evoluir_faccao.php?faccao={{ $faccao['cod'] }}'
            {{ $reputacao < $reputacao_necessaria || $nivel >= $userDetails->tripulacao['influencia'] ? 'disabled' : '' }}>Evoluir</button>
    </div>
</div>
