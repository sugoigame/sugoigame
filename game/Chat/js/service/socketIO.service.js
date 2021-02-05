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
                    if (url.indexOf('localhost') === -1) {
                        socket = io.connect('https://sugoigame.com.br:8080');
                    } else {
                        socket = io.connect('http://localhost:8080');
                    }

                }
                return socket;
            };


            return {
                getSocket: getSocket
            };
        });
}());