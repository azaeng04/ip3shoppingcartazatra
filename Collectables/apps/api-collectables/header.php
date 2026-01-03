<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    require_once(__DIR__ . '/db-layer/DBHander.php');
    $DBConnection = new DBHander();
    $userDetails = null;
    if (class_exists("DBHander"))
    {
        if (isset($_SESSION['validUser']))
        {
           $DBConnection->connectToDB();
           $userID = $_SESSION['validUser'];
           $userDetails = $DBConnection->getUserDetails($userID);
        }      
    }
    else 
    {
         $ErrorMsgs[] = "The DBHanger class is not available!";
         echo '<p>'.$ErrorMsgs[0].'</p>';
         $userID = NULL;
    }
?>

<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<link rel="stylesheet" href="styles/style.css" media="screen">
<!--[if lte IE 7]><link rel="stylesheet" href="styles/style.ie7.css" media="screen" /><![endif]-->
<link rel="stylesheet" href="styles/style.responsive.css" media="all">


<script src="jscript/jquery.js"></script>
<script src="jscript/script.js"></script>
<script src="jscript/script.responsive.js"></script>
<meta name="description" content="Description">
<meta name="keywords" content="Keywords">


<style>.art-content .art-postcontent-0 .layout-item-0 { border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#B6C5C8; padding-right: 10px;padding-left: 10px;  }
.ie7 .post .layout-cell {border:none !important; padding:0 !important; }
.ie6 .post .layout-cell {border:none !important; padding:0 !important; }

</style>
</head>
<body>
<div id="art-main">
<header class="art-header clearfix">
    <div class="art-shapes">
        <h1 class="art-headline" data-left="0%">
            <a href="home.php">Collectables</a>
        </h1>
        
        <div align="right">
            <?php if ($userDetails !== null): ?>
                <label style="font-size: larger;">You are logged in as: </label>
                <label style="font-size: x-large;">
                    <?php 
                        $_SESSION['custFirstName'] =  $userDetails["firstName"];
                        $_SESSION['custLastName'] =  $userDetails["lastName"];
                        $_SESSION['custAddress'] =  $userDetails["address"];
                        echo $userDetails["firstName"]." ".$userDetails["lastName"]; 
                    ?>
                </label>
            <?php else: ?>
                <a href="login.php" style="font-size: large">Log in</a>
            <?php endif; ?>
        </div>
        <?php if ($userDetails !== null): ?>
        <div align="right">
            <a href="logout.php" style="font-size: large">Log out</a>
        </div>
        <?php endif; ?>
    </div>       
</header>