<?php
// include connect to db
include_once "config/database.php";
//include objects
include_once 'objects/product.php';
include_once 'objects/CartItem.php';
 
// get database connection
$database = new database();
$db = $database->getConnection();
 
// initial objects
$product = new Product($db);
$cart_item = new CartItem($db);
 
// get ID of the product to be edited
$catalogNumber = isset($_GET['catalogNumber']) ? $_GET['catalogNumber'] : die('ERROR: missing catalog Number.');
$action = isset($_GET['action']) ? $_GET['action'] : "";
 
// set the catalog Numberas product catalog Number property
$product->catalogNumber = $catalogNumber;
 
// to read single record product
$product->oneItem();
 //echo "catalogNumber : ".$product->price;
// set page title
$page_title = $product->title;
 
// include header
include_once 'head.php';
 
echo "<div class='col-md-12'>";
    if($action=='added'){
        echo "<div class='alert alert-info'>";
            echo "Product was added to your cart!";
        echo "</div>";
    }
 
    else if($action=='unable_to_add'){
        echo "<div class='alert alert-info'>";
            echo "Unable to add product to cart. Please contact Admin.";
        echo "</div>";
    }
echo "</div>";

echo "<div class='col-md-5'>";
 
    echo "<div class='product-detail'>Price:</div>";
    echo "<h4 class='m-b-10px price-description'>&#36;" . number_format($product->price, 2, '.', ',') . "</h4>";
 
    echo "<div class='product-detail'>Product description:</div>";
    echo "<div class='m-b-10px'>";
        // make html
        $page_description = htmlspecialchars_decode(htmlspecialchars_decode($product->description));
 
        // show to user
        echo $page_description;
    echo "</div>";
 
 
echo "</div>";



echo "<div class='col-md-2'>";
    // cart item settings
    $cart_item->userId=1; // we default to a user with ID "1" for now
    $cart_item->catalogNumber=$catalogNumber;
 
    // if product was already added in the cart
    if($cart_item->exists()){
        echo "<div class='m-b-10px'>This product is already in your cart.</div>";
        echo "<a href='cartProduct.php' class='btn btn-success w-100-pct'>";
            echo "Update Cart";
        echo "</a>";
    }
 
    // if product was not added to the cart yet
    else{
 
        echo "<form class='add-to-cart-form'>";
            // catalog number
            echo "<div class='product-id display-none'>{$catalogNumber }</div>";
 
            // select quantity
            echo "<div class='m-b-10px f-w-b'>Quantity:</div>";
            echo "<input type='number' class='form-control m-b-10px cart-quantity' value='1' min='1' />";
 
            // enable add to cart button
            echo "<button style='width:100%;' type='submit' class='btn btn-primary add-to-cart m-b-10px'>";
                echo "<span class='glyphicon glyphicon-shopping-cart'></span> Add to cart";
            echo "</button>";
 
        echo "</form>";
    }
echo "</div>";
 
// include footer 
include_once 'footer.php';
?>