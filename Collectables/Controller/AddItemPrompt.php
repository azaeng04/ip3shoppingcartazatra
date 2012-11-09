
<?php
 function changeURL($uriPassed)
    {
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	header('Location: '.$uri.$uriPassed);
    }
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
        <form>
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
                            <input type = "button" value ="Yes"/>
                    </td>
                    <td width ="100">
                        <input type = "button" value ="No"/>
                    </td>
            </tr>
            </table>
        </form>
    </body>
    
</html>