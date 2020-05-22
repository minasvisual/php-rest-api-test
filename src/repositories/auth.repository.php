<?php
include_once __DIR__ . "/../repositories/users.repository.php";
include_once __DIR__ . "/../repositories/drink.repository.php";

use \Firebase\JWT\JWT;

class AuthRepository {
   
   private $keyHash = "example_key";
   private $UserRepository;
   private $DrinkRepository;
  
   public function __construct($db){
     $this->UserRepository = new UsersRepository($db); // Dependencu injection
     $this->DrinkRepository = new DrinkRepository($db); // Dependencu injection
   }  
  
   public function login($body){
      [ 'email' => $email, 'password' => $password ] = $body; 
      try{
        $sttm = $this->UserRepository->getAll(
          ['*'],  // select 
          "email = :email", // where
          [':email' => $email]
        );
       
        if( !$sttm || count($sttm) === 0 ) throw new Exception("User not exists", 404);
        $user = $sttm[0];

        if( $user['password'] !== md5($password) ) throw new Exception("Invalid login data, try again!", 412);
        unset($user['password']);
        
        $auth = $this->DrinkRepository->getUserDrinkCounter($user['iduser']);
        $auth['token'] = $this->generateToken($user);
        return $auth;
      }catch(Exception $e){
        throw $e;
      }
   }
  
   public function checkAuth(){
      try{
        $headers = apache_request_headers();
    
        if( !isset($headers['Authorization']) || empty($headers['Authorization']) ) return false;
          
        $jwt = (array) $this->decodeToken($headers['Authorization']);
        ['data'=> $data] = $jwt;
        // TODO VERIFICAR VALIDADE TO TOKEN ETC E TAL
        if( !isset($data) || !isset($data->iduser) ) return false;
          
        return (array) $data;
      }catch(Exception $e){
        throw $e;
      }
   }
  
   private function generateToken($data){
      $payload = [
          "iss" => $_SERVER['HTTP_HOST'],
          "iat" => strtotime('now'),
          "nbf" => strtotime('now'),
          "data" => $data
      ];

      return JWT::encode($payload, $this->keyHash);
   }
  
  private function decodeToken($token){
    try{
      return JWT::decode($token, $this->keyHash, ['HS256'] );
    }catch(Exception $e){
      throw $e;
    }
  }
  
  
}