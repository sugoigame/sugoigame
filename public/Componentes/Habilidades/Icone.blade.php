{{-- $habilidade, $vontade --}}
@php
    $habilidade = \Regras\Habilidades::habilidade_default_values($habilidade);
@endphp

<a class="noHref habilidade-icone"
    href="#"
    data-toggle="popover"
    data-html="true"
    data-placement="bottom"
    data-container="#tudo"
    data-placement="right"
    data-trigger="focus"
    data-content='{{ Componentes::render('Habilidades.Descricao', ['habilidade' => $habilidade, 'vontade' => $vontade]) }}'>
    <img width="50px"
        height="50px"
        alt=""
        src="Imagens/Skils/{{ $habilidade['icone'] }}.jpg" />
    @if (isset($habilidade['quantidade']))
        <span class="badge badge-default position-absolute">
            {{ $habilidade['quantidade'] }}
        </span>
    @endif
</a>
