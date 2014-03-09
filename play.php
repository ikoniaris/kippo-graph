<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<title>Kippo-Graph | Fast Visualization for your Kippo SSH Honeypot Stats</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<link rel="stylesheet" href="styles/playlog.css" type="text/css">
<script type="text/javascript" src="scripts/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="scripts/BinFileReader.js"></script>
<script type="text/javascript" src="scripts/jquery.getUrlParam.js"></script>
</head>
<body id="top">
<div class="wrapper">
  <div id="header">
    <h1><a href="index.php">Kippo-Graph</a></h1>
    <br/><p>Fast Visualization for your Kippo SSH Honeypot Stats</p>
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
  <div id="topbar">
    <div class="fl_left">Version: 0.9 | Website: <a href="http://bruteforce.gr/kippo-graph">bruteforce.gr/kippo-graph</a></div>
    <br class="clear" />
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
  <div id="topnav">
    <ul class="nav">
      <li><a href="index.php">Homepage</a></li>
      <li><a href="kippo-graph.php">Kippo-Graph</a></li>
      <li><a href="kippo-input.php">Kippo-Input</a></li>
      <li class="active"><a href="kippo-playlog.php">Kippo-PlayLog</a></li>
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
          <h2>Kippo TTY Log</h2>
          <hr />
<?php
#Package: Kippo-Graph
#Version: 0.9
#Author: ikoniaris, CCoffie
#Website: bruteforce.gr/kippo-graph

require_once('config.php');

$db_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); //host, username, password, database, port

if (mysqli_connect_errno())
{
    echo 'Error connecting to the database: ' . mysqli_connect_error();
    exit();
}

$session = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['f']);

$db_query = "SELECT ttylog, session FROM ttylog "
    . "WHERE session=" . "\"" . $session . "\"";

$result = $db_conn->query($db_query);

while ($row = $result->fetch_array(MYSQLI_BOTH)) {
	$log = base64_encode($row['ttylog']);
}

$db_conn->close();
?>

<!-- Pass PHP variables to javascript - Please ignore the below section -->
<script type="text/javascript">
  var log = "<?php echo $log; ?>";
</script>
<script type="text/javascript" src="scripts/jspl.js"></script>

      <div id="description">Error loading specified log.</div>
      <br>
      <div id="playlog"></div>

      <!-- ####################################################################################################### -->
      <div class="clear"></div>
    </div>
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
  <div id="copyright">
    <p class="fl_left">Copyright &copy; 2011 - 2013 - All Rights Reserved - <a href="http://bruteforce.gr/kippo-graph">Kippo-Graph</a></p>
    <p class="fl_right">Thanks to <a href="http://www.os-templates.com/" title="Free Website Templates">OS Templates</a></p>
    <br class="clear" />
  </div>
</div>
<script type="text/javascript" src="scripts/superfish.js"></script>
<script type="text/javascript">
jQuery(function () {{{
    jQuery('ul.nav').superfish();
}};
</script>
</body>
</html>
