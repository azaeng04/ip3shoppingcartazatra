<?php
session_start();
require_once('\Controller\ShoppingCart.php');
if (class_exists("ShoppingCart")){
     if (isset($_SESSION['currentStore']))
     {
        $Store = unserialize($_SESSION['currentStore']);
     }
     else 
     {
        $Store = new ShoppingCart();
     }
     $Store->processUserInput();
}
else {
     $ErrorMsgs[] = "The ShoppingCart class is not available!";
     echo '<p>'.$ErrorMsgs[0].'</p>';
     $Store = NULL;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html dir="ltr" lang="en-US">
    <head><!-- Created by Artisteer v4.0.0.58475 -->
        <meta charset="utf-8">
        <title>Show Cart</title>
        <?php include('header.php')?>
        <?php include('navigation.php')?>
    
    <div class="art-sheet clearfix">
        <div class="art-layout-wrapper clearfix">
            <div class="art-content-layout">
                <div class="art-content-layout-row">
                    <div class="art-layout-cell art-content clearfix">
                        <article class="art-post art-article">
                            <?php
                                $Store->generateStatement();
                                
                                $_SESSION['currentStore'] = serialize($Store);
                           ?>
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