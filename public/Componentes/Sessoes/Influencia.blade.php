@php
    global $userDetails;
    global $connection;

    $faccoes = \Utils\Data::load('mundo')['faccoes'];
    $relacoes = $connection
        ->run('SELECT * FROM tb_tripulacao_faccao WHERE tripulacao_id = ?', 'i', [$userDetails->tripulacao['id']])
        ->fetch_all_array();
@endphp
<div class="panel-heading">
    Influência
    {!! ajuda_tooltip('Sua influência aumenta o poder da tripulação ao participar de confrontos') !!}
</div>

<div class="panel-body">
    <div class="row">
        <div class="col col-xs-4"></div>
        <div class="col col-xs-4">
            <div class="mb2">
                <h4>Seu nível de influência: {{ $userDetails->tripulacao['influencia'] }}</h4>
                @component('Influencia.Requisitos', ['relacoes' => $relacoes])
                @endcomponent
            </div>
        </div>
        <div class="col col-xs-4">
            <div class="mb2">
                @component('Influencia.Bonus', ['relacoes' => $relacoes])
                @endcomponent
            </div>
        </div>
    </div>


    <h4>Sua relação com as facções do mundo:</h4>
    @component('Influencia.Faccao', ['faccao' => $faccoes[0]])
    @endcomponent
    <div class="row justify-content-center">
        @foreach ($faccoes as $key => $faccao)
            @if ($key > 0)
                <div class="col-xs-3">
                    @component('Influencia.Faccao', [
                        'faccao' => $faccao,
                        'relacao' => array_find($relacoes, ['faccao_id' => $faccao['cod']]),
                    ])
                    @endcomponent
                </div>
            @endif
        @endforeach
    </div>
</div>
