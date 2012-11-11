<?php
    session_start();
    require_once('\DBLayer\DBHander.php');   
    
    
    if (is_array($_POST['login'])) 
    {
        processInput();
    }
    
    function processInput()
    {
        $errorMsg = "";
        $loginDetails = $_POST['login'];
        isFieldsEmpty(  $loginDetails, $errorMsg);
    }
    
    function isFieldsEmpty($loginDetails, $errorMsg) {
        if (isEmpty($loginDetails)) 
        {
            userExists($loginDetails, $errorMsg);
        }         
        else         
        {
            $errorMsg = "Username and/or password fields cannot be left blank";
            errorMessage($errorMsg);
        }
    }
    
    function userExists($loginDetails, $errorMsg)
    {  
        $DBConnection = new DBHander();
        $DBConnection->connectToDB();
        if ($DBConnection->userExists($loginDetails)) 
        {
            $validUser = $_POST['login'];
            $_SESSION['validUser'] = $validUser[0];
            header("Location: testFunctions.php");
        }
        else
        {
            $errorMsg = "Invalid username and/or password";
            errorMessage($errorMsg);
        }
    }
    
    function errorMessage($errorMsg)
    {
        $msg = ($_SESSION['errorMsg'] = $errorMsg);
        header("Location: login.php");
    }
    
    function isEmpty($array)
    {
        $retval = FALSE;
        if ($array[0] != NULL && $array[1] != NULL)
            $retval = TRUE;        
        return $retval;
    }
?>