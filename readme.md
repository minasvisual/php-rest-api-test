
# Requirements
- php 7.1
- composer package manager

# Installation
- Run on CMD: composer install
- Access localhost/path/of/app/folder/

# api
### POST /users/ (criar um novo usuário) 
Body:
- name *
- email *
- password *

### POST /login (autenticar com um usuário)
Body:
- email *
- password *

Response:
- token
- iduser
- email
- name
- drink_counter

### GET /users/:iduser (obter um usuário)
Response:
- iduser
- name
- email
- drink_counter

Headers: Auhtorization: <login token>

### GET /users/ (obter a lista de usuários)
Response Array:
- iduser
- name
- email
- drink_counter

Headers: Auhtorization: <login token>

### PUT /users/:iduser (editar o seu próprio usuário)
Body:
- email
- name
- password

Headers: Auhtorization: <login token>

### DELETE /users/:iduser (apagar o seu próprio usuário)
Response:
- OK

Headers: Auhtorization: <login token>

### POST /users/:iduser/drink (incrementar o contador de quantas vezes bebeu água)
Body:
- drink_ml (int) 

Respoonse:
- iduser
- email
- name
- drink_counter

Headers: Auhtorization: <login token>
