<div class="panel-heading">
    Calculadoras
</div>
<div class="panel-body">


    <div class="panel panel-default">
        <div class="panel-heading">Calculadora de Combate</div>
        <div class="panel-body">
            <?php $atk_1 = isset($_GET["atk_1"]) && validate_number($_GET["atk_1"]) ? $_GET["atk_1"] : null ?>
            <?php $def_1 = isset($_GET["def_1"]) && validate_number($_GET["def_1"]) ? $_GET["def_1"] : null ?>
            <?php $agl_1 = isset($_GET["agl_1"]) && validate_number($_GET["agl_1"]) ? $_GET["agl_1"] : null ?>
            <?php $res_1 = isset($_GET["res_1"]) && validate_number($_GET["res_1"]) ? $_GET["res_1"] : null ?>
            <?php $pre_1 = isset($_GET["pre_1"]) && validate_number($_GET["pre_1"]) ? $_GET["pre_1"] : null ?>
            <?php $dex_1 = isset($_GET["dex_1"]) && validate_number($_GET["dex_1"]) ? $_GET["dex_1"] : null ?>
            <?php $per_1 = isset($_GET["per_1"]) && validate_number($_GET["per_1"]) ? $_GET["per_1"] : null ?>
            <?php $mantra_1 = isset($_GET["mantra_1"]) && validate_number($_GET["mantra_1"]) ? $_GET["mantra_1"] : null ?>
            <?php $armamento_1 = isset($_GET["armamento_1"]) && validate_number($_GET["armamento_1"]) ? $_GET["armamento_1"] : null ?>
            <?php $classe_1 = isset($_GET["classe_1"]) && validate_number($_GET["classe_1"]) ? $_GET["classe_1"] : null ?>
            <?php $score_1 = isset($_GET["score_1"]) && validate_number($_GET["score_1"]) ? $_GET["score_1"] : null ?>
            <?php $atk_2 = isset($_GET["atk_2"]) && validate_number($_GET["atk_2"]) ? $_GET["atk_2"] : null ?>
            <?php $def_2 = isset($_GET["def_2"]) && validate_number($_GET["def_2"]) ? $_GET["def_2"] : null ?>
            <?php $agl_2 = isset($_GET["agl_2"]) && validate_number($_GET["agl_2"]) ? $_GET["agl_2"] : null ?>
            <?php $res_2 = isset($_GET["res_2"]) && validate_number($_GET["res_2"]) ? $_GET["res_2"] : null ?>
            <?php $pre_2 = isset($_GET["pre_2"]) && validate_number($_GET["pre_2"]) ? $_GET["pre_2"] : null ?>
            <?php $dex_2 = isset($_GET["dex_2"]) && validate_number($_GET["dex_2"]) ? $_GET["dex_2"] : null ?>
            <?php $per_2 = isset($_GET["per_2"]) && validate_number($_GET["per_2"]) ? $_GET["per_2"] : null ?>
            <?php $mantra_2 = isset($_GET["mantra_2"]) && validate_number($_GET["mantra_2"]) ? $_GET["mantra_2"] : null ?>
            <?php $armamento_2 = isset($_GET["armamento_2"]) && validate_number($_GET["armamento_2"]) ? $_GET["armamento_2"] : null ?>
            <?php $classe_2 = isset($_GET["classe_2"]) && validate_number($_GET["classe_2"]) ? $_GET["classe_2"] : null ?>
            <?php $score_2 = isset($_GET["score_2"]) && validate_number($_GET["score_2"]) ? $_GET["score_2"] : null ?>
            <?php $habilidade = isset($_GET["habilidade"]) && validate_number($_GET["habilidade"]) ? $_GET["habilidade"] : null ?>
            <form method="get">
                <input type="hidden" name="ses" value="calculadoras">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ataque do atacante:</label>
                            <input min="1" class="form-control" type="number" name="atk_1" required
                                value="<?= $atk_1 ? $atk_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Defesa do atacante:</label>
                            <input min="1" class="form-control" type="number" name="def_1" required
                                value="<?= $def_1 ? $def_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Agilidade do atacante:</label>
                            <input min="1" class="form-control" type="number" name="agl_1" required
                                value="<?= $agl_1 ? $agl_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Resistência do atacante:</label>
                            <input min="1" class="form-control" type="number" name="res_1" required
                                value="<?= $res_1 ? $res_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Precisão do atacante:</label>
                            <input min="1" class="form-control" type="number" name="pre_1" required
                                value="<?= $pre_1 ? $pre_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Destreza do atacante:</label>
                            <input min="1" class="form-control" type="number" name="dex_1" required
                                value="<?= $dex_1 ? $dex_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Percepção do atacante:</label>
                            <input min="1" class="form-control" type="number" name="per_1" required
                                value="<?= $per_1 ? $per_1 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Mantra do atacante:</label>
                            <input min="0" max="20" class="form-control" type="number" name="mantra_1" required
                                value="<?= $mantra_1 ? $mantra_1 : 0 ?>">
                        </div>

                        <div class="form-group">
                            <label>Armamento do atacante:</label>
                            <input min="0" max="20" class="form-control" type="number" name="armamento_1" required
                                value="<?= $armamento_1 ? $armamento_1 : 0 ?>">
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
                            <input min="1" class="form-control" type="number" name="score_1" required
                                value="<?= $score_1 ? $score_1 : 1000 ?>">
                        </div>

                        <div class="form-group">
                            <label>Dano da Habilidade:</label>
                            <input min="1" class="form-control" type="number" name="habilidade" required
                                value="<?= $habilidade ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ataque do defensor:</label>
                            <input min="1" class="form-control" type="number" name="atk_2" required
                                value="<?= $atk_2 ? $atk_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Defesa do defensor:</label>
                            <input min="1" class="form-control" type="number" name="def_2" required
                                value="<?= $def_2 ? $def_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Agilidade do defensor:</label>
                            <input min="1" class="form-control" type="number" name="agl_2" required
                                value="<?= $agl_2 ? $agl_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Resistência do defensor:</label>
                            <input min="1" class="form-control" type="number" name="res_2" required
                                value="<?= $res_2 ? $res_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Precisão do defensor:</label>
                            <input min="1" class="form-control" type="number" name="pre_2" required
                                value="<?= $pre_2 ? $pre_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Destreza do defensor:</label>
                            <input min="1" class="form-control" type="number" name="dex_2" required
                                value="<?= $dex_2 ? $dex_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Percepção do defensor:</label>
                            <input min="1" class="form-control" type="number" name="per_2" required
                                value="<?= $per_2 ? $per_2 : 1 ?>">
                        </div>

                        <div class="form-group">
                            <label>Mantra do defensor:</label>
                            <input min="0" max="20" class="form-control" type="number" name="mantra_2" required
                                value="<?= $mantra_2 ? $mantra_2 : 0 ?>">
                        </div>

                        <div class="form-group">
                            <label>Armamento do defensor:</label>
                            <input min="0" max="20" class="form-control" type="number" name="armamento_2" required
                                value="<?= $armamento_2 ? $armamento_2 : 0 ?>">
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
                            <input min="1" class="form-control" type="number" name="score_2" required
                                value="<?= $score_2 ? $score_2 : 1000 ?>">
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
            && $mantra_1 !== null && $mantra_2 !== null
            && $armamento_1 !== null && $armamento_2 !== null
            && $classe_1 && $classe_2
            && $score_1 && $score_2
            && $habilidade
        ) : ?>
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
            <div class="panel-footer text-left">
                <h4>Porcentagens:</h4>
                <ul>
                    <li>
                        Chance de Esquiva:
                        <?= chance_esquiva($pers, $alvo) ?>%
                    </li>
                    <li>
                        Chance de Acerto Crítico:
                        <?= chance_crit($pers, $alvo) ?>%
                    </li>
                    <li>
                        Aumento de dano por Acerto Crítico:
                        <?= dano_crit($pers, $alvo) * 100 ?>%
                    </li>
                    <li>
                        Chance de Bloqueio:
                        <?= chance_bloq($pers, $alvo) ?>%
                    </li>
                    <li>
                        Redução de dano por Bloqueio:
                        <?= dano_bloq($pers, $alvo) * 100 ?>%
                    </li>
                </ul>
                <h4>Simulação de Habilidade com
                    <?= $habilidade ?> pontos de dano:
                </h4>
                <?php $resultado = calc_dano($pers, $alvo, $habilidade); ?>
                <p>Atacante usou Habilidade de
                    <?= $habilidade ?> pontos de dano
                </p>
                <p>
                    Defensor rolou
                    <?= $resultado["dado_esquivou"] ?>/100 e
                    <?= $resultado["esquivou"] ? "" : "não" ?> Esquivou
                </p>
                <?php if (! $resultado["esquivou"]) : ?>
                    <p>
                        Atacante rolou
                        <?= $resultado["dado_critou"] ?>/100 e
                        <?= $resultado["critou"] ? "" : "não" ?> acertou um Golpe Crítico
                    </p>
                    <p>
                        Defensor rolou
                        <?= $resultado["dado_bloqueou"] ?>/100 e
                        <?= $resultado["bloqueou"] ? "" : "não" ?> Bloqueou
                    </p>
                    <p>
                        Defensor
                        <?= $resultado["bloqueou"] ? "Bloqueou" : "" ?> e
                        perdeu
                        <?= $resultado["dano"] ?> pontos de
                        vida
                        <?= $resultado["critou"] ? "Ataque Crítico" : "" ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
