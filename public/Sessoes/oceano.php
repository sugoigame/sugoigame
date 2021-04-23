<style type="text/css">
    #mar-game {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
    }

    .menu-col {
        z-index: 1;
        position: absolute;
        left: 0;
        padding: 0;
    }

    .menu-content {
        margin-left: 0;
        margin-right: 0;
        padding-bottom: 80px;
        overflow: auto;
        max-width: 250px;
    }

    #tripulantes-bar {
        position: fixed;
        right: 15px;
        width: 70px;
        height: 80vh;
        overflow: auto;
        margin-top: 38px;
    }

    #tripulantes-bar table,
    #tripulantes-bar tr,
    #tripulantes-bar td {
        display: block;
    }

    #tripulantes-bar .tripulante_quadro {
        background: transparent;
        width: 40px;
        height: auto;
    }

    #tripulantes-bar .tripulante_quadro .recompensa_text {
        display: none;
    }

    #tripulantes-bar .tripulante_quadro_img {
        top: 0;
        width: 100%;
        margin-bottom: -7px;
    }

    .tripulante-lvl-up img {
        height: 30px;
        margin-bottom: -25px;
    }

    @media (max-width: 767px) {
        #header {
            display: none;
        }
    }

    #mar-game-tooltip {
        display: none;
        position: fixed;
        width: 250px;
        z-index: 5;
        max-height: 300px;
        overflow: auto;
    }

    #skills-area {
        position: fixed;
        z-index: 1;
        bottom: 15px;
        margin: auto;
        text-align: center;
        left: 50%;
    }

    #skills-area .skills-container {
        margin-left: -50%;
    }

    #skills-area .skill {
        width: 55px;
        margin: auto;
        position: relative;
        border-radius: 5px;
        display: inline-block;
    }

    #skills-area .skill.skill-allowed {
        cursor: pointer;
    }

    #skills-area .skill.skill-allowed:hover {
        box-shadow: 0 0 9px 3px #fff;
    }

    #skills-area .skill:not(#skill-kairouseki) img {
        border-radius: 5px;
        border: 2px solid #000;
    }

    #skills-area .skill .badge {
        display: none;
    }

    #skills-area .skill.skill-allowed .badge {
        display: block;
        position: absolute;
        right: 5px;
        bottom: 5px;
    }

    #skill-profissoes {
        background: black;
        width: 3em !important;
        padding: 0.5em 0;
    }
</style>

<div id="mar-game-tooltip">
    <div class="panel panel-default" style="margin: 0;">
        <button type="button" class="close" style="position: absolute; right: 10px">&times;</button>
        <div class="panel-body" style="padding: 4px 18px;"></div>
    </div>
</div>

<div id="skills-area">
    <div class="skills-container">
        <div class="skill" id="skill-heal" data-toggle="tooltip" title="Restaurar o navio"
             data-placement="top">
            <span class="badge">1</span>
            <img src="Imagens/Skils/513.jpg"/>
        </div>
        <div class="skill need-target" id="skill-shot" data-toggle="tooltip" title="Disparar uma bala de canhão"
             data-placement="top">
            <span class="badge">2</span>
            <img src="Imagens/Skils/514.jpg"/>
        </div>
        <div class="skill need-target" id="skill-attack" data-toggle="tooltip" title="Atacar um navio inimigo"
             data-placement="top">
            <span class="badge">3</span>
            <img src="Imagens/Skils/33.jpg"/>
        </div>
        <div class="skill hidden" id="skill-coup-de-burst" data-toggle="tooltip" title="Coup De Burst"
             data-placement="top">
            <span class="badge"></span>
            <img src="Imagens/Skils/159.jpg"/>
        </div>
        <div class="skill hidden" id="skill-kairouseki" data-toggle="tooltip" title="Ativar/Desativar Kairouseki"
             data-placement="top">
            <span class="badge"></span>
            <img src="Imagens/Itens/165.png"/>
        </div>
        <div class="skill skill-allowed" id="skill-profissoes" data-toggle="tooltip"
             title="Abrir o painel de profissões"
             data-placement="top">
            <i class="fa fa-gavel"></i>
        </div>
    </div>
</div>

<div id="mar-game"></div>

<script type="text/javascript">
    (function (jQuery) {

        jQuery.eventEmitter = {
            _JQInit: function () {
                this._JQ = jQuery(this);
            },
            emit: function (evt, data) {
                !this._JQ && this._JQInit();
                this._JQ.trigger(evt, data);
            },
            once: function (evt, handler) {
                !this._JQ && this._JQInit();
                this._JQ.one(evt, handler);
            },
            on: function (evt, handler) {
                !this._JQ && this._JQInit();
                this._JQ.bind(evt, handler);
            },
            off: function (evt, handler) {
                !this._JQ && this._JQInit();
                this._JQ.unbind(evt, handler);
            }
        };

    }(jQuery));

    function EventEmitter() {
    }

    jQuery.extend(EventEmitter.prototype, jQuery.eventEmitter);
</script>

<script type="text/javascript">

    function getPatenteMarks() {
        return [
            5000,
            6000,
            8000,
            10000,
            12000,
            16000,
            18000,
            20000,
            24000,
            28000,
            30000,
            32000,
            34000
        ];
    }

    function getPatenteId($reputacao) {
        var $marks = getPatenteMarks();

        for (var $i = 0; $i < $marks.length; $i++) {
            if ($reputacao < $marks[$i]) {
                return $i;
            }
        }
        return $marks.length;
    }

    function getPatenteNome($faccao, $reputacao) {
        var $id = getPatenteId($reputacao);

        var $patentes = [
            [
                "Recruta",
                "Aprendiz",
                "Soldado de Primeira Classe",
                "Sargento",
                "Sargento-Major",
                "Subtenente",
                "Terceiro Tenente",
                "Segundo Tenente",
                "Tenente",
                "Capitão",
                "Tenente-Comandante",
                "Tenente-Coronel",
                "Coronel",
                "Vice-Almirante"
            ], [
                "Recruta",
                "Bucaneiro ",
                "Mestre de Navio",
                "Capitão Experiente",
                "Aprendiz de Pirata",
                "Pirata Novato",
                "Pirata Explorador",
                "Pirata Problemático",
                "Pirata Veterano",
                "Pirata Conhecido",
                "Pirata de Renome",
                "Novato do Novo Mundo",
                "Veterano no Novo Mundo",
                "General"
            ]
        ];

        return $patentes[$faccao][$id];
    }

    function getHumanLocation($x, $y) {
        return $x + "º L, " + (359 - $y) + "º N";
    }

</script>

<script type="text/javascript">
    var game;
    var gameState       = new Game();
    var eventEmitter    = new EventEmitter();

    var ships       = {};
    var chains      = [];
    var rdms        = [];
    var swirls      = [];
    var nps         = [];
    var islands     = [];
    var skin_sizes  = [ 65, 65, 65, 75, 75, 75, 85, 85, 75, 85, 85 ];

    var ws;

    const MAX_X = 22;
    const MAX_Y = 17;

    const SQUARE_SIZE = 40;

    function syncShip(data) {
       if (ships[data.id]) {
            ships[data.id].sync(data);
        } else {
            var ship = new Ship();
            ship.sync(data);
            ships[data.id] = ship;
        }
    }

    function initFieldOfView(data) {
        gameState.player.sync(data.me);

        gameState.hiddenArea.visible = !data.me.luneta;

        if (data.me.has_kairouseki) {
            $('#skill-kairouseki')
                .removeClass('hidden')
                .css('background', data.me.kairouseki_ativo ? 'green' : 'red');
            $('#skill-kairouseki .badge').html(data.me.kairouseki_ativo ? 'Ativ.' : 'Des.');
        }

        if (data.me.canhao) {
            $('#skill-shot').removeClass('hidden');
        } else {
            $('#skill-shot').addClass('hidden');
        }

        if (data.me.coup_de_burst > 0) {
            $('#skill-coup-de-burst').removeClass('hidden');
            $('#skill-coup-de-burst .badge').html('x' + data.me.coup_de_burst);
        } else {
            $('#skill-coup-de-burst').addClass('hidden');
        }

        game.add.tween(gameState.fog).to({
            alpha: parseInt(data.map.fog, 10) / 100
        }, 200, Phaser.Easing.Linear.None, true);

        var ids = [];
        data.players.forEach(function (player) {
            syncShip(player);
            ids.push(player.id + '');
        });

        for (var key in ships) {
            if (ships.hasOwnProperty(key)) {
                if (ids.indexOf(key + '') === -1) {
                    ships[key].destroy();
                }
            }
        }

        chains.forEach(function (chain) {
            chain.destroy();
        });
        chains.length = 0;
        data.map.chains.forEach(function (chain) {
            var chainSprite = game.add.tileSprite(chain.x * SQUARE_SIZE, chain.y * SQUARE_SIZE, 40, 40, 'corrente');
            chainSprite.animations.add('play', null, 10);
            chainSprite.rotation = (chain.direcao / 8) * Math.PI * 2;
            chainSprite.anchor.set(0.5);
            chainSprite.power = chain.power || 0.3;
            chains.push(chainSprite);
        });

        rdms.forEach(function (rdm) {
            rdm.destroy();
        });
        rdms.length = 0;
        data.map.rdms.forEach(function (rdm) {
            var rdmSprite = gameState.overlayGroup.create(rdm.x * SQUARE_SIZE, rdm.y * SQUARE_SIZE, 'bubbles_' + rdm.ameaca);
            rdmSprite.animations.add('play', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], 6);
            rdmSprite.animations.play('play');
            rdmSprite.rotation = (rdm.direcao / 8) * Math.PI * 2;
            rdmSprite.anchor.set(0.5);
            rdmSprite.width = SQUARE_SIZE;
            rdmSprite.height = SQUARE_SIZE;
            rdmSprite.alpha = 0.5;
            rdms.push(rdmSprite);
        });

        swirls.forEach(function (swirl) {
            swirl.destroy();
        });
        swirls.length = 0;
        data.map.swirls.forEach(function (swirl) {
            var swirlSprite = game.add.image(swirl.x * SQUARE_SIZE, swirl.y * SQUARE_SIZE, 'redemoinho');
            swirlSprite.rotation = Math.random() * Math.PI * 2;
            swirlSprite.anchor.set(0.5);
            swirls.push(swirlSprite);
        });

        var npsBkp = nps;
        nps = [];
        data.nps.forEach(function (np) {
            var npsSprite = game.add.image(np.x * SQUARE_SIZE, np.y * SQUARE_SIZE, 'nps_' + np.icon);
            npsSprite.anchor.set(0.5);
            npsSprite.data = np;
            // npsSprite.tween = game.add.tween(npsSprite.position).to({
            //     x: (np.x + np.move_x) * SQUARE_SIZE,
            //     y: (np.y + np.move_y) * SQUARE_SIZE
            // }, 15000, Phaser.Easing.Linear.None, true);
            nps.push(npsSprite);

            if (gameState.target && !gameState.target.sprite && gameState.target.data.id == np.id) {
                gameState.target = npsSprite;
            }
        });

        npsBkp.forEach(function (np) {
            np.destroy();
        });

        islands.forEach(function (island) {
            island.destroy();
        });
        islands = [];
        data.map.islands.forEach(function (island) {
            var islandSprite = game.add.image(island.x * SQUARE_SIZE, island.y * SQUARE_SIZE, 'island');
            islandSprite.anchor.set(0.5);
            islandSprite.data = island;
            islands.push(islandSprite);
        });

        gameState.windIndicator.visible = !!data.map.wind;
        if (gameState.windIndicator.visible) {
            gameState.windIndicator.tint = 0xFF * data.map.wind.power * 0x10000;
            gameState.windIndicator.frame = data.map.wind.direction;
        }

        renderSelectedPlayers();
    }

    var selectedCoord = null;
    var selectedCoordId = null;
    var selectedEvent = null;

    function showPlayersInCoord(coord, event) {
        var cordId = coord.x + '_' + coord.y;
        var $tooltip = $('#mar-game-tooltip');

        $tooltip.css('display', $tooltip.css('display') === 'none' || selectedCoordId != cordId ? 'block' : 'none');

        selectedCoordId = cordId;
        selectedCoord = coord;
        selectedEvent = event;
        renderSelectedPlayers();

        var top = (event.clientY - SQUARE_SIZE);
        var left = (event.clientX + SQUARE_SIZE);

        if (left > window.innerWidth - 250) {
            left -= 250 + SQUARE_SIZE * 2;
        }
        if (top > window.innerHeight - 300) {
            top -= $('#mar-game-tooltip').height() - SQUARE_SIZE * 2;
        }
        $tooltip
            .css('top', top + 'px')
            .css('left', left + 'px');
    }

    function renderSelectedPlayers() {
        $('#mar-game-tooltip .panel-body').html('');
        if (!selectedCoord) {
            return;
        }

        islands.forEach(function (island) {
            if (Math.abs(selectedEvent.worldX - island.position.x) <= SQUARE_SIZE / 2 && Math.abs(selectedEvent.worldY - island.position.y) <= SQUARE_SIZE / 2) {
                renderIsland(island);
            }
        });

        if (gameState.player.x == selectedCoord.x && gameState.player.y == selectedCoord.y) {
            renderShip(gameState.player);
        }
        for (var key in ships) {
            var ship = ships[key];
            if (ship.sprite && Phaser.Point.distance(new Phaser.Point(selectedEvent.worldX, selectedEvent.worldY), ship.sprite.position) <= SQUARE_SIZE) {
                renderShip(ship);
            }
        }
        nps.forEach(function (np) {
            if (Phaser.Point.distance(new Phaser.Point(selectedEvent.worldX, selectedEvent.worldY), np.position) <= SQUARE_SIZE) {
                renderNps(np);
            }
        });

        $('#mar-game-tooltip .panel-body .row:last-child')
            .css('border', 'none')
            .css('margin-bottom', '0')
            .css('padding-bottom', '0');

        if (!$('#mar-game-tooltip .panel-body').html().length) {
            $('#mar-game-tooltip').hide();
        }
    }

    function renderShip(ship) {
        $('#mar-game-tooltip .panel-body')
            .append(
                $('<DIV>')
                    .addClass('row')
                    .css('margin-bottom', '10px')
                    .css('padding-bottom', '10px')
                    .css('border-bottom', '3px double rgba(224, 190, 122, 0.6)')
                    .append(
                        $('<DIV>')
                            .addClass('col-xs-2')
                            .css('padding', '0 0 0 5px')
                            .append(
                                $('<DIV>').html(
                                    '<img src="Imagens/Bandeiras/img.php?cod=' + ship.data.bandeira + '&f=' + ship.data.faccao + '" style="max-width: 100%"><br/>'
                                    + '<img title="' + getPatenteNome(ship.data.faccao, ship.data.reputacao) + '" src="Imagens/Ranking/Patentes/' + ship.data.faccao + '_' + getPatenteId(ship.data.reputacao) + '.png" style="max-width: 100%"/>'
                                )
                            )
                    )
                    .append(
                        $('<DIV>')
                            .addClass('col-xs-8')
                            .append(
                                $('<DIV>')
                                    .append('<h6>' + ship.data.tripulacao + '</h6>')
                                    .append('<small>' + ship.data.capitao_nome + (ship.data.capitao_titulo ? '<br/>' + ship.data.capitao_titulo : '') + '<br/>Nível ' + ship.data.lvl_mais_forte + '</small>')
                            )
                    )
                    .append(
                        $('<DIV>')
                            .addClass('col-xs-2')
                            .css('padding', '0')
                            .append(
                                gameState.target && gameState.target.sprite && gameState.target.data.id == ship.data.id
                                    ? $('<P>').addClass('text-danger').html('Alvo!')
                                    : $('<BUTTON>')
                                        .attr('title', 'Marcar Alvo')
                                        .addClass('btn')
                                        .addClass('btn-success')
                                        .html('<i class="glyphicon glyphicon-screenshot"></i>')
                                        .click(function () {
                                            gameState.target = ship;
                                            renderSelectedPlayers();
                                        })
                            )
                    )
                    .append(
                        ship.data.poder_batalha
                            ? $('<DIV>')
                                .addClass('col-xs-12')
                                .append(
                                    $('<DIV>')
                                        .append('<p class="text-warning" style="margin: 0;"><b>Poder de Batalha: ' + ship.data.poder_batalha + '</b></p>')
                                )
                            : ''
                    )
                    .append(
                        ship.data.reputacao_vitoria
                            ? $('<DIV>')
                                .addClass('col-xs-12')
                                .append(
                                    $('<DIV>')
                                        .append('<small>Vale ' + ship.data.reputacao_vitoria.vencedor_rep + ' pontos de reputação na Era.</small>')
                                )
                            : ''
                    )
                    .append(
                        ship.data.reputacao_mensal_vitoria
                            ? $('<DIV>')
                                .addClass('col-xs-12')
                                .append(
                                    $('<DIV>')
                                        .append('<small>Vale ' + ship.data.reputacao_mensal_vitoria.vencedor_rep + ' pontos de reputação no Mês.</small>')
                                )
                            : ''
                    )
                    .append(
                        ship.data.is_adm
                            ? $('<DIV>')
                                .addClass('col-xs-12')
                                .append(
                                    $('<DIV>')
                                        .append('<p class="text-info" style="margin: 0;"><b>Membro do Governo Mundial</b></p>')
                                )
                            : ''
                    )
            );
    }

    function renderNps(ship) {
        $('#mar-game-tooltip .panel-body')
            .append(
                $('<DIV>')
                    .addClass('row')
                    .css('margin-bottom', '10px')
                    .css('padding-bottom', '10px')
                    .css('border-bottom', '3px double rgba(224, 190, 122, 0.6)')
                    .append(
                        $('<DIV>')
                            .addClass('col-xs-3')
                            .append(
                                $('<DIV>').html(
                                    '<img src="Imagens/Batalha/Npc/Navios/' + ship.data.icon + '.png" style="max-width: 100%"><br/>'
                                )
                            )
                    )
                    .append(
                        $('<DIV>')
                            .addClass('col-xs-6')
                            .append(
                                $('<DIV>')
                                    .append('<h6>' + ship.data.nome + '</h6>')
                            )
                    )
                    .append(
                        $('<DIV>')
                            .addClass('col-xs-3')
                            .append(
                                gameState.target && !gameState.target.sprite && gameState.target.data.id == ship.data.id
                                    ? $('<P>').addClass('text-danger').html('Alvo!')
                                    : $('<BUTTON>')
                                        .attr('title', 'Marcar Alvo')
                                        .addClass('btn')
                                        .addClass('btn-success')
                                        .html('<i class="glyphicon glyphicon-screenshot"></i>')
                                        .click(function () {
                                            gameState.target = ship;
                                            renderSelectedPlayers();
                                        })
                            )
                    )
            );
    }

    function renderIsland(island) {
        var $panel = $('#mar-game-tooltip .panel-body');
        $panel.append(
            $('<DIV>')
                .addClass('row')
                .css('margin-bottom', '10px')
                .css('padding-bottom', '2px')
                .css('border-bottom', island.data.govern.tripulacao ? 'none' : '3px double rgba(224, 190, 122, 0.6)')
                .append(
                    $('<DIV>')
                        .addClass('col-xs-12')
                        .append(
                            $('<DIV>')
                                .append('<h6>' + island.data.island_name + '</h6>')
                        )
                )
        );
        if (island.data.govern.tripulacao) {
            $panel.append(
                $('<DIV>')
                    .addClass('row')
                    .css('margin-bottom', '10px')
                    .css('padding-bottom', '10px')
                    .css('border-bottom', '3px double rgba(224, 190, 122, 0.6)')
                    .append(
                        $('<DIV>')
                            .addClass('col-xs-4')
                            .append(
                                $('<DIV>').html(
                                    '<img src="Imagens/Bandeiras/img.php?cod=' + island.data.govern.bandeira + '&f=' + island.data.govern.faccao + '" style="max-width: 100%"><br/>'
                                )
                            )
                    )
                    .append(
                        $('<DIV>')
                            .addClass('col-xs-8')
                            .append(
                                $('<DIV>')
                                    .append('<small>Esta ilha está ' + (island.data.govern.karma_bom ? 'protegida pelo ' : 'sob o controle do ') + island.data.govern.tripulacao + '</small>')
                            )
                    )
            );
        }
    }

    var inIlha = undefined;

    function reloadMenu(data) {
        if (inIlha !== data.me.ilha) {
            $.ajax({
                url: 'menu_for_oceano.php',
                success: function (response) {
                    inIlha = data.me.ilha;
                    $('#menu-cover .menu-content').html(response);
                }
            });
        }
    }

    function Boot() {

    }

    Boot.prototype.preload = function () {
        game.load.spritesheet('health_bar', 'Imagens/Backgrounds/Barras/gradienteGreen.jpg', 444, 20);
    };
    Boot.prototype.create = function () {
        game.state.start('wait');
    };

    function Wait() {

    }

    Wait.prototype.preload = function () {
        game.load.spritesheet(
            'ship_<?=$userDetails->tripulacao['id'];?>',
            'Imagens/Bandeiras/navio_sprite.php?cod=<?=$userDetails->tripulacao['bandeira'];?>&f=<?=$userDetails->tripulacao['faccao'];?>&s=<?=$userDetails->tripulacao['skin_navio'];?>',
            skin_sizes[<?=$userDetails->tripulacao['skin_navio'];?>],
            skin_sizes[<?=$userDetails->tripulacao['skin_navio'];?>]
        );

        game.load.image('hover',                'Imagens/Transparent-white.png');
        game.load.image('route',                'Imagens/Oceano/route.png');
        game.load.image('route_target',         'Imagens/Oceano/route_target.png');
        game.load.image('target',               'Imagens/Oceano/target.png');
        game.load.image('attack',               'Imagens/Oceano/attack.png');
        game.load.image('cannon_ball',          'Imagens/Oceano/ball.png');

        game.load.spritesheet('explosion',      'Imagens/Skils/Animacoes/Explosion1.png', 192, 192);
        game.load.spritesheet('water',          'Imagens/Skils/Animacoes/Water1.png', 192, 192);
        game.load.spritesheet('bubbles_0',      'Imagens/Skils/Animacoes/Bubbles.png', 192, 192);
        game.load.spritesheet('bubbles_1',      'Imagens/Skils/Animacoes/BubblesOrange.png', 192, 192);
        game.load.spritesheet('bubbles_2',      'Imagens/Skils/Animacoes/BubblesRed.png', 192, 192);
        game.load.spritesheet('bubbles_3',      'Imagens/Skils/Animacoes/Pollen.png', 192, 192);

        game.load.image('hidden_area',          'Imagens/Oceano/hidden_area.png');
        game.load.image('nevoa',                'Imagens/Oceano/nevoa.png');
        game.load.image('corrente',             'Imagens/Oceano/corrente.png', 40, 40);
        game.load.image('redemoinho',           'Imagens/Oceano/redemoinho.png', 40, 40);
        game.load.image('island',               'Imagens/Oceano/island.png', 200, 200);
        game.load.image('rocket_fire1',         'Imagens/Oceano/rocket-fire1.png', 200, 200);

        game.load.spritesheet('wind_indicator', 'Imagens/Oceano/vento-indicador.png', 50, 70);
        game.load.spritesheet('target_ship',    'Imagens/Oceano/target_ship.png', 50, 80);
        game.load.spritesheet('fire',           'Imagens/Oceano/fire.png', 55, 100);

        game.load.image('nps_1',                'Imagens/Batalha/Npc/Navios/1.png');
        game.load.image('nps_2',                'Imagens/Batalha/Npc/Navios/2.png');
        game.load.image('nps_3',                'Imagens/Batalha/Npc/Navios/3.png');
        game.load.image('nps_4',                'Imagens/Batalha/Npc/Navios/4.png');
        game.load.image('nps_5',                'Imagens/Batalha/Npc/Navios/5.png');
        game.load.image('nps_6',                'Imagens/Batalha/Npc/Navios/6.png');
        game.load.image('nps_7',                'Imagens/Batalha/Npc/Navios/7.png');
        game.load.image('nps_8',                'Imagens/Batalha/Npc/Navios/8.png');
        game.load.image('nps_9',                'Imagens/Batalha/Npc/Navios/9.png');

        // this.preloadBar = game.add.sprite(400, 150, 'health_bar');
        this.preloadBar = game.add.sprite(444, 380, 'health_bar');
        game.load.setPreloadSprite(this.preloadBar);
    };

    Wait.prototype.create = function () {
        if (websockets['mar']) {
            websockets['mar'].close();
        }

        websockets['mar'] = new ReconnectingWebSocket((location.protocol === 'https:' ? 'wss://' : 'ws://') + '<?=OCEANO_SERVER;?>' + '/mar');
        ws = websockets['mar'];
        ws.onopen = function () {
            if (game.state.current === 'game') {
                gameState.reconnecting.visible = false;
            }
            ws.send(JSON.stringify({
                event: 'auth',
                sg_c: '<?= $_SESSION["sg_c"]; ?>',
                sg_k: '<?= $_SESSION["sg_k"]; ?>'
            }));
        };

        ws.onmessage = function (e) {
            var body = JSON.parse(e.data);
            eventEmitter.emit(body.event, body.data);
        };

        ws.onclose = function (e) {
            if (game.state && game.state.current === 'game') {
                gameState.reconnecting.visible = true;
            }
        };

        ws.onerror = function (e) {
        };

        eventEmitter.on('field_of_view', function (e, data) {
            if (game.state.current !== 'game') {
                gameState.fieldOfViewData = data;
                game.state.start('game');
            } else {
                initFieldOfView(data);
            }

            reloadMenu(data);
            $('#location').html(data.me.location);
            $('#destino_mar').html(data.me.destino_mar);
            $('#destino_ilha').html(data.me.destino_ilha);
        });
        eventEmitter.on('muda_status', function (e, data) {
            if (gameState.player.id === data.id) {
                gameState.player.sync(data);
            } else {
                syncShip(data);
            }
        });
        eventEmitter.on('add_user', function (e, data) {
            syncShip(data);
        });
        eventEmitter.on('cura_hp', function (e, data) {
            if (gameState.player.id === data.id) {
                gameState.player.heal(data.hp_navio, data.hp_curada);
            }
        });
        eventEmitter.on('reduz_hp', function (e, data) {
            if (gameState.player.id === data.id) {
                gameState.player.damage(data.hp_navio, data.hp_reduzida);
            }
        });
        eventEmitter.on('disparo', function (e, data) {
            gameState.disparo(data);
        });
        eventEmitter.on('remove_user', function (e, data) {
            if (ships[data.id]) {
                ships[data.id].destroy();
            }
        });
        eventEmitter.on('redirect', function (e, data) {
            ws.close();
            delete websockets['mar'];
            loadPagina(data);
        });
    };

    function Game() {
        this.visibleMaps = {};
    }

    Game.prototype.preload = function () {
    };

    Game.prototype.create = function () {
        this.player = new Player();
        this.characterGroup = game.add.group();
        this.overlayGroup = game.add.group();
        this.hiddenArea = game.add.image(0, 0, 'hidden_area');
        this.fog = game.add.image(0, 0, 'nevoa');

        this.targetSprite = gameState.overlayGroup.create(this.x * SQUARE_SIZE, this.y * SQUARE_SIZE, 'target_ship');
        this.targetSprite.anchor.set(0.5);
        this.targetSprite.animations.add('play', null, 6);

        this.windIndicator = game.add.sprite(0, 0, 'wind_indicator');
        this.windIndicator.anchor.set(0.5);

        initFieldOfView(this.fieldOfViewData);

        game.stage.disableVisibilityChange = true;

        game.scale.scaleMode = Phaser.ScaleManager.EXACT_FIT;
        game.scale.pageAlignHorizontally = true;
        game.scale.pageAlignVertically = true;
        game.scale.refresh();
        adjust();

        game.world.setBounds(-1366, -768, (MAX_X + 2) * 800 + 1366, (MAX_Y + 2) * 800 + 768);
        game.camera.follow(this.player.sprite);

        this.drawVisibleMap();

        this.reconnecting = game.add.text(0, 0, "Conexão perdida com o servidor, tentando se reconectar...", {
            font: '25px',
            fill: '#ffffff',
            align: 'center',
            wordWrap: false,
            stroke: '#000000',
            strokeThickness: 1
        });
        this.reconnecting.anchor.set(0.5);
        this.reconnecting.visible = false;

        game.input.onTap.add(function (event, isDoubleClick) {
            var destination = this.convertTileToPoint(new Phaser.Point(event.worldX, event.worldY));
            if (isDoubleClick) {
                $('#mar-game-tooltip').hide();
                ws.send(JSON.stringify({
                    event: 'set_destination',
                    destination: {
                        x: destination.x,
                        y: destination.y
                    }
                }));
                if (gameState.player.destination) {
                    gameState.player.destination.x = destination.x * SQUARE_SIZE;
                    gameState.player.destination.y = destination.y * SQUARE_SIZE;
                }
            } else {
                if (this.wantUseShot) {
                    this.useShot(destination);
                } else if (this.wantAttack) {
                    this.useAttack(destination);
                } else {
                    showPlayersInCoord(destination, event);
                }
            }
        }, this);

        this.locationText = game.add.text(0, 0, '', {
            font: '20px',
            fill: '#000000',
            align: 'center',
            wordWrap: false
        });
        this.locationText.anchor.set(0.5);

        game.input.addMoveCallback(function () {
            this.showHover();
        }, this);

        this.healSkill = new Skill({
            interval: 5,
            element: '#skill-heal',
            key: Phaser.Keyboard.ONE,
            trigger: function () {
                gameState.useHeal();
            }
        });

        if (this.player.data.canhao) {
            this.shotSkill = new Skill({
                interval: 5,
                element: '#skill-shot',
                key: Phaser.Keyboard.TWO,
                trigger: function () {
                    gameState.useShot();
                }
            });
        }

        this.attackSkill = new Skill({
            interval: 0,
            element: '#skill-attack',
            key: Phaser.Keyboard.THREE,
            trigger: function () {
                gameState.useAttack();
            }
        });

        this.kairousekiSkill = new Skill({
            interval: 0,
            element: '#skill-kairouseki',
            trigger: function () {
                gameState.useKairouseki();
            }
        });

        this.coupDeBurstSkill = new Skill({
            interval: 0,
            element: '#skill-coup-de-burst',
            trigger: function () {
                gameState.useCoupDeBurst();
            }
        });

        this.attackRangeCircle = game.add.graphics(0, 0);
        this.attackRangeCircle.beginFill(0xFF0000, 1);
        this.attackRangeCircle.drawCircle(0, 0, 4 * SQUARE_SIZE);
        this.attackRangeCircle.alpha = 0.3;
    };

    Game.prototype.showHover = function () {
        if (this.hover) {
            this.hover.destroy();
        }
        var tile = new Phaser.Point(game.input.activePointer.worldX, game.input.activePointer.worldY);
        var position = this.gridTilePosition(tile);
        var hoverName = this.getCurrentHoverName(position);
        this.hover = game.add.sprite(position.x, position.y, hoverName);
        this.hover.anchor.set(0.5, 0.5);
        this.hover.scale.set(hoverName === 'hover' ? SQUARE_SIZE : 1);

        var realPosition = this.convertTileToPoint(tile);
        this.locationText.text = getHumanLocation(realPosition.x, realPosition.y);
    };

    Game.prototype.getCurrentHoverName = function (position) {
        if (this.wantUseShot) {
            return 'target';
        } else if (this.wantAttack) {
            return (Phaser.Point.distance(this.player.sprite.position, position) <= 2 * SQUARE_SIZE) ? 'attack' : 'hover';
        } else {
            return 'hover';
        }
    };

    Game.prototype.useHeal = function () {
        if (parseFloat(this.player.data.hp_navio) >= 1 && !this.floatingHealError) {
            this.floatingHealError = true;
            this.player.showFloatingText('Seu navio já está consertado!', {
                font: '15px',
                fill: '#ff0000',
                align: 'center',
                wordWrap: false
            }, Phaser.Easing.Linear.None);

            setTimeout(function () {
                gameState.floatingHealError = false
            }, 3000);
            return;
        }
        if (parseFloat(this.player.data.hp_navio) < 1) {
            ws.send(JSON.stringify({
                event: 'curar'
            }));
            this.healSkill.postUse();
        }
    };

    Game.prototype.useShot = function (destination) {
        if (!this.player.data.canhao) {
            this.player.showFloatingText('Seu navio não possui um canhão!', {
                font: '15px',
                fill: '#ff0000',
                align: 'center',
                wordWrap: false
            }, Phaser.Easing.Linear.None);

            return;
        }
        if (this.player.data.canhao_balas < 1) {
            this.player.showFloatingText('Você está sem balas de canhão!', {
                font: '15px',
                fill: '#ff0000',
                align: 'center',
                wordWrap: false
            }, Phaser.Easing.Linear.None);

            return;
        }
        if (destination) {
            ws.send(JSON.stringify({
                event: 'disparar',
                destination: {
                    x: destination.x,
                    y: destination.y
                }
            }));
            this.wantUseShot = false;
            this.shotSkill.postUse();
        } else if (this.target && this.target.sprite) {
            var distance = Phaser.Point.distance(this.player, this.target.sprite ? this.target : this.target.data);
            if (distance > 10) {
                this.player.showFloatingText('Você está muito longe do seu alvo!', {
                    font: '15px',
                    fill: '#ff0000',
                    align: 'center',
                    wordWrap: false
                }, Phaser.Easing.Linear.None);

                return;
            }

            for (i = 0; i < islands.length; i++) {
                if (Math.abs(islands[i].data.x - this.target.data.x) <= 2
                    && Math.abs(islands[i].data.y - this.target.data.y) <= 2) {
                    this.player.showFloatingText('O seu alvo está em uma área segura!', {
                        font: '15px',
                        fill: '#ff0000',
                        align: 'center',
                        wordWrap: false
                    }, Phaser.Easing.Linear.None);

                    return;
                }
            }

            ws.send(JSON.stringify({
                event: 'disparar_alvo',
                alvo: this.target.id
            }));
            this.wantUseShot = false;
            this.shotSkill.postUse();
        } else if (this.target) {
            ws.send(JSON.stringify({
                event: 'disparar',
                destination: {
                    x: this.target.data.x,
                    y: this.target.data.y
                }
            }));
            this.wantUseShot = false;
            this.shotSkill.postUse();
        } else {
            this.wantUseShot = !this.wantUseShot;
        }
        this.showHover();
    };

    Game.prototype.useAttack = function (destination) {
        if (!this.target) {
            this.player.showFloatingText('Você precisa de um alvo!', {
                font: '15px',
                fill: '#ff0000',
                align: 'center',
                wordWrap: false
            }, Phaser.Easing.Linear.None);
            return;
        }

        var distance = Phaser.Point.distance(this.player, this.target.sprite ? this.target : this.target.data);
        if (distance > 2) {
            this.player.showFloatingText('Você está muito longe do seu alvo!', {
                font: '15px',
                fill: '#ff0000',
                align: 'center',
                wordWrap: false
            }, Phaser.Easing.Linear.None);

            return;
        }

        if (this.target.sprite) {
            for (var i = 0; i < islands.length; i++) {
                if (Math.abs(islands[i].data.x - this.player.data.x) <= 2
                    && Math.abs(islands[i].data.y - this.player.data.y) <= 2) {
                    this.player.showFloatingText('Você está em uma área segura!', {
                        font: '15px',
                        fill: '#ff0000',
                        align: 'center',
                        wordWrap: false
                    }, Phaser.Easing.Linear.None);

                    return;
                }
            }

            for (i = 0; i < islands.length; i++) {
                if (Math.abs(islands[i].data.x - this.target.data.x) <= 2
                    && Math.abs(islands[i].data.y - this.target.data.y) <= 2) {
                    this.player.showFloatingText('O seu alvo está em uma área segura!', {
                        font: '15px',
                        fill: '#ff0000',
                        align: 'center',
                        wordWrap: false
                    }, Phaser.Easing.Linear.None);

                    return;
                }
            }

            /*ws.send(JSON.stringify({
                event: 'atacar',
                alvo: this.target.data.id,
                type: 1
            }));*/
            sendGet('Mapa/mapa_atacar.php?id=' + this.target.data.id + '&tipo=1');
        } else {
            ws.send(JSON.stringify({
                event: 'atacar_nps',
                destination: {
                    x: this.target.data.x,
                    y: this.target.data.y
                }
            }));
        }
        this.wantUseShot = false;
        this.showHover();
        this.attackSkill.postUse();
    };

    Game.prototype.useKairouseki = function () {
        if (this.player.data.has_kairouseki) {
            ws.send(JSON.stringify({
                event: 'toggle_kairouseki'
            }));
        }
    };

    Game.prototype.useCoupDeBurst = function () {
        if (this.player.data.coup_de_burst > 0) {
            ws.send(JSON.stringify({
                event: 'coup_de_burst'
            }));
        }
    };

    Game.prototype.gridTilePosition = function (point) {
        return new Phaser.Point(Math.round(point.x / SQUARE_SIZE) * SQUARE_SIZE, Math.round(point.y / SQUARE_SIZE) * SQUARE_SIZE);
    };
    Game.prototype.convertPointToTile = function (point) {
        return new Phaser.Point(point.x * SQUARE_SIZE, point.y * SQUARE_SIZE);
    };
    Game.prototype.convertTileToPoint = function (point) {
        return new Phaser.Point(Math.round(point.x / SQUARE_SIZE), Math.round(point.y / SQUARE_SIZE));
    };

    Game.prototype.drawVisibleMap = function () {
        this.iterateBetweenVisibleMapTiles(function (x, y) {
            if (!this.visibleMaps[x + '_' + y]) {
                this.visibleMaps[x + '_' + y] = true;
                var loader = new Phaser.Loader(game);
                var realX = x;
                var realY = y;

                if (x > MAX_X) {
                    realX = x - MAX_X - 1;
                } else if (x < 0) {
                    realX = MAX_X + x + 1;
                }
                if (y > MAX_Y) {
                    realY = y - MAX_Y - 1;
                } else if (y < 0) {
                    realY = MAX_Y + y + 1;
                }

                loader.image('mapa_' + x + '_' + y, 'Imagens/Mapa/Mapa_Mundi/' + realX + '_' + realY + '.jpg');
                loader.onLoadComplete.add(function () {
                    this.visibleMaps[x + '_' + y] = game.add.sprite(x * 800, y * 800, 'mapa_' + x + '_' + y);
                    game.world.sendToBack(this.visibleMaps[x + '_' + y]);
                }, this);
                loader.start();
            }
        });
    };

    Game.prototype.iterateBetweenVisibleMapTiles = function (callback) {
        var currentTile = this.getCurrentMapTile();
        var dist = 1;
        for (var x = currentTile.x - dist; x <= currentTile.x + dist; x++) {
            for (var y = currentTile.y - dist; y <= currentTile.y + dist; y++) {
                callback.apply(this, [x, y]);
            }
        }
    };

    Game.prototype.update = function () {
        islands.forEach(function (island) {
            game.world.bringToTop(island);
        });
        swirls.forEach(function (swril) {
            swril.rotation += 0.05;
            game.world.bringToTop(swril);
        });
        nps.forEach(function (np) {
            game.world.bringToTop(np);
        });

        for (var key in ships) {
            if (ships.hasOwnProperty(key)) {
                ships[key].update();
            }
        }

        this.player.update();

        if (this.target) {
            this.targetSprite.visible = true;
            if (this.target.sprite) {
                this.targetSprite.position.x = this.target.sprite.position.x;
                this.targetSprite.position.y = this.target.sprite.position.y;
            } else {
                this.targetSprite.position.x = this.target.position.x;
                this.targetSprite.position.y = this.target.position.y;
            }
            this.targetSprite.animations.play('play');
        } else {
            this.targetSprite.visible = false;
        }

        this.attackRangeCircle.visible = this.wantAttack || this.wantAttackNps;
        this.attackRangeCircle.position.x = this.player.sprite.position.x;
        this.attackRangeCircle.position.y = this.player.sprite.position.y;
        game.world.bringToTop(this.attackRangeCircle);

        game.world.bringToTop(this.characterGroup);
        this.characterGroup.sort('y');

        chains.forEach(function (chain) {
            game.world.bringToTop(chain);
            chain.tilePosition.y -= chain.power;
        });

        game.world.bringToTop(this.overlayGroup);

        this.hiddenArea.position.x = game.camera.position.x;
        this.hiddenArea.position.y = game.camera.position.y;
        game.world.bringToTop(this.hiddenArea);

        this.fog.position.x = game.camera.position.x;
        this.fog.position.y = game.camera.position.y;
        game.world.bringToTop(this.fog);

        this.windIndicator.position.x = game.camera.position.x + game.camera.width - 250;
        this.windIndicator.position.y = game.camera.position.y + 150;
        game.world.bringToTop(this.windIndicator);

        this.locationText.position.x = game.camera.position.x + game.camera.width - 150;
        this.locationText.position.y = game.camera.position.y + game.camera.height - 30;
        this.game.world.bringToTop(this.locationText);

        this.reconnecting.position.x = game.camera.position.x + game.camera.width / 2;
        this.reconnecting.position.y = game.camera.position.y + game.camera.height / 2;
        game.world.bringToTop(this.reconnecting);

        rdms.forEach(function (rdm) {
            rdm.animations.play('play');
        });

    };

    Game.prototype.getCurrentMapTile = function () {
        var squaresByTile = 800 / SQUARE_SIZE;
        return {
            x: Math.floor(this.player.x / squaresByTile),
            y: Math.floor(this.player.y / squaresByTile),
            offsetX: (this.player.x % squaresByTile) * SQUARE_SIZE,
            offsetY: (this.player.y % squaresByTile) * SQUARE_SIZE
        };
    };

    Game.prototype.disparo = function (data) {
        var origin = this.convertPointToTile(data.origin);
        var destination = this.convertPointToTile(data.destination);

        var ball = game.add.image(origin.x, origin.y, 'cannon_ball');
        ball.anchor.set(0.5);
        ball.scale.set(0.3);

        this.overlayGroup.add(ball);
        var movementTween = game.add.tween(ball.position).to({
            x: destination.x,
            y: destination.y
        }, 500, Phaser.Easing.Linear.None, true);

        movementTween.onComplete.add(function () {
            ball.destroy();

            if (data.targets) {
                var explosion = this.overlayGroup.create(destination.x, destination.y, 'explosion');
                explosion.anchor.set(0.5);
                explosion.animations.add('play', null, 10, false);
                explosion.animations.play('play', 10, false, true);
            } else {
                var water = this.overlayGroup.create(destination.x, destination.y, 'water');
                water.anchor.set(0.5);
                water.animations.add('play', [11, 12, 13, 14, 18, 20], 10, false);
                water.animations.play('play', 10, false, true);
                water.scale.set(0.5);
            }
        }, this);
    };

    function Skill(spec) {
        this.interval = spec.interval;
        this.$element = $(spec.element);
        this.events = new EventEmitter();

        if (spec.key) {
            this.key = game.input.keyboard.addKey(spec.key);
            this.key.onDown.add(this.use, this);
        }

        if (spec.trigger) {
            this.events.on('trigger', spec.trigger);
        }

        this.postUse();
    }

    Skill.prototype.use = function () {
        var now = new Date();
        if (now > this.allowed) {
            this.events.emit('trigger', this);
        }
    };

    Skill.prototype.postUse = function () {
        this.allowed = this.getNextAllowed();
        this.tweenUse();
    };

    Skill.prototype.tweenUse = function () {
        this.$element
            .removeClass('skill-allowed')
            .css('opacity', '0')
            .animate({
                opacity: 1
            }, this.interval * 1000, function () {
                $(this).addClass('skill-allowed');
            });
    };

    Skill.prototype.getNextAllowed = function () {
        var allowedDate = new Date();
        allowedDate.setSeconds(allowedDate.getSeconds() + this.interval);
        return allowedDate;
    };

    function Ship() {
        this.x = 0;
        this.y = 0;
    }

    Ship.prototype.update = function () {
        if (this.fire) {
            this.fire.position.x = this.sprite.position.x;
            this.fire.position.y = this.sprite.position.y;
            this.fire.animations.play('play');
            var scale = Math.max(0, (0.8 - parseFloat(this.data.hp_navio)) / 0.8);
            this.fire.scale.set(scale);
            this.fire.alpha = scale;
        }
        if (this.emitter) {
            game.world.bringToTop(this.emitter);
            this.emitter.forEachExists(game.world.bringToTop, game.world);
            this.emitter.minParticleSpeed.set((this.emitter.emitX - this.sprite.x) * 10, (this.emitter.emitY - this.sprite.y) * 10);
            this.emitter.maxParticleSpeed.set((this.emitter.emitX - this.sprite.x) * 10, (this.emitter.emitY - this.sprite.y) * 10);
            this.emitter.emitX = this.sprite.x;
            this.emitter.emitY = this.sprite.y;
        }
    };

    Ship.prototype.destroy = function () {
        if (this.sprite) {
            this.sprite.destroy();
        }

        delete ships[this.id];
    };
    Ship.prototype.visible = function () {
        return Math.abs(this.x - gameState.player.x) < 17 && Math.abs(this.y - gameState.player.y) < 10;
    };

    Ship.prototype.sync = function (data) {
        this.id     = data.id;
        this.x      = data.x;
        this.y      = data.y;
        this.data   = data;

        if (!this.visible()) {
            this.destroy();
            return;
        }
        renderSelectedPlayers();

        var loader = new Phaser.Loader(game);
        loader.spritesheet('ship_' + data.id, 'Imagens/Bandeiras/navio_sprite.php?cod=' + data.bandeira + '&f=' + data.faccao + '&s=' + data.skin_navio, skin_sizes[data.skin_navio], skin_sizes[data.skin_navio]);
        loader.onLoadComplete.add(function () {
            if (!this.sprite) {
                this.sprite = gameState.characterGroup.create(this.x * SQUARE_SIZE, this.y * SQUARE_SIZE, 'ship_' + data.id);
                this.sprite.anchor.set(0.5, 0.8);

                this.fire = gameState.characterGroup.create(0, 0, 'fire');
                this.fire.anchor.set(0.5, 0.85);
                this.fire.animations.add('play', null, 6);
                this.fire.animations.play('play');

                this.emitter = game.add.emitter(this.sprite.position.x, this.sprite.position.y, 400);
                this.emitter.makeParticles(['rocket_fire1']);
                this.emitter.gravity = 0;
                this.emitter.setAlpha(1, 0, 2000);
                this.emitter.setScale(0.3, 0.2, 0.3, 0.2, 2000);
                this.emitter.minParticleSpeed.set(0, 0);
                this.emitter.maxParticleSpeed.set(0, 0);
            }

            if (this.emitter) {
                if (data.coup_de_burst_usado) {
                    this.emitter.start(false, 2000, 100);
                    this.emitter.visible = true;
                } else {
                    this.emitter.visible = false;
                }
            }

            this.sprite.position.x = this.x * SQUARE_SIZE;
            this.sprite.position.y = this.y * SQUARE_SIZE;
            this.direction = data.direcao_navio;
            this.traveling = data.navegando;
            this.travelProgress = Math.min(1, data.navegacao_progresso);
            this.travelRemain = Math.max(0, data.navegacao_restante);

            if (this.traveling) {
                if (this.tween) {
                    this.tween.stop();
                }
                var movement = this.projectMovementIncrement(this.travelProgress);
                var nextDestination = gameState.convertPointToTile(this.projectDestinationBasedOnDirection());

                this.sprite.position.x += movement.x * SQUARE_SIZE;
                this.sprite.position.y += movement.y * SQUARE_SIZE;
                this.tween = game.add.tween(this.sprite.position).to(nextDestination, this.travelRemain * 1000, Phaser.Easing.Linear.None, true);
            }

            this.sprite.frame = this.direction;
        }, this);
        loader.start();
    };

    Ship.prototype.projectMovementIncrement = function (progress = 1) {
        return new Phaser.Point(
            (this.direction >= 5 && this.direction <= 7 ? -1 : (this.direction >= 1 && this.direction <= 3 ? 1 : 0)) * progress,
            (this.direction >= 7 || this.direction <= 1 ? -1 : (this.direction >= 3 && this.direction <= 5 ? 1 : 0)) * progress
        );
    };

    Ship.prototype.projectDestinationBasedOnDirection = function () {
        var movement = this.projectMovementIncrement();
        return new Phaser.Point(
            this.x + movement.x,
            this.y + movement.y
        );
    };

    function Player() {
        this.x = 155;
        this.y = 20;
        this.direction = 4;
        this.traveling = false;
        this.travelProgress = null;
        this.travelRemain = null;
    }

    Player.prototype.update = function () {
        this.sprite.frame = this.direction;

        if (this.health) {
            var hpPercent = parseFloat(this.data.hp_navio);
            this.health.width = 40 * hpPercent;
            this.health.position.x = this.sprite.position.x - SQUARE_SIZE / 2;
            this.health.position.y = this.sprite.position.y;
            this.healthBg.position.x = this.sprite.position.x - SQUARE_SIZE / 2;
            this.healthBg.position.y = this.sprite.position.y;
        }

        if (this.route && this.destination && this.sprite) {
            this.route.width = Phaser.Point.distance(this.sprite.position, this.destination);
            this.route.position.x = this.sprite.position.x + (this.destination.x - this.sprite.position.x) / 2;
            this.route.position.y = this.sprite.position.y + (this.destination.y - this.sprite.position.y) / 2;
            this.route.tilePosition.x -= 1;
            this.route.rotation = Phaser.Point.angle(this.sprite.position, this.destination);
            this.route.visible = true;
            game.world.bringToTop(this.route);
            this.routeTarget.position.x = this.destination.x;
            this.routeTarget.position.y = this.destination.y;
            this.routeTarget.visible = true;
            game.world.bringToTop(this.routeTarget);
            game.world.bringToTop(this.emitter);
            this.emitter.forEachExists(game.world.bringToTop, game.world);
            this.emitter.minParticleSpeed.set((this.emitter.emitX - this.sprite.x) * 10, (this.emitter.emitY - this.sprite.y) * 10);
            this.emitter.maxParticleSpeed.set((this.emitter.emitX - this.sprite.x) * 10, (this.emitter.emitY - this.sprite.y) * 10);
            this.emitter.emitX = this.sprite.x;
            this.emitter.emitY = this.sprite.y;
        } else if (this.route) {
            this.route.visible = false;
            this.routeTarget.visible = false;
        }

        if (this.fire) {
            this.fire.position.x = this.sprite.position.x;
            this.fire.position.y = this.sprite.position.y;
            this.fire.animations.play('play');
            var scale = Math.max(0, (0.8 - parseFloat(this.data.hp_navio)) / 0.8);
            this.fire.scale.set(scale);
            this.fire.alpha = scale;
        }
    };

    Player.prototype.sync = function (data) {
        this.id     = data.id;
        this.x      = data.x;
        this.y      = data.y;
        this.data   = data;

        renderSelectedPlayers();

        if (!this.sprite) {
            this.sprite = gameState.characterGroup.create(this.x * SQUARE_SIZE, this.y * SQUARE_SIZE, 'ship_' + data.id);
            this.sprite.anchor.set(0.5, 0.8);

            this.healthBg = gameState.characterGroup.create(this.x * SQUARE_SIZE - SQUARE_SIZE / 2, this.y * SQUARE_SIZE, 'health_bar');
            this.healthBg.anchor.setTo(0, -9);
            this.healthBg.width = 40;
            this.healthBg.height = 2;
            this.healthBg.tint = 0xFF0000;

            this.health = gameState.characterGroup.create(this.x * SQUARE_SIZE - SQUARE_SIZE / 2, this.y * SQUARE_SIZE, 'health_bar');
            this.health.anchor.setTo(0, -9);
            this.health.width = 40;
            this.health.height = 2;

            this.fire = gameState.characterGroup.create(0, 0, 'fire');
            this.fire.anchor.set(0.5, 0.85);
            this.fire.animations.add('play', null, 6);
            this.fire.animations.play('play');

            this.emitter = game.add.emitter(this.sprite.position.x, this.sprite.position.y, 400);
            this.emitter.makeParticles(['rocket_fire1']);
            this.emitter.gravity = 0;
            this.emitter.setAlpha(1, 0, 2000);
            this.emitter.setScale(0.3, 0.2, 0.3, 0.2, 2000);
            this.emitter.minParticleSpeed.set(0, 0);
            this.emitter.maxParticleSpeed.set(0, 0);
        }

        if (this.emitter) {
            if (data.coup_de_burst_usado) {
                this.emitter.start(false, 2000, 100);
                this.emitter.visible = true;
            } else {
                this.emitter.visible = false;
            }
        }

        this.sprite.position.x = this.x * SQUARE_SIZE;
        this.sprite.position.y = this.y * SQUARE_SIZE;
        this.sprite.alpha = data.mar_visivel ? 1 : 0.3;
        this.direction = data.direcao_navio;
        this.traveling = data.navegando;
        this.travelProgress = data.navegacao_progresso;
        this.travelRemain = data.navegacao_restante > 0 ? data.navegacao_restante : 0.0001;

        if (this.traveling) {
            if (this.tween) {
                this.tween.stop();
            }
            var movement = this.projectMovementIncrement(this.travelProgress);
            var nextDestination = gameState.convertPointToTile(this.projectDestinationBasedOnDirection());

            this.sprite.position.x += movement.x;
            this.sprite.position.y += movement.y;
            this.tween = game.add.tween(this.sprite.position).to(nextDestination, this.travelRemain * 1000, Phaser.Easing.Linear.None, true);

            this.tween.onComplete.add(function () {
                gameState.drawVisibleMap();
                ws.send(JSON.stringify({
                    event: 'update_position'
                }));
            }, this);

            if (!this.destination) {
                this.destination = new Phaser.Point();
            }
            this.destination.x = this.traveling.split('_')[0] * SQUARE_SIZE;
            this.destination.y = this.traveling.split('_')[1] * SQUARE_SIZE;
            if (!this.route) {
                this.route = game.add.tileSprite(0, 0, 0, 30, 'route');
                this.route.anchor.set(0.5);
                this.routeTarget = game.add.image(0, 0, 'route_target');
                this.routeTarget.anchor.set(0.5);
            }
            this.route.visible = true;
        } else {
            this.destination = null;
        }

        for (var key in ships) {
            if (!ships[key].visible()) {
                ships[key].destroy();
            }
        }
    };
    Player.prototype.showFloatingText = function (text, style, easing) {
        var textSprite = game.add.text(this.sprite.position.x, this.sprite.position.y, text, style);

        textSprite.anchor.setTo(0.5, 40 / 15);
        gameState.characterGroup.add(textSprite);

        var tween = game.add.tween(textSprite.anchor).to({
            y: 80 / parseInt(textSprite.fontSize + '', 10)
        }, 3000, easing, true);

        tween.onComplete.add(function () {
            textSprite.destroy();
        });
    };

    Player.prototype.heal = function (newHP, amount) {
        this.data.hp_navio = newHP;

        this.showFloatingText(amount, {
            font: '15px',
            fill: '#00ff00',
            align: 'center',
            wordWrap: false,
            stroke: '#bbffbb',
            strokeThickness: 1
        }, Phaser.Easing.Linear.None);
    };

    Player.prototype.damage = function (newHP, amount) {
        this.data.hp_navio = newHP;

        this.showFloatingText(amount, {
            font: '15px',
            fill: '#ff0000',
            align: 'center',
            wordWrap: false,
            stroke: '#ffbbbb',
            strokeThickness: 1
        }, Phaser.Easing.Linear.None);
    };

    Player.prototype.projectMovementIncrement = function (progress = 1) {
        return new Phaser.Point(
            (this.direction >= 5 && this.direction <= 7 ? -1 : (this.direction >= 1 && this.direction <= 3 ? 1 : 0)) * progress,
            (this.direction >= 7 || this.direction <= 1 ? -1 : (this.direction >= 3 && this.direction <= 5 ? 1 : 0)) * progress
        );
    };

    Player.prototype.projectDestinationBasedOnDirection = function () {
        var movement = this.projectMovementIncrement();
        return new Phaser.Point(
            this.x + movement.x,
            this.y + movement.y
        );
    };

    function adjust() {
        if (window.innerWidth < window.innerHeight) {
            $('#modal-widescreen-needed').modal('show');
        } else {
            $('#modal-widescreen-needed').modal('hide');
        }
        this.game.scale.refresh();
    }

    $(function () {
        game = new Phaser.Game(1366, 768, Phaser.CANVAS, 'mar-game');
        games['oceano'] = game;
        game.state.add('boot', new Boot());
        game.state.add('wait', new Wait());
        game.state.add('game', gameState);
        game.state.start('boot');

        window.addEventListener('resize', function () {
            adjust();
        });

        $('#mar-game-tooltip .close').on('click', function () {
            $('#mar-game-tooltip').hide();
        });

        $('#skill-heal').click(function () {
            gameState.healSkill.use();
        });

        $('#skill-shot').click(function () {
            gameState.shotSkill.use();
        });

        $('#skill-attack').click(function () {
            gameState.attackSkill.use();
        });

        $('#skill-kairouseki').click(function () {
            gameState.kairousekiSkill.use();
        });

        $('#skill-coup-de-burst').click(function () {
            gameState.coupDeBurstSkill.use();
        });

        $('#skill-profissoes').click(function () {
            $('#modal-profissoes-oceano').modal('show');

            $.ajax({
                type: 'get',
                url: 'Scripts/Mapa/profissoes_panel.php',
                cache: false,
                success: function (retorno) {
                    retorno = retorno.trim();
                    if (retorno.substr(0, 1) == "#") {
                        bancandoEspertinho(retorno.substr(1, retorno.length - 1));
                    } else {
                        $("#modal-profissoes-oceano-body").html(retorno);
                    }
                }
            });
        });
    });
</script>

<div class="modal fade" id="modal-widescreen-needed">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <p>Por favor utilize seu dispositivo na horizontal para uma melhor experiência!</p>
                <img src="Imagens/Backgrounds/rotate-device.png" width="100%"/>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-profissoes-oceano">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Profissões</h4>
            </div>
            <div class="modal-body">
                <div id="modal-profissoes-oceano-body">

                </div>
            </div>
        </div>
    </div>
</div>