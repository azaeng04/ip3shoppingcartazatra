<?php    
    if(session_id() != '')
    {
        unset($_SESSION);
        header('Location: login.php');
    }
    else
        header('Location: login.php');
?>
