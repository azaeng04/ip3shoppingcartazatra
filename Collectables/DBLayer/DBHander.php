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
         $insert = "INSERT INTO ORDERS VALUES($orderID, CURDATE(),".$_SESSION['validUser'].")";
         
         $this->insertUpdateDelete($insert);
     }

     public function insertIntoOrderLine($shoppingCartArray)
     {
         try 
         {
            $maxOrderID = $this->getMaxID("orders", "orderID");
            $this->insertIntoOrders($maxOrderID);
            $_SESSION['currentOrder'] = $maxOrderID;
            foreach ($shoppingCartArray as $productID => $quantity) 
            {
                $maxOrdlineID = $this->getMaxID("orderline", "orderLineID");
                $insertOrder = "INSERT INTO orderline VALUES($maxOrdlineID, $maxOrderID, $productID, $quantity)";

                $this->insertUpdateDelete($insertOrder);                
            }
            $this->updateInStock($shoppingCartArray);
            $orderDetails = $this->getOrderDetailsOnProdsOfCust($maxOrderID);
            return $orderDetails;
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
     
     private function cancelOrderUpdateInstock($array) 
     {
         try 
         {  
             foreach ($array as $key => $value) 
             {
                 $updateInStock = "UPDATE product SET inStock = inStock + ".$array[$key]['quantity']." WHERE prodID = $key";
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
     
     function userExists($array) {
         $exists = TRUE;
         try 
         {
             $findUser = "SELECT * FROM logininfo WHERE customerID = " . $array[0] . " AND password = '" . $array[1] . "'";
             $result = $this->db_conn->query($findUser);
             if ($result->num_rows !== 0) 
             {
                 return $exists;
             }
             else
             {
                 return FALSE;
             }
         } 
         catch (Exception $exc) 
         {
             echo $exc->getMessage();
         }
     }
     
     
     public function getUserDetails($custID) 
     {
         try 
         {
             $findUser = "SELECT * FROM customer WHERE customerID = $custID";
             $result = $this->db_conn->query($findUser);
             return ($result->fetch_assoc());
         } 
         catch (Exception $exc) 
         {
             echo $exc->getMessage();
         }
     }

     public function getOrderDetailsOnProdsOfCust() 
     {
         try 
         {
             $getOrdersProd = "select orderline.orderID, orders.orderDate, product.prodID, product.prodName, product.prodPrice, orderline.quantity".
                              " from orders, product, orderline".
                              " where orderline.prodID = product.prodID".
                              " and orderline.orderID = ".$_SESSION['currentOrder'].
                              " and orders.orderID = ".$_SESSION['currentOrder'];
             $orderDetails = $this->getMultipleOrderDetails($getOrdersProd);
             return $orderDetails;
//             return $result->fetch_assoc();
         } 
         catch (Exception $exc) 
         {
             echo $exc->getMessage();
         }
     }

     
     private function getMultipleOrderDetails($getOrdersProd) 
     {
         try 
         {
             $orderDetails = array();
             $result = $this->db_conn->query($getOrdersProd);
             while ( ($data = $result->fetch_assoc()) !== NULL )
             {
                $orderDetails[$data['prodID']] = array();
                $orderDetails[$data['prodID']]['orderID'] = $data['orderID'];
                $orderDetails[$data['prodID']]['orderDate'] = $data['orderDate'];
                $orderDetails[$data['prodID']]['prodName'] = $data['prodName'];
                $orderDetails[$data['prodID']]['prodPrice'] = $data['prodPrice'];
                $orderDetails[$data['prodID']]['quantity'] = $data['quantity'];
             }
             return $orderDetails;
         } catch (Exception $exc) 
         {
             echo $exc->getMessage();
         }
    }
    
    public function getAllOrdersOfCustomer() 
    {
        try 
         {
             $getAllOrders = "SELECT * FROM orders WHERE customerID = ".$_SESSION['validUser'].";";
             $orderDetails = array();
             $result = $this->db_conn->query($getAllOrders);
             
             if ($result != FALSE) 
             {
                 while (($data = $result->fetch_assoc()) !== NULL)              
                 {
                     $orderDetails[$data['orderID']] = array();
                     $orderDetails[$data['orderID']]['orderDate'] = $data['orderDate'];
                 }
             }
             else
                 $orderDetails = $result;
             return $orderDetails;
         } 
         catch (Exception $exc) 
         {
             echo $exc->getMessage();
         }        
    }


    public function getInStockValue($prodID) 
     {
         try 
         {
             $getInstockVaue = "SELECT inStock FROM product WHERE prodID = $prodID;";
             $result = $this->db_conn->query($getInstockVaue);
             return $result->fetch_array();
         } 
         catch (Exception $exc) 
         {
             echo $exc->getMessage();
         }
     }
     
     public function cancelOrder($array) 
     {
         $retval = FALSE;
         try 
         {
             $cancelOrder = "DELETE FROM orderline WHERE orderID = ".$_SESSION['currentOrder'];
             $this->insertUpdateDelete($cancelOrder);
             $cancelOrder = "DELETE FROM orders WHERE orderID = ".$_SESSION['currentOrder'];
             $this->insertUpdateDelete($cancelOrder);
             $this->cancelOrderUpdateInstock($array);
             $retval = TRUE;
         } 
         catch (Exception $exc) 
         {
             echo $exc->getMessage();
         }
         return $retval;
     }
     
     public function displayErrorMsgs()
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