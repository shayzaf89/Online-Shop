<?php
session_start();
// include objects
include_once 'config/database.php';

include_once 'objects/CartItem.php';
include_once 'objects/product.php';
include_once 'objects/systemManager.php';
include_once 'objects/clients.php';


#Use mailgun library installed on server
require '/var/www/html/mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
$domain = "irwebsites.co.il";


// get database connection
$database = new database();
$db = $database->getConnection();
 
// initialize objects
$cart_item = new CartItem($db);
$managerSys=new systemManager($db);
$product=new Product($db);
 
$orderDetails=$_SESSION["orderDetails"];//get the details of order
$userId=$_SESSION['userId'];

$cart_item->userId=$userId;



//$cart_item->updateCartPurchased();

//remove all the space and html charactrers 
function test_input($data)
{
$data=trim($data);//remove the all spaces
$data=stripslashes($data);
$data=htmlspecialchars($data);//remove html chars
    return $data;
}


if( isset($_POST['submit']) )//Check if submit button clicked.
{

//be sure to validate and clean your variables
$email = htmlentities($_POST['email']);
$lastName = htmlentities($_POST['lname']);
$firstName = htmlentities($_POST['fname']);
$phoneNumber = htmlentities($_POST['phoneNumber']);


$emailErr =$lastNameErr=$firstNameErr=$phoneNumberErr= "";
$flagToRemoveItems=0;
        /*START email input */
if (empty($email)) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($email);
   
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format"; 
    }
  }
        /*END email input */

        /*START last name input */
if (empty($lastName)) {
    $lastNameErr = "Last name is required";
    } else {
        $lastName = test_input($lastName);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$lastName)) {
        $lastNameErr = "Only letters allowed"; 
    }
    }
        /*END last name input */

                /*START first name input */
if (empty($firstName)) {
    $firstNameErr = "First name is required";
    } else {
        $firstName = test_input($firstName);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$firstNameErr)) {
        $firstNameErr = "Only letters allowed"; 
    }
    }
        /*END first name input */

        /*START phone number input */
    if (empty($phoneNumber)) {
        $phoneNumberErr = "Phone number is required";
        } else {
            $phoneNumber = test_input($phoneNumber);
        // check if name only contains letters and whitespace
        if (!is_numeric($phoneNumber)) {
            $phoneNumberErr = "Only numbers allowed"; 
        }
        }
        /*END phone number input */
        


}
    
if($emailErr<>"" || $lastNameErr<>"" || $firstNameErr<>"" || $phoneNumberErr<> ""){
    header("Location:checkout.php?emailErr=".urlencode($emailErr)."&firstNameErr=".urlencode($firstNameErr)."&lastNameErr=".urlencode($lastNameErr)."&phoneNumberErr=".urlencode($phoneNumberErr));
   }
else {


// remove all the cart by userId
   //$cart_item->removeItemsAfterCheckOut();
   $cart_item->updateCartPurchased();
    
     $sum_total=0;

   $orderStr="";
    for($i=0;$i<count($orderDetails);$i++){

        $product->catalogNumber=$orderDetails[$i]["catalogNumber"];
        $product->quantity=$orderDetails[$i]["quantity"];
        $product->updateQuantityBycatalogNumber();

        $sum_total+=round($orderDetails[$i]["sub_total"], 2);
        $orderStr=$orderStr."<ul style='list-style-type: none;'>
    <li>Catalog Number :".$orderDetails[$i]["catalogNumber"]."</li>  
    <li> Title :".$orderDetails[$i]["title"]."</li> 
    <li> Quantity :".$orderDetails[$i]["quantity"]."</li> 
    <li> Price :".round($orderDetails[$i]["sub_total"], 2)."</li>
    </ul>";


    //$orderStr=$orderStr."<br>Catalog Number :".$orderDetails[$i]["catalogNumber"]." Title :".$orderDetails[$i]["tilte"]." Quantity :".$orderDetails[$i]["quantity"]." sub_total :".round($orderDetails[$i]["sub_total"], 2);
      
    }

    $orderStr=$orderStr."<br> Total :".$sum_total;
        /* sending orders details to the manager*/
    $stmt=$managerSys->getDetails();
    $stmt->bind_result($emailManager,$firsName,$lasName);
    


       /* sending email to system manager using mail gun */
   /* while($stmt->fetch()){
    

$name=$firsName." ".$lasName;

    $htmlBodyPosts1=<<<EOT
    <!doctype html>
<html lang="en" 
      xmlns="http://www.w3.org/1999/xhtml" 
      xmlns:v="urn:schemas-microsoft-com:vml" 
      xmlns:o="urn:schemas-microsoft-com:office:office">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    
    <title>Online Shopping</title>
    
    
  </head>
  <body style="margin:0; padding:0; background:#eeeeee;">
    
   
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
	    &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>
    
    <center>
    
    <div style="width:100%; max-width:600px; background:#ffffff; padding:30px 20px; text-align:left; font-family: 'Arial', sans-serif;">
  
      
      
      <h1 style="font-size:16px; line-height:22px; font-weight:normal; color:#333333;">
        Hello $name !
      </h1>
      
      <b> Customer Details :</b> 
        <ul style='list-style-type: none;'>
        <li>Email : $email</li>
        <li>First Name : $firstName</li>
        <li>Last Name : $lastName</li>
        <li>Phone Number : $phoneNumber</li>
        </ul>
      

        <b>Order Details :</b> 
     
        $orderStr
     
      
     
      <hr style="border:none; height:1px; color:#dddddd; background:#dddddd; width:100%; margin-bottom:20px;">
      
      <p style="font-size:12px; line-height:18px; color:#999999; margin-bottom:10px;">
        &copy; Copyright 2019 
        Online Shopping , All Rights Reserved.
      </p>
      
      <!--[if mso | IE]>
      </td>
      </tr>
      </table>
      <![endif]-->
      
    </div>
      
    </center>
    
  </body>
</html>
EOT;
    
    #New Mailgun object
    $objArr[$i] = new Mailgun('key-1abc61ad099241246e85983d15c4ea02');
    
    #Send Mail
    $res[$i] = $objArr[$i]->sendMessage($domain, array(
        'from'    => 'Shopping_online@shoponline.co.il',
        'to'      => $emailManager,
        'subject' => 'New Order',
        'html'    => $htmlBodyPosts1
    ));
    
    //echo "Mail sent Successfully !";
    
  }*/
}
  
/*  sending email to customer */
/*$customerName=$lastName." ".$firstName;
$htmlBodyPosts2=<<<EOT
<!doctype html>
<html lang="en" 
  xmlns="http://www.w3.org/1999/xhtml" 
  xmlns:v="urn:schemas-microsoft-com:vml" 
  xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="x-apple-disable-message-reformatting">

<title>Online Shopping</title>


</head>
<body style="margin:0; padding:0; background:#eeeeee;">


<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
    &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
</div>

<center>

<div style="width:100%; max-width:600px; background:#ffffff; padding:30px 20px; text-align:left; font-family: 'Arial', sans-serif;">

  
  
  <h1 style="font-size:16px; line-height:22px; font-weight:normal; color:#333333;">
    Hello $customerName !
  </h1>
  
 
    <b>Order Details :</b> 
 
    $orderStr
 
  
 
  <hr style="border:none; height:1px; color:#dddddd; background:#dddddd; width:100%; margin-bottom:20px;">
  
  <p style="font-size:12px; line-height:18px; color:#999999; margin-bottom:10px;">
    &copy; Copyright 2019 
    Online Shopping , All Rights Reserved.
  </p>
  
  <!--[if mso | IE]>
  </td>
  </tr>
  </table>
  <![endif]-->
  
</div>
  
</center>

</body>
</html>
EOT;

#New Mailgun object
$objArr[$i] = new Mailgun('key-1abc61ad099241246e85983d15c4ea02');

#Send Mail
$res[$i] = $objArr[$i]->sendMessage($domain, array(
    'from'    => 'Shopping_online@shoponline.co.il',
    'to'      => $email,
    'subject' => 'Your Order',
    'html'    => $htmlBodyPosts2
));*/




// set page title
$page_title="Thank You!";
 
// include  header 
include_once 'head.php';
 
echo "<div class='col-md-12'>";
    // tell the user order has been placed
    echo "<div class='alert alert-success'>";
        echo "<strong>Your order has been placed!</strong> Thank you very much!";
    echo "</div>";
echo "</div>";

 // remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 

// include footer 
include_once 'footer.php';


?>