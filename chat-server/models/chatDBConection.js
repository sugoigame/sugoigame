/**
 * Created by Luiz Eduardo on 21/05/2017.
 */

var ChatDB = function () {

    var util = require('util');
    var mysql = require('mysql');

    var connection = mysql.createPool({
        connectionLimit: 2,
        host: 'HOST',
        user: 'sugoigame',
        password: 'PASSWORD',
        database: 'sugoigame'
    });

    // var connection = mysql.createConnection({
    //     host: 'localhost',
    //     user: 'root',
    //     password: '',
    //     database: 'sugoigame3'
    // });


    //
    // Conecta com o banco de dados
    //
    openConnection = function () {
        connection.connect();
    };

    //
    // PEGANDO DADOS DO USUÁRIO NO BANCO.
    //
    insertMessage = function (conta_id, capitao, canal, message, callback) {

        var sql = util.format('INSERT INTO chat (conta_id, capitao, canal, message) VALUES (\'%s\', \'%s\', \'%s\', \'%s\')', conta_id, capitao, canal, message);

        // var sql = 'INSERT INTO `chat` (`conta_id`, `capitao`, `message`) VALUES (?, ?, ?);';

        connection.query(sql, function (err, rows) {
            if (err) {
                console.error(sql);
            }
            connection.end();
            return callback(err, rows);
        });
    };

    //
    // CLOSE CONNECTION
    //
    closeConnection = function (model, callback) {
        connection.end();
    };

    // Definindo as variaves que serão acessadas
    return {
        insertMessage: insertMessage,
        closeConnection: closeConnection
    }
};

module.exports = ChatDB;
