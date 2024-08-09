@php
    global $userDetails;
    global $connection;
    $ilha = \Regras\Ilhas::get_ilha($userDetails->ilha['ilha']);

    $faccao_ilha = $connection
        ->run('SELECT faccao FROM tb_mapa WHERE ilha = ?', 'i', [$userDetails->ilha['ilha']])
        ->fetch_array()['faccao'];
    $faccao = \Utils\Data::find_inside('mundo', 'faccoes', ['cod' => $faccao_ilha]);

    mt_srand($userDetails->ilha['ilha']);
    $viajante_img = rand(1, PERSONAGENS_MAX);
    mt_srand();
@endphp
<div class="panel-heading">
    Viajantes
    {!! ajuda_tooltip(
        'Viajantes pegam carona com a sua tripulação, e em troca realizam pesquisas que produzem diferentes recursos bastante úteis.',
    ) !!}
</div>

<div class="panel-body">
    <p>
        Essa ilha está sob influência da facção: {{ $faccao['nome'] }}
    </p>

    <h4>Viajante disponível:</h4>
    <p>
        @component('Personagem.BigImg', [
            'img' => $viajante_img,
            'skin_c' => 0,
        ])
        @endcomponent
    </p>
    <p>
        Produção: {{ $faccao['viajante']['producao'] }}
    </p>
    <button data-question="Você só pode ter um viajante no barco, deseja que esse seja seu viajante?"
        href="Viajante/convidar.php"
        class="link_confirm btn btn-success">
        Convidar
    </button>
</div>
