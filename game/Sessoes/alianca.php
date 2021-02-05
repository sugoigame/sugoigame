<?php $termo_correto = $userDetails->tripulacao["faccao"] == 0 ? "Frota" : "Aliança"; ?>

<?php function render_editor_permissoes($autoridade) { ?>
    <?php global $userDetails; ?>
    <div class="row">
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_0"
                        <?php if (can_convidar($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Convidar
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_1"
                        <?php if (can_expulsar($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Expulsar
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_2"
                        <?php if (can_alt_cargo($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Alterar cargos
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_3"
                        <?php if (can_edit_mural($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Alterar o mural
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_4"
                        <?php if (can_ini_guerra($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Iniciar uma guerra
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_5"
                        <?php if (can_fin_guerra($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Finalizar um guerra
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_6"
                        <?php if (can_ini_missao($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Iniciar uma missão
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_7"
                        <?php if (can_fin_missao($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Finalizar uma missão
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_8"
                        <?php if (can_guardar_itens($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Guardar itens
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_9"
                        <?php if (can_sacar_itens($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Sacar itens
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_10"
                        <?php if (can_guardar_berries($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Guardar Berries
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="<?= $autoridade ?>_11"
                        <?php if (can_sacar_berries($userDetails->ally, $autoridade)) echo 'checked="1"'; ?> />
                    Sacar Berries
                </label>
            </div>
        </div>
    </div>
<?php } ?>

<div class="panel-heading">
    <?= $userDetails->ally["nome"] ?>
</div>
<script type="text/javascript">
    $(function () {
        $("#alianca_mural_submit").click(function () {
            var mural = $("#alianca_mural").val();

            obj = {
                mural: mural
            };
            var pagina = "Alianca/alianca_mural";
            sendForm(pagina, obj);
        });
        $("#alianca_convidar_submit").click(function () {
            var mural = $("#alianca_convidar").val();

            var obj = {
                nome: mural
            };
            var pagina = "Alianca/alianca_convidar";
            sendForm(pagina, obj);
            loadPagina(pagina_atual);
        });
        $(".alterar_cargo").click(function () {
            var $sel = $("#" + this.id + "_sel");
            var cargo = $sel.val();
            var cod = $sel.attr("href");
            var locale = "Alianca/alianca_cargo.php" + "?cod=" + cod + "&cargo=" + cargo;
            bootbox.confirm({
                message: "Alterar o cargo desse membro?",
                buttons: {
                    confirm: {
                        label: 'Sim',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Não',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        sendGet(locale);
                    }
                }
            });
        });
        $(".alianca_expulsar").click(function () {
            var locale = $(this).attr("href");
            bootbox.confirm({
                message: "Expulsar esse membro?",
                buttons: {
                    confirm: {
                        label: 'Sim',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Não',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        sendGet(locale);
                    }
                }
            });
        });
        $("#sair_alianca").click(function () {
            var locale = $(this).attr("href");
            bootbox.confirm({
                message: "Sair dessa <?= ($usuario["faccao"] == 0) ? "Frota" : "Aliança" ?>?",
                buttons: {
                    confirm: {
                        label: 'Sim',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Não',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        sendGet(locale);
                    }
                }
            });
        });
        $("#apagar_alianca").click(function () {
            var locale = $(this).attr("href");
            bootbox.confirm({
                message: "Apagar eessa <?= ($usuario["faccao"] == 0) ? "Frota" : "Aliança" ?>?",
                buttons: {
                    confirm: {
                        label: 'Sim',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Não',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        sendGet(locale);
                    }
                }
            });
        });
    });
</script>

<div class="panel-body">
    <?= ajuda("Frotas / Alianças", "Veja as informações da sua Aliança/Frota.") ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <textarea id="alianca_mural"
                          class="form-control" <?= !can_edit_mural($userDetails->ally) ? 'readonly="true"' : "" ?>
                          style="height: 200px; text-align: center; overflow: auto; resize: none;"><?= $userDetails->ally["mural"] ?></textarea>
            </div>
            <?php if (can_edit_mural($userDetails->ally)) : ?>
                <button class="btn btn-primary" id="alianca_mural_submit">Editar Mural</button><br>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <div>
                <div class="progress">
                    <div class="progress-bar progress-bar-default" role="progressbar"
                         style="width: <?= $userDetails->ally["xp"] / $userDetails->ally["xp_max"] * 100 ?>%;">
                        XP:<?= $userDetails->ally["xp"] . "/" . $userDetails->ally["xp_max"] ?>
                    </div>
                </div>
                <? if ($userDetails->ally["xp"] >= $userDetails->ally["xp_max"] AND $userDetails->ally["lvl"] < 10) : ?>
                    <button href='link_Alianca/alianca_evoluir.php' class="link_send btn btn-success">
                        Evoluir
                    </button>
                <? endif; ?>
            </div>
            <div><strong>Nível:</strong> <?= $userDetails->ally["lvl"] ?> </div>
            <div><strong>Reputação:</strong> <?= $userDetails->ally["score"] ?> </div>
            <div>
                <strong>Membros:</strong>
                <?= $connection
                    ->run("SELECT count(id) AS `count` FROM tb_alianca_membros WHERE cod_alianca = ?",
                        "i", $userDetails->ally["cod_alianca"])
                    ->fetch_array()["count"] ?>
            </div>
            <div><strong>Vitórias:</strong> <?= $userDetails->ally["vitorias"] ?> </div>
            <div><strong>Derrotas:</strong> <?= $userDetails->ally["derrotas"] ?> </div>
        </div>
    </div>
    <br/>
    <?php if (can_convidar($userDetails->ally)) : ?>
        <div class="form-inline">
            <div class="form-group">
                <input id="alianca_convidar" class="form-control" size="50"
                       placeholder="Informe o nome de um capitão para convidá-lo">
            </div>
            <button class="btn btn-primary" id="alianca_convidar_submit">Convidar</button>
        </div>
        <br/>
    <?php endif; ?>

    <h4>Membros</h4>
    <?php $result = $connection->run("
SELECT 
usr.tripulacao AS tripulacao,
pers.nome AS capitao,
usr.id AS id,
allymemb.autoridade AS autoridade,
allymemb.cooperacao AS cooperacao,
usr.ultimo_logon AS ultimo_logon,
usr.bandeira AS bandeira,
usr.faccao AS faccao
FROM tb_alianca_membros allymemb
INNER JOIN tb_usuarios usr ON allymemb.id=usr.id
INNER JOIN tb_personagens pers ON usr.cod_personagem = pers.cod
WHERE allymemb.cod_alianca = ?", "i", $userDetails->ally["cod_alianca"]); ?>
    <ul class="list-group">
        <?php while ($membro = $result->fetch_array()): ?>
            <li class="list-group-item">
                <h4>
                    <img src="Imagens/Bandeiras/img.php?cod=<?= $membro["bandeira"] . "&f=" . $membro["faccao"] ?>"/>
                    <?= $membro["tripulacao"] ?> - <?= $membro["capitao"] ?>
                    <img src="Imagens/Icones/logon_<?= ultimo_logon($membro["ultimo_logon"]) ?>.png"
                         data-toggle="tooltip" data-placement="right"
                         title="<?= ultimo_logon_texto($membro["ultimo_logon"]) ?>"/>
                </h4>
                <div class="row">
                    <div class="col-md-6">
                        <div>Cargo: <?= get_cargo_name($membro["autoridade"]) ?></div>
                        <div>Cooperação: <?= $membro["cooperacao"] ?></div>
                    </div>
                    <div class="col-md-6">
                        <?php if (can_alt_cargo($userDetails->ally)
                            AND $membro["id"] != $userDetails->tripulacao["id"]
                            AND $membro["autoridade"] > $userDetails->ally["autoridade"]
                        ) : ?>
                            <div class="form-inline">
                                <div class="form-group">
                                    <label>Alterar o cargo:</label>
                                    <select class="form-control" href="<?= $membro["id"]; ?>"
                                            id="cargo_<?= $membro["id"]; ?>_sel">
                                        <option value="4">Novato</option>
                                        <option value="3">Aprendiz</option>
                                        <option value="2">Veterano</option>
                                        <option value="1">Oficial</option>
                                        <?php if ($userDetails->ally["autoridade"] == 0) : ?>
                                            <option value="0">Líder</option>
                                        <?php endif; ?>
                                    </select>
                                    <button id="cargo_<?= $membro["id"]; ?>" class="btn btn-primary alterar_cargo">
                                        Alterar
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (can_expulsar($userDetails->ally)
                    AND $membro["id"] != $userDetails->tripulacao["id"]
                    AND $membro["autoridade"] > $userDetails->ally["autoridade"]
                ) : ?>
                    <div>
                        <button href='Alianca/alianca_expulsar.php?cod=<?= $membro["id"] ?>'
                                class="alianca_expulsar btn btn-danger">
                            Expulsar
                        </button>
                    </div>
                <?php endif; ?>

                <div>
                    <?php if ($membro["id"] == $userDetails->tripulacao["id"] AND $userDetails->ally["autoridade"] != 0) : ?>
                        <button href='Alianca/alianca_sair.php' id="sair_alianca" class="btn btn-danger">
                            Sair da <?= $termo_correto ?>
                        </button>
                    <?php elseif ($membro["id"] == $userDetails->tripulacao["id"]) : ?>
                        <button href='Alianca/alianca_apagar.php' id="apagar_alianca" class="btn btn-danger">
                            Excluir <?= $termo_correto ?>
                        </button>
                    <?php endif; ?>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>

    <?php if ($userDetails->ally["autoridade"] == 0) : ?>
        <br/>
        <div>
            <a class="btn btn-info" data-toggle="collapse" href="#painel-permissao">
                Exibir o painel de permissões
            </a>
        </div>
        <div id="painel-permissao" class="collapse out">
            <form action="Scripts/Alianca/alianca_permicoes.php" method="POST">
                <ul class="list-group">
                    <li class="list-group-item">
                        <h4>Oficial</h4>
                        <?php render_editor_permissoes(1); ?>
                    </li>
                    <li class="list-group-item">
                        <h4>Veterano</h4>
                        <?php render_editor_permissoes(2); ?>
                    </li>
                    <li class="list-group-item">
                        <h4>Aprendiz</h4>
                        <?php render_editor_permissoes(3); ?>
                    </li>
                    <li class="list-group-item">
                        <h4>Novato</h4>
                        <?php render_editor_permissoes(4); ?>
                    </li>
                </ul>
                <button class="btn btn-primary" type="submit">Salvar permissões</button>
            </form>
        </div>
    <? endif; ?>
</div>