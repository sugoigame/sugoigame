@php
    global $userDetails;

    $requisitos = \Regras\Influencia::get_requisitos($userDetails->tripulacao['influencia']);
@endphp
<h5>
    Requisitos para o próximo nível:
</h5>
<div>
    @php
        $todos_concluidos = true;
    @endphp
    @foreach ($requisitos as $requisito)
        @php
            $faccao = \Utils\Data::find_inside('mundo', 'faccoes', ['cod' => $requisito['faccao']]);
            $relacao = array_find($relacoes, ['faccao_id' => $requisito['faccao']]);
            $concluido = $relacao['nivel'] >= $requisito['nivel'];
            if (!$concluido) {
                $todos_concluidos = false;
            }
        @endphp
        <div class="{{ $concluido ? 'text-line-through text-success' : '' }}">
            Relação de nível {{ $requisito['nivel'] }} com {{ $faccao['nome'] }}
        </div>
    @endforeach
</div>
<button class="btn btn-success link_send"
    href='link_Influencia/evoluir_influencia.php'
    {{ !$todos_concluidos ? 'disabled' : '' }}>
    Evoluir
</button>
