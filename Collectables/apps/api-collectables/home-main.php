<?php
    $storeID = "";
    $storeInfo = array();
    require_once(__DIR__ . '/controller/ShoppingCart.php');
    
    function setStoreInfo($storeID, $storeInfo) 
    {
        if (class_exists("ShoppingCart"))
        { 
             $Store = new ShoppingCart();           
             $Store->setStoreID($storeID);
             $storeInfo = $Store->getStoreInformation();             
             return $storeInfo;
        }
        else 
        {
             $ErrorMsgs[] = "The ShoppingCart class is not available!";
             echo '<p>'.$ErrorMsgs[0].'</p>';
             return NULL;
        }
    }
?>
<div class="art-postcontent art-postcontent-0 clearfix">
    <div class="art-content-layout">
        <div class="art-content-layout-row">
            <div class="art-layout-cell layout-item-0" style="width: 100%" >
                <?php 
                    $storeIDArr = array("Action Figures", "Cars", "Comics");
                    foreach ($storeIDArr as $storeID) {
                        $storeInfo = setStoreInfo($storeID, $storeInfo);
                ?>
                    <h3 style="border-bottom: 1px solid #B6C5C8; padding-bottom: 5px"><?php echo htmlentities($storeInfo['storeName']); ?></h3>
                    <p>
                        <span style="font-weight: bold;"><?php echo htmlentities($storeInfo['welcomeMsg']); ?></span>
                    </p>

                    <p>
                        <?php echo htmlentities($storeInfo['storeDesc']); ?>
                    </p>

                    <form method="post" action="products.php">
                        <p>
                            <input type="submit" name="storeID[<?php echo $storeID; ?>]" value="View and Purchase Store Products" class="btnStyle" />
                        </p>
                    </form>
                <?php                     
                        $storeInfo = array();
                    }
                ?>
            </div>
        </div>
    </div>
</div>