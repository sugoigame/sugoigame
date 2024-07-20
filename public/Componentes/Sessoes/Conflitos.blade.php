@php
    global $userDetails;
@endphp
<div class="panel-heading">
    Conflitos
    {!! ajuda_tooltip(
        'Participe de eventos importantes para ganhar recompensas e aumentar sua influência no mundo.',
    ) !!}
</div>

<div class="panel-body">
    <div>
        <div class="progress">
            <div class="progress-bar progress-bar-success"
                style="width: 25%">
                <span>Conflitos disponíveis: 1/5</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col col-xs-3 d-flex flex-column justify-content-center align-items-center">
            <div class="panel">
                <div class="panel-heading">
                    Próximo conflito
                </div>
                <div class="panel-body">
                    <div>Dificuldade: Nível 10</div>
                    <div>Facção: Jornal Econômico Mundial</div>
                    <h5>Recompensas:</h5>
                    {{ render_recompensa(
                        [
                            'tipo' => 'berries',
                            'quant' => 5000,
                        ],
                        [],
                        [],
                    ) }}
                    {{ render_recompensa(
                        [
                            'tipo' => 'xp',
                            'quant' => 250,
                        ],
                        [],
                        [],
                    ) }}
                </div>
            </div>
        </div>
        <div class="col col-xs-3">
            <h5>Capitão adversário:</h5>
            @component('Personagem.Avatar', [
                'pers' => $userDetails->personagens[0],
                'tripulacao' => $userDetails->tripulacao,
            ])
            @endcomponent
        </div>
        <div class="col col-xs-6">
            <h5>Tripulação adversária:</h5>
            <div class="">
                @foreach ($userDetails->personagens as $pers)
                    @component('Personagem.Cartaz', ['pers' => $pers, 'faccao' => 1])
                    @endcomponent
                @endforeach
            </div>
            <div class="text-right panel-body">
                <button class="btn btn-success">Iniciar</button>
            </div>
        </div>
    </div>
</div>
