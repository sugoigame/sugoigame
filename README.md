# Sugoi Game

Este é o repositório oficial do www.sugoigame.com.br!

## Como funciona?

Qualquer pessoa pode fazer um fork desse projeto para modificar o jogo e abrir pull requests que se aprovados pela staff entrarão em produção.

O branch `prod` contém a versão estável do jogo que está rodando em produção, e o branch `main` contem a versão mais recente do que foi desenvolvido para o jogo e não está necessáriamente estável.

## Como colaborar?

Crie um fork desse projeto e ele será salvo na sua conta do github.

Após fazer suas alterações e comita-las no seu branch, você poderá abrir um pull request para esse repositório.

O pulll request será aprovado pela staff e poderá entrar em produção.

## Banco de dados

Os dumps do banco de dados do jogo são baixados por uma Action:

https://github.com/sugoigame/sugoigame/actions?query=workflow%3A%22Database+Dump%22

Basta abrir a execução mais recente do workflow e na sessão de "artifacts" você encontrará 2 artefatos: structure.sql e data.sql. Basta importa-los no seu banco MySQL para rodar o jogo.

