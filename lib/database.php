<?php

class Database extends PDO
{
	private $config;
  
	public function __construct()
	{
    $this->config = include __DIR__."/../config.php";
    $db = $this->config->db;
		parent::__construct($db['driver'].':host='.$db['host'].';dbname='.$db['database'], $db['username'], $db['password']);
	}
	
	public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC)
	{
      $sth = $this->prepare($sql);
      foreach ($array as $key => $value) {
        $sth->bindValue("$key", $value);
      }
      if(!$sth->execute()){
        $this->handleError();
        }
      else{
        return $sth->fetchAll($fetchMode);
      }
	}
	
	public function insert($table, $data)
	{
      ksort($data);

      $fieldNames = implode('`, `', array_keys($data));
      $fieldValues = ':' . implode(', :', array_keys($data));

      $sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");

      foreach ($data as $key => $value) {
        $sth->bindValue(":$key", $value);
      }

      if(!$sth->execute()){
        throw new Exception($sth->errorInfo());
        //print_r($sth->errorInfo());
      }else{
        return $this->lastInsertId();
      }
	}
	
	public function update($table, $data, $where)
	{
      ksort($data);

      $fieldDetails = NULL;
      foreach($data as $key=> $value) {
        $fieldDetails .= "`$key`=:$key,";
      }
      $fieldDetails = rtrim($fieldDetails, ',');

      $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");

      foreach ($data as $key => $value) {
        $sth->bindValue(":$key", $value);
      }

      if(!$sth->execute()){
        throw new Exception( json_encode($sth->errorInfo()) );
        //print_r($sth->errorInfo());
      }else{
        return true;
      }
	}
	
	public function delete($table, $where, $limit = 1)
	{
      $sth =  $this->prepare("DELETE FROM $table WHERE $where LIMIT $limit");

      if(!$sth->execute()){
        throw new Exception([ $sth->errorInfo() ]);
        //print_r($sth->errorInfo());
      }else{
        return true;
      }
	}
	
	/* count rows*/
	public function rowsCount($table){
			$sth = $this->prepare("SELECT * FROM ".$table);
			$sth->execute();
			return $sth -> rowCount(); 
	}
	
	/* error check */
	private function handleError()
	{
      if ($this->errorCode() != '00000')
      {
        if ($this->_errorLog == true)
        //Log::write($this->_errorLog, "Error: " . implode(',', $this->errorInfo()));
        echo json_encode($this->errorInfo());
        throw new Exception("Error: " . implode(',', $this->errorInfo()));
      }
	}
	
}