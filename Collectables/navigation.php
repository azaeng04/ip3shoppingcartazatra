<nav class="art-nav clearfix">
    <div class="art-nav-inner">
        <ul class="art-hmenu">
            <?php 
                if(strstr($_SERVER['SCRIPT_NAME'], 'home.php')  === 'home.php')
                    echo '<li><a href="home.php" class="active">Home</a></li>';
                else
                    echo '<li><a href="home.php">Home</a></li>';
            ?>            
            <?php 
                if(strstr($_SERVER['SCRIPT_NAME'], 'products.php')  === 'products.php')
                    echo '<li><a href="products.php" class="active">Products</a></li>';
                else
                    echo '<li><a href="products.php">Products</a></li>';
            ?>
            <?php 
                if(strstr($_SERVER['SCRIPT_NAME'], 'contact-us.php')  === 'contact-us.php')
                    echo '<li><a href="contact-us.php"  class="active">Contact Us</a></li>';
                else
                    echo '<li><a href="contact-us.php">Contact Us</a></li>';
            ?>
        </ul> 
    </div>
</nav>
