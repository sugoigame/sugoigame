/**
 * Created by Luiz Eduardo on 24/05/2017.
 */

(function () {
    'use strict';

    angular.module('sugoi.chat').controller('SilenciadosCtrl', ['$scope',
        function ($scope) {

            $scope.silenciados = window.localStorage.getItem('sg_silenciados');

            if ($scope.silenciados) {
                $scope.silenciados = JSON.parse($scope.silenciados);
            } else {
                $scope.silenciados = [];
            }

            $scope.removeSilenciado = function (index) {
                $scope.silenciados.splice(index, 1);
                window.localStorage.setItem('sg_silenciados', JSON.stringify($scope.silenciados));
            };
        }]);
}());
