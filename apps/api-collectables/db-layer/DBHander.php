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
             $hashed = hash('sha256', $array[1]);
             $stmt = $this->db_conn->prepare("SELECT customerID FROM logininfo WHERE customerID = ? AND password = ?");
             if ($stmt === false) {
                 throw new Exception($this->db_conn->error);
             }
             $stmt->bind_param('is', $array[0], $hashed);
             $stmt->execute();
             $result = $stmt->get_result();
             if ($result && $result->num_rows !== 0) 
             {
                 $stmt->close();
                 return $exists;
             }
             $stmt->close();
             return FALSE;
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
             $stmt = $this->db_conn->prepare("SELECT * FROM customer WHERE customerID = ?");
             if ($stmt === false) {
                 throw new Exception($this->db_conn->error);
             }
             $stmt->bind_param('i', $custID);
             $stmt->execute();
             $result = $stmt->get_result();
             $data = $result ? $result->fetch_assoc() : null;
             $stmt->close();
             return $data;
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
             $stmt = $this->db_conn->prepare(
                 "select orderline.orderID, orders.orderDate, product.prodID, product.prodName, product.prodPrice, orderline.quantity " .
                 "from orders, product, orderline " .
                 "where orderline.prodID = product.prodID " .
                 "and orderline.orderID = ? " .
                 "and orders.orderID = ?"
             );
             if ($stmt === false) {
                 throw new Exception($this->db_conn->error);
             }
             $stmt->bind_param('ii', $_SESSION['currentOrder'], $_SESSION['currentOrder']);
             $stmt->execute();
             $result = $stmt->get_result();
             $orderDetails = $this->getMultipleOrderDetails($result);
             $stmt->close();
             return $orderDetails;
         } 
         catch (Exception $exc) 
         {
             echo $exc->getMessage();
         }
     }

     
     private function getMultipleOrderDetails($resultSet) 
     {
         try 
         {
             $orderDetails = array();
             if ($resultSet) {
                 while ( ($data = $resultSet->fetch_assoc()) !== NULL )
                 {
                    $orderDetails[$data['prodID']] = array();
                    $orderDetails[$data['prodID']]['orderID'] = $data['orderID'];
                    $orderDetails[$data['prodID']]['orderDate'] = $data['orderDate'];
                    $orderDetails[$data['prodID']]['prodName'] = $data['prodName'];
                    $orderDetails[$data['prodID']]['prodPrice'] = $data['prodPrice'];
                    $orderDetails[$data['prodID']]['quantity'] = $data['quantity'];
                 }
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
             $stmt = $this->db_conn->prepare("SELECT orderID, orderDate FROM orders WHERE customerID = ?");
             if ($stmt === false) {
                 throw new Exception($this->db_conn->error);
             }
             $stmt->bind_param('i', $_SESSION['validUser']);
             $stmt->execute();
             $result = $stmt->get_result();
             $orderDetails = array();
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
             $stmt->close();
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
             $stmt = $this->db_conn->prepare("SELECT inStock FROM product WHERE prodID = ?");
             if ($stmt === false) {
                 throw new Exception($this->db_conn->error);
             }
             $stmt->bind_param('i', $prodID);
             $stmt->execute();
             $result = $stmt->get_result();
             $data = $result ? $result->fetch_array() : null;
             $stmt->close();
             return $data;
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