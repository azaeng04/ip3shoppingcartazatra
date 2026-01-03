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
}
else 
{
     $ErrorMsgs[] = "The ShoppingCart class is not available!";
     echo '<p>'.$ErrorMsgs[0].'</p>';
     $Store = NULL;
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
    <head><!-- Created by Artisteer v4.0.0.58475 -->
        <meta charset="utf-8">
        <title>Home</title>
        <?php include('header.php')?>    
        <?php include ('navigation.php');?>

    
    <div class="art-sheet clearfix">
        <div class="art-layout-wrapper clearfix">
            <div class="art-content-layout">
                <div class="art-content-layout-row">
                    <div class="art-layout-cell art-content clearfix">
                        <article class="art-post art-article">
                            <?php 
                                $Store->checkout();
                                
                                $_SESSION['currentStore'] = serialize($Store);
                           ?>
                            <p style="font-size: larger">Order successfully processed!! :)</p>
                           <form action="home.php">
                               <input type="submit" value="Purchase items from a different store" class="btnStyle"/>
                           </form>
                           <form action="generate-order.php">
                               <input type="submit" value="Generate Statement for Order" class="btnStyle"/>
                           </form>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<?php include ('footer.php');?>

</div>


</body>
</html>