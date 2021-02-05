/**
 * Created by Luiz Eduardo on 23/05/2017.
 */
(function () {
    'use strict';

    angular.module('sugoi.chat').directive('userPopouver', function () {

        return {
            restrict: 'A',
            scope: {
                user: '=',
                placement: '@'
            },
            link: function (scope, element) {
                $(element).popover({
                    html: true,
                    title: scope.user.tripulacao,
                    placement: scope.placement,
                    content: '<div>' +
                    '<div class="col-lg-5">' +
                    '<img src="https://sugoigame.com.br/Imagens/Bandeiras/img.php?cod=' + scope.user.bandeira + '&f=' + scope.user.faccao + '" alt="User Avatar">' +
                    '</div>' +
                    '<div class="col-lg-7 espacamento">' + scope.user.capitao + (scope.user.capitaoTitulo == null ? '' : ' - ' + scope.user.capitaoTitulo) + '</div>' +
                    '<div class="col-lg-7 espacamento">Reputação: ' + scope.user.reputacao + '</div>' +
                    '<div class="col-lg-7 espacamento">Nível do mais forte: ' + scope.user.nivelMaisForte + '</div>' +
                    '</div>'
                });
            }
        };
    });
}());