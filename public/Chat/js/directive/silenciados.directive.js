/**
 * Created by Luiz Eduardo on 24/05/2017.
 */
(function () {
    'use strict';

    angular.module('sugoi.chat').directive('userSilenciados', function () {

        return {
            controller: 'SilenciadosCtrl',
            templateUrl: 'views/silenciados.html',
            restrict: 'E',
            scope: {
                user: '@user'
            }
        };
    });
}());