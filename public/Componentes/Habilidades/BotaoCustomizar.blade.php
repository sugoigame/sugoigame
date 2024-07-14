{{-- $pers ,$habilidade, $animacoes --}}
@php
    $modal_id = $pers['cod'] . '_' . $habilidade['cod'];
@endphp

<script type="text/javascript">
    $(function() {
        $('#customiza-{{ $modal_id }}').click(function() {
            $('#modal-edit-skill-{{ $modal_id }}').modal();
        });
    });
</script>
<button id="customiza-{{ $modal_id }}"
    class="btn btn-sm btn-info">Customizar</button>

<div class="modal fade"
    id="modal-edit-skill-{{ $modal_id }}">
    <div class="modal-dialog modal-lg"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    Customizar Habilidade
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    @if (\Regras\Habilidades::is_editavel($habilidade))
                        <div class="col-md-6">
                            <form action="Vip/customiza_skill"
                                class="ajax_form"
                                onsubmit="$('#modal-edit-skill-{{ $modal_id }}').modal('hide');"
                                data-question="Deseja customizar a habilidade?"
                                id="form-custumza-skill"
                                method="POST">
                                <input value="{{ $pers['cod'] }}"
                                    name="codpers"
                                    type="hidden">
                                <input value="{{ $habilidade['cod'] }}"
                                    name="codskil"
                                    type="hidden">
                                <input id="skill-input-{{ $modal_id }}"
                                    name="img"
                                    type="hidden"
                                    value="{{ $habilidade['icone'] }}"
                                    required>

                                <label>Selecione uma imagem:</label>
                                <img width="40px"
                                    id="skill-img-{{ $modal_id }}"
                                    src="Imagens/Skils/{{ $habilidade['icone'] }}.jpg"
                                    onclick="geraImgsSkill('skill-list-{{ $modal_id }}','skill-input-{{ $modal_id }}','skill-img-{{ $modal_id }}', {{ SKILLS_ICONS_MAX }});" />

                                <div class="selecao_img"
                                    style="display: none"
                                    id="skill-list-{{ $modal_id }}">
                                </div>
                                <div class="form-group">
                                    <label>Nome da habilidade</label>
                                    <input name="nome"
                                        size="10"
                                        maxlength="20"
                                        class="form-control"
                                        value="{{ $habilidade['nome'] }}"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label>Descrição da habilidade</label>
                                    <textarea cols="18"
                                        maxlength="300"
                                        name="descricao"
                                        class="form-control"
                                        required>{{ $habilidade['descricao'] }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <input type="radio"
                                            name="tipo_pagamento"
                                            value="gold"
                                            required>
                                        {{ PRECO_GOLD_CUSTOMIZAR_SKILL }}
                                        <img src="Imagens/Icones/Gold.png" />
                                    </label>
                                </div>
                                <button class="btn btn-success"
                                    type="submit">
                                    Salvar nome, descrição e ícone
                                </button>
                            </form>
                        </div>
                        @if (!isset($habilidade['efeitos']) || !isset($habilidade['efeitos']['passivos']))
                            <div class="col-md-6">
                                <h3>Animação ativa:</h3>
                                <p>
                                    <button data-effect="{{ $habilidade['animacao'] }}"
                                        class="play-effect btn btn-primary">
                                        {{ $habilidade['animacao'] }} <i class="fa fa-play"></i>
                                    </button>
                                </p>
                                <h4>Animações disponíveis:</h4>
                                @if (count($animacoes))
                                    <form class="ajax_form"
                                        method="post"
                                        action="Personagem/mudar_animacao_skill"
                                        onsubmit="$('#modal-edit-skill-{{ $modal_id }}').modal('hide');"
                                        data-question="Você consumirá uma unidade da animação para aplica-la a essa habilidade. Deseja continuar?">
                                        <input type="hidden"
                                            name="pers"
                                            value="{{ $pers['cod'] }}" />
                                        <input type="hidden"
                                            name="cod_skil"
                                            value="{{ $habilidade['cod_skil'] }}" />
                                        <div class="form-group">
                                            <select class="form-control"
                                                name="effect"
                                                required>
                                                @foreach ($animacoes as $animacao)
                                                    <option value="{{ $animacao['effect'] }}">
                                                        {{ $animacao['effect'] }} x{{ $animacao['quant'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit"
                                            class="btn btn-info">
                                            Mudar Animação
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger"
                    type="button"
                    data-dismiss="modal">Cancelar</button>
                <button class="noHref btn btn-info"
                    onclick="window.open('Scripts/habilidade_random.php','Sugoi Game - Sugestão de habilidade','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=200');">
                    Sugestão de habilidade
                </button>
            </div>
        </div>
    </div>
</div>
