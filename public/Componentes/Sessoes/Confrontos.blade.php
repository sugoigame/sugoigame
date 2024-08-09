@php
    global $userDetails;
    global $connection;
    $ilha = \Regras\Ilhas::get_ilha($userDetails->ilha['ilha']);
    $confrontos_realizados =
        $connection
            ->run('SELECT * FROM tb_tripulacao_ilha_confrontos WHERE tripulacao_id = ? AND ilha_id = ?', 'ii', [
                $userDetails->tripulacao['id'],
                $userDetails->ilha['ilha'],
            ])
            ->fetch_array()['confrontos'] ?:
        0;

    if ($ilha['mar'] < 5 && $confrontos_realizados >= $ilha['confrontos']) {
        $confronto = null;
    } else {
        // todo carregar faccao do governante da ilha
        $faccao =
            $confrontos_realizados < $ilha['confrontos']
                ? \Utils\Data::find_inside('mundo', 'faccoes', ['evolui_outros' => true])
                : \Utils\Data::load('mundo')['faccoes'][array_rand(\Utils\Data::load('mundo')['faccoes'])];

        $confronto = \Regras\Influencia::generate_confronto($userDetails->tripulacao['nivel_confronto']);
        $recompensas = \Regras\Influencia::generate_recompensas($userDetails->tripulacao['nivel_confronto']);
    }
@endphp
<div class="panel-heading">
    Próximo confronto
    {!! ajuda_tooltip(
        'Participe de eventos importantes para ganhar recompensas e aumentar sua influência no mundo.',
    ) !!}
</div>

<div class="panel-body">
    @if ($confrontos_realizados < $ilha['confrontos'])
        <div>
            <div class="progress">
                <div class="progress-bar progress-bar-success"
                    style="width: {{ ($confrontos_realizados / $ilha['confrontos']) * 100 }}%">
                    <span>Confrontos realizados: {{ $confrontos_realizados }}/{{ $ilha['confrontos'] }}</span>
                </div>
            </div>
        </div>
    @endif

    @if ($confronto)
        <div class="row">
            <div class="col col-xs-3 d-flex flex-column justify-content-center align-items-center">
                <div class="panel">
                    <div class="panel-heading">
                        Dificuldade: {{ $userDetails->tripulacao['nivel_confronto'] }}
                    </div>
                    <div class="panel-body">
                        <div>Facção: {{ $faccao['nome'] }}</div>
                        <h5>Recompensas:</h5>
                        @foreach ($recompensas as $recompensa)
                            {{ render_recompensa($recompensa, [], []) }}
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col col-xs-3">
                <h5>Capitão adversário:</h5>
                @component('Personagem.Avatar', [
                    'pers' => $confronto['personagens'][0],
                    'tripulacao' => $confronto['tripulacao'],
                ])
                @endcomponent
            </div>
            <div class="col col-xs-6">
                <h5>Tripulação adversária:</h5>
                <div class="text-left">
                    @foreach ($confronto['personagens'] as $pers)
                        @component('Personagem.Cartaz', ['pers' => $pers, 'faccao' => $confronto['tripulacao']['faccao']])
                        @endcomponent
                    @endforeach
                </div>
                <div class="text-right panel-body">
                    {{-- todo validar se pode mesmo iniciar o confronto --}}
                    <button class="btn btn-success link_send"
                        href='link_Influencia/iniciar_confronto.php'>
                        <i class="fa fa-bolt"></i>&nbsp;
                        Iniciar
                    </button>
                </div>
            </div>
        </div>
    @else
        <div>Você já completou todos os confrontos aqui, viaje para outra ilha.</div>
    @endif
</div>

@component('Sessoes.Influencia')
@endcomponent
