<?php    
    if (isset($_POST['storeID']))
        $_SESSION['storeID'] = $_POST['storeID'];
    if (isset($_SESSION['storeID']))
    { 
        echo "Select store: <a>Figurines</a>".
         "<a> Comicon</a>".
         "<a> Car Collectables</a>";
        require_once(__DIR__ . '/Controller/ShoppingCart.php');

        $storeKey = $_SESSION['storeID'];

        foreach ($storeKey as $key => $value) 
        {
            $storeID = $key;
        }
        $storeInfo = array();
        if (class_exists("ShoppingCart"))     
        {
            if (isset($_SESSION['currentStore']))         
            {
                    $Store = unserialize($_SESSION['currentStore']);
            }         
            else 
            {
                    $Store = new ShoppingCart();
            }
            $Store->setStoreID($storeID);
            $storeInfo = $Store->getStoreInformation();
            $Store->processUserInput();
        }
        else 
        {
             $ErrorMsgs[] = "The ShoppingCart class is not available!";
             echo '<p>'.$ErrorMsgs[0].'</p>';
             $Store = NULL;
        }
    

?>
<div class="art-postmetadataheader">
    <h2 class="art-postheader"> <?php echo $storeInfo['storeName'];  ?> </h2>    
    
</div>
<?php 
        $Store->getProductList();
        $_SESSION['currentStore'] = serialize($Store);
    }
    else
        echo "Select store: <a>Figurines</a><a> Comicon</a><a> Car Collectables</a>";
?>
<div class="art-postcontent art-postcontent-0 clearfix"><p><br></p></div>