<?php 


$catalogNumber=isset($_GET["catalogNumber"]) ? $_GET["catalogNumber"] : "";

//connect to db
include "config/database.php";
//load object
include "objects/CartItem.php";

$database=new database();
$db=$database->getConnection();//create connection to db

$cart_item=new CartItem($db);// initial object cartItem type

$cart_item->userId=1;//default userId=1 beacuse not have logged in user
$cart_item->catalogNumber=$catalogNumber;
$cart_item->removeItem();


// redirect to product list and tell the user it was removed from cart
header('Location: cartProduct.php?action=removed&id=' . $id);


?>