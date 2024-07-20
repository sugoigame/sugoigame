{{-- $faccao --}}
<div class="panel">
    <div class="panel-heading">
        <div>{{ $faccao['nome'] }}</div>
    </div>
    <div class="panel-body">
        <div class="mb">Nível 9/10</div>
        <div class="progress">
            <div class="progress-bar progress-bar-{{ $faccao['evolui_outros'] ? 'secondary' : 'info' }}"
                style="width: 50%">
                <span>Reputação: 500/1000</span>
            </div>
        </div>
        <div>
            Conflitos: 5/10
            {!! ajuda_tooltip(
                'Você participou de 5 conflitos envolvendo essa facção, a cada hora você ganha 5 pontos de reputação.',
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
                    <div>Bônus em conflitos:</div>
                    <div>
                        @foreach ($faccao['bonus'] as $atr)
                            <div>
                                @component('Habilidades.IconeAtributo', ['atr' => $atr])
                                @endcomponent +10%
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="panel-footer">
        <button class="btn btn-success">Evoluir</button>
    </div>
</div>
