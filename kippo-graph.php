<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<title>Kippo-Graph | Fast Visualization for your Kippo SSH Honeypot Stats</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<script type="text/javascript" src="scripts/jquery-1.4.1.min.js"></script>
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
    <div class="fl_left">Version: 0.7.7 | Website: <a href="http://bruteforce.gr/kippo-graph">bruteforce.gr/kippo-graph</a></div>
    <br class="clear" />
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
  <div id="topnav">
    <ul class="nav">
      <li><a href="index.php">Homepage</a></li>
      <li class="active"><a href="kippo-graph.php">Kippo-Graph</a></li>
	  <li><a href="kippo-input.php">Kippo-Input</a></li>
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
      <!-- ############################# -->
	  <h2>Overall honeypot activity</h2>
	  <hr />
<?php
#Package: Kippo-Graph
#Version: 0.7.7
#Author: ikoniaris
#Website: bruteforce.gr/kippo-graph

require_once('config.php');
require_once('class/KippoGraph.class.php');

$kippoGraph = new KippoGraph();

$kippoGraph->createTop10Passwords();
$kippoGraph->createTop10Usernames();
$kippoGraph->createTop10Combinations();
$kippoGraph->createSuccessRation();
$kippoGraph->createMostSuccessfulLoginsPerDay();
$kippoGraph->createSuccessesPerDay();
$kippoGraph->createSuccessesPerWeek();
$kippoGraph->createNumberOfConnectionsPerIP();
$kippoGraph->createSuccessfulLoginsFromSameIP();
$kippoGraph->createMostProbesPerDay();
$kippoGraph->createProbesPerDay();
$kippoGraph->createProbesPerWeek();
$kippoGraph->createTop10SSHClients();

//-----------------------------------------------------------------------------------------------------------------
//OVERALL HONEYPOT ACTIVITY
//-----------------------------------------------------------------------------------------------------------------
$kippoGraph->printOverallHoneypotActivity();

echo '<br /><br />';
?>
	  <h2>Graphical statistics generated from your Kippo honeypot database<br/><!--For more, visit the other pages/components of this package--></h2> 
      <div class="portfolio">
        <div class="fl_left">
          <h2>Top 10 passwords</h2>
          <p>This vertical bar chart diplays the top 10 passwords that attackers try when attacking the system.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/top10_passwords.png" alt="" /></div>
        <div class="clear"></div>
      </div>
      <!-- ############################# -->
      <div class="portfolio">
        <div class="fl_left">
          <h2>Top 10 usernames</h2>
          <p>This vertical bar chart diplays the top 10 usernames that attackers try when attacking the system.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/top10_usernames.png" alt="" /></div>
        <div class="clear"></div>
      </div>
      <!-- ############################# -->
      <div class="portfolio">
        <div class="fl_left">
          <h2>Top 10 user-pass combos</h2>
          <p>This vertical bar chart diplays the top 10 username and password combinations that attackers try when attacking the system.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/top10_combinations.png" alt="" /></div>
		<div class="fl_left">
          <p>This pie chart diplays the top 10 username and password combinations that attackers try when attacking the system.</p>
        </div>
		<div class="fl_right"><img src="generated-graphs/top10_combinations_pie.png" alt="" /></div>
        <div class="clear"></div>
      </div>
      <!-- ############################# -->
      <div class="portfolio">
        <div class="fl_left">
          <h2>Success ratio</h2>
          <p>This vertical bar chart diplays the overall attack success ratio for the particular honeypot system.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/success_ratio.png" alt="" /></div>
        <div class="clear"></div>
      </div>
      <!-- ############################# -->
      <div class="portfolio">
        <div class="fl_left">
          <h2>Successes per day/week</h2>
          <p>This vertical bar chart diplays the most successful break-ins per day (Top 20) for the particular honeypot system. The numbers indicate how many times correct credentials were given by attackers.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/most_successful_logins_per_day.png" alt="" /></div>
		<div class="clear"></div>
		<div class="fl_left">
          <p>This line chart diplays the daily successes on the honeypot system. Spikes indicate successful entries over a weekly period.<br/><br/><strong>Warning:</strong> Dates with zero successes are not displayed.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/successes_per_day.png" alt="" /></div>
        <div class="clear"></div>
		<div class="fl_left">
          <p>This line chart diplays the weekly successes on the honeypot system. Curves indicate successful entries over a weekly period.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/successes_per_week.png" alt="" /></div>
        <div class="clear"></div>
	  </div>
      <!-- ############################# -->
      <div class="portfolio">
        <div class="fl_left">
          <h2>Connections per IP</h2>
          <p>This vertical bar chart diplays the top 10 unique IPs ordered by the number of overall connections to the system.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/connections_per_ip.png" alt="" /></div>
        <div class="fl_left">
          <p>This pie chart diplays the top 10 unique IPs ordered by the number of overall connections to the system.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/connections_per_ip_pie.png" alt="" /></div>
		<div class="clear"></div>
      </div>
	  <!-- ############################# -->
      <div class="portfolio">
        <div class="fl_left">
          <h2>Successful logins from the same IP</h2>
          <p>This vertical bar chart diplays the number of successful logins from the same IP address (Top 20). The numbers indicate how many times the particular source opened a successful session.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/logins_from_same_ip.png" alt="" /></div>
        <div class="clear"></div>
      </div>
      <!-- ############################# -->
      <div class="portfolio">
        <div class="fl_left">
          <h2>Probes per day/week</h2>
          <p>This horizontal bar chart diplays the most probes per day (Top 20) against the honeypot system.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/most_probes_per_day.png" alt="" /></div>
		<div class="fl_left">
          <p>This line chart diplays the daily activity on the honeypot system. Spikes indicate hacking attempts.<br/><br/><strong>Warning:</strong> Dates with zero probes are not displayed.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/probes_per_day.png" alt="" /></div>
		<div class="fl_left">
          <p>This line chart diplays the weekly activity on the honeypot system. Curves indicate hacking attempts over a weekly period.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/probes_per_week.png" alt="" /></div>
        <div class="clear"></div>
      </div>
      <!-- ############################# -->
      <div class="portfolio">
        <div class="fl_left">
          <h2>Top 10 SSH clients</h2>
          <p>This vertical bar chart diplays the top 10 SSH clients used by attackers during their hacking attempts.</p>
        </div>
        <div class="fl_right"><img src="generated-graphs/top10_ssh_clients.png" alt="" /></div>
        <div class="clear"></div>
      </div>
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
jQuery(function () {
    jQuery('ul.nav').superfish();
});
</script>
</body>
</html>