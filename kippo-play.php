<?php

# Used for <title></title>
$page_title = "TTY Log Playback | Fast Visualization for your Kippo Based SSH Honeypot";

# Used for nav menu
$page_file = "kippo-play.php";

# Custom head
$page_head = '
        <link rel="stylesheet" href="./styles/playlog.css" type="text/css">
        <script type="text/javascript" src="scripts/BinFileReader.js"></script>
        <script type="text/javascript" src="scripts/jquery.getUrlParam.js"></script>';

require('include/header.php');
?>

<div class="wrapper">
    <div class="container">
        <div class="whitebox">
            <!-- ####################################################################################################### -->
            <h2>TTY log</h2>
            <hr>
            <?php
            # Author: ikoniaris, CCoffie

            require_once('config.php');
            require_once(DIR_ROOT . '/include/rb.php');
            require_once(DIR_ROOT . '/include/misc/xss_clean.php');
            require_once(DIR_ROOT . '/include/maxmind/geoip2.phar');
            require_once(DIR_ROOT . '/include/geoplugin/geoplugin.class.php');

            R::setup('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

            $xss_clean = new xssClean();
            $session = preg_replace('/[^-a-zA-Z0-9_]/', '', $xss_clean->clean_input($_GET['f']));


            // Sessions
            $db_query = "SELECT ip, starttime, endtime FROM sessions WHERE id='$session'";
            $rows = R::getAll($db_query);
            foreach ($rows as $row) {
                $ip = $row['ip'];
                $starttime = $row['starttime'];
                $endtime = $row['endtime'];
            }
            $length = round(abs(strtotime($endtime) - strtotime($starttime)) / 60);

            // Sessions
            $db_query = "SELECT count(ttylog) as count FROM ttylog WHERE session='$session'";
            $rows = R::getAll($db_query);
            foreach ($rows as $row) {
                $countsession = $row['count'];
            }

            // Attacker's IP
            $db_query = "SELECT count(DISTINCT id) as count FROM sessions WHERE ip='$ip'";
            $rows = R::getAll($db_query);
            foreach ($rows as $row) {
                $countip = $row['count'];
            }

            // Any logins
            $db_query = "SELECT count(session) as count FROM auth WHERE session='$session'";
            $rows = R::getAll($db_query);
            foreach ($rows as $row) {
                $countauths = $row['count'];
            }

            // All valid logins
            $db_query = "SELECT username, password FROM auth WHERE session='$session' and success = '1'";
            $rows = R::getAll($db_query);
            foreach ($rows as $row) {
                $username = $row['username'];
                $password = $row['password'];
            }

            // Input
            $db_query = "SELECT count(session) as count FROM input WHERE session='$session'";
            $rows = R::getAll($db_query);
            foreach ($rows as $row) {
                $countinput = $row['count'];
            }

            // Failed input commands
            $db_query = "SELECT count(session) as count FROM input WHERE session='$session' and success = '0'";
            $rows = R::getAll($db_query);
            foreach ($rows as $row) {
                $countinputfailed = $row['count'];
            }

            // Fingerprints
            $db_query = "SELECT count(session) as count FROM keyfingerprints WHERE session='$session'";
            $rows = R::getAll($db_query);
            foreach ($rows as $row) {
                $countfinger = $row['count'];
            }

            // TTY log file(s)
            $db_query = "SELECT ttylog, session FROM ttylog WHERE session='$session'";
            $rows = R::getAll($db_query);
            $log = "";
            foreach ($rows as $row) {
                if (strtoupper(BACK_END_ENGINE) === 'COWRIE') {
                    if (function_exists('shell_exec')) {
                        $log_path = BACK_END_PATH . "/" . $row['ttylog'];

                        if (file_exists($log_path) && is_readable($log_path)) {
                            if (strtoupper(PLAYBACK_SYSTEM) != "PYTHON")
                                $log .= shell_exec("base64 -w 0 " . $log_path . " 2>&1");
                            else {
                                $log .= "*** Log: $log_path ***\n";
                                $log .= shell_exec("python /opt/cowrie/utils/playlog.py -m 0 " . $log_path);
                                $log .= "\n\n*** End Of Log ***\n\n";
                            }
                        } else
                            $errors .= "Unable to access: " . $log_path . "<br />\n";
                    } else
                        $errors .= "Missing PHP function, shell_exec<br />\n";
                } else {
                    if (strtoupper(PLAYBACK_SYSTEM) != "PYTHON")
                        $log .= base64_encode($row['ttylog']);
                    else
                        $log .= $row['ttylog'];
                }
            }

            if (!empty($ip) && empty($errors)) {
                echo "IP: <b>" . $ip . "</b> on " . str_replace(".000000", "", $starttime) . "<br /><br />";

                if (strtoupper(PLAYBACK_SYSTEM) != "PYTHON") { ?>
                <!-- Pass PHP variables to javascript - Please ignore the below section -->
                <script type="text/javascript">
                    var log = "<?php echo $log; ?>";
                </script>
                <script type="text/javascript" src="scripts/jspl.js"></script>

                <?php
                if (($countsession > 1) && (strtoupper(PLAYBACK_SYSTEM) != "PYTHON"))
                    echo "<h1>Issue using JavaScript playback and having multiple log ($countsession files).</h1><br />\n"
                ?>

                <noscript>Please enable Javascript for log playback.<br /><br /></noscript>
                <div id="description">Error loading specified log.</div>
                <br />
            <?php } else { ?>
                <pre id="description"><?php echo htmlentities($log); ?></pre>
            <?php } ?>

            <div id="playlog"></div>
            <br /><br />

            <?php
            echo "<hr>";
            echo "<h3>Information about the attacker and session:</h3>";

            // Display out
            echo "Session ID: <b>" . $session . "</b><br />\n";

            if (!empty($starttime)){
                $length = round(abs(strtotime($endtime) - strtotime($starttime)) / 60, 1);
                echo "Timestamp: <b>" . str_replace(".000000", "", $starttime) . "</b> (<b>" . $length . "</b> minutes)<br />\n";
            }
            if (!empty($ip))
                echo "Attacker's IP: <b>" . $ip . "</b><br />\n";
            if (!empty($countsession))
                echo "Number of sessions for the attacker's IP: <b>" . $countsession . "</b><br />\n";
            if (!empty($countip))
                echo "Number of times the attacker's IP have been seen: <b>" . $countip . "</b><br />\n";
            if (!empty($countauths))
                echo "Total login attempts: <b>" . $countauths . "</b><br />\n";
            if (!empty($username) && !empty($password))
                echo "SSH credentials: <b>" . $username . "</b> / <b>" . $password . "</b><br />\n";
            if (!empty($countfinger))
                echo "Attacker's SSH fingerprints: <b>" . $countfinger . "</b><br />\n";
            if (!empty($countinput))
                echo "Total number of input commands: <b>" . $countinput . "</b> (<b>" . $countinputfailed . "</b> failed commands)<br />\n";
            ?>
            <br />

            <hr>
            <h3>Downloaded files:</h3>
            <?php

            $db_query = "SELECT input, TRIM(LEADING 'wget' FROM input) as file, timestamp, session
                  FROM input
                  WHERE input LIKE '%wget%' AND input NOT LIKE 'wget' AND session = '$session'
                  ORDER BY timestamp DESC";

            $rows = R::getAll($db_query);

            if (count($rows)) {
                // We create a skeleton for the table
                $counter = 1;
                echo '<table><thead>';
                echo '<tr class="dark">';
                echo '<th>ID</th>';
                echo '<th>Timestamp</th>';
                echo '<th>Input</th>';
                echo '<th>File link</th>';
                echo '<th>Kippo-Scanner</th>';
                echo '</tr></thead><tbody>';

                // For every row returned from the database we create a new table row with the data as columns
                foreach ($rows as $row) {
                    echo '<tr class="light word-break">';
                    echo '<td>' . $counter . '</td>';
                    echo '<td>' . $row['timestamp'] . '</td>';
                    echo '<td>' . $xss_clean->clean_input($row['input']) . '</td>';
                    //PHP < 5.4 doesn't like array dereferencing
                    $file_link_array = explode(" ", trim($xss_clean->clean_input($row['file'])));
                    $file_link = $file_link_array[0];
                    // If the link has no "http://" in front, then add it
                    if (substr(strtolower($file_link), 0, 4) !== 'http') {
                        $file_link = 'http://' . $file_link;
                    }
                    echo '<td><a href="http://anonym.to/?' . $file_link . '" target="_blank"><img class="icon" src="images/warning.png"/>http://anonym.to/?' . $file_link . '</a></td>';
                    echo '<td><a href="kippo-scanner.php?file_url=' . $file_link . '" target="_blank">Scan File</a></td>';
                    echo '</tr>';
                    $counter++;
                }

                // Close tbody and table element, it's ready.
                echo '</tbody></table>';
                echo '<hr><br />';
            } else {
                echo "No files have been downloaded in this session.<br /><br />";
            }

            R::close();

            // Additional information about IP address
            if (!empty($ip) && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                if (function_exists('exec')) {
                    exec("host " . $ip . " 2>&1", $host, $returnValue);
                    exec("dig -x " . $ip . " +additional @8.8.8.8 2>&1", $dig, $returnValue);
                }

                if (!empty($host) || !empty($dig)) {
                    echo "<hr>";
                    echo "<h3>Additional information about IP:</h3>";

                    if (!empty($host)) {
                        echo "<b>host</b> data:<br />\n";
                        echo "<pre>";
                        foreach ($host as $parse) {
                            echo $parse . "\n";
                        }
                        echo "</pre>\n\n";
                    }
                    if (!empty($dig)) {
                        echo "<b>dig</b> data:<br />\n";
                        echo "<pre>";
                        foreach ($dig as $parse) {
                            echo $parse . "\n";
                        }
                        echo "</pre>\n\n";
                    }

                }

                // Geolocate the IP
                $latitude = NULL;
                $longitude = NULL;
                if (GEO_METHOD == 'LOCAL') {
                    $maxmind = new \GeoIp2\Database\Reader(DIR_ROOT . '/include/maxmind/GeoLite2-City.mmdb');
                    try {
                        $geodata = $maxmind->city($ip);
                        $latitude = $geodata->location->latitude;
                        $longitude = $geodata->location->longitude;
                    } catch (\GeoIp2\Exception\GeoIp2Exception $e) {
                        echo "<br />Unable to geolocate IP using MaxMind.";
                    }
                } else if (GEO_METHOD == 'GEOPLUGIN') {
                    $geoplugin = new geoPlugin();
                    $geoplugin->locate($ip);
                    $latitude = $geoplugin->latitude;
                    $longitude = $geoplugin->longitude;
                }

                // If geolocation succeeded show Google Map
                if ($latitude && $longitude) {
                    ?>

                    <br />
                    <b>Google Map</b>:<br />

                    <div id="map" style="width:100%;height:400px;margin-top:10px;"></div>

                    <script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
                    <script type="text/javascript">

                        // Define the latitude and longitude positions
                        var latitude = parseFloat("<?php echo $latitude; ?>");
                        var longitude = parseFloat("<?php echo $longitude; ?>");
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
                <?php
                } //google map
            } //additional IP info

            } else {    // if (!empty($ip)) {
                echo "<b>Error locating session</b> (" . $session . ")<br /><br />";
                if (!empty($errors))
                    echo $errors . "<br /><br />";
                echo "<hr /><br />";
            }
            ?>

            <!-- ####################################################################################################### -->
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php
require('include/footer.php');
?>
