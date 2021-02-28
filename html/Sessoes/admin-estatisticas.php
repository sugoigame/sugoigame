<div class="panel-heading">
    Estatisticas
</div>
<script type="text/javascript">
    <?php include "JS/Chart.min.js"; ?>
</script>
<?php $relatorios_diarios = array_reverse($connection->run("SELECT * FROM tb_relatorio_diario ORDER BY dia DESC LIMIT 15")->fetch_all_array()); ?>
<?php $tripulacoes_ativas = array(); ?>
<?php $contas_ativas = array(); ?>
<?php $ips_ativos = array(); ?>
<?php $novas_contas = array(); ?>
<?php $gold = array(); ?>
<?php $dobroes = array(); ?>
<?php $datas = array(); ?>
<?php foreach ($relatorios_diarios as $relatorio) {
    $datas[] = "'" . date('d/m', strtotime($relatorio["dia"])) . "'";
    $tripulacoes_ativas[] = $relatorio["tripulacoes_ativas_24_horas"];
    $contas_ativas[] = $relatorio["contas_ativas_24_horas"];
    $ips_ativos[] = $relatorio["ips_ativos_24_horas"];
    $novas_contas[] = $relatorio["novas_contas_24_horas"];
    $gold[] = $relatorio["gold_gasto_24_horas"];
    $dobroes[] = $relatorio["dobrao_gasto_24_horas"];
} ?>
<script type="text/javascript">
    $(function () {
        timeOuts["estatistica"] = setTimeout(function () {
            reloadPagina();
        }, 15000);

        var accessCtx = document.getElementById("accessChart");
        var accessChart = new Chart(accessCtx, {
            type: 'line',
            data: {
                labels: [<?= implode(",", $datas) ?>],
                datasets: [
                    {
                        label: 'Tripulações ativas',
                        data: [<?= implode(",", $tripulacoes_ativas) ?>],
                        borderColor: '#f00',
                        borderWidth: 1
                    }, {
                        label: 'Contas ativas',
                        data: [<?= implode(",", $contas_ativas) ?>],
                        borderColor: '#00f',
                        borderWidth: 1
                    }, {
                        label: 'IP\'s ativos',
                        data: [<?= implode(",", $ips_ativos) ?>],
                        borderColor: '#a0a',
                        borderWidth: 1
                    }, {
                        label: 'Novas contas',
                        data: [<?= implode(",", $novas_contas) ?>],
                        borderColor: '#0f0',
                        borderWidth: 1
                    }
                ]
            }
        });
        var goldCtx = document.getElementById("goldChart");
        var goldChart = new Chart(goldCtx, {
            type: 'line',
            data: {
                labels: [<?= implode(",", $datas) ?>],
                datasets: [
                    {
                        label: 'Ouro gasto',
                        data: [<?= implode(",", $gold) ?>],
                        borderColor: '#ff0',
                        borderWidth: 1
                    }, {
                        label: 'Dobrões gastos',
                        data: [<?= implode(",", $dobroes) ?>],
                        borderColor: '#f70',
                        borderWidth: 1
                    }
                ]
            }
        });
    });
</script>

<div class="panel-body">
    <canvas id="accessChart" width="400" height="150"></canvas>
    <br />
    <br />
    <canvas id="goldChart" width="400" height="150"></canvas>
    <br />
    <br/>
    <ul class="text-left">
        <?php $total = $connection->run("SELECT count(conta_id) AS total FROM tb_conta ")->fetch_array()["total"]; ?>
        <li>Contas criadas: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(conta_id) AS total FROM tb_conta WHERE ativacao IS NULL")->fetch_array()["total"]; ?>
        <li>Contas ativas: <?= $total ?></li>

        <?php //$total = $connection->run("SELECT count(conta_id) AS total FROM tb_conta WHERE (SELECT count(id) FROM tb_vip_pagamentos WHERE mensagem LIKE concat('%moedas de ouro para ', CAST(conta_id AS UNSIGNED))) > 0")->fetch_array()["total"]; ?>
        <?php $total = $connection->run("SELECT id FROM tb_vip_compras WHERE status = 'PAID' OR status = 'AVAILABLE' GROUP BY conta_id")->count(); ?>
        <li>Contas que compraram ouro: <?= $total ?></li>
    </ul>
    <ul class="text-left">
        <?php $total = $connection->run("SELECT count(conta_id) AS total FROM tb_conta WHERE tb_conta.cadastro > SUBDATE(now(), INTERVAL 1 DAY)")->fetch_array()["total"]; ?>
        <li>Novas contas nas últimas 24 horas: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(conta_id) AS total FROM tb_conta WHERE tb_conta.cadastro > SUBDATE(now(), INTERVAL 3 DAY)")->fetch_array()["total"]; ?>
        <li>Novas contas nos últimos 3 dias: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(conta_id) AS total FROM tb_conta WHERE tb_conta.cadastro > SUBDATE(now(), INTERVAL 7 DAY)")->fetch_array()["total"]; ?>
        <li>Novas contas na última semana: <?= $total ?></li>
    </ul>
    <ul class="text-left">
        <?php $total = $connection->run("SELECT count(DISTINCT u.conta_id) AS total FROM tb_conta c INNER JOIN tb_usuarios u ON c.conta_id = u.conta_id WHERE c.cadastro > SUBDATE(now(), INTERVAL 3 DAY) AND c.cadastro < SUBDATE(now(), INTERVAL 1 DAY) AND u.ultimo_logon > ?", "i", atual_segundo() - 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>Contas criadas nos últimos três dias que tiveram atividade nas últimas 24 horas: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT u.conta_id) AS total FROM tb_conta c INNER JOIN tb_usuarios u ON c.conta_id = u.conta_id WHERE c.cadastro > SUBDATE(now(), INTERVAL 7 DAY) AND c.cadastro < SUBDATE(now(), INTERVAL 3 DAY) AND u.ultimo_logon > ?", "i", atual_segundo() - 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>Contas criadas na última semana que tiveram atividade nas últimas 24 horas: <?= $total ?></li>
    </ul>
    <ul class="text-left">
        <?php $total = $connection->run("SELECT count(id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 60)->fetch_array()["total"]; ?>
        <li>Tripulações ativas nos últimos 60 segundos: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT conta_id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 60)->fetch_array()["total"]; ?>
        <li>Contas ativas nos últimos 60 segundos: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT ip) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 60)->fetch_array()["total"]; ?>
        <li>IP's ativos nos últimos 60 segundos: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 10 * 60)->fetch_array()["total"]; ?>
        <li>Tripulações ativas nos últimos 10 minutos: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT conta_id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 10 * 60)->fetch_array()["total"]; ?>
        <li>Contas ativas nos últimos 10 minutos: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT ip) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 10 * 60)->fetch_array()["total"]; ?>
        <li>IP's ativos nos últimos 10 minutos: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>Tripulações ativas nas útimas 24 horas: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT conta_id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>Contas ativas nas útimas 24 horas: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT ip) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>IP's ativos nas útimas 24 horas: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 3 * 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>Tripulações ativas nas útimas 72 horas: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT conta_id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 3 * 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>Contas ativas nas útimas 72 horas: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT ip) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 3 * 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>IP's ativos nas útimas 72 horas: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 7 * 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>Tripulações ativas na útima semana: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT conta_id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 7 * 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>Contas ativas na útima semana: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT ip) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - 7 * 24 * 60 * 60)->fetch_array()["total"]; ?>
        <li>IP's ativos na útima semana: <?= $total ?></li>
    </ul>
    <ul class="text-left">
        <?php $total = $connection->run("SELECT count(cod) AS total FROM tb_personagens")->fetch_array()["total"]; ?>
        <li>Personagens criados: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(cod) AS total FROM tb_personagens WHERE lvl = 50")->fetch_array()["total"]; ?>
        <li>Personagens no nível 50: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT id) AS total FROM tb_personagens WHERE lvl = 50")->fetch_array()["total"]; ?>
        <li>Tripulações com o mais forte no nível 50: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(cod) AS total FROM tb_personagens WHERE lvl >= 45")->fetch_array()["total"]; ?>
        <li>Personagens no nível 45 ou superior: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT id) AS total FROM tb_personagens WHERE lvl >= 45")->fetch_array()["total"]; ?>
        <li>Tripulações com o mais forte no nível 45 ou superior: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(cod) AS total FROM tb_personagens WHERE lvl >= 15")->fetch_array()["total"]; ?>
        <li>Personagens no nível 15 ou superior: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT id) AS total FROM tb_personagens WHERE lvl >= 15")->fetch_array()["total"]; ?>
        <li>Tripulações com o mais forte no nível 15 ou superior: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(cod) AS total FROM tb_personagens WHERE lvl >= 10")->fetch_array()["total"]; ?>
        <li>Personagens no nível 10 ou superior: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT id) AS total FROM tb_personagens WHERE lvl >= 10")->fetch_array()["total"]; ?>
        <li>Tripulações com o mais forte no nível 10 ou superior: <?= $total ?></li>
    </ul>
    <ul class="text-left">
        <?php $total = $connection->run("SELECT count(id) AS total FROM tb_personagens WHERE haki_lvl = 25 GROUP BY id HAVING total >= 15")->count(); ?>
        <li>Tripulações full Haki: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(cod) AS total FROM tb_personagens WHERE haki_lvl = 25")->fetch_array()["total"]; ?>
        <li>Personagens full Haki: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT id) AS total FROM tb_personagens WHERE haki_lvl = 25")->fetch_array()["total"]; ?>
        <li>Tripulações com pelo menos 1 personagem full Haki: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(cod) AS total FROM tb_personagens WHERE haki_lvl >= 10")->fetch_array()["total"]; ?>
        <li>Personagens com 10 de Haki ou mais: <?= $total ?></li>

        <?php $total = $connection->run("SELECT count(DISTINCT id) AS total FROM tb_personagens WHERE haki_lvl >= 10")->fetch_array()["total"]; ?>
        <li>Tripulações com pelo menos 1 personagem com 10 de Haki ou mais: <?= $total ?></li>
    </ul>

    <ul class="text-left">
        <?php $total = $connection->run("SELECT max(berries) AS total FROM tb_usuarios WHERE adm = 0")->fetch_array()["total"]; ?>
        <li>Berries do jogador mais rico: <?= mascara_berries($total) ?></li>

        <?php $total = $connection->run("SELECT max(gold) AS total FROM tb_conta")->fetch_array()["total"]; ?>
        <li>Moedas de Ouro do jogador mais rico: <?= mascara_berries($total) ?></li>

        <?php $total = $connection->run("SELECT max(dobroes) AS total FROM tb_conta")->fetch_array()["total"]; ?>
        <li>Dobrões de Ouro do jogador mais rico: <?= mascara_berries($total) ?></li>

        <?php $total = $connection->run("SELECT max(dobroes_criados) AS total FROM tb_conta")->fetch_array()["total"]; ?>
        <li>Dobrões de Ouro não comercializados do jogador mais rico: <?= mascara_berries($total) ?></li>
    </ul>

    <ul class="text-left">
        <?php $gold_log = $connection->run(
            "SELECT l.script, SUM(l.quant) AS quant, count(l.id) AS cont FROM tb_gold_log l INNER JOIN tb_usuarios u ON l.user_id = u.id WHERE u.adm = 0 GROUP BY l.script ORDER BY SUM(l.quant)"
        ); ?>
        <?php while ($log = $gold_log->fetch_array()): ?>
            <li>Ouro gasto em <?= $log["cont"] ?> x <?= $log["script"] ?>: <?= $log["quant"] ?></li>
        <?php endwhile; ?>
    </ul>
    <ul class="text-left">
        <?php $gold_log = $connection->run(
            "SELECT l.script, SUM(l.quant) AS quant, count(l.id) AS cont FROM tb_dobroes_log l INNER JOIN tb_usuarios u ON l.tripulacao_id = u.id WHERE u.adm = 0 GROUP BY l.script ORDER BY SUM(l.quant)"
        ); ?>
        <?php while ($log = $gold_log->fetch_array()): ?>
            <li>Dobrões gastos em <?= $log["cont"] ?> x <?= $log["script"] ?>: <?= $log["quant"] ?></li>
        <?php endwhile; ?>
    </ul>
</div>