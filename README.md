<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Webchat Furia CS
## Sobre o projeto:
- Os usuários podem se cadastrar com email e senha ou username e senha 
(há uma verificação para não permitir que usuários diferentes cadastrem o mesmo email ou username).
- O primeiro usuário cadastrado (ao executar as migrations e seeder do banco de dados) é o *FURIA BOT*, que é o 
usuário owner (responsável) pela *FURIA TEAM*, um grupo de mensagens onde todos os usuários cadastrados podem enviar 
mensagens (parecido com o chat de lives do youtube);
- O *FURIA BOT* vai ser o primeiro a enviar mensagem para todos os usuários cadastrados, ele vai enviar uma mensagem de 
"Boas vindas", e deixar as opções disponíveis do chatbot;
- Todos os usuários cadastrados podem enviar mensagens um para o outro, e para a *FURIA TEAM*.
#### *Ideias de melhorias (novas features):*
- Será otimizada a coleta de preferências de cada usuário, de acordo com as informações trocadas por ele e o 
*FURIA BOT* nas mensagens: interações (essas preferencias são sobre o CS GO  e CS 2, jogos, partidas e produtos 
da FURIA, não vamos salvar dados confidenciais nem dados sensíveis sobre os usuários);
- Futuramente será implementado o login pela steam, trazendo os dados públicos do perfil do usuário na Steam;
- Será implementado futuramente IA no chatbot, para respostas otimizadas e personalizadas;
- Será implementado a criação de grupos (teams para troca de mensagem) pelos usuários;

### Tecnologias:
  - Laravel 12
  - PostgreSQL 15 (docker)
  - php 8.4
  - VueJS 3
  - InertiaJs

- ### Instalar dependencias e configurar banco de dados:
  - Composer: ``composer install`` 
  - Node: ``npm install``
  - Criar um arquivo .env a partir do .env.exaample: ``touch .env``
  - Migrations e seeder: ``php artisan migrate --seed``

- ### Executar projeto:  
  - Iniciar o servidor vite e artisan: ``composer run dev``
  - Iniciar os containers docker com banco de dados e network: ``docker compose up -d``
  - Iniciar o websocket com reverb: ``php artisan reverb:start --debug``

### Documentações utilizadas:
  - [Laravel reverb](https://laravel.com/docs/12.x/reverb)
  - [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum)
  - [Laravel Socialite](https://laravel.com/docs/12.x/socialite)
  - [Laravel Front-end](https://laravel.com/docs/12.x/frontend)
  - [Laravel Broadcasting](https://laravel.com/docs/12.x/broadcasting)
  - [Laravel Echo](https://github.com/laravel/echo)
  - [Pandascore]()

- ### Links úteis:
  - [Get SteamId by profile](https://steamid.xyz/)
