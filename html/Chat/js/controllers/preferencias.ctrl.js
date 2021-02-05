/**
 * Created by Luiz Eduardo on 24/05/2017.
 */

(function () {
    'use strict';

    angular.module('sugoi.chat').controller('PreferenciasCtrl', ['$scope',
        function ($scope) {

            $scope.preferencias = JSON.parse(window.localStorage.getItem('sg_preferences'));

            if (!$scope.preferencias) {
                $scope.preferencias = {alertaSonoro: true, filtroLinguagem: true};
            }

            $scope.salvarMudancas = function () {
                window.localStorage.setItem('sg_preferences', JSON.stringify($scope.preferencias));
            };
        }]);
}());
