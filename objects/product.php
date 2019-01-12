<?php 

class Product{


//object proprties

public $catalogNumber;
public $description;
public $title;
public $quantity;
public $price;
public $createdDateTime;
public $discountPerCent;
public $limitQuantity;

// connection to database and table name
public $conn;
private $table_name="products";


    public function __construct($db){
    $this->conn=$db;
    }


  public function getProducts($record_per_page,$from_record_num) {

    //example ?=15, ?=10 -return only 10 records, start on record 16 (OFFSET 15)
     $query="select * from ".$this->table_name." where quantity>0 order by createdDateTime desc LIMIT ?, ?";
     
     // prepare and bind the query
     $stmt=$this->conn->prepare($query);
     $stmt->bind_param(ii,$from_record_num,$record_per_page);
     //$stmt->bind_param(i,$record_per_page);

     // execute query
     $stmt->execute();
     
     
     return $stmt;
  }

  public function CountRecord(){
     
    // count the number of records
    $query="select count(*) as cnt from ".$this->table_name;

    // prepare the query
    $stmt=$this->conn->prepare($query);

    $stmt->execute();
        /* bind result variables */
    $stmt->bind_result($count);

    while ($row2=$stmt->fetch()) {

        $cnt=$count;
         }
    return $cnt;

  }

  public function getProductByIds($ids){
    // for example where id in (1,2,3....), the count($ids)-1 is multiplier
    $ids_arr = str_repeat('?,', count($ids) - 1) . '?';
 
    // query to select products
    $query = "SELECT catalogNumber,title,price FROM " . $this->table_name . " WHERE id IN ({$ids_arr}) ORDER BY title";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute($ids);
 
    // return values from database
    return $stmt;
}

// used when filling up the update product form
public function oneItem(){
 
  // query to select single record
  $query = "SELECT title,description,price FROM " .$this->table_name. " WHERE catalogNumber=? LIMIT 0,1";

  // prepare query statement
  $stmt = $this->conn->prepare($query);

  // sanitize
  $this->catalogNumber=htmlspecialchars(strip_tags($this->catalogNumber));

  // bind product id value
  $stmt->bind_param(i,$this->catalogNumber);

  // execute query
  $stmt->execute();
  
  $stmt->bind_result($title,$description,$price);
  // get row values
  $stmt->fetch();

  // assign retrieved value to object properties
  $this->title = $title;
  $this->description = $description;
  $this->price = $price;
}



public function getProductsBycatalogNumber(){
  
   $query="select quantity from ".$this->table_name." where catalogNumber=?";
   
   // prepare and bind the query
   $stmt=$this->conn->prepare($query);
    // sanitize
   $this->catalogNumber=htmlspecialchars(strip_tags($this->catalogNumber));
   $stmt->bind_param(i,$this->catalogNumber);

   // execute query
   $stmt->execute();
   $stmt->bind_result($quantity);
   $stmt->fetch();
   
   $this->quantity=$quantity;
}

    /* update the quantity after place order*/
public function updateQuantityBycatalogNumber(){
  $updated=$this->quantity;
  $query="update ".$this->table_name." set quantity=(quantity-?) where catalogNumber=?";
  
  // prepare and bind the query
  $stmt=$this->conn->prepare($query);
   
  $this->catalogNumber=htmlspecialchars(strip_tags($this->catalogNumber));
  $updated=htmlspecialchars(strip_tags($updated));
  $stmt->bind_param(ii,$updated,$this->catalogNumber);

  // execute query
  $stmt->execute();

}


}



?>