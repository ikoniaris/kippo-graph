<?php

# Used for <title></title>
$page_title = "Network | Fast Visualization for your Kippo Based SSH Honeypot";

# Used for nav menu
$page_file = "kippo-ip.php";

# Custom head
$page_head = '
        <link rel="stylesheet" href="styles/tablesorter.css" type="text/css"/>
        <script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="scripts/jquery.tablesorter.pager.js"></script>
        <script type="text/javascript" src="scripts/kippo-ip.js"></script>';

require('include/header.php');
?>

<div class="wrapper">
    <div class="container">
        <div class="whitebox">
            <!-- ####################################################################################################### -->
            <h2>IP activity gathered from the honeypot system</h2>
            <hr>
            <?php
            # Author: ikoniaris, s0rtega

            require_once('config.php');
            require_once(DIR_ROOT . '/class/KippoIP.class.php');

            $kippoIP = new KippoIp();

            //-----------------------------------------------------------------------------------------------------------------
            //APT-GET COMMANDS
            //-----------------------------------------------------------------------------------------------------------------
            $kippoIP->printOverallIpActivity();
            //-----------------------------------------------------------------------------------------------------------------
            //END
            //-----------------------------------------------------------------------------------------------------------------

            ?>
            <!-- ####################################################################################################### -->
            <div id="extended-ip-info"></div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php
require(DIR_ROOT . "/include/footer.php");
?>