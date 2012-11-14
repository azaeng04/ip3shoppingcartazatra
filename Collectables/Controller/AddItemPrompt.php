<html>
    <head>
    <link href="tableStyling.css" rel="stylesheet" type="text/css" />
    
    </head>
    <body>
        <form action = " <?php 
        $prevLink = $_SERVER['HTTP_REFERER'];
        echo ($prevLink);?>" method="get">
            <table class="imagetable">
            	<tr>
                <th colspan = 2>
                 	<label>Are are sure you want to add 
                         <?php 
                         if (isset($_GET['productName']) )
                         {
                             echo ($_GET['productName']);
                         }
                         ?> to the shopping cart again?</label>
                    </th>
                </tr>
                <tr>
                    <td width ="100">
                            <input type = "submit" name ="btnYes" value ="Yes"/>
                    </td>
                    <td width ="100">
                        <input type = "submit" name ="btnNo" value ="No"/>
                        <input type ="hidden" name ="ItemToAdd" value ="<?php echo($_GET['ID'])?>">
                        <input type ="hidden" name ="tokenID" value ="<?php echo(sha1(microtime(true)))?>">
                    </td>
            </tr>
            </table>
        </form>
    </body>
    
</html>