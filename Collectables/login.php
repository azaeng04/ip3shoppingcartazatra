<?php
if(session_id() == '')
        session_start();
?>
<html>
    <head>
    </head>
    <body>
        <form method="post" action="validate-user.php" align="center"> 
            <table style="display: inline-block; vertical-align: central;">
                <tr><th colspan=2>Collectables Login</th></tr> 
                <tr><td>Username: </td><td><input id="txtUsername" type="text" maxlength="10" name="login[]"/><label id="lblInputErrorUsername"></label></td><tr> 
                <tr><td>Password: </td><td><input id="txtPassword" type="password" maxlength="15" name="login[]"/><label id="lblInputErrorPassword"></label></td><tr> 
                <tr><td colspan=2 align='center'><input id="btnLogin" type="submit" value="Login"/></td><tr>
                <?php 
                    $msg = "";
                    if (isset($_SESSION['errorMsg'])) 
                    {
                        $msg = $_SESSION['errorMsg'];
                        echo "<tr><td colspan=2>$msg</td></tr>";
                    }
                    else
                        echo "<tr><td colspan=2>$msg</td></tr>";
                ?>
            </table>
        </form>
    </body>
</html>