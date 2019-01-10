<?php

//session_start();

// connect to db
include 'config/database.php';
// include objects
include_once 'objects/product.php';
include_once 'objects/CartItem.php';


$page_title="Products";

// head layout page
include 'head.php';

// database connection
$database=new database();
$db=$database->getConnection();

//START initial object
$product=new Product($db);
$cart_item=new CartItem($db);
//END 

/*if ($cart_item->conn->connect_error) {
    die("Connection failed: " . $db->connect_error);
   }else echo "test!!";*/


// to prevent undefined index notice
$action = isset($_GET['action']) ? $_GET['action'] : "";

// pagination
$page=isset($_GET["page"]) ? $_GET["page"] : 1; // current page,if there is nothing set deafault 1

$record_per_page=5; // set 5 products per page
$from_record_num=($record_per_page*$page)-$record_per_page; // calculate for sql query
//echo "page : ".$page."<br> from_record_num :".$from_record_num;
$result=$product->getProducts($record_per_page,$from_record_num);// get all product in the db

$result->bind_result($catalogNumber,$description,$title,$price,$quantity,$createdDateTime);

//number of retrieved products
$cnt=0;
while ($row=$result->fetch()) {
$cnt++;
    
     }


// check if products retrieved more than zero
if($cnt>0){
    //echo "<br>size of ".$cnt;
 $page_url="products.php?";
 $total_rows=$product->CountRecord();
 
 
  //include_once 'item_template.php';

  $result=$product->getProducts($record_per_page,$from_record_num);// get all product in the db

  $result->bind_result($catalogNumber,$description,$title,$price,$quantity,$createdDateTime,$discountPerCent,$limitQuantity);
  
  
      // fetch values 
      while ($row=$result->fetch()) {

        $productsDetails[]=array(

            'catalogNumber'=>$catalogNumber,
            'title'=> $title,
            'description'=>$description,
            'price'=>$price,
            'quantity'=> $quantity,
            'createdDateTime'=> $createdDateTime,
            'discountPerCent'=>$discountPerCent,
            'limitQuantity'=>$limitQuantity      
            );


      }
         
        // pass on the values 
        for($i=0;$i<count($productsDetails);$i++){
          //echo "<br>catalogNumber : ".$catalogNumber ."desc : ".$description ."  ".$title;
      
          // creating box
          echo "<div class='col-md-4 m-b-20px'>";
       
              // product id for javascript access
              echo "<div class='product-id display-none'>{$productsDetails[$i]["catalogNumber"]}</div>";
             
              echo "<a href='item.php?catalogNumber={$productsDetails[$i]["catalogNumber"]}' class='product-link'>";
                  // select and show first product image
              
                  
                  // product title
                  echo "<div class='product-name m-b-10px'><b>Title: </b>{$productsDetails[$i]["title"]}</div>";
              echo "</a>";
               echo "<div class='product-name m-b-10px'><b>Description : </b>{$productsDetails[$i]["description"]}</div>";      
              if($productsDetails[$i]["discountPerCent"]>0)$css="style='text-decoration: line-through'";
              else $css="";
                  // product price 
                  echo "<div class='m-b-10px' {$css}><b>Price: </b>";
                      echo "&#36;" . number_format($productsDetails[$i]["price"], 2, '.', ',');
                  echo "</div>";
                  
                    if($productsDetails[$i]["discountPerCent"]>0){
                        if($productsDetails[$i]["limitQuantity"]>0) $display="";
                        else $display="style='display:none;'";
                        echo "<div class='product-name m-b-10px'><b>Discount:  </b>{$productsDetails[$i]["discountPerCent"]}%     <b {$display}>The discount is limited to the <b style='color:red;'>{$productsDetails[$i]["limitQuantity"]}</b> of products</b></div>";
                  // product price after discount
                   $priceAfterDiscount=(1-($productsDetails[$i]["discountPerCent"]/100))*$productsDetails[$i]["price"];                   
                    echo "<div class='m-b-10px' style='color:green;'><b>Price After Discount: </b>";
                    echo "&#36;" . number_format($priceAfterDiscount, 2, '.', ',');
                    echo "</div>";
                    }
                 
                  // add to cart button
                  echo "<div class='m-b-10px'>";
                      
                      $cart_item->userId=1; // default to a user with id "1" for now    
                      //$cart_item->userId=$cart_item->getMaxUserId()+1;              
                      $cart_item->catalogNumber=$productsDetails[$i]["catalogNumber"];
                      //$_SESSION["userId"]=$cart_item->userId;
                    
                     
                    // echo "<br>cart->catalogNumber :".$productsDetails[$i]["catalogNumber"];
                     //echo "<br>catalogNumber :".$catalogNumber;
                     
                      if($cart_item->exists())$count=1;
                      else $count=0;
                
                      // if product was already added in the cart
                      if($cart_item->exists()){
                          echo "<a href='cartProduct.php' class='btn btn-success w-100-pct'>";
                              echo "Update Cart";
                          echo "</a>";
                      }else{
                          echo "<a href='addToCart.php?id={$productsDetails[$i]["catalogNumber"]}&page={$page}' class='btn btn-primary w-100-pct'>Add to Cart</a>";
                      }
                  echo "</div>";
       
       
       
          echo "</div>";
      }
       
      include_once "paging.php";

}
// if there's no products in the database
else{
    echo "<div class='col-md-12'>";
        echo "<div class='alert alert-danger'>No products found.</div>";
    echo "</div>";
}



// footer layout page
include 'footer.php';


?>