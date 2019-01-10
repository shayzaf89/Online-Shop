<?php


class CartItem{

    //object properties   
    public $userId; 
    public $catalogNumber;
    public $quantity;
    public $email;
    public $phoneNumber;
    public $privateName;
    public $familyName;
    public $purchaseDateTIme;


    public $conn;
    private $table_name="cart_items";

    public function __construct($db){
        $this->conn=$db;
    }


    //check if item exists in the cart

    public function exists(){
    $cnt=0;
    $query="select count(*) as cnt from ".$this->table_name." where catalogNumber=? and userId=?";

    // preapre query statment
    $stmt=$this->conn->prepare($query);
    
    //  remove tags from the variable
    $this->catalogNumber=htmlspecialchars(strip_tags($this->catalogNumber));
    $this->userId=htmlspecialchars(strip_tags($this->userId));
     
    //bind param
    $stmt->bind_Param(ii,$this->catalogNumber,$this->userId);
    //$stmt->bindParam(":userId",$this->userId);

    //stmt execute
    $stmt->execute();

    $stmt->bind_result($count);
    
    while ($row2=$stmt->fetch()) {
        $cnt=$count;
         }

    if($cnt>0){return true;}//if cart item exists return true,else false
    return false;
    

    }

    public function countRecord(){

    $query="select count(*) as cnt from ".$this->table_name." where userId=?";

    $stmt=$this->conn->prepare($query);
    $this->userId=htmlspecialchars(strip_tags($this->userId));
    $stmt->bind_param(i,$this->userId);
    $stmt->execute();

    $stmt->bind_result($cnt);
    //$stmt->fetch();
    while ($row2=$stmt->fetch()) {
        $count=$cnt;
         }
         return $count;

    }


    // create cart item record
public function createitemInCart(){
 
    $queryMax="select max(id) as maxId from ".$this->table_name;
    /*$this->conn->query($queryMax);
    while($row = $result->fetch_assoc()) {$maxId=$row["maxId"];}*/
    $res=$this->conn->prepare($queryMax);
    $res->execute();
      /* bind result variables */
      $res->bind_result($maxId);

      while ($res->fetch()) {
  
          $max=$maxId;
           }
     

    // to get times-tamp for 'created' field
    $this->purchaseDateTIme=date('Y-m-d H:i:s');
    $maxId=$maxId+1;
    // query to insert cart item record
    $query = "insert into  ".$this->table_name. " (catalogNumber,quantity,userId,id) values (?,?,?,".$maxId.")";
 
    // prepare query 
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->catalogNumber=htmlspecialchars(strip_tags($this->catalogNumber));
    $this->quantity=htmlspecialchars(strip_tags($this->quantity));
    $this->userId=htmlspecialchars(strip_tags($this->userId));
 
    // bind values
    $stmt->bind_param(iii, $this->catalogNumber,$this->quantity,$this->userId);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

// get items in the cart
public function getItemsInCart(){
 

    $query= "SELECT p.catalogNumber, p.title, p.price, c.quantity, (c.quantity * p.price) AS total,(((1-(p.discountPerCent/100))*p.price)*c.quantity) as priceAfterDiscount,p.limitQuantity,p.discountPerCent FROM ".$this->table_name." as c
        left join products as p on p.catalogNumber=c.catalogNumber
        where c.userId=? and purchased=0";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->user_id=htmlspecialchars(strip_tags($this->user_id));
 
    // bind value
    $stmt->bind_param(i,$this->userId);
 
    // execute query
    $stmt->execute();
 
    // return values
    return $stmt;
  }


   public function updateCart(){

   $query="update ".$this->table_name." set quantity=? where userId=? and catalogNumber=?";
   $stmt=$this->conn->prepare($query);
   
   $this->quantity=htmlspecialchars(strip_tags($this->quantity));
   $this->catalogNumber=htmlspecialchars(strip_tags($this->catalogNumber));
   $this->userId=htmlspecialchars(strip_tags($this->userId));

   $stmt->bind_param(iii,$this->quantity,$this->userId,$this->catalogNumber);
   if($stmt->execute()){return true;}
   return false;
   }

   // remove item from cart by userId and catalogNumber
  public function removeItem(){
  
  $query="delete from ".$this->table_name." where userId=? and catalogNumber=?";

  $stmt=$this->conn->prepare($query);

  $this->catalogNumber=htmlspecialchars(strip_tags($this->catalogNumber));
  $this->userId=htmlspecialchars(strip_tags($this->userId));

  $stmt->bind_param(ii,$this->userId,$this->catalogNumber);

  if($stmt->execute()) return true;
  return false;


  }

    // delete all items from cart after place order
  public function removeItemsAfterCheckOut(){
   
  $setQuery="SET SQL_SAFE_UPDATES=0";
   $this->conn->query($setQuery);
  
 
   $query="delete from cart_items where userId=?";
   $stmt=$this->conn->prepare($query);
   $this->userId=htmlspecialchars(strip_tags($this->userId));
   $stmt->bind_param(i,$this->userId);
   if($stmt->execute()) {$flag=true;}
   else $flag=false;


   $setOneQuery="SET SQL_SAFE_UPDATES=1";
   $one=$this->conn->prepare($setOneQuery);
   $one->execute();

   return $flag;

  }



  public function getMaxUserId(){


    $queryMax="select max(userId) as maxUserId from ".$this->table_name;
    /*$this->conn->query($queryMax);
    while($row = $result->fetch_assoc()) {$maxId=$row["maxId"];}*/
    $res=$this->conn->prepare($queryMax);
    $res->execute();
      /* bind result variables */
      $res->bind_result($maxUserId);

      $res->fetch();
       return $maxUserId;

  }

  public function updateCartPurchased(){

    $query="update ".$this->table_name." set purchased=1 where userId=?";
    $stmt=$this->conn->prepare($query);
    
    $this->userId=htmlspecialchars(strip_tags($this->userId));
 
    $stmt->bind_param(i,$this->userId);
    if($stmt->execute()){return true;}
    return false;
    }
}



?>