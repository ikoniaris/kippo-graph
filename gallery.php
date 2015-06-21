<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
    <title>Kippo-Graph | Fast Visualisation for your Kippo SSH Honeypot Stats</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link rel="stylesheet" href="styles/layout.css" type="text/css"/>
    <script type="text/javascript" src="scripts/jquery-1.4.1.min.js"></script>
    <!-- FancyBox -->
    <script type="text/javascript" src="scripts/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="scripts/fancybox/jquery.fancybox-1.3.2.js"></script>
    <script type="text/javascript" src="scripts/fancybox/jquery.fancybox-1.3.2.setup.js"></script>
    <link rel="stylesheet" href="scripts/fancybox/jquery.fancybox-1.3.2.css" type="text/css"/>
    <!-- End FancyBox -->
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
            <li><a href="kippo-input.php">Kippo-Input</a></li>
            <li><a href="kippo-playlog.php">Kippo-PlayLog</a></li>
            <li><a href="kippo-ip.php">Kippo-Ip</a></li>
            <li><a href="kippo-geo.php">Kippo-Geo</a></li>
            <li class="active last"><a href="gallery.php">Graph Gallery</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div class="container">
        <!-- ############################# -->
        <div id="gallery" class="whitebox">
            <h2>Provided you have visited all the other pages/components (for the graphs to be generated) you can see
                all the images in this single page with the help of fancybox</h2>
            <hr/>
            <br/>
            <ul>
                <li><a rel="gallery_group" href="generated-graphs/top10_passwords.png"
                       title="Top 10 passwords attempted"><img src="generated-graphs/top10_passwords.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/top10_usernames.png"
                       title="Top 10 usernames attempted"><img src="generated-graphs/top10_usernames.png" alt=""/></a>
                </li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/top10_combinations.png"
                                    title="Top 10 username-password combinations"><img
                            src="generated-graphs/top10_combinations.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/top10_combinations_pie.png"
                       title="Top 10 username-password combinations"><img
                            src="generated-graphs/top10_combinations_pie.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/success_ratio.png" title="Overall success ratio"><img
                            src="generated-graphs/success_ratio.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/most_successful_logins_per_day.png"
                                    title="Most successful logins per day (Top 20)"><img
                            src="generated-graphs/most_successful_logins_per_day.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/successes_per_day.png" title="Successes per day"><img
                            src="generated-graphs/successes_per_day.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/successes_per_week.png"
                       title="Successes per week"><img src="generated-graphs/successes_per_week.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/connections_per_ip.png"
                                    title="Number of connections per unique IP (Top 10)"><img
                            src="generated-graphs/connections_per_ip.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/connections_per_ip_pie.png"
                       title="Number of connections per unique IP (Top 10)"><img
                            src="generated-graphs/connections_per_ip_pie.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/logins_from_same_ip.png"
                       title="Successful logins from same IP (Top 20)"><img
                            src="generated-graphs/logins_from_same_ip.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/most_probes_per_day.png"
                                    title="Most probes per day (Top 20)"><img
                            src="generated-graphs/most_probes_per_day.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/probes_per_day.png" title="Probes per day"><img
                            src="generated-graphs/probes_per_day.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/probes_per_week.png" title="Probes per week"><img
                            src="generated-graphs/probes_per_week.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/top10_ssh_clients.png"
                                    title="Top 10 SSH clients"><img src="generated-graphs/top10_ssh_clients.png"
                                                                    alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/connections_per_country_pie.png"
                       title="Number of connections per country"><img
                            src="generated-graphs/connections_per_country_pie.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/connections_per_ip_geo.png"
                       title="Number of connections per unique IP (Top 10) + Country Codes"><img
                            src="generated-graphs/connections_per_ip_geo.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/connections_per_ip_geo_pie.png"
                                    title="Number of connections per unique IP (Top 10) + Country Codes"><img
                            src="generated-graphs/connections_per_ip_geo_pie.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/human_activity_busiest_days.png"
                       title="Human activity busiest days (Top 20)"><img
                            src="generated-graphs/human_activity_busiest_days.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/human_activity_per_day.png"
                       title="Human activity per day"><img src="generated-graphs/human_activity_per_day.png"
                                                           alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/human_activity_per_week.png"
                                    title="Human activity per week"><img
                            src="generated-graphs/human_activity_per_week.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/top10_overall_input.png"
                       title="Top 10 input (overall)"><img src="generated-graphs/top10_overall_input.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/top10_successful_input.png"
                       title="Top 10 successful input"><img src="generated-graphs/top10_successful_input.png"
                                                            alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/top10_failed_input.png"
                                    title="Top 10 failed input"><img src="generated-graphs/top10_failed_input.png"
                                                                     alt=""/></a></li>
            </ul>
            <br class="clear"/>
        </div>
        <!-- ############################# -->
        <div class="clear"></div>
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