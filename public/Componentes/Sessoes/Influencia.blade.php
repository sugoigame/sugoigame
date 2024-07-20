<div class="panel-heading">
    Influência
    {!! ajuda_tooltip('Sua influência aumenta o poder da tripulação ao participar de conflitos') !!}
</div>

<div class="panel-body">
    <div class="row">
        <div class="col col-xs-4"></div>
        <div class="col col-xs-4">
            <div class="mb2">
                <h4>Seu nível de influência: 10</h4>
                @component('Influencia.Requisitos')
                @endcomponent
            </div>
        </div>
        <div class="col col-xs-4">
            <div class="mb2">
                @component('Influencia.Bonus')
                @endcomponent
            </div>
        </div>
    </div>


    <h4>Sua relação com as facções do mundo:</h4>
    @php
        $faccoes = \Utils\Data::load('mundo')['faccoes'];
    @endphp
    @component('Influencia.Faccao', ['faccao' => $faccoes[0]])
    @endcomponent
    <div class="row justify-content-center">
        @foreach ($faccoes as $key => $faccao)
            @if ($key > 0)
                <div class="col-xs-3">
                    @component('Influencia.Faccao', ['faccao' => $faccao])
                    @endcomponent
                </div>
            @endif
        @endforeach
    </div>
</div>
