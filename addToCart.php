<?php 

//session_start();

// parameters
$catalogNumber = isset($_GET['id']) ? $_GET['id'] : "";
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 1;
 
// make quantity a minimum of 1
$quantity=$quantity<=0 ? 1 : $quantity;
 
// connect to database
include 'config/database.php';
 
// include object
include_once 'objects/CartItem.php';
 
// get database connection
$database = new database();
$db = $database->getConnection();
 
// initialize objects
$cart_item = new CartItem($db);
 
// set cart item values
$cart_item->userId=1; // we default to '1' because we do not have logged in user
//$cart_item->userId=$_SESSION["userId"];
$cart_item->catalogNumber=$catalogNumber;
$cart_item->quantity=$quantity;
 
 
 $flag2=$cart_item->exists();

 
 
// check if the item is in the cart, if it is, do not add
if($flag2){
    // redirect to product list and tell the user it was added to cart
   //echo "<br>exists";
    header("Location: cartProduct.php?action=exists");
}  
// else, add the item to cart
else{
    //echo "<br>not exists";

    $flag=$cart_item->createitemInCart();//create new item in the cart
    
    //echo "<br>flag ".$flag;
    if($flag){
        // redirect to product list and tell the user it was added to cart
        //echo "create item ";
       header("Location: item.php?catalogNumber={$catalogNumber}&action=added");
    }else{
      //echo "dont create item ";
        header("Location: item.php?catalogNumber={$catalogNumber}&action=unable_to_add");
    }
}



?>