<?php 

class systemManager {

   public $email;
   public $firstName;
   public $lastname;

   public $conn;
   private $table_name="system_manager";
  
   public function __construct($db){
    $this->conn=$db;
    }

    public function getDetails(){

      $query="select * from ".$this->table_name;
      $stmt=$this->conn->prepare($query);
       
       $stmt->execute();
      //$stmt->bind_result($email,$firstName,$lasName);
return $stmt;
    }
    



}



?>