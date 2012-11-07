<?php

define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'\DBLayer\DBHandler.php'); 

class ShoppingCart
{
            
            private $DBHandler = null;
            private $DBConnect = null;
            private $inventory = array();
            private $shoppingCart = array();
            private $storeID = "";


            function __construct()
            {
                    $this->DBHandler = new DBHander();
                    $this->DBHandler->setDB('localhost', 'root', '', 'Collectables');
                    $this->DBConnect = $this->DBHandler->connectToDB();
                    echo "<p>Hello DB</p>";
            }

            function __destruct()
            {
                    if(!$this->DBConnect->connect_error)
                            $this->DBConnect->close();
            }

            function __wakeup()
            {
                    $this->DBConnect = $this->DBHandler->connectToDB();
            }

            public function setStoreID($storeID)
            {
                    if($this->storeID != $storeID)
                    {
                        $this->storeID = $storeID;
                        $SQLString = "SELECT * FROM inventory WHERE storeID = '" . $this->storeID ."'";
                        $QueryResult = @$this->DBConnect->query($SQLString);
                        if($QueryResult === FALSE)
                        {
                            $this->storeID = "";
                        }
                        else
                        {
                            $this->inventory = array();
                            while(($Row = $QueryResult->fetch_assoc()) !== NULL)
                            {
                                $this->inventory[$Row['productID']] = array();
                                $this->inventory[$Row['productID']]['name'] = $Row['name'];
                                $this->inventory[$Row['productID']]['description'] = $Row['description'];
                                $this->inventory[$Row['productID']]['price'] = $Row['price'];
                                $this->shoppingCart[$Row['productID']] = 0;
                            }
                        }
                    }
            }

            public function getStoreInformation()
            {
                    $retval = FALSE;
                    if($this->storeID != "")
                    {
                            $SQLString = "SELECT * FROM store_info WHERE storeID = '" . $this->storeID . "'";
                            $QueryResult = @$this->DBConnect->query($SQLString);
                            if($QueryResult !== FALSE)
                            {
                                            $retval = $QueryResult->fetch_assoc();
                            }
                    }
                    return($retval);
            }

            public function getProductList()
            {
                    $retval = FALSE;
                    $subtotal = 0;
                    if(count($this->inventory) > 0)
                    {
                        echo"<table width= '100%'>\n";
                        echo"<tr><th>Product</th><th>Description</th>".
                        "<th>Price Each</th><th># in Cart</th>" .
                        "<th>Total Price</th><th>&nbsp;</th></tr>\n";
                        foreach($this->inventory as $ID => $Info)
                        {
                            echo "<tr><td>".
                                    htmlentities($Info['name'])."</td>\n";
                            echo "<td>".htmlentities($Info['description'])."</td>\n";
                            printf("<td class= 'currency'>$%.2f</td>\n", $Info['price']);
                            echo "<td class= 'currency'>".$this->shoppingCart[$ID]."</td>\n";
                            printf("<td class= 'currency'>$%.2f</td>\n", $Info['price'] * $this->shoppingCart[$ID]);
                            echo "<td><a href='" .
                            $_SERVER['SCRIPT_NAME'] .
                            "?PHPSESSID=" . session_id() .
                            "&ItemToAdd=$ID'>Add " .
                            " Item</a><br />\n";
                            echo "<a href='" . $_SERVER['SCRIPT_NAME'].
                            "?PHPSESSID=" . session_id() .
                            "&ItemToRemove=$ID'>Remove " .
                            " Item</a></td>\n";
                            $subtotal += ($Info['price'] * $this->shoppingCart[$ID]);
                        }
                        echo "<tr><td colspan= '4'>Subtotal</td>\n";
                        printf("<td class= 'currency'>$%.2f</td>\n", $subtotal);
                        echo "<td><a href='" .
                        $_SERVER['SCRIPT_NAME'] .						
                        "?PHPSESSID=" . session_id() .
                        "&EmptyCart=TRUE'>Empty ".
                        " Cart</a></td></tr>\n";
                        echo"</table>";
                        $retval = TRUE;
                    }
                    return($retval);
            }

            public function showCart() 
            {

                        //test
            }

            private  function addItem()
            {

            }

            private  function addOne()
            {

            }

            private function removeItem()
            {

            }

            private function emptyCart()
            {

            }

            private function removeAll()
            {

            }

            public function processUserInput()
            {
                    if(!empty($_GET['ItemToAdd']))
                            $this->addItem();
                    if(!empty($_GET['AddOne']))
                            $this->addOne();
                    if(!empty($_GET['ItemToRemove']))
                            $this->removeItem();
                    if(!empty($_GET['EmptyCart']))
                            $this->emptyCart();
                    if(!empty($_GET['RemoveAll']))
                            $this->removeAll();
            }

            public function getProductInfo($prodID) 
            {
                    $SQLstring = "SELECT * FROM inventory WHERE productID = '" . $prodID . "'";
                    $QueryResult = @$this->DBConnect->query($SQLstring);
                    if($QueryResult === FALSE)
                        exit("<p>Error Obtaining Product Info!</p>");
                    else 
                    {
                        $Row = $QueryResult->fetch_assoc();
                        if($Row !== NULL) 
                        {
                                return($Row);
                        }
                    }			
            }

            public function checkout()
            {			
                    echo "<p><strong>Your order has been recorded.</strong></p>\n";
            }
    }
?>