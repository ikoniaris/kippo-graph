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
            <li class="active"><a href="index.php">Homepage</a></li>
            <li><a href="kippo-graph.php">Kippo-Graph</a></li>
            <li><a href="kippo-input.php">Kippo-Input</a></li>
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
            <h3>Thank you for using Kippo-Graph!</h3>

            <p>
            <hr>
            </p>
            <p align=center><img src="images/kippo-graph-img.png" alt="Kippo-Graph" height=215 width=320></p>

            <p align=center>
                <?php
                # Author: ikoniaris

                require_once('config.php');
                include_once('include/misc/versionCheck.php');

                echo "Version: " . VERSION;
                if (UPDATE_CHECK == 'YES') {
                    if (isUpToDate()) {
                        echo LATEST_VERSION;
                    } else {
                        echo NEW_VERSION_AVAILABLE;
                    }
                }
                ?>
            </p>

            <p>&nbsp;</p>

            <p><strong>CHANGES:</strong></p>

            <p>Version 1.5.1:<br/>+ Various important fixes.</p>

            <p>Version 1.5:<br/>+ Added configuration option for realtime statistics.
                <br/>+ Added cron example to update charts in the background.
                <br/>+ Updated RedBeanPHP to version 4.1.4.
                <br/>+ Various small fixes.
            </p>

            <p>Version 1.4.2:<br/>+ Fixed Kippo-Playlog's results and added sorting to the table.
                <br/>+ Added geo method selection in play.php.
                <br/>+ Various small fixes.
            </p>

            <p>Version 1.4.1:<br/>+ Added check for Tor exit nodes.</p>

            <p>Version 1.4:<br/>+ Added support for local MaxMind geolocation instead of geoplugin.com.
                <br/>+ Various small fixes.
                <br/>+ Added favicon.ico.
                <br/>- Removed README.txt.
            </p>

            <p>Version 1.3:<br/>+ Switched all SQL operations to the RedBeanPHP library.
                <br/>+ Reformatted and standardized all SQL queries.
                <br/>+ Added VirusTotal IP lookup in Kippo-Geo.
                <br/>+ Fixed XSS problem in Kippo-IP (AJAX requester).
                <br/>+ Updated README.md file.
                <br/>- Removed manual DIR_ROOT configuration.
            </p>

            <p>Version 1.2:<br/>+ Substituted the defunct NoVirusThanks with Gary's Hood Online Virus Scanner.
                <br/>+ Added Kippo-Scanner module to handle (future) AV and anti-malware submissions.
                <br/>+ Added IP-address.com's tracer to Kippo-Geo IPs.
                <br/>+ Added Czech language support.
                <br/>+ Added robots.txt file to disallow crawling by bots.
                <br/>+ Added .gitgnore to exclude config.php file from VCS.</p>

            <p>Version 1.1:<br/>+ Added downloads, dig output and geolocation of current session in Kippo-Playlog.</p>

            <p>Version 1.0:<br/>+ Various fixes and updates.</p>

            <p>Version 0.9.3:<br/>+ Added Kippo-IP: attack details by IP address.</p>

            <p>Version 0.9.2:<br/>+ Added experimental playlog display.</p>

            <p>Version 0.9.1:<br/>+ Fixed Google Map rendering issue.</p>

            <p>Version 0.9:<br/>+ Added CSV export capabilities.<br/>+ Added Spanish language support.</p>

            <p>Version 0.8:<br/>+ Changed code to OOP style.<br/>+ Added FortiGuard, AlientVault, WatchGuard and McAfee
                IP scanning services (Kippo-Geo).<br/>+ Various CSS-related fixes for tables and cross-browser
                compatibility.</p>

            <p>Version 0.7.7:<br/>+ Added German language support.</p>

            <p>Version 0.7.6:<br/>+ Added Polish & Swedish language support.</p>

            <p>Version 0.7.5:<br/>+ Added French language support.</p>

            <p>Version 0.7.4:<br/>+ Added config option for non-standard MySQL port.</p>

            <p>Version 0.7.3:<br/>+ Fixed XSS issues in Kippo-Input.<br/>+ Added tables with overall/basic stats in
                Kippo-Graph and Kippo-Input.</p>

            <p>Version 0.7.2:<br/>+ Minor fixes and various changes.</p>

            <p>Version 0.7.1:<br/>+ Added chart localization - need volunteers.<br/>+ Languages: Greek, Italian, Dutch,
                Estonian.<br/>+ New chart fonts added - default: OpenSans.<br/>+ Added API key to
                QGoogleVisualizationAPI.</p>

            <p>Version 0.7:<br/>+ Fixed human activity charts: Top 20 and mod limit.<br/>+ Fixed probes per week and
                successes per week charts.<br/>+ Added human activity per week graph - updated grallery
                <br/>+ Added most successful logins per day graph - updated gallery.<br/>+ Added most probes per day
                graph - updated gallery<br/>+ Other small fixes.</p>

            <p>Version 0.6.5:<br/>+ Fixed "http://" in file links (Kippo-Input).<br/>+ Added installation instructions
                and Google Map note in README.txt<br/>+ Fixed successful logins from same IP chart: Top 20.
                <br/>+ Fixed successes per day chart: Top 20.<br/>+ Fixed probes per day chart: display only 25 distinct
                date values.</p>

            <p>Version 0.6.4:<br/>- Removed dayofyear2date(), has a bug that adds +1 day in all 2012 dates (leap year?).
                <br/>+ Changed SQL queries to timestamp values and date() parses the results - fixed graphs.<br/>+ Added
                successes per week graph - updated gallery.<br/>+ Small fixes.</p>

            <p>Version 0.6.3:<br/>+ Added passwd, executed scripts and interesting commands tables.<br/>+ Added
                successes per day graph - updated gallery.<br/>+ Added human activity per day vertical bar chart -
                updated gallery.
                <br/>+ Fixed successful logins from same IP graph.<br/>+ Changed top 10 SSH clients graph to horizontal.<br/>+
                Small UI fixes, etc.</p>

            <p>Version 0.6.2:<br/>+ Added hostname resolution for IPs (include/misc/ip2host.php).<br/>+ Added robtex IP
                lookup feature.</p>

            <p>Version 0.6.1:<br/>+ Changed all links and information about the project.</p>

            <p>Version 0.6:<br/>+ Added human activity per day graph (Kippo-Input) - updated gallery.<br/>+ Added probes
                per week graph - updated gallery.<br/>+ Added break-ins from same IP graph - updated gallery.
                <br/>+ Added IP Void lookup feature (Kippo-Geo).<br/>+ Added NoVirusThanks scan feature
                (Kippo-Input).<br/>+ Fixed SSH clients graph: shows top 10, ordered by volume.<br/>- Removed favicon.
            </p>

            <p>Version 0.5.1:<br/>+ Made version checking more secure with a directive in config.php (UPDATE CHECK
                YES/NO).<br/>+ Posted CHECKSUMS for the .tar archive online (and noted for future releases).<br/>+ Added
                LICENSE.txt</p>

            <p>Version 0.5:<br/>+ Added Kippo-Input: display and visualization of input data, wget (with file links) and
                apt-get commands.<br/>+ Added online version checking function (include/misc/versionCheck.php).
                <br/>+ Added new pie charts, Kippo-Graph now shows 15 - updated gallery.<br/>+ Added IP table on
                Kippo-Geo with whois/lookup feature.<br/>+ Changed all files to .php.<br/></p>

            <p>Version 0.4:<br/>+ Added geolocation features at beta stage, using geoplugin and google maps/charts.<br/>+
                Fixed file/folder structure and updated config.php.<br/>+ Added new logo.</p>

            <p>Version 0.3:<br/>+ Added 3 new input-related graphs.<br/>+ Updated graph gallery.<br/>+ Fixed minor web
                UI and graph details.<br/>+ Added TODO.txt.<br/>+ Updated README.txt</p>

            <p>Version 0.2:<br/>+ Added web template to Kippo-Graph.<br/>+ Changed functionality of kippo-graph.php
                turning into a generator for the graphs.<br/>- index.php removed.</p>

            <p>Version 0.1:<br/>+ Initial version.</p>

            <p>&nbsp;</p>

            <p class="fl_right">
                <small><a href="http://www.freedigitalphotos.net/images/view_photog.php?photogid=2280">Image: digitalart
                        / FreeDigitalPhotos.net</a></small>
            </p>
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