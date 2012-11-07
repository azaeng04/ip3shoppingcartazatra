<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBConn
 *
 * @author Travis
 */
class DBHander 
{
    
    var $db_port = null;
    var $db_user = null;
    var $db_pass = null;
    var $db_name = null;
    var $errorMsgs = null;
    
    
    function setDB($db_port,$db_user,$db_pass,$db_name)
    {
        $this->db_port = $db_port;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
    }
    
    function connectToDB()
    {
                        $this->errorMsgs = array();
			$dbcnx = new mysqli($this->db_port, $this->db_user,$this->db_pass, $this->db_name);
			if($dbcnx->connect_errno)
                        {
                                $this->errorMsgs[] = "Unable to connect to the database server. Error code " . $dbcnx->connect_errno . ": " . $dbcnx->connect_error;
                        }
                        return $dbcnx;
     }
     
     function clearErrorMsgs()
     {
         foreach ($this->errorMsgs as $value)
         {
             unset($value);
         }
     }
     
     function displayErrorMsgs()
     {
         foreach ($this->errorMsgs as $value)
         {
             echo($value);
         }
     }
     
     public function getErrorMsgs() 
     {
         return $this->errorMsgs;
     }

     
     public function setErrorMsgs($errorMsgs) 
     {
         $this->errorMsgs = $errorMsgs;
     }

      function closeConn($dbcnx)
     {
         return ($dbcnx->close());
     }

}

?>
