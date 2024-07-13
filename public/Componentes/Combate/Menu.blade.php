{{-- $combate --}}

<div id="menu_batalha">
    @if ($combate->get_tempo_restante_turno() !== null)
        <div class="tempo-combate mb2">
            <img src="Imagens/Batalha/Menu/Tempo.png"
                alt="tempo" />
            <span id="tempo_batalha">
                {{ $combate->get_tempo_restante_turno() }}
            </span>
        </div>
    @endif
    @if ($combate->vez_de_quem() == $combate->minhaTripulacao->indice)
        <div>
            <button id="botao_atacar"
                onclick="atacar()"
                class="btn btn-sm btn-danger">
                <i class="fa fa-dot-circle-o"></i><br />
                Atacar
            </button>
        </div>
        <input type="hidden"
            id="moves_remain"
            value="{{ $combate->minhaTripulacao->get_movimentos_restantes() }}">
        @if ($combate->minhaTripulacao->get_movimentos_restantes())
            <button id="botao_mover"
                onclick="mover()"
                class="btn btn-sm btn-info">
                <i class="fa fa-arrows-alt"></i>
                {{ $combate->minhaTripulacao->get_movimentos_restantes() }}<br />
                Mover
            </button>
        @endif
        <div>
            <button id="botao_passar"
                onclick="passar_vez()"
                class="btn btn-sm btn-primary">
                <i class="fa fa-step-forward"></i><br />
                Passar
            </button>
        </div>
    @endif
    <div>
        <button id="botao_turno_automatico"
            onclick="toggleTurnoAutomatico()"
            style="z-index: 99999999999;"
            class="btn btn-sm btn-primary">
            <i class="fa fa-pause"></i><br />
            Auto
        </button>
    </div>
    <div>
        <button id="botao_desistir"
            onclick="desistir()"
            class="btn btn-sm btn-primary">
            <i class="fa fa-flag"></i><br />
            Desistir
        </button>
    </div>
</div>
