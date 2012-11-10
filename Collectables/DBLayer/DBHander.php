<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBConn
 *
 * @author Travis
 * test
 */
class DBHander 
{
    var $db_conn = NULL;
    var $db_port = NULL;
    var $db_user = NULL;
    var $db_pass = NULL;
    var $db_name = NULL;
    var $errorMsgs = NULL;
    
    function __construct() {
        $this->setDB('localhost', 'root', '', 'collectables');
    }

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
        try 
        {
            $dbcnx = new mysqli($this->db_port, $this->db_user, $this->db_pass, $this->db_name);
            if ($dbcnx->connect_errno)         {
                $this->errorMsgs[] = "Unable to connect to the database server. Error code " . $dbcnx->connect_errno . ": " . $dbcnx->connect_error;
            }         else         {
                $this->db_conn = $dbcnx;
                return $dbcnx;
            }
        } 
        catch (Exception $exc) 
        {
            echo $exc->getMessage();
        }
         }
     
    function clearErrorMsgs()
    {
         foreach ($this->errorMsgs as $value)
         {
             unset($value);
         }
     }
     
     public function getMaxID($table, $column)
     {            
        $count = "SELECT COUNT(*) FROM $table;";
        $query = "SELECT MAX($column) FROM $table;";            
        $maxID = 1;

        try 
        {
            $res = $this->db_conn->query($count);
            $row = $res->fetch_row();
            if ($row[0] > 0) 
            {
                $res = $this->db_conn->query($query);

                $row = $res->fetch_row();
                $maxID = $row[0];
                return ($maxID + 1);
            } 
            else 
            {
                return $maxID;
            }
        } 
        catch (Exception $exc) 
        {
            echo $exc->getMessage();
        }
     }

     private function insertIntoOrders($orderID)
     {
         $insert = "INSERT INTO ORDERS VALUES($orderID, CURDATE())";
         
         $this->insertUpdateDelete($insert);
     }

     public function insertIntoOrderLine($shoppingCartArray)
     {
         try 
         {
            $maxOrderID = $this->getMaxID("orders", "orderID");
            $this->insertIntoOrders($maxOrderID);
            foreach ($shoppingCartArray as $productID => $quantity) 
            {
                $maxOrdlineID = $this->getMaxID("orderline", "orderLineID");
                $insertOrder = "INSERT INTO orderline VALUES($maxOrdlineID, $maxOrderID, $productID, $quantity)";

                $this->insertUpdateDelete($insertOrder);
                echo "<p>Orderline ID: $maxOrdlineID, Order ID: $maxOrderID, Product ID: $productID Quantity: $quantity </p>";
            }
            $this->updateInStock($shoppingCartArray);            
         }
         catch (Exception $exc) 
         {
             echo $exc->getMessage();
         }
     }
     
     private function updateInStock($shoppingCartArray)
     {
         try 
         {  
             foreach ($shoppingCartArray as $key => $value) 
             {
                 $updateInStock = "UPDATE product SET inStock = inStock - $value WHERE prodID = $key";
                 $this->insertUpdateDelete($updateInStock);
             }
         } 
         catch (Exception $exc) 
         {
             echo $exc->getMessage();
         }
     }
     
     private function insertUpdateDelete($query)
     {
         try 
         {
             $this->db_conn->query($query);             
         } 
         catch (Exception $exc) 
         {
             echo $exc->getMessage();
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