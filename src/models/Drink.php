<?php

class Drink {
  
    protected $db;
      
    public function __construct($db){
      $this->db = $db;
    }
  
   public function getAll($select = null, $where = 'where 1', $data = []){
      $select = implode( ",", $select ?? ['D.iduser', 'D.drink_counter', 'U.name', 'U.email'] );
      try{
        return $this->db->select("SELECT $select FROM drink_counter D INNER JOIN users U ON U.iduser = D.iduser  WHERE $where", $data);
      }catch(Exception $e){
        throw $e;
      }
   }
  
   public function create($body){
      try{
       $sttm = $this->db->insert('drink_counter', $body);
        
       if( !isset($sttm) || (int) $sttm == 0 ) throw new Exception("Error to insert new Drink ".$sttm, 403);
         
       return $this->getOne($sttm);
      }catch(Exception $e){
        throw $e;
      }
   }
  
   public function getOne($id, $select = null){
      $select = implode( ",", $select ?? ['D.iduser', 'D.drink_counter', 'U.name', 'U.email'] );
     
      try{
         $sttm = $this->db->select("SELECT $select FROM drink_counter D INNER JOIN users U ON U.iduser = D.iduser WHERE D.iduser = :iduser limit 1", [ ':iduser' => $id ] );
         if( !isset($sttm) || count($sttm) === 0 ){
           throw new Exception('Drink Not found', 404);
         }
        
         return $sttm[0];
      }catch(Exception $e){
         throw $e;
      }
   }
  
   public function update($body, $id){
      try{
          $sttm = $this->db->update('drink_counter', $body,  'iduser = '.$id );
        
          if( !$sttm ) throw new Exception("Not found");
        
          return $this->getOne($id);
      }catch(Exception $e){
         throw $e;
      }
   }
  
}