<?php

// get mysql database connection
 class database{

//    
private $host="****";
private $username="***";
private $password="*****";
private $dbname="shopdb";

public $conn;

public function getConnection(){

    $this->conn=null;

   
    $this->conn=new mysqli($this->host,$this->username,$this->password,$this->dbname);
    
  

    return $this->conn;
  }


}


?>