
<?php

define('__ROOTItemPrompt__', dirname(dirname(__FILE__))); 
?>


<html>
    <head>
        
    <style type="text/css">
    .tablebg {
	background-color: #CCC;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-color: #000;
	border-right-color: #000;
	border-bottom-color: #000;
	border-left-color: #000;
}
    </style>
    </head>
    <body>
        
        <form action = "<?php echo(__ROOTItemPrompt__."\\testFunctions.php" );?>" method="get">
            <table class="tablebg">
            	<tr>
                <td colspan = 2>
                 	<label>Are are sure you want to add 
                         <?php 
                         if (isset($_GET['productName']) )
                         {
                             echo ($_GET['productName']);
                         }
                         ?> to the shopping cart again?</label>
                    </td>
                </tr>
                <tr>
                    <td width ="100">
                            <input type = "submit" name ="btnYes" value ="Yes"/>
                    </td>
                    <td width ="100">
                        <input type = "submit" name ="btnNo" value ="No"/>
                    </td>
            </tr>
            </table>
        </form>
    </body>
    
</html>