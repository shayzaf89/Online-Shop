<?php 

session_start();

//  database object
include 'config/database.php';
 
// include objects
include_once 'objects/product.php';
include_once 'objects/CartItem.php';

            /* set error message */
$emailErr = isset($_GET['emailErr']) ? $_GET['emailErr'] : "";
$firstNameErr=isset($_GET['firstNameErr'] )? $_GET['firstNameErr'] :"";
$lastNameErr=isset($_GET['lastNameErr'] )? $_GET['lastNameErr'] :"";
$phoneNumberErr=isset($_GET['phoneNumberErr']) ? $_GET['phoneNumberErr'] :"";
 
// get database connection
$database = new database();
$db = $database->getConnection();
 
// initialize objects

$product = new Product($db);
$cart_item = new CartItem($db);
 

// set page title
$page_title="Checkout";
 
// include head 
include 'head.php';
 

// $cart_count variable is initial in navigation.php
if($cart_count>0){
 
    //$cart_item->userId=1;//default is 1 because not logged in user
    $cart_item->userId=$_SESSION["userId"];
    $stmt=$cart_item->getItemsInCart();
    $stmt->bind_result($catalogNumber,$title,$price,$quantity,$total,$priceAfterDiscount,$limitQuantity,$discountPerCent);

    $sumTotal=0;
    $item_count=0;
    $sub_total=0;
    while ($row = $stmt->fetch()){

        $sub_total=$priceAfterDiscount;//set the price 
 
        echo "<div class='cart-row'>";
            echo "<div class='col-md-8'>";
 
                echo "<div class='product-name m-b-10px'><h4>{$title}</h4></div>";
                echo $quantity>1 ? "<div>{$quantity} items</div>" : "<div>{$quantity} item</div>";
 
            echo "</div>";
 
            echo "<div class='col-md-4'>";
                echo "<h4>&#36;" . number_format($sub_total, 2, '.', ',') . "</h4>";
            echo "</div>";
        echo "</div>";
 
        $item_count += $quantity;
        $sumTotal+=$sub_total;

        $orderDetails[]=array(

            'catalogNumber'=>$catalogNumber,
            'title'=> $title,
            'quantity'=> $quantity,
            'sub_total'=> $sub_total        
            );
    }
    $_SESSION['orderDetails']=$orderDetails;
    //$_SESSION['userId']=$cart_item->userId;

    echo "<form class='form-horizontal' name='myForm'   action='placeOrder.php' method='post'>";
        
    
    //Email
      echo "<div class='input-group margin-bottom-sm' style='width:40%;margin-top:10px;'>
      <span class='input-group-addon'><i class='fa fa-envelope-o fa-fw'></i></span>
         <input type='email' class='form-control' id='email'   placeholder='Enter email*' name='email'> </div>";
        if($emailErr<>""){ echo "<span style='color:red;'>* {$emailErr}</span>";}
    //First Name    
    echo "<div class='input-group margin-bottom-sm' style='width:40%;margin-top:5px;'>
    <span class='input-group-addon'><i class='fa fa-user fa-fw'></i></span>
    <input type='text' class='form-control' id='fname' placeholder='Enter First Name*'  name='fname' >  </div>";
    if($firstNameErr<>""){ echo "<span style='color:red;'>* {$firstNameErr}</span>";}
    
    //Last Name     
    echo "<div class='input-group margin-bottom-sm' style='width:40%;margin-top:5px;'>
      <span class='input-group-addon'><i class='fa fa-users fa-fw'></i></span>
    <input type='text' class='form-control' id='lname' placeholder='Enter Last Name*' name='lname'>  </div>";
    if($lastNameErr<>""){ echo "<span style='color:red;'>* {$lastNameErr}</span>";}
    
    //Phone Number     
    echo "<div class='input-group margin-bottom-sm' style='width:40%;margin-top:5px;'>
      <span class='input-group-addon'><i class='fa fa-users fa-fw'></i></span>
    <input type='text' class='form-control' id='phoneNumber' placeholder='Enter Phone Number*' name='phoneNumber'>  </div>";
    if($phoneNumberErr<>""){ echo "<span style='color:red;'>* {$phoneNumberErr}</span>";}
    
    echo "<div class='col-md-12 text-align-center'>";
        echo "<div class='cart-row'>";
            if($item_count>1){
                echo "<h4 class='m-b-10px'>Total ({$item_count} items)</h4>";
            }else{
                echo "<h4 class='m-b-10px'>Total ({$item_count} item)</h4>";
            }
            echo "<h4>&#36;" . number_format( $sumTotal, 2, '.', ',') . "</h4>";
    
           echo "<button type='submit' href='placeOrder.php' name='submit'  class='btn btn-lg btn-success m-b-10px'>";
                echo "<span class='glyphicon glyphicon-shopping-cart'></span> Place Order";
            echo "</button>";
            
           /* echo "<a href='placeOrder.php' class='btn btn-lg btn-success m-b-10px' >";
                echo "<span class='glyphicon glyphicon-shopping-cart'></span> Place Order";
            echo "</a>";*/
           
        echo "</div>";
    echo "</div>";


 
}
 
else{
    if($_SESSION["userId"]<>0){$_SESSION["userId"]=$cart_item->getMaxUserId()+1;}
    echo "<div class='col-md-12'>";
        echo "<div class='alert alert-danger'>";
            echo "No products found in your cart!";
        echo "</div>";
    echo "</div>";
}
 
include 'footer.php';



?>