/**
 * Created by Luiz Eduardo on 15/05/2017.
 */

(function () {
    'use strict';

    angular.module('sugoi.chat', ['ngSanitize', 'ui.bootstrap'])
        .controller('SugoiChatCtrl', ['$scope', 'SocketIO', 'NotificationService', 'FiltroLinguagemService',
            function ($scope, SocketIO, NotificationService, FiltroLinguagemService) {

                $scope.preferencias = JSON.parse(window.localStorage.getItem('sg_preferences'));

                if (!$scope.preferencias) $scope.preferencias = {alertaSonoro: true, filtroLinguagem: true};

                $scope.tela = 'Chat';
                $scope.user = {};
                $scope.messages = [];
                $scope.usersOnline = [];
                $scope.usersOnlineNaSala = [];

                /** HABILITA O ENVIO DE MENSAGENS */
                $scope.enviarMensagem = true;

                /** LIMITE MÁXIMO DE MENSAGENS NO CHAT */
                var numeroMaxMensagensNoChat = 50;

                /** LIMITE DE EMPILHAMENTO DE MENSAGESNS DE UM MESMO USUÁRIO */
                var empilhamentoMaxDeMensagens = 10;


                $scope.directivepopouver = '<user-popouver></user-popouver>';
                $scope.imagemBandeira = function () {
                    $('[data-toggle="popover"]').popover({});
                    return "https://sugoigame.com.br/Imagens/Bandeiras/img.php?cod=010113046758010128123542010115204020&f=0";
                };

                $scope.user.conta_id = window.localStorage.getItem('sg_c');
                $scope.user.token = window.localStorage.getItem('sg_k');

                if (!$scope.user.conta_id || !$scope.user.token) {
                    return;
                }

                var userLastMensage;
                var socket;


                /**
                 * Muda a tela do chat para a que foi passada por parâmetro.
                 */
                $scope.mudarTela = function (tela) {
                    if ($scope.tela === tela) {
                        return;
                    }
                    $scope.tela = tela;
                    $scope.preferencias = JSON.parse(window.localStorage.getItem('sg_preferences'));
                    if (!$scope.preferencias) $scope.preferencias = {alertaSonoro: true, filtroLinguagem: true};
                };


                function start() {
                    socket = SocketIO.getSocket();
                    setEventHandlers();
                }

                start();


                /**
                 * Esperando eventos do servidor
                 */
                function setEventHandlers() {

                    // Socket connection successful
                    socket.on('connect', onSocketConnected);

                    // Recebe os dados do usuário.
                    socket.on('userConfig', userConfig);

                    // Socket disconnection
                    socket.on('disconnect', onSocketDisconnect);

                    // Socket disconnection
                    socket.on('remove user', removeUser);

                    // Socket disconnection
                    socket.on('users online', usersOnline);

                    // Recebendo as mensagens
                    socket.on('new message', newMessage);

                    // Recebendo as mensagens
                    socket.on('changeChannel', changeChannel);

                    // Recebendo as mensagens
                    socket.on('new room', newRoom);
                }


                /**
                 * Solicitado dados do usuário para o servidor.
                 */
                function onSocketConnected() {
                    socket.emit('sessionData', $scope.user);
                }


                /**
                 * Desconectando usuário do servidor.
                 */
                function onSocketDisconnect(data) {
                    console.log('Desconectado do servidor!! ' + data.message);
                }


                /**
                 * Desconectando usuário do servidor.
                 */
                function removeUser(user) {
                    removeUsersOnline(user);
                }


                /**
                 * Recebendo nova mensagem.
                 */
                function newMessage(message) {

                    if ($scope.preferencias.filtroLinguagem) {
                        message.message = FiltroLinguagemService.filtroLinguagem(message.message);
                    }

                    if (!verificaAutorSilenciado(message)) {

                        if (userLastMensage !== message.conta_id || empilhamentoMaxDeMensagens === 0) {
                            userLastMensage = message.conta_id;
                            $scope.messages.push(message);
                            empilhamentoMaxDeMensagens = 10;
                        } else {
                            $scope.messages[$scope.messages.length - 1].message += "<br>" + message.message;
                            $scope.messages[$scope.messages.length - 1].time = message.time;
                            empilhamentoMaxDeMensagens--;
                        }

                        $scope.messages = limitarMensagens(numeroMaxMensagensNoChat, $scope.messages);

                        $scope.$apply();
                        $scope.notificacaoAbaSemFoco(message);

                        if ($('.panel-body')[0].scrollHeight - ($('.panel-body').height() + $('.panel-body').scrollTop()) < 150) {
                            $('.panel-body').animate({
                                scrollTop: 1000000
                            });
                        }
                    }
                }


                /**
                 * Recebendo dados do usuário.
                 */
                function userConfig(data) {
                    $scope.user = data;
                    $scope.usersOnline = data.usersOnline;
                    $scope.rooms = data.rooms;
                    console.log('Conectado ao servidor!!');
                    var sala = localStorage.getItem('salaAtiva');
                    if (sala) {
                        socket.emit('changeChannel', {nameChannel: sala});
                    }
                }


                /**
                 * Recebendo as mensagens dos canais.
                 */
                function changeChannel(data) {
                    if (data.nameChannel == 'Global') {
                        $scope.mudarSala();
                    } else {
                        userLastMensage = '';
                        $scope.messages = [];
                        $scope.user.canal = data.nameChannel;
                        preencheMensagens(data.messages);
                        $scope.$apply();
                        localStorage.setItem('salaAtiva', data.nameChannel);
                    }
                }


                /**
                 * Recebendo as mensagens dos canais.
                 */
                function newRoom(data) {
                    $scope.rooms = data;
                    $scope.$apply();
                }


                /***********
                 *  EMIT'S *
                 **********/

                /**
                 *  Solicita o envio de mensagem para o servidor.
                 */
                $scope.sendMessage = function () {
                    if ($scope.message) {
                        socket.emit('message', {message: $scope.message.trim()});
                        $scope.message = '';
                    }
                };


                /**
                 * Solicita a mudança de canal do chat.
                 */
                $scope.mudarSala = function () {
                    $scope.user.canal = '';
                    socket.emit('exit room');
                    localStorage.removeItem('salaAtiva');
                };


                /**
                 * Solicita a mudança de canal do chat.
                 */
                $scope.mudarCanal = function (nomeCanal) {
                    if (!roomByName(nomeCanal)) {
                        console.log('Esse canal não existe!!');
                        return;
                    }
                    socket.emit('changeChannel', {nameChannel: nomeCanal});
                };


                /**
                 * Tornando player administrador da sala.
                 */
                $scope.tornarAdministrador = function (user) {
                    socket.emit('mudarPermissaoAdministrador', {capitao: user});
                };

                $scope.isAdministrador = function (user) {
                    var i;
                    for (i = 0; i < $scope.rooms.length; i++) {
                        if ($scope.rooms[i].name == $scope.user.canal) {
                            return $scope.rooms[i].administradores.indexOf(user) !== -1;
                        }
                    }
                    return false;
                };
                $scope.removerAdministrador = function (user) {
                    socket.emit('mudarPermissaoAdministrador', {capitao: user});
                };

                $scope.removerDaSala = function (capitao) {
                    socket.emit('removerSala', capitao);
                };

                $scope.banirDaSala = function (capitao) {
                    socket.emit('banSala', capitao);
                };


                /*************
                 *  HELPERS  *
                 ************/

                var canSendNotifications = false;

                setTimeout(function () {
                    canSendNotifications = true;
                }, 5000);


                /**
                 * Toca o audio e manda notificação caso a janela do chat não esteja em foco.
                 */
                $scope.notificacaoAbaSemFoco = function (message) {
                    var audio = new Audio('audio/gun-silencer.mp3');
                    if (!document.hasFocus() && canSendNotifications) {

                        if ($scope.preferencias.alertaSonoro) {
                            audio.play();
                        }
                        NotificationService.enviarNotificacao(message);
                    }
                };


                /**
                 * Limitando o total de mensagens exbidas no chat.
                 */
                function limitarMensagens(limiteDeMensagens, mensagens) {
                    if (mensagens.length > limiteDeMensagens) {
                        mensagens.splice(0, 1);
                    }
                    return mensagens;
                }


                $scope.silenciarUser = function (user) {

                    var silenciados = window.localStorage.getItem('sg_silenciados');

                    if (!silenciados) {
                        silenciados = [];
                    } else {
                        silenciados = JSON.parse(silenciados);
                    }

                    if (!verificarSeExisteArraySilenciados(silenciados, user.conta_id)) {
                        silenciados.push(
                            {
                                conta_id: user.conta_id,
                                adm: user.adm,
                                capitao: user.capitao,
                                capitaoTitulo: (user.capitaoTitulo === null ? '' : user.capitaoTitulo),
                                capitaoImg: user.capitaoImg,
                                capitaoSkin_rosto: user.capitaoSkin_rosto
                            });
                        window.localStorage.setItem('sg_silenciados', JSON.stringify(silenciados));
                    }
                };


                function verificarSeExisteArraySilenciados(silenciados, conta_id) {

                    var achou = false;
                    silenciados.forEach(function (silenciado, index) {
                        if (silenciado.conta_id === conta_id) {
                            achou = true;
                        }
                    });
                    return achou;
                }


                function verificaAutorSilenciado(message) {

                    var achou = false;
                    var autoresSilenciados = window.localStorage.getItem('sg_silenciados');

                    if (autoresSilenciados) {
                        autoresSilenciados = JSON.parse(autoresSilenciados);
                    } else {
                        autoresSilenciados = [];
                    }

                    autoresSilenciados.forEach(function (silenciado, index) {
                        if (silenciado.conta_id === message.conta_id) {
                            achou = true;
                        }
                    });
                    return achou;
                }


                function preencheMensagens(mensagens) {
                    if (mensagens) {
                        mensagens.forEach(function (message, index) {
                            newMessage(message)
                        });
                    }
                }


                function usersOnline(usersOnline) {
                    $scope.usersOnline = usersOnline;
                    // pegaUsersNaMesmaSala();
                    $scope.usersOnlineNaSala = usersOnline.filter(function (elemento) {
                        return elemento.nameChannel == $scope.user.canal;
                    });
                    $scope.$apply();
                }


                function removeUsersOnline(user) {
                    $scope.usersOnline.forEach(function (online, index) {
                        if (online.conta_id === user.conta_id) {
                            $scope.usersOnline.splice(index, 1);
                            $scope.$apply();
                        }
                    });
                }

                function pegaUsersNaMesmaSala() {
                    $scope.usersOnline.forEach(function (user, index) {
                        if (user.nameChannel === $scope.user.canal) {
                            $scope.usersOnlineNaSala.push($scope.usersOnline[index]);
                        }
                    });
                    $scope.$apply();
                    console.log($scope.usersOnlineNaSala);
                }
            }]);
}());