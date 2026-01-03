<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
   session_start();
}
require_once(__DIR__ . '/controller/ShoppingCart.php');
if (class_exists("ShoppingCart")){
     if (isset($_SESSION['currentStore']))
     {
        $Store = unserialize($_SESSION['currentStore']);
     }
     else 
     {
        $Store = new ShoppingCart();
     }
     //Build invenroty items on Store within
     session_unset();
     session_destroy();
     header('Location: login.php');
}
else {
     $ErrorMsgs[] = "The ShoppingCart class is not available!";
     echo '<p>'.$ErrorMsgs[0].'</p>';
     $Store = NULL;
}
?> 