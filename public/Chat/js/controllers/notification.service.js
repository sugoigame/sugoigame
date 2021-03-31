/**
 * Created by Luiz Eduardo on 22/05/2017.
 */

(function () {
    'use strict';

    angular.module('sugoi.chat').factory('NotificationService',
        function () {

            Notification.requestPermission();

            function enviarNotificacao(message) {
                // funcao desabilitada temporariamente
                return;

                var config = {
                    body: message.message,
                    icon: 'https://sugoigame.com.br/Imagens/Personagens/Icons/' + message.capitaoImg + '(' + message.capitaoSkin_rosto + ').jpg',
                    vibrate: [200, 100, 200]
                };

                // Let's check if the browser supports notifications
                if (!("Notification" in window)) {
                    alert("Esse browser não suporta desktop notification!!");
                }

                // Let's check whether notification permissions have already been granted
                else if (Notification.permission === "granted") {
                    // If it's okay let's create a notification
                    var notification = new Notification(message.capitao, config);

                    notification.onclick = function () {
                        window.focus();
                    };

                    notification.vibrate;
                    notification.sound;
                    notification.renotify;
                }

                // Otherwise, we need to ask the user for permission
                else if (Notification.permission !== 'denied') {
                    Notification.requestPermission(function (permission) {
                        // If the user accepts, let's create a notification
                        if (permission === "granted") {
                            var notification = new Notification(message.capitao, config);
                        }
                    });

                    Notification.vibrate = true;
                    Notification.sound = false;
                    Notification.renotify = true;
                }

                // At last, if the user has denied notifications, and you
                // want to be respectful there is no need to bother them any more.
            }

            return {

                enviarNotificacao: enviarNotificacao
            };
        });
}());