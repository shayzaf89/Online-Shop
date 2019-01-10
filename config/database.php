<?php

// get mysql database connection
 class database{

//    
private $host="";
private $username="***";
private $password="*****";
private $dbname="shopdb";

public $conn;

public function getConnection(){

    
  try { 
      $this->conn=null;
      $this->conn=new mysqli($this->host,$this->username,$this->password,$this->dbname);
       
   } catch (mysqli_sql_exception $e) { 
      throw $e; 
   } 
   
    return $this->conn;
  }


}


?>