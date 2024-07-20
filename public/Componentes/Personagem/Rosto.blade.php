{{-- $pers, $class, $attributes --}}
<img class="icon-pers-skin {{ $class }}"
    src="Imagens/Personagens/Icons/{{ sprintf('%04d', $pers['img']) }}({{ $pers['skin_r'] }}).jpg"
    alt=""
    {{ $attributes }} />
