# Desafio PHP FullStack

### Proposta
Criar uma aplicação em PHP para cadastro de usuários utilizando o padrão de arquitetura MVC (model–view–controller), com dois níveis de acesso: Admin / Cliente.

## Pré-requisitos
 - PHP >= 7.3
 - Composer
 - npm

## Instalação
1. Para instalar as dependências do projeto, execute o comando `composer install && composer update`;
2. Laravel migrations e seeders `php artisan migrate:fresh --seed`.

## Deployment
 1. Executar o comando `php artisan serve` para iniciar o servidor WEB;
 2. Executar o comando `php artisan serve --port=8088` para iniciar um novo servidor em paralelo (API);
 3. Use o servidor WEB http://localhost:8000 para acessar o sistema;

### Usuários para teste
 - **Perfil Admin**
``E-mail: admin@teste.com``
``Senha: 123456``

 - **Perfil Cliente**
``E-mail: client@teste.com``
``Senha: 123456``
