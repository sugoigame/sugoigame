{{-- $habilidade --}}
@php
    $habilidade = habilidade_default_values($habilidade);
@endphp

<a class="noHref"
    href="#"
    class="habilidade-icone"
    data-toggle="popover"
    data-html="true"
    data-placement="bottom"
    data-container="#tudo"
    data-placement="right"
    data-trigger="focus"
    data-content='{{ Componentes::render('Habilidades.Descricao', ['habilidade' => $habilidade]) }}'>
    <img src="Imagens/Skils/{{ $habilidade['icone'] }}.jpg" />
</a>
