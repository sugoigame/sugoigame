/**
 * Created by Luiz Eduardo on 24/05/2017.
 */
(function () {
    'use strict';

    angular.module('sugoi.chat').directive('preferencias', function () {

        return {
            controller: 'PreferenciasCtrl',
            templateUrl: 'views/preferencias.html',
            restrict: 'E',
            scope: {
                user: '@user'
            }
        };
    });
}());