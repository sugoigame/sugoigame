/**
 * Created by Luiz Eduardo on 24/06/2017.
 */

(function () {
    'use strict';

    angular.module('sugoi.chat').factory('SocketIO',
        function () {

            var socket;

            var getSocket = function () {
                if (!socket) {
                    var url = window.location.href;
                    if (url.indexOf('sugoigame.com.br') === -1) {
                        socket = io.connect('http://localhost:8080');
                    } else {
                        socket = io.connect('https://chat.sugoigame.com.br');
                    }

                }
                return socket;
            };


            return {
                getSocket: getSocket
            };
        });
}());