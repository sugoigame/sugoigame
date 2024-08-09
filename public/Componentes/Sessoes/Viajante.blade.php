@php
    global $userDetails;
    global $connection;
    $faccao = \Utils\Data::find_inside('mundo', 'faccoes', ['cod' => $userDetails->tripulacao['viajante_faccao']]);
@endphp
<div class="panel-heading">
    Seu viajante
    {!! ajuda_tooltip(
        'Viajantes pegam carona com a sua tripulação, e em troca realizam pesquisas que produzem diferentes recursos bastante úteis.',
    ) !!}
</div>

<div class="panel-body">
    @if ($userDetails->tripulacao['viajante_img'])
        <h4>Viajante no navio:</h4>
        <p>
            @component('Personagem.BigImg', [
                'img' => $userDetails->tripulacao['viajante_img'],
                'skin_c' => 0,
            ])
            @endcomponent
        </p>
        <p>
            Produção: {{ $faccao['viajante']['producao'] }}
        </p>
    @else
        <p>Você não tem nenhum viajante no barco</p>
    @endif
</div>
