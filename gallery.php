<?php

# Used for <title></title>
$page_title = "Graph Gallery | Fast Visualization for your Kippo Based SSH Honeypot";

# Used for nav menu
$page_file = "gallery.php";

# Custom head
$page_head = '
    <!-- FancyBox -->
    <script type="text/javascript" src="scripts/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="scripts/fancybox/jquery.fancybox-1.3.2.js"></script>
    <script type="text/javascript" src="scripts/fancybox/jquery.fancybox-1.3.2.setup.js"></script>
    <link rel="stylesheet" href="scripts/fancybox/jquery.fancybox-1.3.2.css" type="text/css"/>
    <!-- End FancyBox -->';

require('include/header.php');
?>

<div class="wrapper">
    <div class="container">
        <!-- ############################# -->
        <div id="gallery" class="whitebox">
            <h2>Graph Gallery</h2>
            <i>Provided you have visited all the other pages (<a href="kippo-graph.php">Overview</a> & <a href="kippo-input.php">Input</a>), for the graphs to be generated, you can see all the images in this single page.</i>
            <hr>
            <br />

            <?php
            require_once('config.php');
            if (!is_writable(DIR_ROOT . '/generated-graphs/')) {
                echo '<h3>WARNING: ' . DIR_ROOT . '/generated-graphs/' . ' <b>is not writeable</b>. Images will not be generated.</h3>';
                echo "<br /><hr>";
            }
            ?>

            <ul>
                <li><a rel="gallery_group" href="generated-graphs/top10_passwords.png"
                       title="Top 10 passwords attempted">
                    <img src="generated-graphs/top10_passwords.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/top10_usernames.png"
                       title="Top 10 usernames attempted">
                    <img src="generated-graphs/top10_usernames.png" alt=""/></a>
                </li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/top10_combinations.png"
                                    title="Top 10 username-password combinations">
                                 <img src="generated-graphs/top10_combinations.png" alt=""/></a>
                 </li>

                <li><a rel="gallery_group" href="generated-graphs/top10_combinations_pie.png"
                       title="Top 10 username-password combinations">
                    <img src="generated-graphs/top10_combinations_pie.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/success_ratio.png"
                       title="Overall success ratio">
                    <img src="generated-graphs/success_ratio.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/most_successful_logins_per_day.png"
                                    title="Most successful logins per day (Top 20)">
                                 <img src="generated-graphs/most_successful_logins_per_day.png" alt=""/></a>
                 </li>

                <li><a rel="gallery_group" href="generated-graphs/successes_per_day.png"
                       title="Successes per day">
                    <img src="generated-graphs/successes_per_day.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/successes_per_week.png"
                       title="Successes per week">
                    <img src="generated-graphs/successes_per_week.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/connections_per_ip.png"
                                    title="Number of connections per unique IP (Top 10)">
                                 <img src="generated-graphs/connections_per_ip.png" alt=""/></a>
                 </li>

                <li><a rel="gallery_group" href="generated-graphs/connections_per_ip_pie.png"
                       title="Number of connections per unique IP (Top 10)">
                    <img src="generated-graphs/connections_per_ip_pie.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/logins_from_same_ip.png"
                       title="Successful logins from same IP (Top 20)">
                    <img src="generated-graphs/logins_from_same_ip.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/most_probes_per_day.png"
                                    title="Most probes per day (Top 20)">
                                 <img src="generated-graphs/most_probes_per_day.png" alt=""/></a>
                </li>

                <li><a rel="gallery_group" href="generated-graphs/probes_per_day.png"
                       title="Probes per day">
                    <img src="generated-graphs/probes_per_day.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/probes_per_week.png"
                       title="Probes per week">
                    <img src="generated-graphs/probes_per_week.png" alt=""/></a>
                </li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/top10_ssh_clients.png"
                                    title="Top 10 SSH clients">
                                 <img src="generated-graphs/top10_ssh_clients.png" alt=""/></a>
                </li>

                <li><a rel="gallery_group" href="generated-graphs/connections_per_country_pie.png"
                       title="Number of connections per country">
                       <img src="generated-graphs/connections_per_country_pie.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/connections_per_ip_geo.png"
                       title="Number of connections per unique IP (Top 10) + Country Codes">
                       <img src="generated-graphs/connections_per_ip_geo.png" alt=""/></a>
                </li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/connections_per_ip_geo_pie.png"
                                    title="Number of connections per unique IP (Top 10) + Country Codes">
                                 <img src="generated-graphs/connections_per_ip_geo_pie.png" alt=""/></a>
                </li>

                <li><a rel="gallery_group" href="generated-graphs/human_activity_busiest_days.png"
                       title="Human activity busiest days (Top 20)">
                    <img src="generated-graphs/human_activity_busiest_days.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/human_activity_per_day.png"
                       title="Human activity per day">
                    <img src="generated-graphs/human_activity_per_day.png" alt=""/></a>
                </li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/human_activity_per_week.png"
                                    title="Human activity per week">
                                 <img src="generated-graphs/human_activity_per_week.png" alt=""/></a>
                </li>

                <li><a rel="gallery_group" href="generated-graphs/top10_overall_input.png"
                       title="Top 10 input (overall)">
                    <img src="generated-graphs/top10_overall_input.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/top10_successful_input.png"
                       title="Top 10 successful input">
                    <img src="generated-graphs/top10_successful_input.png" alt=""/></a>
                </li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/top10_failed_input.png"
                                    title="Top 10 failed input">
                                 <img src="generated-graphs/top10_failed_input.png" alt=""/></a>
                </li>

                <li><a rel="gallery_group" href="generated-graphs/top10_successful_combinations.png"
                       title="Top 10 successful username-password combinations">
                    <img src="generated-graphs/top10_successful_combinations.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/top10_successful_combinations_pie.png"
                       title="Top 10 successful username-password combinations">
                    <img src="generated-graphs/top10_successful_combinations_pie.png" alt=""/></a></li>
                <li class="last">
                </li>
            </ul>
            <br class="clear"/>
        </div>
        <!-- ############################# -->
        <div class="clear"></div>
    </div>
</div>

<?php
require('include/footer.php');
?>
