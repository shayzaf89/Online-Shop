<?php 

session_start();

 $catalogNumber = isset($_POST['catalogNumber']) ? $_POST['catalogNumber'] : 0;
 $limitQuantity = isset($_POST['limitQuantity']) ? $_POST['limitQuantity'] : 0;
 $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : "";
// get the product id
//$catalogNumber = isset($_GET['catalogNumber']) ? $_GET['catalogNumber'] : 1;
//$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : "";

// make quantity a minimum of 1
$quantity=$quantity<=0 ? 1 : $quantity;
 
// connect to database
include 'config/database.php';
 
// include object
include_once "objects/CartItem.php";
include_once "objects/product.php";
 
// get database connection
$database = new database();
$db = $database->getConnection();
 
// initialize objects
$cart_item = new CartItem($db);
$product=new Product($db);
 
// set cart item values
//$cart_item->userId=1; // default to '1' because not have logged in user
$cart_item->userId=$_SESSION["userId"];
$cart_item->catalogNumber=$catalogNumber;
$cart_item->quantity=$quantity;

/* get the quantity in stock*/
$product->catalogNumber=$catalogNumber;
$product->getProductsBycatalogNumber();

echo "<br>quantityInStock ".$product->quantity;
echo "<br>quantity ".$quantity;
$st=$product->quantity;
echo "<br>Stock ".$st;
if($st<$quantity){ 
    header("Location:cartProduct.php?action=bigerThanStock"); 
}
/* check if choosen quantity biger then limit quantity*/
else if($limitQuantity<$quantity && $limitQuantity>0){
    header("Location:cartProduct.php?action=limitQuantity");
}
else {
// add to cart
    if($cart_item->updateCart()){
        // redirect to product list and tell the user it was added to cart
        header("Location:cartProduct.php?action=updated");
        //echo "<br>updated";
    }else{
        //echo "<br>unable to update";
        header("Location:cartProduct.php?action=unable_to_update");
    }

}
?>