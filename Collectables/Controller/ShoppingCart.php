
<SCRIPT language="JavaScript">
<!--

function PopUp(pPage) 
{
var wid = screen.width;
var hei = screen.height;
var popwid = "880";
var pophei = "550";
var leftPos = (wid-popwid)/2;
var topPos = (hei-pophei)/2;
window.open(pPage,'popWin','resizable=yes,scrollbars=no,toolbar=no,width=' + popwid + ',height=' + pophei + ',left='+leftPos+',top='+topPos);
}

//-->
</SCRIPT>

<link href="tableStyling.css" rel="stylesheet" type="text/css" />

<?php
define('__ROOTShoppingCart__', dirname(dirname(__FILE__))); 
require_once(__ROOTShoppingCart__.'\DBLayer\DBHander.php'); 

class ShoppingCart
{      
    private $DBHandler = null;
    private $DBConnect = null;
    private $statement = array();
    private $inventory = NULL;
    private $shoppingCart = NULL;
    private $checkout = array();
    private $storeID = "";
    private $orderCancelStatus = FALSE;
    private $pageIteration = 0;
    private $cartPageIteration = 0;
    private $pageLimit = 3;

    
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
                if (strstr($_SERVER['SCRIPT_NAME'], 'home.php')) {
                    return $QueryResult->fetch_array();
                }
                else
                    $this->populateInventory($QueryResult);
            }
    }
    
    public function getPageLimit() 
    {
        return $this->pageLimit;
    }

    public function setPageLimit($pageLimit) 
    {
        $this->pageLimit = $pageLimit;
    }
    
    public function getFirstKey($inventory)
   {
        $testArray = $inventory;
        $key = key($testArray);
        reset ($testArray);
        return ($key);
   }
   
   private function getImageURL($value)
   {
       $path = "images/images/";
       $fileFormat  = ".jpg";
       if ($value['imageURL'] != null)
       {
           $path = $path . $this->storeID . "/";
           return ($path . $value['imageURL'] . $fileFormat);
       }
       else
            return ($path . "image_not_available" . $fileFormat);
   }

        
    private function populateInventory($QueryResult)
    {
        if($QueryResult === FALSE)
        {
            $this->storeID = "";
        }
        else
        {
            if (isset($this->inventory,$this->shoppingCart))
            {
                unset($this->inventory);
                unset($this->shoppingCart);
            }
            else
            {
                $this->inventory = array();
                $this->shoppingCart = array();
            }            
            
            while(($Row = $QueryResult->fetch_assoc()) !== NULL)
            {
                $this->inventory[$Row['prodID']] = array();
                $this->inventory[$Row['prodID']]['prodName'] = $Row['prodName'];
                $this->inventory[$Row['prodID']]['prodDesc'] = $Row['prodDesc'];
                $this->inventory[$Row['prodID']]['prodPrice'] = $Row['prodPrice'];
                $this->inventory[$Row['prodID']]['imageURL'] = $Row['imageURL'];
                $this->shoppingCart[$Row['prodID']] = 0;
            }
        }
        $this->inventory = $this->sortArray($this->inventory);
        $this->getFirstKey($this->inventory);     
    }
    
   
    //much faster
    
    public function sortArray ($passedArr)
    {
       $size = count($passedArr);
       $min = $this->getFirstKey($passedArr);
       $max = $size + $min - 1;
       for ($i=$min; $i<$max; $i++) 
       {
            for ($j=$min; $j<$max; $j++) 
            {
                if (strcmp($passedArr[$j+1]['prodName'], $passedArr[$j]['prodName']) < 0) 
                {
                    if (strcmp($passedArr[$j+1]['prodName'], $passedArr[$j]['prodName']) < 0) 
                    {
                        $this->swap($passedArr, $j, $j+1);
                    }
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
            if (strstr($_SERVER['SCRIPT_NAME'], 'products.php')) 
            {
                if ($this->cartEmpty()) 
                {
                    echo $this->tableHeaders()."<th>&nbsp;</th></tr>\n";
                }
                else
                    echo $this->tableHeaders() . "<th><a href='$redirect'><img border='0' src='images/images/cart/shopping-cart.jpg' /></a></th></tr>\n";
                                    
                $this->populateInvTableContent($subtotal, $timestamp);
            }
            else
            {
                if (!$this->cartEmpty()) 
                {
                    echo $this->tableHeaders() . "<th><a href='$checkoutPage'><img border='0' src='images/images/cart/checkout.jpg' /></a></th></tr>\n";
                }
                else
                    echo $this->tableHeaders() . "<th>&nbsp;</th></tr>\n";
                
                $this->populateCartTableContent($subtotal, $timestamp);
            }
           
            $this->subtotal($subtotal, $timestamp);
            $this->calcGrandTotal();
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
        return "<table class='imagetable' width= '100%' align='center'>\n".
                "<tr align='left'><th>Product Name</th><th>Product Description</th>".
                "<th>Price Each</th><th align='center'>Quantity in Cart</th>" .
                "<th>Total Price</th><th>Photo</th>";
    }
    
    private function subtotal($subtotal, $timestamp)
    {  
        $productsPage = "products.php";
        
        echo "<tr><td colspan= '4' align='right' >Subtotal</td>\n";
        printf("<td colspan = '2' class='currency'>R%.2f</td>\n", $subtotal);
        echo "<tr><td colspan= '4' align='right' >Grandtotal</td>\n";
        printf("<td class= 'currency'>R%.2f</td>\n", $this->calcGrandTotal());
        
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
    
    private function calcGrandTotal()
    {
        $grandtotal = 0;
        foreach ($this->shoppingCart as $key => $value)
        {
            $grandtotal += ($this->inventory[$key]['prodPrice'] * $value);
        }
        return ($grandtotal);
    }
    
    private function populateInvTableContent(&$subtotal, $timestamp)
    { 
        
        $invSize = sizeof($this->inventory);
        $invKeys = array_keys($this->inventory);
        
        if (!isset($_SESSION['invIteration']))
            $this->pageIteration = 0;
        else
        {
            if (isset($_GET['nextPage']))
                $this->pageIteration = $_SESSION['invIteration'] + 1;
            else if (isset($_GET['prevPage']))
                $this->pageIteration = $_SESSION['invIteration'] - 1;
        }
        $_SESSION['invIteration'] = $this->pageIteration;
        

        $recordsPassed = $this->pageIteration *  $this->pageLimit;
        $counter = 1;
        $firstKey = $this->getFirstKey($this->inventory);
        
        
        while((($counter + $recordsPassed + $firstKey) <= ($invSize + $firstKey)) && $counter <= $this->pageLimit)
        {  
            
            
            $value = $this->inventory[$counter + $recordsPassed + $firstKey - 1];
            $ID = $invKeys[$counter + $recordsPassed - 1];
            $inStock = $this->DBHandler->getInStockValue($ID);
            
            echo "<tr><td >". htmlentities($value['prodName'])."</td>\n";
            echo "<td >".htmlentities($value['prodDesc'])."</td>\n";
            
            printf("<td class= 'currency'>R%.2f</td>\n", $value['prodPrice']);
            echo "<td align='center' class= 'currency'>".$this->shoppingCart[$ID]."</td>\n";
            printf("<td class= 'currency'>R%.2f</td>\n", $value['prodPrice'] * $this->shoppingCart[$ID]);
            echo "<td align='center'><a href = 'javascript:PopUp(\"" . $this->getImageURL($value) . "\")'><img src='". $this->getImageURL($value) . "' height='60' width='80'> </a></td>\n";
            
            $this->addRemoveDelete($ID, $timestamp, $inStock);
            $subtotal += ($value['prodPrice'] * $this->shoppingCart[$ID]);
            $counter++;
        } 
        
        echo("<tr>");
        if (($this->pageIteration + 1) < ($invSize / $this->pageLimit))
            echo("<td ><a href='" . $_SERVER['SCRIPT_NAME']. "?PHPSESSID=" . session_id() . "&nextPage=yes'>Next Page</a></td>");
        if ($this->pageIteration != 0)
            echo("<td ><a href='" . $_SERVER['SCRIPT_NAME']. "?PHPSESSID=" . session_id() ."&prevPage=yes''>Previous Page</a></td>");
        echo("</tr>");
        echo("<tr><td >Page: " . ($this->pageIteration + 1) . "</td></tr>");
        //echo("<tr><td>" . $this->pageIteration + 1 . "</td></tr>");
    }
    
    private function addRemoveDelete($ID, $timestamp, $inStock) 
    {
        if ($inStock[0] > 0 && $this->shoppingCart[$ID] < $inStock[0]) 
        {
            echo "<td align='left'><a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() .

            "&ItemToAdd=$ID&tokenID=" . $timestamp . "'><img border='0' src='images/images/cart/add-to-cart-1.jpg' /></a>\n";
            echo "<a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() .
            "&ItemToRemove=$ID&tokenID=" . $timestamp . "'><img border='0' src='images/images/cart/remove-from-cart-1.jpg' /></a>\n";
            echo "<a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() .
            "&RemoveAll=$ID&tokenID=" . $timestamp . "'><img border='0' src='images/images/cart/remove-all-from-cart.jpg' /></a></td>\n";
        }
        else
            echo "<td align='center'><img border='0' src='images/images/inventory/sold-out.jpg' /></td>";

    }


    private function getRealCartSize()
    {
        $count = 0;
        foreach($this->shoppingCart as $key => $value)
        {
            if ($value > 0)
                $count++;
        }
        return $count;
    }
    
    private function getRecordsPassed($recordsArr, $iteration)
    {
        $recordsPassed = 0;
        foreach ($recordsArr as $key => $value)
        {
            if ($key < $iteration)
                $recordsPassed = $recordsPassed + $value;
        }
        return $recordsPassed;
    }

    private function populateCartTableContent(&$subtotal, $timestamp)
    {  
        $cartSize = sizeof($this->shoppingCart);
        $cartKeys = array_keys($this->shoppingCart);
        if (!isset($_SESSION['cartIteration']))
        {
            $recordsArr = array();
            $this->pageIteration = 0;
            $recordsPassed = 0;
        }
        else
        {            
            $recordsArr = $_SESSION['recordsArr'];
            if (isset($_GET['nextPage']))
                $this->cartPageIteration = $_SESSION['cartIteration'] + 1;
            else if (isset($_GET['prevPage']))
                $this->cartPageIteration = $_SESSION['cartIteration'] - 1;
            else
                $this->cartPageIteration = $_SESSION['cartIteration'];
            $recordsPassed = $this->getRecordsPassed($recordsArr, $this->cartPageIteration);
        }
        $_SESSION['cartIteration'] = $this->cartPageIteration;
        
        $counter = 1;
        $boughtItemIterator = 1;
        $firstKey = $this->getFirstKey($this->inventory);
        while((($counter + $recordsPassed + $firstKey) <= ($cartSize + $firstKey)) && $boughtItemIterator <= $this->pageLimit)
        {              
            $value = $this->shoppingCart[$counter + $recordsPassed + $firstKey -1];
            $ID = $cartKeys[$counter + $recordsPassed - 1];
            $inStock = $this->DBHandler->getInStockValue($ID);
            if ($value > 0) 
            {                
                echo "<tr><td >". htmlentities($this->inventory[$ID]['prodName'])."</td>\n";
                echo "<td >".htmlentities($this->inventory[$ID]['prodDesc'])."</td>\n";
                printf("<td >R%.2f</td>\n", $this->inventory[$ID]['prodPrice']);
                echo "<td align='center'>".$this->shoppingCart[$ID]."</td>\n";
                printf("<td >R%.2f</td>\n", $this->inventory[$ID]['prodPrice'] * $this->shoppingCart[$ID]);
                echo "<td align='center'><a href = 'javascript:PopUp(\"" . $this->getImageURL($this->inventory[$ID]) . "\")'><img src='". $this->getImageURL($this->inventory[$ID]) . "' height='60' width='80'> </a></td>\n";
                $this->addRemoveDelete($ID, $timestamp, $inStock);
                $subtotal += ($this->inventory[$ID]['prodPrice'] * $this->shoppingCart[$ID]);
                $this->checkout[$ID] = $this->shoppingCart[$ID];
                $boughtItemIterator ++;
            }
            $counter++;    
        }
        $recordsArr[$this->cartPageIteration] = $counter - 1;
        $_SESSION['recordsArr'] = $recordsArr;
        
        echo("<tr>");
        $realCartSize = $this->getRealCartSize();
        if (($this->cartPageIteration + 1) < ($realCartSize / $this->pageLimit))
            echo("<td ><a href='" . $_SERVER['SCRIPT_NAME']. "?PHPSESSID=" . session_id() . "&nextPage=yes'>Next Page</a></td>");
        if ($this->cartPageIteration != 0)
            echo("<td ><a href='" . $_SERVER['SCRIPT_NAME']. "?PHPSESSID=" . session_id() ."&prevPage=yes''>Previous Page</a></td>");
        echo("</tr>");
        echo("<tr><td >Page: " . ($this->cartPageIteration + 1) . "</td></tr>");
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
                        if ($this->shoppingCart[$ID] == 0)
                        {
                            $this->movePageBack();
                        }
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

    
    function movePageBack()
    {
        if (isset($_SESSION['cartIteration']))
        {
            $realCartSize = $this->getRealCartSize();
            $maxIterator = (int)($realCartSize/$this->pageLimit);
            if ($maxIterator == $_SESSION['cartIteration'] && ($realCartSize % $this->pageLimit == 0))
            {
                $_SESSION['cartIteration'] = $_SESSION['cartIteration'] - 1;
            }
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
                        $this->movePageBack();
                            
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
            if(!empty($_GET['OrderToView']))
                $this->viewOrder();
            if(!empty($_GET['CancelOrder']))
                $this->cancelOrder();
    }

    private function viewOrder() 
    {
        $_SESSION['currentOrder'] = $_GET['OrderToView'];
        if (isset($this->statement)) {
            $this->setStatement();
        }
        else
            $this->setStatement();
        header('Location: generate-order.php');
    }
    
    private function setStatement() 
    {
        $replacement = $this->DBHandler->getOrderDetailsOnProdsOfCust();
        $this->statement = $replacement;
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
        $this->statement = $this->DBHandler->insertIntoOrderLine($this->checkout);
        $this->clearShoppingCart();
    }
    
    public function generateStatement() 
    {  
        $subTotal = 0;
        $grandTotal = 0;
        echo "<table width='100%'>";
        echo "<tr><td>";
        echo "<table width='100%'>";
        echo "<tr><td>Customer ID: </td><td>".$_SESSION['validUser']."</td></tr>";
        echo "<tr><td>Customer Name: </td><td>".$_SESSION['custFirstName']." ".$_SESSION['custLastName']."</td></tr>";
        echo "<tr><td>Customer Address: </td><td>".$_SESSION['custAddress']."</td></tr>";
        foreach ($this->statement as $key => $value) 
        {
            echo "<tr><td>Order ID: </td><td>".$this->statement[$key]['orderID']."</td></tr>";
            echo "<tr><td>Order Made: </td><td>". date("j-F-Y", strtotime($this->statement[$key]['orderDate']))."</td></tr>";
            break;
        }
        echo "</table></td>";
        echo "<td colspan='4'><img src='images/images/logo/collectables-logo.jpg' /></td></tr>";        
        echo "<tr>";
        echo "<td>Product ID</td>";
        echo "<td>Product Name</td>";
        echo "<td>Product Price</td>";
        echo "<td>Quantity</td>";
        echo "<td>Subtotal</td>";
        echo "</tr>";
        foreach ($this->statement as $key => $value) 
        {
            echo "<tr>";
            echo "<td>$key</td>";
            echo "<td>".$this->statement[$key]['prodName']."</td>";
            printf("<td> R%.2f </td>", $this->statement[$key]['prodPrice']);
            echo "<td>".$this->statement[$key]['quantity']."</td>";
            $subTotal = $this->statement[$key]['prodPrice'] * $this->statement[$key]['quantity'];
            $grandTotal += $subTotal;
            printf("<td> R%.2f </td>", $subTotal);
            echo "</tr>";
        }
        printf("<tr><td colspan='4' algin='right'>Grand Total</td><td> R%.2f </td></tr>", $grandTotal);
        echo "<tr><td colspan='5' algin='right'><a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() ."&CancelOrder=TRUE'><img src='images/images/cart/cancel-order.jpg' /></a></td></tr>";
        echo "</table>";
        
        $this->clearShoppingCart();
    }
    
    public function cancelOrder() 
    {
        $this->orderCancelStatus = $this->DBHandler->cancelOrder($this->statement);
        header('Location: view-orders.php');
    }
    
    public function viewOrders() 
    {
        $results = $this->DBHandler->getAllOrdersOfCustomer();
        if ($results != FALSE) 
        {  
            echo "<td colpan='3' style='font-size='larger''>Order ID: ".$_SESSION['currentOrder']." successfully deleted</td>";
            $this->orderTableHeaders();
            foreach ($results as $key => $value) 
            {
                
                if ($this->orderCancelStatus != FALSE) 
                {                    
                    $this->ordersContent($key, $results);              
                    $this->orderCancelStatus = FALSE;
                }
                else
                    $this->ordersContent($key, $results);  
            }
            echo "</table>";
        }
        else
            echo "<p style='font-size=larger'>You have not placed any orders yet</p>";
    }   
    
    private function ordersContent($key, $results) 
    {
        echo "<tr><td>" . $key . "</td>";
        echo "<td>" . date("j-F-Y", strtotime($results[$key]['orderDate'])) . "</td>";
        echo "<td><a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() . "&OrderToView=$key'><img src='images/images/cart/view-order-details.jpg' /></a></td></tr>";
    }


    private function orderTableHeaders()
    {
        echo "<table width= '100%' align='center'>\n".
                "<tr align='left'><th>Order ID</th><th>Order Date</th><th>&nbsp;</th><tr>";
    }
    
    public function clearShoppingCart() 
    {
        if (isset($this->shoppingCart)) 
        {
            unset($this->shoppingCart);
            $this->storeID = null;
        }
        $this->shoppingCart = array();
    }
}
?>
