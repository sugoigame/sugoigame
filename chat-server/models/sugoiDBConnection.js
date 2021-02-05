/**
 * Created by Luiz Eduardo on 21/05/2017.
 */

var SugoiDB = function () {

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
    // Executa Query
    //
    runQuery = function (sql, codUser, callback) {
        connection.query(sql, codUser, function (err, rows, fields) {
            if (err) {
                console.error(sql);
                console.error(err);
            }
            connection.end();
            callback(err, rows, fields);
        });
    };

    //
    // PEGANDO DADOS DO USUÁRIO NO BANCO.
    //
    buscarUser = function (codUser, callback) {
        var sql =
            'SELECT ' +
            'conta.conta_id, ' +
            'conta.cookie as token, ' +
            'usuarios.tripulacao as tripulacao, ' +
            'usuarios.reputacao as reputacao, ' +
            'usuarios.bandeira as bandeira, ' +
            'usuarios.faccao as faccao, ' +
            'usuarios.adm as adm, ' +
            'pers.nome as capitao, ' +
            'pers.img as capitao_img, ' +
            'pers.skin_r as capitao_skin_rosto, ' +
            'pers.skin_c as capitao_skin_corpo, ' +
            'pers.lvl as capitao_lvl, ' +
            'pers.fama_ameaca as fama_ameaca, ' +
            'IF (pers.sexo = 0, titulo.nome, titulo.nome_f) as capitao_titulo, ' +
            '(SELECT max(allpers.lvl) as lvl FROM tb_personagens allpers WHERE allpers.id = usuarios.id) as nivel_mais_forte ' +
            'FROM tb_conta conta ' +
            'INNER JOIN tb_usuarios usuarios ON conta.tripulacao_id = usuarios.id ' +
            'INNER JOIN tb_personagens pers ON usuarios.cod_personagem = pers.cod ' +
            'LEFT JOIN tb_titulos titulo ON pers.titulo = titulo.cod_titulo ' +
            'WHERE conta.conta_id = ? LIMIT 1';

        connection.query(sql, codUser, function (err, rows) {
            if (err) {
                // console.error(sql);
                console.error(codUser);
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
        buscarUser: buscarUser,
        closeConnection: closeConnection
    }
};

module.exports = SugoiDB;
