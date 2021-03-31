/**
 * Created by Luiz Eduardo on 15/05/2017.
 */

var util        = require('util');
var http        = require('http');
var path        = require('path');
var ecstatic    = require('ecstatic');
var io          = require('socket.io');

var User        = require('./objects/User');
var SugoiDB     = require('./models/sugoiDBConnection');
var ChatDB      = require('./models/chatDBConection');

var port        = process.env.PORT || 8080;
var socket;
var rooms;
var users;
var messages;
var administradores;


/** LIMITE DE ENVIO DE MENSAGENS SUCESSIVAS NO CHAT NUM INTERVALO DE 10 SEGUNDOS */
var limiteDeMensagensSucessivasMax = 7;


// Iniciando o servidor http

var server = http.createServer(
    ecstatic({root: path.resolve(__dirname, '../client')})
).listen(port, function (err) {
    if (err) {
        console.log(err);
    }
    init();
});

// Iniciando o servidor http

// var server = http.createServer(
//     ecstatic({root: path.resolve(__dirname, '../game/Chat')})
// ).listen(port, function (err) {
//     if (err) {
//         error.log(err);
//     }
//     init();
// });


function init() {

    rooms = [{
        name: 'Sala Comum',
        usersOnline: 0,
        administradores: ['Boss'],
        banList: []
    }];

    users = [];
    messages = [];
    administradores = [];

    socket = io.listen(server);
    console.log('Server running at: ' + port);

    // Escutando eventos disparados
    socket.sockets.on('connection', onSocketConnection);
}


function onSocketConnection(client) {

    // Esperando o cliente se desconectar.
    client.on('disconnect', onClientDisconnect);

    // Recebendo a mensagem do user.
    client.on('message', recebendoMensagem);

    // Mudando o user de canal.
    client.on('changeChannel', changeChannel);

    // Mudando o user de canal.
    client.on('sessionData', sessionData);

    // Crianco nova sala.
    client.on('new room', newRoom);

    // saindo da sala.
    client.on('exit room', exitRoom);

    // Tornar user ddministrador da sala.
    client.on('mudarPermissaoAdministrador', mudarPermissaoAdministrador);

    client.on('removerSala', removerSala);

    client.on('banSala', banSala);
}

function removerSala(capitao) {
    var me = userById(this.id);
    var user = userByCapitao(capitao);
    if (!user) {
        return;
    }

    if (me.getChannel() !== user.getChannel()) {
        return;
    }

    user.client.emit('changeChannel', {nameChannel: 'Global', messages: retornaMensagensDoCanal('Global')});
}

function banSala(capitao) {
    var me = userById(this.id);
    var user = userByCapitao(capitao);
    if (!user) {
        return;
    }

    if (me.getChannel() !== user.getChannel()) {
        return;
    }

    user.client.emit('changeChannel', {nameChannel: 'Global', messages: retornaMensagensDoCanal('Global')});
    var room = roomByName(me.getChannel());
    rooms[rooms.indexOf(room)].banList.push(capitao);
}

function exitRoom() {

    // Encontrando o user no array de users
    var i = findUserIndexById(this.id);

    if (i == -1) {
        console.error('EXIT ROOM => User não encontrado: ' + this.id);
        return;
    }

    //Remove o usuário do canal.
    changeUserFromRoom(users[i]);

    users[i].setChannel('Global');

    // Enviando novo user online pra todos.
    socket.sockets.emit('users online', usersOnline(users));
}

function onClientDisconnect() {
    exitRoom();

    var i = findUserIndexById(this.id);

    if (i == -1) {
        console.error('DISCONET => User não encontrado: ' + this.id);
        return;
    }
    var user = users[i];

    changeUserFromRoom(users[i]);

    // Remove user from users array
    users.splice(users.indexOf(user), 1);
    socket.sockets.emit('remove user', {conta_id: user.getContaId(), capitao: user.getCapitao()});
}


function changeChannel(data) {

    // Encontrando o user no array de users
    var i = findUserIndexById(this.id);

    // User não encontrado
    if (i == -1) {
        console.log('CHANEL => User não encontrado: ' + this.id);
        return;
    }

    var user = users[i];

    // Verificando se o canal passado está na lista.
    var room = roomByName(data.nameChannel);
    if (!room) {
        users[i].setChannel('');
        console.log('CHANEL => Canal passado não encontrado: ' + data.nameChannel);
        socket.sockets.emit('users online', usersOnline(users));
        return;
    }

    if (room.banList.indexOf(user.getCapitao()) != -1) {
        return;
    }

    this.leave(users[users.indexOf(user)].getChannel());
    this.join(data.nameChannel);

    changeUserFromRoom(user, data.nameChannel);

    this.emit('changeChannel', {nameChannel: data.nameChannel, messages: retornaMensagensDoCanal(data.nameChannel)});
    users[users.indexOf(user)].setChannel(data.nameChannel);
    socket.sockets.emit('new room', rooms);
    socket.sockets.emit('users online', usersOnline(users));
    // console.log('User ' + user.getCapitao() + ' conectado ao canal: ' + data.nameChannel);
}

function escapeString(value) {
    var entityMap = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
        '/': '&#x2F;',
        '`': '&#x60;',
        '=': '&#x3D;'
    };
    return String(value).replace(/[&<>"'`=\/]/g, function (s) {
        return entityMap[s];
    });
}

function recebendoMensagem(data) {

    var chatDB = new ChatDB();

    // Encontrando o user no array de users
    var user = userById(this.id);

    // User não encontrado
    if (!user) {
        console.error('MESSAGE => User não encontrado: ' + this.id);
        return;
    }

    //Verificando se o usuário está enviando muitas mensagems num curto espaço de tempo.
    if (!permissaoDeEnvioDeMensagem(user)) {
        return;
    }

    // Verifica se o usuário excedeu o limite de mensagens por tempo.
    if (user.getNumeroDeMensagensEmCurtoEspacoDeTempo() > 5) {
        user.setNumeroDeMensagensEmCurtoEspacoDeTempo(0);
    }

    // Resetando o tempo da última mensagem.
    if (Math.abs(new Date() - user.getLastMessageTime()) > 10000) {
        user.setLastMessageTime(new Date());
    }

    user.setNumeroDeMensagensEmCurtoEspacoDeTempo(user.getNumeroDeMensagensEmCurtoEspacoDeTempo() + 1);

    var channel = users[users.indexOf(user)].getChannel();

    var newMessage = {
        conta_id: user.getContaId(),
        adm: user.getAdm(),
        capitao: user.getCapitao(),
        message: escapeString(data.message),
        capitaoImg: user.getCapitaoImg(),
        capitaoSkin_rosto: user.getCapitaoSkin_rosto(),
        bandeira: user.getBandeira(),
        capitaoTitulo: user.getCapitaoTitulo(),
        faccao: user.getFaccao(),
        reputacao: user.getReputacao(),
        nivelMaisForte: user.getNivelMaisForte(),
        tripulacao: user.getTripulacao(),
        time: getDate()
    };

    socket.to(channel).emit('new message', newMessage);
    chatDB.insertMessage(user.getContaId(), user.getCapitao(), user.getChannel(), data.message, function () {
    });
    addMensagemCanal(newMessage, user.getChannel());
}


function sessionData(data) {

    if (!data) {
        console.log('empt data sent');
        return;
    }

    var sugoiDb = new SugoiDB();
    var client = this;

    sugoiDb.buscarUser(data.conta_id, function (err, userDados) {

        if (err) {
            console.log(err);
            return;
        }

        if (userDados.length === 0) {
            console.log('Não foi possível encotrar os dados desse usuário no banco de dados!!');
            return;
        }

        if (userDados.length && data.token !== userDados[0].token) {
            client.emit('disconnect', {message: 'Token fornecido é inválido!!'});
            return;
        }

        var user = new User({idUser: client.id, channelName: 'Global'}, userDados[0]);
        users.push(user);
        userDados[0].usersOnline = usersOnline(users);
        userDados[0].rooms = rooms;
        user.client = client;

        // Enviando os dados do bando de dados para o usuário.
        client.emit('userConfig', userDados[0]);

        // Enviando novo user online pra todos.
        socket.sockets.emit('users online', usersOnline(users));

        client.join('Global');
    });
}

function findUserIndexById(id) {
    var i;
    for (i = 0; i < users.length; i++) {
        if (users[i].getId() === id) {
            return i;
        }
    }
    return -1;
}

function userById(id) {
    var index = findUserIndexById(id);
    return index !== -1 ? users[index] : false;
}

function userByCapitao(capitao) {
    var i;
    for (i = 0; i < users.length; i++) {
        if (users[i].getCapitao() === capitao) {
            return users[i];
        }
    }
    return false;
}


function roomByName(name) {
    var i;
    for (i = 0; i < rooms.length; i++) {
        if (rooms[i].name === name) {
            return rooms[i];
        }
    }
    return false;
}


function getDate() {
    var now = new Date;
    return now.getDate() + '/' + (now.getMonth() + 1) + '/' + now.getFullYear() + ' ' + now.getHours() + ':' + now.getMinutes();
}


/**
 * Criando nova sala.
 */
function newRoom(newRoom) {

    // Encontrando o user no array de users
    var user = userById(this.id);

    if (!user) {
        console.log('Usuário não encontrado!!');
        return;
    }

    if (roomByName(user.getCapitao())) {
        console.log('Já existe uma sala com esse nome!!');
        return;
    }

    newRoom.name = 'Sala do ' + user.getCapitao();
    newRoom.usersOnline = 0;
    newRoom.administradores = [user.getCapitao()];
    newRoom.banList = [];
    rooms.push(newRoom);
    this.join(newRoom.name);
    changeUserFromRoom(user, newRoom.name);
    socket.sockets.emit('new room', rooms);
    users[users.indexOf(user)].setChannel(newRoom.name);
    this.emit('changeChannel', {nameChannel: newRoom.name, messages: retornaMensagensDoCanal(newRoom.name)});
    // Enviando novo user online pra todos.
    socket.sockets.emit('users online', usersOnline(users));
}


/**
 * Aplica ou remove permissão de administrador da sala.
 */
function mudarPermissaoAdministrador(userData) {
    var user = userById(this.id);
    if (!removePermissaoAdministrador(userData.capitao, user.getChannel())) {
        aplicaPermissaoAdministrador(userData.capitao, user.getChannel());
    }
    socket.sockets.emit('new room', rooms);
}


/**
 * Aplica permissão de administrador da sala.
 */
function aplicaPermissaoAdministrador(capitao, nameChannel) {
    var room = roomByName(nameChannel);
    if (room) {
        if (room.administradores.indexOf(capitao) === -1) {
            rooms[rooms.indexOf(room)].administradores.push(capitao);
            return true;
        }
    }
    return false;
}


/**
 * Remove permissão de administrador da sala.
 */
function removePermissaoAdministrador(capitao, nameChannel) {
    var room = roomByName(nameChannel);
    if (room.administradores.indexOf(capitao) !== -1) {
        rooms[rooms.indexOf(room)].administradores.splice(room.administradores.indexOf(capitao), 1);
        return true;
    }
    return false;
}


/*************************
 * REGISTRO DE MENSAGENS *
 ************************/


function retonaArray10Posicoes(mensagens) {
    if (mensagens.length > 10) {
        mensagens.splice(0, 1);
    }
    return mensagens;
}


function addMensagemCanal(novaMensagem, canal) {

    var mensagens = [];

    messages.forEach(function (message, index) {
        if (message.canal === canal) {
            mensagens = message.messages;
            mensagens.push(novaMensagem);
            mensagens = retonaArray10Posicoes(mensagens);
            messages[index].messages = mensagens;
        }
    });

    if (mensagens.length === 0) {
        var msg = [];
        msg.push(novaMensagem);
        messages.push({canal: canal, messages: msg});
    }
}


function retornaMensagensDoCanal(canal) {

    var mensagens = [];

    messages.forEach(function (message, index) {
        if (message.canal === canal) {
            mensagens = message.messages;
        }
    });
    return mensagens;
}


function permissaoDeEnvioDeMensagem(user) {
    return !(Math.abs(new Date() - user.getLastMessageTime()) < 10000
        && user.getNumeroDeMensagensEmCurtoEspacoDeTempo() > limiteDeMensagensSucessivasMax);
}


function usersOnline(users) {
    var usersOnline = [];
    users.forEach(function (user) {
        if (user.getAdm()) {
            return;
        }

        var encontrado = false;
        for (var i = 0; i < usersOnline.length; i++) {
            if (usersOnline[i].conta == user.getContaId()) {
                encontrado = true;
                break;
            }
        }
        if (!encontrado) {
            usersOnline.push({conta_id: user.getContaId(), capitao: user.getCapitao(), nameChannel: user.getChannel()});
        }
    });
    return usersOnline;
}


function changeUserFromRoom(user, channel) {
    var i;
    for (i = 0; i < rooms.length; i++) {
        if (rooms[i].name == user.getChannel()) {
            rooms[i].usersOnline -= 1;
        }

        if (channel && rooms[i].name == channel) {
            rooms[i].usersOnline += 1;
        }

        if (rooms[i].usersOnline <= 0 && rooms[i].name != 'Sala Comum') {
            rooms.splice(i, 1);
            i--;
        }
    }
    socket.sockets.emit('new room', rooms);
}