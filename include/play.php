<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
    <title>Kippo-Graph | Fast Visualization for your Kippo SSH Honeypot Stats</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link rel="stylesheet" href="../styles/layout.css" type="text/css"/>
    <link rel="stylesheet" href="../styles/playlog.css" type="text/css">
    <script type="text/javascript" src="../scripts/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="../scripts/BinFileReader.js"></script>
    <script type="text/javascript" src="../scripts/jquery.getUrlParam.js"></script>
</head>
<body id="top">
<div class="wrapper">
    <div id="header">
        <h1><a href="../index.php">Kippo-Graph</a></h1>
        <br/>

        <p>Fast Visualization for your Kippo SSH Honeypot Stats</p>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="topbar">
        <div class="fl_left">Version: 1.0 | Website: <a href="http://bruteforce.gr/kippo-graph">bruteforce.gr/kippo-graph</a>
        </div>
        <br class="clear"/>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="topnav">
        <ul class="nav">
            <li><a href="../index.php">Homepage</a></li>
            <li><a href="../kippo-graph.php">Kippo-Graph</a></li>
            <li><a href="../kippo-input.php">Kippo-Input</a></li>
            <li class="active"><a href="../kippo-playlog.php">Kippo-PlayLog</a></li>
            <li><a href="../kippo-ip.php">Kippo-Ip</a></li>
            <li><a href="../kippo-geo.php">Kippo-Geo</a></li>
            <li class="last"><a href="../gallery.php">Graph Gallery</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div class="container">
        <div class="whitebox">
            <!-- ####################################################################################################### -->
            <h2>Kippo TTY log</h2>
            <hr/>
            <?php
            #Author: ikoniaris, CCoffie
            #Website: bruteforce.gr/kippo-graph

            require_once('../config.php');
            require_once(DIR_ROOT . '/include/misc/xss_clean.php');

            $db_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); //host, username, password, database, port

            if (mysqli_connect_errno()) {
                echo 'Error connecting to the database: ' . mysqli_connect_error();
                exit();
            }

            $session = preg_replace('/[^-a-zA-Z0-9_]/', '', xss_clean($_GET['f']));

            $db_query = "SELECT ttylog, session FROM ttylog "
                . "WHERE session=" . "\"" . $session . "\"";

            $result = $db_conn->query($db_query);

            while ($row = $result->fetch_array(MYSQLI_BOTH)) {
                $log = base64_encode($row['ttylog']);
            }
			
			$db_query = "SELECT ip, starttime FROM sessions "
                . "WHERE id=" . "\"" . $session . "\"";
				
			$result = $db_conn->query($db_query);
			
			while ($row = $result->fetch_array(MYSQLI_BOTH)) {
                $ip = $row['ip'];
				$starttime = $row['starttime'];
            }
			

            $db_conn->close();
			
			echo "IP: <b>".$ip."</b> on ".str_replace(".000000","",$starttime)."<br /><br />";
            ?>

            <!-- Pass PHP variables to javascript - Please ignore the below section -->
            <script type="text/javascript">
                var log = "<?php echo $log; ?>";
            </script>
            <script type="text/javascript" src="../scripts/jspl.js"></script>

            <noscript>Please enable Javascript for log playback.<br /><br /></noscript>
            <div id="description">Error loading specified log.</div>
            <br />

            <div id="playlog"></div>
			<br /><br />
			<h3>Downloaded files:</h3>
			<?php
			
				$db_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); //host, username, password, database, port

				if (mysqli_connect_errno()) {
					echo 'Error connecting to the database: ' . mysqli_connect_error();
					exit();
				}
				
				$db_query = "SELECT input, TRIM(LEADING 'wget' FROM input) as file, "
				. "timestamp, session "
				. "FROM input "
				. "WHERE input LIKE '%wget%' AND input NOT LIKE 'wget' AND session = " . "\"" . $session . "\""
				. "ORDER BY timestamp DESC";

				$result =$db_conn->query($db_query);
				//echo 'Found '.$result->num_rows.' records';

				if ($result->num_rows > 0) {
					//We create a skeleton for the table
					$counter = 1;
					echo '<table><thead>';
					echo '<tr class="dark">';
					echo '<th>ID</th>';
					echo '<th>Timestamp</th>';
					echo '<th>Input</th>';
					echo '<th>File link</th>';
					echo '<th>NoVirusThanks</th>';
					echo '</tr></thead><tbody>';

					//For every row returned from the database we create a new table row with the data as columns
					while ($row = $result->fetch_array(MYSQLI_BOTH)) {
						echo '<tr class="light word-break">';
						echo '<td>' . $counter . '</td>';
						echo '<td>' . $row['timestamp'] . '</td>';
						echo '<td>' . xss_clean($row['input']) . '</td>';
						$file_link = trim($row['file']);
						// If the link has no "http://" in front, then add it
						if (substr(strtolower($file_link), 0, 4) !== 'http') {
							$file_link = 'http://' . $file_link;
						}
						echo '<td><a href="http://anonym.to/?' . $file_link . '" target="_blank"><img class="icon" src="images/warning.png"/>http://anonym.to/?' . $file_link . '</a></td>';
						echo '<td><a href="http://vscan.novirusthanks.org/?url=' . $file_link . '&submiturl=' . $file_link . '" target="_blank"><img class="icon" src="images/novirusthanks.ico"/>Scan File</a></td>';
						echo '</tr>';
						$counter++;
					}

					//Close tbody and table element, it's ready.
					echo '</tbody></table>';
					echo '<hr /><br />';
				}
				else
				{
					echo "No files have been downloaded in this session.<br /><br />";
				}
			?>
			<?php
			
					if(!empty($ip) && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
					{
						if(function_exists('exec')) 
						{
							exec("dig -x ".$ip." +additional @130.95.128.1 2>&1", $dig, $returnValue);
							exec("host ".$ip." 2>&1", $host, $returnValue);
						}
						
						
						require_once(DIR_ROOT . '/include/geoplugin/geoplugin.class.php');
					
						$geoplugin = new geoPlugin();
						$geoplugin->locate($ip);
						
						if(!empty($host) || !empty($dig))
						{
							
							echo "<h3>Additional informations about IP:</h3>";
								
							if(!empty($dig))
							{
								echo "<b>dig</b> data:<br />\n";
								echo "<pre>";
								foreach($dig as $parse)
								{
									echo $parse."\n";
								}
								echo "</pre>\n\n";
							}
							if(!empty($host))
							{
								echo "<b>host</b> data:<br />\n";
								echo "<pre>";
								foreach($host as $parse)
								{
									echo $parse."\n";
								}
								echo "</pre>\n\n";
							}
						}
					}
									
			?>
			
			<br />
			Google Maps:<br />
			<div id="map" style="width:100%;height:400px;margin-top:10px;"></div>

			<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>	
			<script type="text/javascript">

				// Define the latitude and longitude positions
				var latitude = parseFloat("<?php echo $geoplugin->latitude; ?>");
				var longitude = parseFloat("<?php echo $geoplugin->longitude; ?>");
				var latlngPos = new google.maps.LatLng(latitude, longitude);
				
				// Set up options for the Google map
				var myOptions = {
					zoom: 8,
					center: latlngPos,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
					
				// Define the map
				map = new google.maps.Map(document.getElementById("map"), myOptions);
					
				// Add the marker
				var marker = new google.maps.Marker({
					position: latlngPos,
					map: map,
					title: "Attacker"
				});
					
			</script>

			
			
            <!-- ####################################################################################################### -->
            <div class="clear"></div>
        </div>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="copyright">
        <p class="fl_left">Copyright &copy; 2011 - 2014 - All Rights Reserved - <a
                href="http://bruteforce.gr/kippo-graph">Kippo-Graph</a></p>

        <p class="fl_right">Thanks to <a href="http://www.os-templates.com/" title="Free Website Templates">OS
                Templates</a></p>
        <br class="clear"/>
    </div>
</div>
<script type="text/javascript" src="../scripts/superfish.js"></script>
<script type="text/javascript">
    jQuery(function () {
        {
            {
                jQuery('ul.nav').superfish();
            }
        }
    }
</script>
</body>
</html>
