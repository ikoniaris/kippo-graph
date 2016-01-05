<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
    <title><?php echo $page_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link rel="stylesheet" href="styles/layout.css" type="text/css"/>
    <script type="text/javascript" src="scripts/jquery-1.4.1.min.js"></script>
    <?php if(isset($page_head)) {echo $page_head;} ?>
</head>
<body id="top">
<div class="wrapper">
    <div id="header">
        <h1><a href="index.php">Kippo-Graph</a></h1>
        <br />

        <p>Fast Visualization for your Kippo Based SSH Honeypot</p>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="topbar">
        <div class="fl_left">Version: 1.5.1 | Website: <a href="https://bruteforce.gr/kippo-graph">bruteforce.gr/kippo-graph</a>
        </div>
        <br class="clear"/>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="topnav">
        <ul class="nav">
            <li <?php if ($page_file == "kippo-graph.php") {echo 'class="active"';}?>><a href="kippo-graph.php">Overview</a></li>
            <li <?php if ($page_file == "kippo-input.php") {echo 'class="active"';}?>><a href="kippo-input.php">Input</a></li>
            <li <?php if ($page_file == "kippo-playlog.php") {echo 'class="active"';}?>><a href="kippo-playlog.php">PlayLog</a></li>
            <li <?php if ($page_file == "kippo-ip.php") {echo 'class="active"';}?>><a href="kippo-ip.php">Network</a></li>
            <li <?php if ($page_file == "kippo-geo.php") {echo 'class="active"';}?>><a href="kippo-geo.php">GeoIP</a></li>
            <li <?php if ($page_file == "gallery.php") {echo 'class="active"';}?>><a href="gallery.php">Graph Gallery</a></li>
            <li class="<?php if ($page_file == "index.php") {echo 'active ';}?>last"><a href="index.php">Changelog</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<!-- ####################################################################################################### -->