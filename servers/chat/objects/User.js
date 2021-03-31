/**
 * Created by Luiz Eduardo on 16/05/2017.
 */

/******************
 ** User CLASS
 *****************/

var User = function (dados, dadosDb) {


    var idUser = dados.idUser;
    var channel = dados.channelName;

    var lastMessageTime = new Date();
    var numeroDeMensagensEmCurtoEspacoDeTempo = 0;

    var conta_id = dadosDb.conta_id;
    var adm = dadosDb.adm;
    var token = dadosDb.token;
    var tripulacao = dadosDb.tripulacao;
    var reputacao = dadosDb.reputacao;
    var bandeira = dadosDb.bandeira;
    var faccao = dadosDb.faccao;
    var capitao = dadosDb.capitao;
    var capitaoImg = dadosDb.capitao_img;
    var capitaoSkin_rosto = dadosDb.capitao_skin_rosto;
    var capitaoSkin_corpo = dadosDb.capitao_skin_corpo;
    var capitaoLvl = dadosDb.capitao_lvl;
    var famaAmeaca = dadosDb.fama_ameaca;
    var capitaoTitulo = dadosDb.capitao_titulo;
    var nivelMaisForte = dadosDb.nivel_mais_forte;

    // Getters

    var getId = function () {
        return idUser;
    };

    var getContaId = function () {
        return conta_id;
    };

    var getAdm = function () {
        return adm;
    };

    var getChannel = function () {
        return channel;
    };

    var getUserToken = function () {
        return idUser;
    };

    var getCapitao = function () {
        return capitao;
    };

    var getCapitaoImg = function () {
        return capitaoImg;
    };

    var getCapitaoSkin_rosto = function () {
        return capitaoSkin_rosto;
    };

    var getBandeira = function () {
        return bandeira;
    };

    var getCapitaoTitulo = function () {
        return capitaoTitulo;
    };

    var getTripulacao = function () {
        return tripulacao;
    };

    var getReputacao = function () {
        return reputacao;
    };

    var getFaccao = function () {
        return faccao;
    };

    var getNivelMaisForte = function () {
        return nivelMaisForte;
    };

    var getLastMessageTime = function () {
        return lastMessageTime;
    };

    var getNumeroDeMensagensEmCurtoEspacoDeTempo = function () {
        return numeroDeMensagensEmCurtoEspacoDeTempo;
    };


    // Setters

    var setId = function (id) {
        idUser = id;
    };

    var setContaId = function (id_conta) {
        conta_id = id_conta;
    };

    var setAdm = function (user_adm) {
        adm = user_adm;
    };

    var setChannel = function (chann) {
        channel = chann;
    };

    var setUserToken = function (user_token) {
        token = user_token;
    };

    var setCapitao = function (user_capitao) {
        capitao = user_capitao;
    };

    var setCapitaoImg = function (user_capitaoImg) {
        capitaoImg = user_capitaoImg;
    };

    var setCapitaoSkin_rosto = function (user_capitao_rosto) {
        capitaoSkin_rosto = user_capitao_rosto;
    };

    var setBandeira = function (user_bandeira) {
        bandeira = user_bandeira;
    };

    var setCapitaoTitulo = function (user_capitaoTitulo) {
        capitaoTitulo = user_capitaoTitulo;
    };

    var setLastMessageTime = function (user_lastMessageTime) {
        lastMessageTime = user_lastMessageTime;
    };

    var setNumeroDeMensagensEmCurtoEspacoDeTempo = function (user_numeroDeMensagensEmCurtoEspacoDeTempo) {
        numeroDeMensagensEmCurtoEspacoDeTempo = user_numeroDeMensagensEmCurtoEspacoDeTempo;
    };


    // Definindo as variaves que serão acessadas
    return {
        getId: getId,
        getContaId: getContaId,
        getAdm: getAdm,
        getChannel: getChannel,
        getUserToken: getUserToken,
        getCapitao: getCapitao,
        getCapitaoImg: getCapitaoImg,
        getCapitaoSkin_rosto: getCapitaoSkin_rosto,
        getBandeira: getBandeira,
        getCapitaoTitulo: getCapitaoTitulo,
        getTripulacao: getTripulacao,
        getFaccao: getFaccao,
        getReputacao: getReputacao,
        getNivelMaisForte: getNivelMaisForte,
        getLastMessageTime: getLastMessageTime,
        getNumeroDeMensagensEmCurtoEspacoDeTempo: getNumeroDeMensagensEmCurtoEspacoDeTempo,

        setId: setId,
        setContaId: setContaId,
        setAdm: setAdm,
        setChannel: setChannel,
        setUserToken: setUserToken,
        setCapitao: setCapitao,
        setCapitaoImg: setCapitaoImg,
        setCapitaoSkin_rosto: setCapitaoSkin_rosto,
        setBandeira: setBandeira,
        setCapitaoTitulo: setCapitaoTitulo,
        setLastMessageTime: setLastMessageTime,
        setNumeroDeMensagensEmCurtoEspacoDeTempo: setNumeroDeMensagensEmCurtoEspacoDeTempo
    }
};

module.exports = User;