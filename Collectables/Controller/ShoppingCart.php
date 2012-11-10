<?php

define('__ROOTShoppingCart__', dirname(dirname(__FILE__))); 
require_once(__ROOTShoppingCart__.'\DBLayer\DBHander.php'); 

class ShoppingCart
{      
    private $DBHandler = null;
    private $DBConnect = null;
    private $inventory = array();
    private $shoppingCart = array();
    private $checkout = array();
    private $storeID = "";

    
    function __construct()
    {
            $this->DBHandler = new DBHander();
            $this->DBConnect = $this->DBHandler->connectToDB();
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
        $this->inventory = $this->sortArray($this->inventory);
    }
    
   
    
    
    public function sortArray ($passedArr)
    {
       $size = count($passedArr);
       for ($i=1; $i<$size; $i++) 
       {
        for ($j=1; $j<$size; $j++) 
        {
            if (strcmp($passedArr[$j+1]['prodName'], $passedArr[$j]['prodName']) < 0) 
            {
                $this->swap($passedArr, $j, $j+1);
            }
        }
    }
    return $passedArr;
    }
    
    function swap(&$passedArr, $a, $b) 
    {
        $tmp = $passedArr[$a];
        $passedArr[$a] = $passedArr[$b];
        $passedArr[$b] = $tmp;
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
        $timestamp = sha1(microtime(true));
        $checkoutPage = "order-processed.php";
        $redirect = "showcart.php";
        if(count($this->inventory) > 0)
        {
            if (strstr($_SERVER['SCRIPT_NAME'], 'testFunctions.php')) 
            {
                if ($this->cartEmpty()) 
                {
                    echo $this->tableHeaders()."<th>&nbsp;</th><th>&nbsp;</th></tr>\n";
                }
                else
                    echo $this->tableHeaders() . "<th><a href='$redirect'><img border='0' src='images/images/cart/shopping-cart.jpg' /></a></th><th>&nbsp;</th></tr>\n";
                                    
                $this->populateInvTableContent($subtotal, $timestamp);
            }
            else
            {
                if (!$this->cartEmpty()) 
                {
                    echo $this->tableHeaders() . "<th><a href='$checkoutPage'><img border='0' src='images/images/cart/checkout.jpg' /></a></th><th>&nbsp;</th></tr>\n";
                }
                else
                    echo $this->tableHeaders() . "<th>&nbsp;</th><th>&nbsp;</th></tr>\n";
                
                $this->populateCartTableContent($subtotal, $timestamp);
            }
           
            $this->subtotal($subtotal, $timestamp);
            $retval = TRUE;
        }
        return($retval);      
    }

    private function cartEmpty()
    {
        $retval = TRUE;
        
        foreach ($this->shoppingCart as $key => $value) 
        {
            if ($value !== 0) 
            {
                $retval = FALSE;
                break;
            }
        }
        
        return $retval;
    }

    private function tableHeaders()
    {
        return "<table width= '100%' align='center'>\n".
                "<tr align='left'><th>Product Name</th><th>Product Description</th>".
                "<th>Price Each</th><th align='center'>Quantity in Cart</th>" .
                "<th>Total Price</th>";
    }
    
    private function subtotal($subtotal, $timestamp)
    {  
        $productsPage = "testFunctions.php";
        
        echo "<tr><td colspan= '4' align='right' >Subtotal</td>\n";
        printf("<td class= 'currency'>$%.2f</td>\n", $subtotal);
        
        if (strstr($_SERVER['SCRIPT_NAME'], 'showcart.php')) 
        {
            if ($this->cartEmpty()) 
            {
                echo "<td ><a href='$productsPage'><img src='images/images/cart/readd-items-to-cart.jpg' /></a></td></tr>\n";
            }
            else
            {
                echo $this->echoEmptyCart($timestamp, $productsPage);
            }
        }
        else
        {            
            echo $this->echoEmptyCart($timestamp, $productsPage);
        }
        echo"<tr><th>&nbsp;</th><th>&nbsp;</th>".
                "<th>&nbsp;</th><th>&nbsp;</th>" .
                "<th>&nbsp;</tr>\n";
        echo"</table>";
    }
    
    private function echoEmptyCart($timestamp, &$productsPage)
    {
        if (!$this->cartEmpty()) 
        {
            if (strstr($_SERVER['SCRIPT_NAME'], 'showcart.php')) 
            {
                return "<td ><a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() .
                        "&EmptyCart=TRUE&tokenID=" . $timestamp . "'><img src='images/images/cart/empty-cart.jpg' /></a><a href='$productsPage'><img src='images/images/cart/edit-cart.jpg' /></a></td></tr>\n";
            }
            else
            {
                return "<td ><a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() .
                        "&EmptyCart=TRUE&tokenID=" . $timestamp . "'><img src='images/images/cart/empty-cart.jpg' /></a></td></tr>\n";
            }
        } 
        else
        {
            return "<td >&nbsp;</td></tr>\n";
        }
    }
    
    private function populateInvTableContent(&$subtotal, $timestamp)
    { 
        foreach($this->inventory as $ID => $value)
        {  
            echo "<tr><td >". htmlentities($value['prodName'])."</td>\n";
            echo "<td >".htmlentities($value['prodDesc'])."</td>\n";
            printf("<td class= 'currency'>$%.2f</td>\n", $value['prodPrice']);
            echo "<td align='center' class= 'currency'>".$this->shoppingCart[$ID]."</td>\n";
            printf("<td class= 'currency'>$%.2f</td>\n", $value['prodPrice'] * $this->shoppingCart[$ID]);
            echo "<td align='left'><a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() . 
                         "&ItemToAdd=$ID&tokenID=" . $timestamp ."'><img border='0' src='images/images/cart/add-to-cart-1.jpg' /></a>\n";
            echo "<a href='" . $_SERVER['SCRIPT_NAME']. "?PHPSESSID=" . session_id() .
                         "&ItemToRemove=$ID&tokenID=" . $timestamp ."'><img border='0' src='images/images/cart/remove-from-cart-1.jpg' /></a>\n";
            echo "<a href='" . $_SERVER['SCRIPT_NAME']. "?PHPSESSID=" . session_id() .
                         "&RemoveAll=$ID&tokenID=" . $timestamp ."'><img border='0' src='images/images/cart/remove-all-from-cart.jpg' /></a></td>\n";
            $subtotal += ($value['prodPrice'] * $this->shoppingCart[$ID]);
        } 
    }

    private function populateCartTableContent(&$subtotal, $timestamp)
    {  
        foreach($this->shoppingCart as $ID => $value)
        {  
            if ($value > 0) 
            {                
                echo "<tr><td >". htmlentities($this->inventory[$ID]['prodName'])."</td>\n";
                echo "<td >".htmlentities($this->inventory[$ID]['prodDesc'])."</td>\n";
                printf("<td >$%.2f</td>\n", $this->inventory[$ID]['prodPrice']);
                echo "<td align='center'>".$this->shoppingCart[$ID]."</td>\n";
                printf("<td >$%.2f</td>\n", $this->inventory[$ID]['prodPrice'] * $this->shoppingCart[$ID]);
                echo "<td align='left'><a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() . 
                             "&ItemToAdd=$ID&tokenID=" . $timestamp ."'><img border='0' src='images/images/cart/add-to-cart-1.jpg' /></a>\n";
                echo "<a href='" . $_SERVER['SCRIPT_NAME']. "?PHPSESSID=" . session_id() .
                             "&ItemToRemove=$ID&tokenID=" . $timestamp ."'><img border='0' src='images/images/cart/remove-from-cart-1.jpg' /></a>\n";
                echo "<a href='" . $_SERVER['SCRIPT_NAME']. "?PHPSESSID=" . session_id() .
                             "&RemoveAll=$ID&tokenID=" . $timestamp ."'><img border='0' src='images/images/cart/remove-all-from-cart.jpg' /></a></td>\n";
                $subtotal += ($this->inventory[$ID]['prodPrice'] * $this->shoppingCart[$ID]);
            
                $this->checkout[$ID] = $this->shoppingCart[$ID];
            }
        }
    }
    
    public function showCart() 
    {
        $this->getProductList();
    }

    public function changeURL($uriPassed)
    {
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	header('Location: '.$uri.$uriPassed);
    }
    
    private function refreshed()
    {
        $checkRef = false;
        if (isset($_GET['tokenID']))
        {
                     $checkRef = isset($_SESSION['prevToken']) && strcmp($_GET['tokenID'], $_SESSION['prevToken']) == 0;
        } 
        return $checkRef;
    }
    
    public  function addItem()
    {
        if(!$this->refreshed() && !isset($_GET['btnNo']) && isset($_GET['ItemToAdd']))
        {

            $ID = $_GET['ItemToAdd'];


            if (isset($_SESSION['Last_ID']) && ($_SESSION['Last_ID'] === $ID))
            {
                unset($_SESSION['Last_ID']);
                $promptURL = "/Collectables/Controller/AddItemPrompt.php?productName=" . $this->inventory[$ID]['prodName'] . "&ID=" . $ID;
                $this->changeURL($promptURL);
            }
            else
            {
                  if (array_key_exists($ID, $this->shoppingCart))
                  {
                    $this->shoppingCart[$ID] = $this->shoppingCart[$ID] + 1;
                  }         
                  $_SESSION['Last_ID'] = $ID;
                  $_SESSION['prevToken'] = $_GET['tokenID'];
            }
        }
    }

    private  function addOne()
    {

    }

    private function removeItem()
    {
        if(!$this->refreshed())
        {
        $ID = $_GET['ItemToRemove'];
            if (array_key_exists($ID, $this->shoppingCart))
            {
                if($this->shoppingCart[$ID] > 0)
                {
                        $this->shoppingCart[$ID] = $this->shoppingCart[$ID] - 1;
                }
                else
                    echo('<script type="text/javascript"> alert("The cart is already empty"); </script>');
            }
        $_SESSION['prevToken'] = $_GET['tokenID'];
        }        
    }

    private function emptyCart()
    {
        if(!$this->refreshed())
        {
            foreach($this->shoppingCart as $key => $value)
            {
               $this->shoppingCart[$key] = 0;
               if (strstr($_SERVER['SCRIPT_NAME'], 'showcart.php')) 
               {
                    unset($this->checkout[$key]);
               }
            }        
            $_SESSION['prevToken'] = $_GET['tokenID'];
        }
    }

    private function removeAll()
    {
        if(!$this->refreshed())
        {
        $ID = $_GET['RemoveAll'];
            if (array_key_exists($ID, $this->shoppingCart))
            {
                if($this->shoppingCart[$ID] > 0)
                {
                        $this->shoppingCart[$ID] = 0;
                        if (strstr($_SERVER['SCRIPT_NAME'], 'showcart.php')) 
                        {
                            unset($this->checkout[$ID]);
                        }
                }
                else
                    echo('<script type="text/javascript"> alert("The cart is already empty"); </script>');
            }
        }
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
        print_r($this->checkout);
        $this->DBHandler->insertIntoOrderLine($this->checkout);
    }  
}
?>