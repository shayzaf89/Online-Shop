<?php 

 session_start();
// connect to db
include 'config/database.php';
// include objects
include_once 'objects/product.php';
include_once 'objects/CartItem.php';


// get database connection
$database = new database();
$db = $database->getConnection();
 
// initialize objects
$product = new Product($db);
$cart_item = new CartItem($db);
 
// set page title
$page_title="Cart";
 
// include  header 
include 'head.php';
 
$action = isset($_GET['action']) ? $_GET['action'] : "";
 
echo "<div class='col-md-12'>";
    if($action=='removed'){
        echo "<div class='alert alert-info'>";
            echo "Product was removed from your cart!";
        echo "</div>";
    }
 
    else if($action=='quantity_updated'){
        echo "<div class='alert alert-info'>";
            echo "Product quantity was updated!";
        echo "</div>";
    }
 
    else if($action=='exists'){
        echo "<div class='alert alert-info'>";
            echo "Product already exists in your cart!";
        echo "</div>";
    }
 
    else if($action=='cart_emptied'){
        echo "<div class='alert alert-info'>";
            echo "Cart was emptied.";
        echo "</div>";
    }
 
    else if($action=='updated'){
        echo "<div class='alert alert-info'>";
            echo "Quantity was updated.";
        echo "</div>";
    }
 
    else if($action=='unable_to_update'){
        echo "<div class='alert alert-danger'>";
            echo "Unable to update quantity.";
        echo "</div>";
    }
    else if($action=='limitQuantity'){
        echo "<div class='alert alert-danger'>";
        echo "Unable to update : Chosen quantity greater than the quantity allowed.";
        echo "</div>";
       
    }
    else if($action=='bigerThanStock'){
        echo "<div class='alert alert-danger'>";
        echo "Unable to update : Chosen quantity greater than the quantity in stock allowed.";
        echo "</div>";
       
    }

  
echo "</div>";

 // $cart_count variable is initialized in navigation.php
if($cart_count>0){
   
    //$cart_item->userId=1; //deafault 1 
    $cart_item->userId=$_SESSION["userId"];
    $stmt=$cart_item->getItemsInCart();
    $stmt->bind_result($catalogNumber,$title,$price,$quantity,$total,$priceAfterDiscount,$limitQuantity,$discountPerCent);
   
     
    $sumTotal=0;
    $item_count=0;


    // display the cart items
    while ($row=$stmt->fetch()){

         //echo "discountPerCent ".$discountPerCent;
         if($discountPerCent>0){
            $sub_total=$priceAfterDiscount;//set the value price after discount
         }else $sub_total=$total;
        //$sub_total=$price*$quantity;
 
        echo "<div class='cart-row'>";
            echo "<div class='col-md-8'>";
                // product title
                echo "<div class='product-name m-b-10px'>";
                    echo "<h4>{$title}</h4>";
                echo "</div>";
                if($limitQuantity>0){
                    echo "<div class='product-name m-b-10px'>";
                    echo "<b style='color:orange;'>Limit Quantity In Discount {$limitQuantity}</b>";
                    echo "</div>";
                }
               
                // update quantity
                echo "<form class='update-quantity-form' action='updateQuantity.php' method='POST'>";
                    //echo "<div class='product-id' style='display:none;'>{$catalogNumber}</div>";
                    echo "<input type='text' name='catalogNumber' value='{$catalogNumber}' class='form-control cart-quantity' style='display:none;' />";
                    echo "<input type='text' name='limitQuantity' value='{$limitQuantity}' class='form-control cart-quantity' style='display:none;' />";
                    echo "<div class='input-group'>";
                        echo "<input type='number' name='quantity' value='{$quantity}' class='form-control cart-quantity' min='1' />";
                            echo "<span class='input-group-btn'>";
                                echo "<button class='btn btn-default update-quantity' type='submit'>Update</button>";
                            echo "</span>";
                    echo "</div>";
                echo "</form>";
 
                // delete from cart
                echo "<a href='removeItemFromCart.php?catalogNumber={$catalogNumber}' class='btn btn-default'>";
                    echo "Delete";
                echo "</a>";
            echo "</div>";
 
            echo "<div class='col-md-4'>";
                echo "<h4>&#36;" . number_format($sub_total, 2, '.', ',') . "</h4>";
            echo "</div>";
        echo "</div>";
 
        $item_count += $quantity;
        $sumTotal+=$sub_total;
    }
 
    echo "<div class='col-md-8'></div>";
    echo "<div class='col-md-4'>";
        echo "<div class='cart-row'>";
            echo "<h4 class='m-b-10px'>Total ({$item_count} items)</h4>";
            echo "<h4>&#36;" . number_format($sumTotal, 2, '.', ',') . "</h4>";
            echo "<a href='checkout.php' class='btn btn-success m-b-10px'>";
                echo "<span class='glyphicon glyphicon-shopping-cart'></span> Proceed to Checkout";
            echo "</a>";
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
// layout footer 
include 'footer.php';




?>