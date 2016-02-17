<?php

# Used for <title></title>
$page_title = "Playlog Overview | Fast Visualization for your Kippo Based SSH Honeypot";

# Used for nav menu
$page_file = "kippo-playlog.php";

# Custom head
$page_head = '
    <link rel="stylesheet" href="styles/tablesorter.css" type="text/css"/>
    <script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="scripts/jquery.tablesorter.pager.js"></script>

    <script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="scripts/jquery.tablesorter.pager.js"></script>
    <script type="text/javascript" src="scripts/kippo-playlog.js"></script>';

require('include/header.php');
?>

<div class="wrapper">
    <div class="container">
        <div class="whitebox">
            <!-- ####################################################################################################### -->
            <h2>Replay input by attackers captured by the honeypot system</h2>
            <hr>
            <?php
            # Author: ikoniaris, CCoffie

            require_once('config.php');
            require_once(DIR_ROOT . '/class/KippoPlayLog.class.php');

            $kippoPlayLog = new KippoPlayLog();

            //-----------------------------------------------------------------------------------------------------------------
            //List all log files
            //-----------------------------------------------------------------------------------------------------------------
            $kippoPlayLog->printLogs();
            //-----------------------------------------------------------------------------------------------------------------
            //END
            //-----------------------------------------------------------------------------------------------------------------
            ?>
            <!-- ####################################################################################################### -->
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php
require('include/footer.php');
?>
