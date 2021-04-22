<div class="panel-heading">
    Calculadoras
</div>
<div class="panel-body">


    <div class="panel panel-default">
        <div class="panel-heading">Calculadora de Combate</div>
        <div class="panel-body">
            <?php $atk_1 = isset($_GET["atk_1"]) && validate_number($_GET["atk_1"]) ? $_GET["atk_1"] : NULL ?>
            <?php $def_1 = isset($_GET["def_1"]) && validate_number($_GET["def_1"]) ? $_GET["def_1"] : NULL ?>
            <?php $agl_1 = isset($_GET["agl_1"]) && validate_number($_GET["agl_1"]) ? $_GET["agl_1"] : NULL ?>
            <?php $res_1 = isset($_GET["res_1"]) && validate_number($_GET["res_1"]) ? $_GET["res_1"] : NULL ?>
            <?php $pre_1 = isset($_GET["pre_1"]) && validate_number($_GET["pre_1"]) ? $_GET["pre_1"] : NULL ?>
            <?php $dex_1 = isset($_GET["dex_1"]) && validate_number($_GET["dex_1"]) ? $_GET["dex_1"] : NULL ?>
            <?php $per_1 = isset($_GET["per_1"]) && validate_number($_GET["per_1"]) ? $_GET["per_1"] : NULL ?>
            <?php $mantra_1 = isset($_GET["mantra_1"]) && validate_number($_GET["mantra_1"]) ? $_GET["mantra_1"] : NULL ?>
            <?php $armamento_1 = isset($_GET["armamento_1"]) && validate_number($_GET["armamento_1"]) ? $_GET["armamento_1"] : NULL ?>
            <?php $classe_1 = isset($_GET["classe_1"]) && validate_number($_GET["classe_1"]) ? $_GET["classe_1"] : NULL ?>
            <?php $score_1 = isset($_GET["score_1"]) && validate_number($_GET["score_1"]) ? $_GET["score_1"] : NULL ?>
            <?php $akuma_1 = isset($_GET["akuma_1"]) && validate_number($_GET["akuma_1"]) ? $_GET["akuma_1"] : NULL ?>
            <?php $atk_2 = isset($_GET["atk_2"]) && validate_number($_GET["atk_2"]) ? $_GET["atk_2"] : NULL ?>
            <?php $def_2 = isset($_GET["def_2"]) && validate_number($_GET["def_2"]) ? $_GET["def_2"] : NULL ?>
            <?php $agl_2 = isset($_GET["agl_2"]) && validate_number($_GET["agl_2"]) ? $_GET["agl_2"] : NULL ?>
            <?php $res_2 = isset($_GET["res_2"]) && validate_number($_GET["res_2"]) ? $_GET["res_2"] : NULL ?>
            <?php $pre_2 = isset($_GET["pre_2"]) && validate_number($_GET["pre_2"]) ? $_GET["pre_2"] : NULL ?>
            <?php $dex_2 = isset($_GET["dex_2"]) && validate_number($_GET["dex_2"]) ? $_GET["dex_2"] : NULL ?>
            <?php $per_2 = isset($_GET["per_2"]) && validate_number($_GET["per_2"]) ? $_GET["per_2"] : NULL ?>
            <?php $mantra_2 = isset($_GET["mantra_2"]) && validate_number($_GET["mantra_2"]) ? $_GET["mantra_2"] : NULL ?>
            <?php $armamento_2 = isset($_GET["armamento_2"]) && validate_number($_GET["armamento_2"]) ? $_GET["armamento_2"] : NULL ?>
            <?php $classe_2 = isset($_GET["classe_2"]) && validate_number($_GET["classe_2"]) ? $_GET["classe_2"] : NULL ?>
            <?php $score_2 = isset($_GET["score_2"]) && validate_number($_GET["score_2"]) ? $_GET["score_2"] : NULL ?>
            <?php $akuma_2 = isset($_GET["akuma_2"]) && validate_number($_GET["akuma_2"]) ? $_GET["akuma_2"] : NULL ?>
            <?php $habilidade = isset($_GET["habilidade"]) && validate_number($_GET["habilidade"]) ? $_GET["habilidade"] : NULL ?>
            <form method="get">
                <input type="hidden" name="ses" value="calculadoras">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ataque do atacante:</label>
                            <input min="1" class="form-control" type="number" name="atk_1"
                                   required value="<?= $atk_1 ? $atk_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Defesa do atacante:</label>
                            <input min="1" class="form-control" type="number" name="def_1"
                                   required value="<?= $def_1 ? $def_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Agilidade do atacante:</label>
                            <input min="1" class="form-control" type="number" name="agl_1"
                                   required value="<?= $agl_1 ? $agl_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Resistência do atacante:</label>
                            <input min="1" class="form-control" type="number" name="res_1"
                                   required value="<?= $res_1 ? $res_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Precisão do atacante:</label>
                            <input min="1" class="form-control" type="number" name="pre_1"
                                   required value="<?= $pre_1 ? $pre_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Destreza do atacante:</label>
                            <input min="1" class="form-control" type="number" name="dex_1"
                                   required value="<?= $dex_1 ? $dex_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Percepção do atacante:</label>
                            <input min="1" class="form-control" type="number" name="per_1"
                                   required value="<?= $per_1 ? $per_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Mantra do atacante:</label>
                            <input min="0" max="20" class="form-control" type="number" name="mantra_1"
                                   required value="<?= $mantra_1 ? $mantra_1 : 0 ?>">
                        </div>

                        <div class="form-group">
                            <label>Armamento do atacante:</label>
                            <input min="0" max="20" class="form-control" type="number" name="armamento_1"
                                   required value="<?= $armamento_1 ? $armamento_1 : 0 ?>">
                        </div>

                        <div class="form-group">
                            <label>Classe do atacante:</label>
                            <select name="classe_1" class="form-control">
                                <option value="1" <?= $classe_1 == 1 ? "selected" : "" ?>>Espadachim</option>
                                <option value="2" <?= $classe_1 == 2 ? "selected" : "" ?>>Lutador</option>
                                <option value="3" <?= $classe_1 == 3 ? "selected" : "" ?>>Atirador</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Score do atacante:</label>
                            <input min="1" class="form-control" type="number" name="score_1"
                                   required value="<?= $score_1 ? $score_1 : 1000 ?>">
                        </div>

                        <div class="form-group">
                            <label>Categoria da Akuma no Mi do Atacante:</label>
                            <select name="akuma_1" class="form-control">
                                <option value="0" <?= $akuma_1 == 0 ? "selected" : "" ?>>Sem Akuma no Mi</option>
                                <option value="1" <?= $akuma_1 == 1 ? "selected" : "" ?>>A</option>
                                <option value="2" <?= $akuma_1 == 2 ? "selected" : "" ?>>B</option>
                                <option value="3" <?= $akuma_1 == 3 ? "selected" : "" ?>>C</option>
                                <option value="4" <?= $akuma_1 == 4 ? "selected" : "" ?>>D</option>
                                <option value="5" <?= $akuma_1 == 5 ? "selected" : "" ?>>E</option>
                                <option value="6" <?= $akuma_1 == 6 ? "selected" : "" ?>>F</option>
                                <option value="7" <?= $akuma_1 == 7 ? "selected" : "" ?>>Mística</option>
                                <option value="8" <?= $akuma_1 == 8 ? "selected" : "" ?>>Neutra</option>
                                <option value="9" <?= $akuma_1 == 9 ? "selected" : "" ?>>Ineficaz</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Dano da Habilidade:</label>
                            <input min="1" class="form-control" type="number" name="habilidade"
                                   required value="<?= $habilidade ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ataque do defensor:</label>
                            <input min="1" class="form-control" type="number" name="atk_2"
                                   required value="<?= $atk_2 ? $atk_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Defesa do defensor:</label>
                            <input min="1" class="form-control" type="number" name="def_2"
                                   required value="<?= $def_2 ? $def_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Agilidade do defensor:</label>
                            <input min="1" class="form-control" type="number" name="agl_2"
                                   required value="<?= $agl_2 ? $agl_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Resistência do defensor:</label>
                            <input min="1" class="form-control" type="number" name="res_2"
                                   required value="<?= $res_2 ? $res_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Precisão do defensor:</label>
                            <input min="1" class="form-control" type="number" name="pre_2"
                                   required value="<?= $pre_2 ? $pre_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Destreza do defensor:</label>
                            <input min="1" class="form-control" type="number" name="dex_2"
                                   required value="<?= $dex_2 ? $dex_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Percepção do defensor:</label>
                            <input min="1" class="form-control" type="number" name="per_2"
                                   required value="<?= $per_2 ? $per_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Mantra do defensor:</label>
                            <input min="0" max="20" class="form-control" type="number" name="mantra_2"
                                   required value="<?= $mantra_2 ? $mantra_2 : 0 ?>">
                        </div>

                        <div class="form-group">
                            <label>Armamento do defensor:</label>
                            <input min="0" max="20" class="form-control" type="number" name="armamento_2"
                                   required value="<?= $armamento_2 ? $armamento_2 : 0 ?>">
                        </div>

                        <div class="form-group">
                            <label>Classe do defensor:</label>
                            <select name="classe_2" class="form-control">
                                <option value="1" <?= $classe_2 == 1 ? "selected" : "" ?>>Espadachim</option>
                                <option value="2" <?= $classe_2 == 2 ? "selected" : "" ?>>Lutador</option>
                                <option value="3" <?= $classe_2 == 3 ? "selected" : "" ?>>Atirador</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Score do defensor:</label>
                            <input min="1" class="form-control" type="number" name="score_2"
                                   required value="<?= $score_2 ? $score_2 : 1000 ?>">
                        </div>

                        <div class="form-group">
                            <label>Categoria da Akuma no Mi do Defensor:</label>
                            <select name="akuma_2" class="form-control">
                                <option value="0" <?= $akuma_2 == 0 ? "selected" : "" ?>>Sem Akuma no Mi</option>
                                <option value="1" <?= $akuma_2 == 1 ? "selected" : "" ?>>A</option>
                                <option value="2" <?= $akuma_2 == 2 ? "selected" : "" ?>>B</option>
                                <option value="3" <?= $akuma_2 == 3 ? "selected" : "" ?>>C</option>
                                <option value="4" <?= $akuma_2 == 4 ? "selected" : "" ?>>D</option>
                                <option value="5" <?= $akuma_2 == 5 ? "selected" : "" ?>>E</option>
                                <option value="6" <?= $akuma_2 == 6 ? "selected" : "" ?>>F</option>
                                <option value="7" <?= $akuma_2 == 7 ? "selected" : "" ?>>Mística</option>
                                <option value="8" <?= $akuma_2 == 8 ? "selected" : "" ?>>Neutra</option>
                                <option value="9" <?= $akuma_2 == 9 ? "selected" : "" ?>>Ineficaz</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-success">Calcular</button>
                </div>
            </form>
        </div>

        <?php if ($atk_1 && $atk_2
            && $def_1 && $def_2
            && $agl_1 && $agl_2
            && $res_1 && $res_2
            && $pre_1 && $pre_2
            && $dex_1 && $dex_2
            && $per_1 && $per_2
            && $mantra_1 !== NULL && $mantra_2 !== NULL
            && $armamento_1 !== NULL && $armamento_2 !== NULL
            && $classe_1 && $classe_2
            && $score_1 && $score_2
            && $habilidade
        ): ?>
            <?php $pers = array(
                "atk" => $atk_1,
                "def" => $def_1,
                "agl" => $agl_1,
                "res" => $res_1,
                "pre" => $pre_1,
                "dex" => $dex_1,
                "con" => $per_1,
                "haki_esq" => $mantra_1,
                "haki_cri" => $armamento_1,
                "classe" => $classe_1,
                "classe_score" => $score_1
            ); ?>
            <?php $alvo = array(
                "atk" => $atk_2,
                "def" => $def_2,
                "agl" => $agl_2,
                "res" => $res_2,
                "pre" => $pre_2,
                "dex" => $dex_2,
                "con" => $per_2,
                "haki_esq" => $mantra_2,
                "haki_cri" => $armamento_2,
                "classe" => $classe_2,
                "classe_score" => $score_2
            ); ?>
            <?php $mod_akuma = $akuma_1 && $akuma_2 ? categoria_akuma($akuma_1, $akuma_2) : 1; ?>
            <div class="panel-footer text-left">
                <h4>Porcentagens:</h4>
                <ul>
                    <li>
                        Chance de Esquiva: <?= chance_esquiva($pers, $alvo) ?>%
                    </li>
                    <li>
                        Chance de Acerto Crítico: <?= chance_crit($pers, $alvo) ?>%
                    </li>
                    <li>
                        Aumento de dano por Acerto Crítico: <?= dano_crit($pers, $alvo) * 100 ?>%
                    </li>
                    <li>
                        Chance de Bloqueio: <?= chance_bloq($pers, $alvo) ?>%
                    </li>
                    <li>
                        Redução de dano por Bloqueio: <?= dano_bloq($pers, $alvo) * 100 ?>%
                    </li>
                    <li>
                        Alteração no dano pela vantagem da Akuma no Mi: <?= ($mod_akuma - 1) * 100 ?>%
                    </li>
                </ul>
                <h4>Simulação de Habilidade com <?= $habilidade ?> pontos de dano:</h4>
                <?php $resultado = calc_dano($pers, $alvo, $habilidade); ?>
                <p>Atacante usou Habilidade de <?= $habilidade ?> pontos de dano </p>
                <p>
                    Defensor rolou <?= $resultado["dado_esquivou"] ?>/100 e
                    <?= $resultado["esquivou"] ? "" : "não" ?> Esquivou
                </p>
                <?php if (!$resultado["esquivou"]) : ?>
                    <p>
                        Atacante rolou <?= $resultado["dado_critou"] ?>/100 e
                        <?= $resultado["critou"] ? "" : "não" ?> acertou um Golpe Crítico
                    </p>
                    <p>
                        Defensor rolou <?= $resultado["dado_bloqueou"] ?>/100 e
                        <?= $resultado["bloqueou"] ? "" : "não" ?> Bloqueou
                    </p>
                    <p>
                        Defensor <?= $resultado["bloqueou"] ? "Bloqueou" : "" ?> e
                        perdeu <?= $resultado["dano"] * $mod_akuma ?> pontos de
                        vida <?= $resultado["critou"] ? "Ataque Crítico" : "" ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Calculadora de reputação</div>
        <div class="panel-body">
            <?php $lvl_mais_forte_vencedor = isset($_GET["lvl_mais_forte_vencedor"]) && validate_number($_GET["lvl_mais_forte_vencedor"]) ? $_GET["lvl_mais_forte_vencedor"] : NULL ?>
            <?php $rep_vencedor = isset($_GET["rep_vencedor"]) && validate_number($_GET["rep_vencedor"]) ? $_GET["rep_vencedor"] : NULL ?>
            <?php $lvl_mais_forte_perdedor = isset($_GET["lvl_mais_forte_perdedor"]) && validate_number($_GET["lvl_mais_forte_perdedor"]) ? $_GET["lvl_mais_forte_perdedor"] : NULL ?>
            <?php $rep_perdedor = isset($_GET["rep_perdedor"]) && validate_number($_GET["rep_perdedor"]) ? $_GET["rep_perdedor"] : NULL ?>
            <form method="get">
                <input type="hidden" name="ses" value="calculadoras">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nível do mais forte do vencedor:</label>
                            <input min="1" max="50" class="form-control" type="number" name="lvl_mais_forte_vencedor"
                                   required value="<?= $lvl_mais_forte_vencedor ?>">
                        </div>
                        <div class="form-group">
                            <label>Reputação do vencedor:</label>
                            <input min="0" class="form-control" type="number" name="rep_vencedor" required
                                   value="<?= $rep_vencedor ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nível do mais forte do perdedor:</label>
                            <input min="1" max="50" class="form-control" type="number" name="lvl_mais_forte_perdedor"
                                   required value="<?= $lvl_mais_forte_perdedor ?>">
                        </div>
                        <div class="form-group">
                            <label>Reputação do perdedor:</label>
                            <input min="0" class="form-control" type="number" name="rep_perdedor" required
                                   value="<?= $rep_perdedor ?>">
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-success">Calcular</button>
                </div>
            </form>
        </div>

        <?php if ($lvl_mais_forte_vencedor
            && $rep_vencedor
            && $lvl_mais_forte_perdedor
            && $rep_perdedor
        ): ?>
            <div class="panel-footer text-left">
                <?php $reputacao = calc_reputacao($rep_vencedor, $rep_perdedor, $lvl_mais_forte_vencedor, $lvl_mais_forte_perdedor); ?>
                <ul>
                    <li>Reputação ganha pelo vencedor: <?= $reputacao["vencedor_rep"] ?></li>
                    <li>Reputação perdida pelo perdedor: <?= $reputacao["perdedor_rep"] ?></li>
                    <li>TESTE!!!! Reputação ganha pelo vencedor: <?= $reputacao["vencedor_rep.new"] ?></li>
                    <li>TESTE!!!! Reputação perdida pelo perdedor: <?= $reputacao["perdedor_rep.new"] ?></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>