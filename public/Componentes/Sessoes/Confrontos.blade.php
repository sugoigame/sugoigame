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
        $faccao_ilha = $connection
            ->run('SELECT faccao FROM tb_mapa WHERE ilha = ?', 'i', [$userDetails->ilha['ilha']])
            ->fetch_array()['faccao'];
        $faccao =
            $confrontos_realizados < $ilha['confrontos']
                ? \Utils\Data::find_inside('mundo', 'faccoes', ['evolui_outros' => true])
                : \Utils\Data::find_inside('mundo', 'faccoes', ['cod' => $faccao_ilha]);

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
    @else
        <div class="panel panel-default m0 h-100">
            @php
                $chefe_derrotado_data = $connection->run(
                    'SELECT * FROM tb_missoes_chefe_ilha WHERE tripulacao_id = ? AND ilha_derrotado = ?',
                    'ii',
                    [$userDetails->tripulacao['id'], $userDetails->ilha['ilha']],
                );
                $chefe_derrotado = $chefe_derrotado_data->count();
                $chefe_derrotado_data = $chefe_derrotado_data->fetch_array();
            @endphp
            <div class="panel-body">
                @if (!$chefe_derrotado_data['recompensa_recebida'])
                    @php
                        $chefes_ilha = DataLoader::load('chefes_ilha');
                        $rdms = DataLoader::load('rdm');

                        $chefe = $chefes_ilha[$userDetails->ilha['ilha']];
                        $recompensas_chefe = $chefe['recompensas'];
                    @endphp
                    <p>
                        Chefe da Ilha: <strong>{{ $rdms[$chefe['rdm']]['nome'] }}</strong>
                    </p>
                    <div>Recompensa:</div>
                    <small>
                        @foreach ($recompensas_chefe as $recompensa)
                            {{ render_recompensa($recompensa, [], []) }}
                        @endforeach
                    </small>
                @endif
            </div>
            <div class="panel-footer">
                @if ($count_missoes_concluidas >= $missoes_total)
                    @if (!$chefe_derrotado)
                        <p>
                            <button data-question="Deseja enfrentar o Chefe da Ilha agora?"
                                href="Missoes/atacar_chefe.php"
                                class="link_confirm btn btn-success">
                                Desafiar
                            </button>
                        </p>
                    @else
                        @if (!$chefe_derrotado_data['recompensa_recebida'])
                            <p>
                                <button class="btn btn-success link_send"
                                    href="link_Missoes/receber_recompensa_chefe.php">
                                    Receber a recompensa
                                </button>
                            </p>
                        @else
                            <p class="text-success">
                                Chefe Derrotado <i class="fa fa-check"></i>
                            </p>
                        @endif
                    @endif
                @endif
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
    @endif
</div>

@component('Sessoes.Influencia')
@endcomponent
