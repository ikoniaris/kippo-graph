<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
    <title>Kippo-Graph | Fast Visualization for your Kippo SSH Honeypot Stats</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link rel="stylesheet" href="styles/layout.css" type="text/css"/>
    <script type="text/javascript" src="scripts/jquery-1.4.1.min.js"></script>
</head>
<body id="top">
<div class="wrapper">
    <div id="header">
        <h1><a href="index.php">Kippo-Graph</a></h1>
        <br/>

        <p>Fast Visualization for your Kippo SSH Honeypot Stats</p>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="topbar">
        <div class="fl_left">Version: 1.5.1 | Website: <a href="http://bruteforce.gr/kippo-graph">bruteforce.gr/kippo-graph</a>
        </div>
        <br class="clear"/>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="topnav">
        <ul class="nav">
            <li><a href="index.php">Homepage</a></li>
            <li><a href="kippo-graph.php">Kippo-Graph</a></li>
            <li class="active"><a href="kippo-input.php">Kippo-Input</a></li>
            <li><a href="kippo-playlog.php">Kippo-PlayLog</a></li>
            <li><a href="kippo-ip.php">Kippo-Ip</a></li>
            <li><a href="kippo-geo.php">Kippo-Geo</a></li>
            <li class="last"><a href="gallery.php">Graph Gallery</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div class="container">
        <div class="whitebox">
            <!-- ####################################################################################################### -->
            <h2>Input presentation and statistics gathered from the honeypot system</h2>
            <hr/>
            <?php
            # Author: ikoniaris

            require_once('config.php');
            require_once(DIR_ROOT . '/class/KippoInput.class.php');

            $kippoInput = new KippoInput();

            //-----------------------------------------------------------------------------------------------------------------
            //OVERALL HONEYPOT ACTIVITY
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printOverallHoneypotActivity();
            //-----------------------------------------------------------------------------------------------------------------
            //HUMAN ACTIVITY BUSIEST DAYS
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printHumanActivityBusiestDays();
            //-----------------------------------------------------------------------------------------------------------------
            //HUMAN ACTIVITY PER DAY
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printHumanActivityPerDay();
            //-----------------------------------------------------------------------------------------------------------------
            //HUMAN ACTIVITY PER WEEK
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printHumanActivityPerWeek();
            //-----------------------------------------------------------------------------------------------------------------
            //TOP 10 OVERALL INPUT
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printTop10OverallInput();
            //-----------------------------------------------------------------------------------------------------------------
            //TOP 10 SUCCESSFUL INPUT
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printTop10SuccessfulInput();
            //-----------------------------------------------------------------------------------------------------------------
            //TOP 10 FAILED INPUT
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printTop10FailedInput();
            //-----------------------------------------------------------------------------------------------------------------
            //PASSWD COMMANDS
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printPasswdCommands();
            //-----------------------------------------------------------------------------------------------------------------
            //WGET COMMANDS
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printWgetCommands();
            //-----------------------------------------------------------------------------------------------------------------
            //EXECUTED SCRIPTS
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printExecutedScripts();
            //-----------------------------------------------------------------------------------------------------------------
            //INTERESTING COMMANDS
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printInterestingCommands();
            //-----------------------------------------------------------------------------------------------------------------
            //APT-GET COMMANDS
            //-----------------------------------------------------------------------------------------------------------------
            $kippoInput->printAptGetCommands();
            //-----------------------------------------------------------------------------------------------------------------
            //END
            //-----------------------------------------------------------------------------------------------------------------


            ?>
            <!-- ####################################################################################################### -->
            <div class="clear"></div>
        </div>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="copyright">
        <p class="fl_left">Copyright &copy; 2011 - 2015 - All Rights Reserved - <a
                href="http://bruteforce.gr/kippo-graph">Kippo-Graph</a></p>

        <p class="fl_right">Thanks to <a href="http://www.os-templates.com/" title="Free Website Templates">OS
                Templates</a></p>
        <br class="clear"/>
    </div>
</div>
<script type="text/javascript" src="scripts/superfish.js"></script>
<script type="text/javascript">
    jQuery(function () {
        jQuery('ul.nav').superfish();
    });
</script>
</body>
</html>