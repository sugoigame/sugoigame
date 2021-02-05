/**
 * Created by Luiz Eduardo on 24/06/2017.
 */

(function () {
    'use strict';

    angular.module('sugoi.chat').directive('listagemSalas', function () {

        return {
            controller: 'ListagemSalasCtrl',
            templateUrl: 'views/listagemSalas.html',
            restrict: 'E',
            scope: {
                rooms: '=rooms'
            }
        };
    });
}());