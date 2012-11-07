<?php
session_start();
require_once('\Controller\ShoppingCart.php');
$storeID = "Cars";
$storeInfo = array();
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
     $Store->setStoreID($storeID);
     $storeInfo = $Store->getStoreInformation();
     $Store->processUserInput();
}
else {
     $ErrorMsgs[] = "The ShoppingCart class is not available!";
     $Store = NULL;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $storeInfo['storeName']; ?></title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
    <h1><?php echo htmlentities($storeInfo['storeName']); ?></h1>
    <h2><?php echo htmlentities($storeInfo['storeDesc']); ?></h2>
    <p><?php echo htmlentities($storeInfo['welcomeMsg']); ?></p>
    <?php
         $Store->getProductList();
         $_SESSION['currentStore'] = serialize($Store);
         //'ShowCart.php?PHPSESSID
    ?>
</body>
</html>

