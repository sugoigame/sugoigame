/**
 * Created by Luiz Eduardo on 24/06/2017.
 */

(function () {
    'use strict';

    angular.module('sugoi.chat').controller('ListagemSalasCtrl', ['$scope', 'SocketIO',
        function ($scope, SocketIO) {

            var socket = SocketIO.getSocket();
            $scope.newRoom = {};
            $scope.tela = 'Salas';
            $scope.confirmPassword = '';

            $scope.mudarTela = function (tela) {
                $scope.tela = tela;
            };

            $scope.criarNovaSala = function () {
                var password = ($scope.newRoom.password ? btoa($scope.newRoom.password) : '');
                socket.emit('new room', {password: password});
                $scope.tela = 'Salas';
            };

            /**
             * Solicita a mudança de canal do chat.
             */
            $scope.mudarCanal = function (room) {
                if(room.password){
                    $scope.sala = room;
                    $scope.mudarTela('Password');
                    return;
                }
                if (!roomByName(room.name)) {
                    console.log('Esse canal não existe!!');
                    return;
                }
                socket.emit('changeChannel', {nameChannel: room.name});
            };


            $scope.conferirSenha = function (confirmPassword) {

                console.log($scope.sala);

                if(confirmPassword === atob($scope.sala.password)){
                    if (!roomByName($scope.sala.name)) {
                        console.log('Esse canal não existe!!');
                        return;
                    }
                    socket.emit('changeChannel', {nameChannel: $scope.sala.name});
                    return;
                }
                $scope.incorrectPassword = true;
            };


            function roomByName(name) {
                var i;
                for (i = 0; i < $scope.rooms.length; i++) {
                    if ($scope.rooms[i].name === name) {
                        return $scope.rooms[i];
                    }
                }
                return false;
            }
        }]);
}());