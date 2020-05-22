<?php
include_once __DIR__ . "/../models/User.php";
include_once __DIR__ . "/../repositories/drink.repository.php";

class UsersRepository {
    
   protected $User;
   protected $DrinkRepository;
   protected $AuthRepository;
  
   public function __construct($db){
     $this->User = new User($db);  
     $this->DrinkRepository = new DrinkRepository($db);
   }  
  
   public function getAll($selectArr = null, $where = null, $data = null){
     try{
       return $this->User->getAll($selectArr, $where, $data);
     }catch(Exception $e){
       throw $e;
     }
   }
  
   public function create($body){
      try{
        $users = $this->User->getAll(['iduser'], 'email  = :email', ['email' => $body['email'] ]);
        
        if( count($users) > 0 ) throw new Exception("User already exists", 412);
          
        $body['password'] = $this->setHash($body['password']);
        
        $user = $this->User->create($body);
          
        return $this->DrinkRepository->createDrink($user['iduser']);
          
      }catch(Exception $e){
        throw $e;
      }
   }
  
   public function getOne($id){
     try{
       return $this->DrinkRepository->getUserDrinkCounter($id);
     }catch(Exception $e){
       throw $e;
     }
   }
  
   public function update($body, $id){
      try{
          if( isset($body['password']) ) 
             $body['password'] = $this->setHash($body['password']);
    
          return $this->User->update($body, $id);
      }catch(Exception $e){
         throw $e;
      }
   }
  
   public function updateDrink($body, $id){
      try{
          $drink = $this->DrinkRepository->updateDrink([
            'drink_counter' => (int) $body['drink_ml']
          ], $id);
        
          return $drink;
      }catch(Exception $e){
         throw $e;
      }
   }
  
   public function delete($id){
      try{
        return $this->User->delete( $id );;
      }catch(Exception $e){
         throw $e;
      }
   }
  
  public function setHash($string){
     return md5($string);
  }
}