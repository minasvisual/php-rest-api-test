<?php
include_once __DIR__ . "/../models/Drink.php";

class DrinkRepository {
    
   protected $Drink;
  
   public function __construct($db){
     $this->Drink = new Drink($db);  
   }  
  
   public function getUserDrinkCounter($id){
       return $this->Drink->getOne($id);
   }
  
   public function createDrink($id, $drink=0){
       return $this->Drink->create([
         'iduser' => $id,
         'drink_counter' => $drink
       ]);
   }
  
   public function updateDrink($body, $id){
       [$drink] = $this->Drink->getAll(['D.iduser', 'D.drink_counter'], 'D.iduser = :iduser', ['iduser'=>$id]);
       if( !isset($drink) ) throw new Exception("Drink to update not found", 404);
         
       $drink['drink_counter'] += $body['drink_counter']; 
       return $this->Drink->update($drink, $id);
   }
  
}