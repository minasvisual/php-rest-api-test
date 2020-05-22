<?php

class User {
  
    protected $db;
      
    public function __construct($db){
      $this->db = $db;
    }
  
   public function getAll($select = null, $where = '1', $data = []){
      $select = implode( ",", $select ?? ['iduser', 'name', 'email'] );
      $data = $data ?? [];
      $where = $where ?? '1';
      try{
        return $this->db->select("SELECT $select FROM users WHERE $where", $data);
      }catch(Exception $e){
        throw $e;
      }
   }
  
   public function create($body){
      try{
       $sttm = $this->db->insert('users', $body);
        
       if( !isset($sttm) || (int) $sttm == 0 ) throw new Exception("Error to insert User"); 
         
       return $this->getOne($sttm);
      }catch(Exception $e){
        throw $e;
      }
   }
  
   public function getOne($id, $select = null){
      $select = implode(', ', $select ?? ['iduser', 'name', 'email']);
     
      try{
         $sttm = $this->db->select("Select $select from users where iduser = :iduser limit 1", [ 'iduser' => $id ] );
        
         if( !isset($sttm[0]) ){
           throw new Exception('Not found', 404);
         }
         
         return $sttm[0];
      }catch(Exception $e){
         throw $e;
      }
   }
  
   public function update($body, $id){
      try{
          $sttm = $this->db->update('users', $body,  'iduser = '.$id );
        
          if( !$sttm ) throw new Exception("Not found");
        
          return $this->getOne($id);
      }catch(Exception $e){
         throw $e;
      }
   }
  
   public function delete($id){
      try{
        $sttm = $this->db->delete('users',  'iduser = '.$id );

       if( !$sttm ){
         throw new Exception('Not found', 404);
       }
        
        return true;
      }catch(Exception $e){
         throw $e;
      }
   }
}