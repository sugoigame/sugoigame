{{-- $explicacao --}}
@php
    $explicacao = str_replace('{ATK}', Componentes::render('Habilidades.IconeAtributo', ['atr' => 'atk']), $explicacao);
    $explicacao = str_replace('{DEF}', Componentes::render('Habilidades.IconeAtributo', ['atr' => 'def']), $explicacao);
    $explicacao = str_replace('{AGL}', Componentes::render('Habilidades.IconeAtributo', ['atr' => 'agl']), $explicacao);
    $explicacao = str_replace('{PRE}', Componentes::render('Habilidades.IconeAtributo', ['atr' => 'pre']), $explicacao);
    $explicacao = str_replace('{RES}', Componentes::render('Habilidades.IconeAtributo', ['atr' => 'res']), $explicacao);
    $explicacao = str_replace('{DEX}', Componentes::render('Habilidades.IconeAtributo', ['atr' => 'dex']), $explicacao);
    $explicacao = str_replace('{PER}', Componentes::render('Habilidades.IconeAtributo', ['atr' => 'per']), $explicacao);
@endphp

{!! $explicacao !!}
