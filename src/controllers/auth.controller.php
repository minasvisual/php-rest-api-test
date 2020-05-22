<?php
include_once __DIR__ . "/../repositories/auth.repository.php";

class AuthController {
   
   private $AuthRepository;
  
   public function __construct($db){
     $this->AuthRepository = new AuthRepository($db); // Dependencu injection
     
   }  
  
   public function login($req){
      [ 'body' => $body ] = $req;
     
      if( !$body['email'] || !$body['password'] ) throw new Exception("Required Email and Password data", 412);

      return [
        'json' => $this->AuthRepository->login($body)
      ];
   }
  
  
  public function checkAuth(){
     return $this->AuthRepository->checkAuth();
  }
  
  public function getLoggedUser($req){
      $user = $this->checkAuth();
    
      if( !isset($user) || !isset($user['iduser']) ) throw new Exception('Invalid user login', 403);
    
      return $user;
  }
  
}