<div class="art-sheet clearfix">
    <div class="art-layout-wrapper clearfix">
        <div class="art-content-layout">
            <div class="art-content-layout-row">
                <div class="art-layout-cell art-content clearfix">
                    <article class="art-post art-article">
                        <?php 
                            if(strstr($_SERVER['SCRIPT_NAME'], 'home.php')  === 'home.php')
                                include('home-main.php');
                            else
                                if(strstr($_SERVER['SCRIPT_NAME'], 'products.php')  === 'products.php')
                                    include('products-main.php');
                                else
                                    if(strstr($_SERVER['SCRIPT_NAME'], 'contact-us.php')  === 'contact-us.php')
                                      include('contact-us-main.php');
                        ?>  
                    </article>
                </div>                    
            </div>                
        </div>            
    </div>    
</div>
