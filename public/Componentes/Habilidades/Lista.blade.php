{{-- $pers, $habilidades, $lvl_field --}}
@php
    global $connection;
    global $userDetails;
    $lvl_field = $lvl_field ?: 'lvl';
@endphp

@php
    $aprendidas_db = $connection
        ->run('SELECT * FROM tb_personagens_skil WHERE cod_pers = ?', 'i', [$pers['cod']])
        ->fetch_all_array();

    $animacoes = $connection
        ->run('SELECT * FROM tb_tripulacao_animacoes_skills WHERE tripulacao_id = ?', 'i', [
            $userDetails->tripulacao['id'],
        ])
        ->fetch_all_array();

    $aprendidas = [];
    foreach ($aprendidas_db as $aprendida) {
        $aprendidas[$aprendida['cod_skil']] = $aprendida;
    }
@endphp
<h5>
    Habilidades:
</h5>
<div class="row align-items-center justify-content-center mb2">
    @foreach ($habilidades as $habilidade)
        @php
            $habilidade = array_merge($habilidade, $aprendidas[$habilidade['cod']] ?: []);
        @endphp
        <div class="d-inline-block mx2 mb2">
            <div>
                @component('Habilidades.Icone', ['habilidade' => $habilidade, 'vontade' => $habilidade['vontade']])
                @endcomponent
            </div>
            @if ($habilidade['requisito_lvl'] <= $pers[$lvl_field])
                @component('Habilidades.BotaoCustomizar', [
                    'pers' => $pers,
                    'habilidade' => $habilidade,
                    'animacoes' => $animacoes,
                ])
                @endcomponent
            @else
                <button class="btn btn-sm btn-primary"
                    disabled>
                    Nível {{ $habilidade['requisito_lvl'] }}
                </button>
            @endif
        </div>
    @endforeach
</div>
