<?php
 include_once __DIR__ . "/../repositories/users.repository.php";
 include_once __DIR__ . "/../repositories/users.repository.php";
 include_once __DIR__ . "/../repositories/auth.repository.php";
 include_once __DIR__ . "/../repositories/auth.repository.php";

class UsersController {
   
   private $UserRepository;
   private $AuthRepository;
  
   public function __construct($db){
     $this->UserRepository = new UsersRepository($db); // Dependencu injection
     $this->AuthRepository = new AuthRepository($db); // Dependencu injection
     
   }  
  
   public function getAll(){
      return [
        'json' => $this->UserRepository->getAll()
      ];
   }
  
   public function create($req){
        [ 'body' => $body ] = $req;
     
       try{
        if( !$body['name'] || !$body['email'] || !$body['password'] ) throw new Exception("Name, email and password are required", 412); 
         
        return [
          'json' => $this->UserRepository->create($body)
        ];
       }catch(Exception $e){
          return [ 'json' => ['message' => $e->getMessage() ], 'statusCode' => $e->getCode() ?? 403 ];
       }
   }
  
   public function getOne($req){
      [ 'params' => $params ] = $req;
     
      try{
        return [
          'json' => $this->UserRepository->getOne($params[0])
        ];
      }catch(Exception $e){
         return [ 'json' => ['message' => $e->getMessage() ], 'statusCode' => $e->getCode() ];
      }
   }
  
   public function update($req){
      [ 'body' => $body, 'params' => $params, 'user' => $user ] = $req;
     
      try{
          return [
            'json' => $this->UserRepository->update( $body, $params[0] )
          ];
      }catch(Exception $e){
         return [ 'json' => ['message' => $e->getMessage() ], 'statusCode' => 403 ];
      }
   }
  
   public function delete($req){
      [ 'params' => $params ] = $req;
     
      try{
        return [
          'json' => ['message' => $this->UserRepository->delete( $params[0] )]
        ];
      }catch(Exception $e){
         return [ 'json' => ['message' => $e->getMessage() ], 'statusCode' => 403 ];
      }
   }
  
   public function updateDrink($req){
      [ 'params' => $params, 'body' => $body ] = $req;
     
      try{
          return [
            'json' => $this->UserRepository->updateDrink( $body, $params[0] )
          ];
      }catch(Exception $e){
         return [ 'json' => ['message' => $e->getMessage() ], 'statusCode' => 403 ];
      }
   }
  
   
   public function isOwnerMiddleware($req){
      [ 'params' => $params, 'method'=>$method, 'route' => $route ] = $req;
      
      $user = $this->AuthRepository->checkAuth();
     
      if( $route == '/users/:iduser' && in_array($method, ['PUT','DELETE']) && $user['iduser'] !== $params[0] ) 
          throw new Exception("Action not allowed", 401);
     
      $req['user'] = $user;
      return $req;
   }
}