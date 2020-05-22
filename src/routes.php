<?php

require_once __DIR__ . "/../lib/database.php";
require_once __DIR__ . "/../lib/rest.php";
require_once __DIR__ . "/controllers/users.controller.php";
require_once __DIR__ . "/controllers/auth.controller.php";

$db =  new Database(); // injeta instancia unica do db 
$UserController = new UsersController($db);
$AuthController = new AuthController($db);

$rest->AddRouteGroup( [$AuthController, 'checkAuth'], function() use ($rest, $UserController, $AuthController) {
                       /// Regex | Methods | Callback
  $rest->addMiddleware( '/\/users\/*.?/', ['PUT', 'DELETE'],  [$UserController, 'isOwnerMiddleware']);
  
  $rest->addRoute('GET', '/users', [ $UserController, 'getAll'] );
  $rest->addRoute('POST', '/users', [ $UserController, 'create'] );
  $rest->addRoute('GET', '/users/:iduser', [ $UserController, 'getOne'] );
  $rest->addRoute('PUT', '/users/:iduser', [ $UserController, 'update'] );
  $rest->addRoute('DELETE', '/users/:iduser', [ $UserController, 'delete'] );

  $rest->addRoute('POST', '/users/:iduser/drink', [ $UserController, 'updateDrink'] );
});
  
$rest->addRoute('POST', '/login', [ $AuthController, 'login'] );
  
$rest->addRoute('GET', '/', function($req){ 
    return ['body' => 'Welcome'];
});