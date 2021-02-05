/**
 * Created by Luiz Eduardo on 29/05/2017.
 */

(function () {
    'use strict';

    angular.module('sugoi.chat').factory('FiltroLinguagemService',
        function () {

            var palavroes = [
                'anal', 'arrombado', 'bicha', 'buceta', 'butão', 'cacete', 'caralho', 'cu', 'fdp', 'porra', 'puta',
                'vadia', 'viado', 'xoxota'
            ];


            function removeCaracteresEspeciais(palavra) {
                var strSChar = "áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ";
                var strNoSChars = "aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC";
                var newStr = "";
                for (var i = 0; i < palavra.length; i++) {
                    if (strSChar.indexOf(palavra.charAt(i)) !== -1) {
                        newStr += strNoSChars.substr(strSChar.search(palavra.substr(i, 1)), 1);
                    } else {
                        newStr += palavra.substr(i, 1);
                    }
                }
                newStr = newStr.replace(/[^a-zA-Z 0-9]/g, '').toLowerCase();
                return newStr;
            }


            function censuraPalavrao(palavra, palavrao) {

                var censura = '#$%@&#';
                var palavraSuspeita = palavra.toLowerCase();

                if(removeCaracteresEspeciais(palavraSuspeita) === palavrao){

                    palavra = censura;
                }
                return palavra;
            }

            function filtroLinguagem(frase) {

                var fraseFinal = '';
                var palavras = frase.split(' ');

                palavras.forEach(function (palavra) {
                    palavroes.forEach(function (palavrao) {
                        palavra = censuraPalavrao(palavra, palavrao);
                    });
                    fraseFinal += palavra + ' ';
                });
                return fraseFinal.trim();
            }

            return {
                filtroLinguagem: filtroLinguagem
            };
        });
}());