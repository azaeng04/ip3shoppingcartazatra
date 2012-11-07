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
            $this->DBHandler->setDB('localhost', 'root', '', 'collectables');
            $this->DBConnect = $this->DBHandler->connectToDB();
            echo "<p>Hello DBDatabase connected successfully</p>";
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
                $SQLString = "SELECT * FROM product WHERE storeID = '" . $this->storeID ."'";
                $QueryResult = @$this->DBConnect->query($SQLString);
                $this->populateInventory($QueryResult);
            }
    }

    private function populateInventory($QueryResult)
    {
        if($QueryResult === FALSE)
        {
            $this->storeID = "";
        }
        else
        {
            $this->inventory = array();
            while(($Row = $QueryResult->fetch_assoc()) !== NULL)
            {
                $this->inventory[$Row['prodID']] = array();
                $this->inventory[$Row['prodID']]['prodName'] = $Row['prodName'];
                $this->inventory[$Row['prodID']]['prodDesc'] = $Row['prodDesc'];
                $this->inventory[$Row['prodID']]['prodPrice'] = $Row['prodPrice'];
                $this->shoppingCart[$Row['prodID']] = 0;
            }
        }
    }

    public function getStoreInformation()
    {
            $retval = FALSE;
            if($this->storeID != "")
            {
                    $SQLString = "SELECT * FROM storeinfo WHERE storeID = '" . $this->storeID . "'";
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
                echo"<tr><th>Product Name</th><th>Product Description</th>".
                        "<th>Price Each</th><th>Quantity in Cart</th>" .
                        "<th>Total Price</th><th>&nbsp;</th></tr>\n";
                $this->populateTableContent($subtotal);
                echo "<tr><td colspan= '4' align='right' >Subtotal</td>\n";
                printf("<td >$%.2f</td>\n", $subtotal);
                echo "<td ><a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() .
                            "&EmptyCart=TRUE'>Empty ". " Cart</a></td></tr>\n";
                echo"</table>";
                $retval = TRUE;
            }
            return($retval);
    }

    private function populateTableContent(&$subtotal)
    {
        foreach($this->inventory as $ID => $Info)
        {
            echo "<tr><td >". htmlentities($Info['prodName'])."</td>\n";
            echo "<td >".htmlentities($Info['prodDesc'])."</td>\n";
            printf("<td >$%.2f</td>\n", $Info['prodPrice']);
            echo "<td >".$this->shoppingCart[$ID]."</td>\n";
            printf("<td >$%.2f</td>\n", $Info['prodPrice'] * $this->shoppingCart[$ID]);
            echo "<td ><a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() . 
                         "&ItemToAdd=$ID'>Add " . " Item</a><br />\n";
            echo "<a href='" . $_SERVER['SCRIPT_NAME']. "?PHPSESSID=" . session_id() .
                         "&ItemToRemove=$ID'>Remove " . " Item</a></td>\n";
            $subtotal += ($Info['prodPrice'] * $this->shoppingCart[$ID]);
        }
        return $subtotal;
    }
    public function showCart() 
    {
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
            $SQLstring = "SELECT * FROM product WHERE prodID = '" . $prodID . "'";
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