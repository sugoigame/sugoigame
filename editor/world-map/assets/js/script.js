/*jslint white: true*/
(function ($, global) {
    'use strict';

    var uniqueColors = {};

    // Globais
    global.minTilesX = 0;
    global.minTilesY = 0;
    global.maxTilesX = 4;
    global.maxTilesY = 4;
    global.tamanhoQuadro = 40;
    global.quadrosPorTile = 20;
    global.tamanhoTile = global.tamanhoQuadro * global.quadrosPorTile;

    global.minQuadroX = function () {
        return global.minTilesX * global.quadrosPorTile;
    };

    global.minQuadroY = function () {
        return global.minTilesY * global.quadrosPorTile;
    };

    global.maxQuadroX = function () {
        return (global.maxTilesX + 1) * global.quadrosPorTile;
    };

    global.maxQuadroY = function () {
        return (global.maxTilesY + 1) * global.quadrosPorTile;
    };

    global.formatTipoData = function (tipo) {
        return 'minX=' + (global.minQuadroX()) + '&minY=' + (global.minQuadroY()) + '&maxX=' + (global.maxQuadroX()) + '&maxY=' + (global.maxQuadroY()) + '&tipo=' + tipo;
    };

    global.unbind = function () {
        $('.quadro')
            .off('click')
            .removeClass('add-ilha')
            .removeClass('com-ilha')
            .removeData('nome')
            .removeData('id')
            .removeClass('add-nao-navegavel')
            .removeClass('com-nao-navegavel')
            .removeClass('add-nevoa')
            .removeClass('com-nevoa')
            .removeClass('add-corrente')
            .removeClass('com-corrente')
            .removeClass('add-redemoinho')
            .removeClass('com-redemoinho')
            .removeClass('add-mergulho')
            .removeClass('com-mergulho')
            .removeClass('add-exploracao')
            .removeClass('com-exploracao')
            .removeClass('add-rdm')
            .removeClass('com-rdm')
            .removeData('quadro-info')
            .css('background', 'transparent')
            .html('');

        $('#nevoa-panel').dialog("close");
        $('#corrente-panel').dialog("close");
        $('#redemoinho-panel').dialog("close");
        $('#mergulho-panel').dialog("close");
        $('#exploracao-panel').dialog("close");
        $('#rdm-panel').dialog("close");
        $('#select-rdm-panel').dialog("close");
    };

    // Heatmap Tool
    function curvaVariacao(x) {
        return -(4 * Math.pow(x, 2)) + (4 * x);
    }

    global.getScale = function (value, scaleMin, scaleMax) {
        return (value - scaleMin) / (scaleMax - scaleMin);
    };

    global.interpolate = function (min, max, point, colorIntensity) {
        colorIntensity = colorIntensity || 255;
        if (point >= min && point <= max) {
            return curvaVariacao(global.getScale(point, min, max)) * colorIntensity;
        }
        return 0;
    };

    function interpolateR(point) {
        return Math.round(global.interpolate(0.5, 1.5, point));
    }

    function interpolateG(point) {
        return Math.round(global.interpolate(0.0, 1.0, point, 150));
    }

    function interpolateB(point) {
        return Math.round(global.interpolate(-0.5, 0.5, point));
    }

    global.getHeatMapColor = function (value) {
        var color;
        if (value < 0) {
            value *= -1;
        }

        return {
            r: interpolateR(value),
            g: interpolateG(value),
            b: interpolateB(value)
        };
    };

    global.heatMap = function (value, scaleMin, scaleMax) {
        var cor = global.getHeatMapColor(global.getScale(value, scaleMin, scaleMax));
        return 'rgb(' + cor.r + ',' + cor.g + ',' + cor.b + ')';
    };
    global.heatMapTransparent = function (value, scaleMin, scaleMax, opacity) {
        var cor = global.getHeatMapColor(global.getScale(value, scaleMin, scaleMax));
        return 'rgba(' + cor.r + ',' + cor.g + ',' + cor.b + ',' + opacity + ')';
    };

    function randColor() {
        return Math.floor(Math.random() * 256);
    }

    function getUniqueColor(id) {
        id = id.toString();
        if (!uniqueColors[id]) {
            uniqueColors[id] = {
                r: randColor(),
                g: randColor(),
                b: randColor()
            };
        }
        return uniqueColors[id];
    }

    global.getUniqueColorFor = function (id, opacity) {
        var cor = getUniqueColor(id);
        return 'rgba(' + cor.r + ',' + cor.g + ',' + cor.b + ',' + opacity + ')';
    };

    // Tiles
    function getTilePositionX(x) {
        return ((global.tamanhoTile * x) - global.minTilesX * global.tamanhoTile);
    }

    function getTilePositionY(y) {
        return ((global.tamanhoTile * y) - global.minTilesY * global.tamanhoTile);
    }

    function constroiTiles() {
        var x, y;
        $('#tilesPlace').html('');
        for (x = global.minTilesX; x <= global.maxTilesX; x += 1) {
            for (y = global.minTilesY; y <= global.maxTilesY; y += 1) {
                $('#tilesPlace').append(
                    $('<IMG>')
                        .attr('src', '../../game/Imagens/Mapa/Mapa_Mundi/' + x + '_' + y + '.jpg')
                        .addClass('tile')
                        .css('top', getTilePositionY(y) + 'px')
                        .css('left', getTilePositionX(x) + 'px')
                );
            }
        }
    }

    // Click Zone
    function getWidthClickZone() {
        return ((global.tamanhoTile * (global.maxTilesX - global.minTilesX + 1)) + 100);
    }

    function getHeightClickZone() {
        return ((global.tamanhoTile * (global.maxTilesY - global.minTilesY + 1)) + 100);
    }

    function constroiClickZone() {
        $('#tilesPlace').append(
            $('<DIV>')
                .addClass('click-zone')
                .css('width', getWidthClickZone() + 'px')
                .css('height', getHeightClickZone() + 'px')
        );
    }

    // Quadros
    function getQuadroPositionX(x) {
        return ((global.tamanhoQuadro * x) - global.minTilesX * global.tamanhoTile);
    }

    function getQuadroPositionY(y) {
        return ((global.tamanhoQuadro * y) - global.minTilesY * global.tamanhoTile);
    }

    function constroiQuadros() {
        var x, y;
        for (x = global.minQuadroX(); x < global.maxQuadroX(); x += 1) {
            for (y = global.minQuadroY(); y < global.maxQuadroY(); y += 1) {
                $('.click-zone').append(
                    $('<DIV>')
                        .addClass('quadro')
                        .attr('id', 'quadro_' + x + '_' + y)
                        .data('x', x)
                        .data('y', y)
                        .css('top', getQuadroPositionY(y) + 'px')
                        .css('left', getQuadroPositionX(x) + 'px')
                        .css('width', global.tamanhoQuadro + 'px')
                        .css('height', global.tamanhoQuadro + 'px')
                        .attr('title', x + '_' + y)
                );
            }
        }
    }

    // Tooltip
    function constroiTooltips() {
        $(document).tooltip({
            content: function () {
                var data = $(this).data('quadro-info'),
                    tooltip = $(this).attr('title');

                if (data) {
                    tooltip += ', ' + data;
                }
                return tooltip;
            }
        });
    }

    // Construtor
    function construct() {
        global.unbind();

        constroiTiles();

        constroiClickZone();

        constroiQuadros();

        constroiTooltips();
    }

    // onReady
    $(function () {
        $('.spinner').spinner();

        $('select').selectmenu();

        $('button').button();

        $(document).on('submit', 'form', function (e) {
            e.preventDefault();
        });

        $('#gerar').button().click(function () {
            global.minTilesX = parseInt($('#minX').val(), 10);
            global.minTilesY = parseInt($('#minY').val(), 10);
            global.maxTilesX = parseInt($('#maxX').val(), 10);
            global.maxTilesY = parseInt($('#maxY').val(), 10);

            construct();
        });

    });

}(window.jQuery, window));
