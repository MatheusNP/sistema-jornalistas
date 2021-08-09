# API RESTFul de Sistema de Jornalistas - Lumen + JWT

Este repositório consiste em uma API RESTFul desenvolvida com o micro-framework [Lumen](https://lumen.laravel.com/docs), disponibilizada pelo Laravel, em sua versão ^8.0.

Neste projeto, foi utilizado o [Eloquent](https://laravel.com/docs/8.x/eloquent), ORM nativo do próprio Laravel que é utilizado para realizar consultas e persistências ao banco de dados - neste projeto, usaremos o MySQL.

Quanto à autenticação de usuários, usaremos o [JWT](https://jwt-auth.readthedocs.io/en/develop/lumen-installation/), que irá autenticar o usuário através de seu e-mail e senha e, caso autenticado com sucesso, gerar uma token de acesso válida por 5 minutos. Quanto a TTL (tempo de vida) da token expirar, usuário não estará habilitado para acessar algumas rotas que necessitam de autenticação. A autenticação dessas rotas é verificada através do middleware Authenticator, também nativo do framework utilizado.

## Configurando Projeto
## 1. Instalando dependências do projeto
No terminal, deve ser executado o seguinte comando no diretório do projeto para baixar as dependencias das bibliotecas definidas em composer.json:

`php composer phar update`

## 2. Gerar a Secret Key do JWT
No terminal, deve ser executado o seguinte comando para gerar a secret key do JWT:

`php artisan jwt:secret`

Copie a key apresentada entre colchetes no retorno do comando e passe para o próximo passo.

## 3. Criando arquivo '.env'
Renomer o arquivo `.env.example` para `.env`. Esse será o arquivo que receberá algumas configurações importantes, como conexão com banco de dados e a secrete key do JWT.

## 4. Configurar arquivo '.env'
Preencha as variáveis de conexão com banco de dados (Mysql) nas variáveis com prefixo 'DB_'. Após isso, configure a variável 'JWT_SECRET' com a secret key gerada no passo 2.

## 5. Migrations das tabelas utilizadas
No terminal, deve ser executado o seguinte comando para criar as tabelas utilizadas no projeto (tenha certeza que a conexão configurada no arquivo '.env' está correta):

`php artisan migrate`

## Regras de Negócio - Entidades

- **`journalists` - Jornalistas**
     -	`id` - Primary Key
     -	`first_name` - varchar|required
     -	`last_name` - varchar|required
     -	`email` - varchar|required|email
     -	`password` - varchar|required|hash

- **`news` - Notícias**
    - `id` - Primary Key
    - `journalist_id` - required|Foreign key para journalists.id
    - `type_id` - required|Foreign key para types.id
    - `title` - varchar|required
    - `description` - varchar|required
    - `body` - varchar|required
    - `img_link` - varchar|nullable

- **`types` - Tipos de Notícias**
    - `id` - Primary Key
    - `journalist_id` - required|Foreign key para journalists.id
    - `name` - varchar|required

## Rotas

- **Jornalistas**
    - **`POST` `/api/register`** (rota para criação de novos jornalistas)
    - **`POST` `/api/login`** (rota para autenticação jornalistas)
    - **`->POST` `/api/me`** (rota que retorna os dados do jornalista, com sua senha ocultada)

- **Notícias**
    - **`->POST` `/api/news/create`** (Cria uma notícia)
    - **`->POST` `/api/news/update/{news_id}`** (Altera uma notícia do jornalista)
    - **`->POST` `/api/news/delete/{news_id}`** (Exclui uma notícia do jornalista)
    - **`->GET` `/api/news/me`** (Lista todas as notícias do jornalista)
    - **`->GET` `/api/news/type/{type_id}`** (Lista todas as notícias do jornalista por um tipo de notícia)

- **Tipos de Notícias**
    - **`->POST` `/api/type/create`** (Cria um novo tipo de notícia)
    - **`->POST` `/api/type/update/{type_id}`** (Altera um tipo de notícia do jornalista)
    - **`->POST` `/api/type/delete/{type_id}`** (Exclui um tipo de notícia do jornalista)
    - **`->GET` `/api/type/me`** (Lista todos os tipos notícias do jornalista)

Observação: as rotas marcadas com '->' indicam que estas necessitam enviar no header da requisição a propriedade `Authorization`, com o valor `bearer %JWT_TOKEN%`, onde %JWT_TOKEN%  deve ser substituído pela token gerada na rota de LOGIN do jornalista.

## Algumas exceções implementadas
- **NewsNotFoundException**: Lançada quando um jornalista tenta editar ou deletar uma notícia, mas o ID informado não é encontrado na tabela 'news'.
- **NewsFromAnotherJournalistException**: Lançada quando um jornalista tenta editar ou deletar uma notícia, mas esta pertence à outro jornalista.
- **TypeNotFoundException**: Lançada quando um jornalista tenta editar ou deletar um tipo de notícia, mas o ID informado não é encontrado na tabela 'types'.
- **TypeFromAnotherJournalistException**: Esta excessão é lançada quando um jornalista tenta editar ou deletar um tipo de notícia, mas este pertence à outro jornalista.
- **TypeAlreadyExistsException**: Esta excessão é lançada quando um jornalista tenta editar ou deletar um tipo de notícia, mas já existe um tipo de notícia com aquele nome cadastrado para o jornalista.
- **TypeWithNewsAssociatedException**: Esta excessão é lançada quando um jornalista tenta deletar um tipo de notícia, mas existem notícias associadas para o tipo de notícia informado.
