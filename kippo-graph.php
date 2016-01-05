<?php

# Used for <title></title>
$page_title = "Honeypot Activity Overview | Fast Visualization for your Kippo Based SSH Honeypot";

# Used for nav menu
$page_file = "kippo-graph.php";

require('include/header.php');
?>

<div class="wrapper">
    <div class="container">
        <div class="whitebox">
            <!-- ############################# -->
            <h2>Honeypot activity overview</h2>
            <hr>

            <?php
            # Author: ikoniaris

            require_once('config.php');

            if (!is_writable(DIR_ROOT . '/generated-graphs/')) {
                echo '<h3>WARNING: ' . DIR_ROOT . '/generated-graphs/' . ' <b>is not writeable</b>. Images will not be generated.</h3>';
                echo "<br /><hr>";
            }

            require_once(DIR_ROOT . '/class/KippoGraph.class.php');

            $kippoGraph = new KippoGraph();

            //if realtime and not a cronjob OR not realtime but is a cronjob OR not realtime but there are no images yet,
            //then populate the generated-graphs folder
            if (REALTIME_STATS == 'YES' && PHP_SAPI != 'cli' || (REALTIME_STATS == 'NO' && PHP_SAPI == 'cli') ||
                (REALTIME_STATS == 'NO' && !$kippoGraph->generatedKippoGraphChartsExist()))
            {
                $kippoGraph->generateKippoGraphCharts();
            }

            //-----------------------------------------------------------------------------------------------------------------
            //OVERALL HONEYPOT ACTIVITY
            //-----------------------------------------------------------------------------------------------------------------
            $kippoGraph->printOverallHoneypotActivity();
            ?>
            <br /><br />

            <h2>Graphical statistics generated from your honeypot database</h2><!--For more, visit the other pages/components of this package-->
            <br />

            <!-- ############################# -->
            <?php if (file_exists(DIR_ROOT . '/generated-graphs/top10_passwords.png')) { ?>
            <div class="portfolio">
                <div class="fl_left">
                    <h2>Top 10 passwords</h2>

                    <p>This vertical bar chart displays the top 10 passwords that attackers try when attacking the system.</p>

                    <p><a href="include/export.php?type=Pass">CSV of all distinct passwords</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/top10_passwords.png" alt=""/></div>
                <div class="clear"></div>
            </div>
            <?php } ?>
            <!-- ############################# -->
            <?php if (file_exists(DIR_ROOT . '/generated-graphs/top10_usernames.png')) { ?>
            <div class="portfolio">
                <div class="fl_left">
                    <h2>Top 10 usernames</h2>

                    <p>This vertical bar chart displays the top 10 usernames that attackers try when attacking the system.</p>

                    <p><a href="include/export.php?type=User">CSV of all distinct usernames</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/top10_usernames.png" alt=""/></div>
                <div class="clear"></div>
            </div>
            <?php } ?>
            <!-- ############################# -->
            <?php if (file_exists(DIR_ROOT . '/generated-graphs/top10_combinations.png')) { ?>
            <div class="portfolio">
                <div class="fl_left">
                    <h2>Top 10 user-pass combos</h2>

                    <p>This vertical bar chart displays the top 10 username and password combinations that attackers try
                        when attacking the system.</p>

                    <p><a href="include/export.php?type=Combo">CSV of all distinct combinations</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/top10_combinations.png" alt=""/></div>
                <div class="fl_left">
                    <p>This pie chart displays the top 10 username and password combinations that attackers try when
                        attacking the system.</p>

                </div>
                <div class="fl_right"><img src="generated-graphs/top10_combinations_pie.png" alt=""/></div>
                <div class="clear"></div>
            </div>
            <?php } ?>
            <!-- ############################# -->
            <?php if (file_exists(DIR_ROOT . '/generated-graphs/success_ratio.png')) { ?>
            <div class="portfolio">
                <div class="fl_left">
                    <h2>Success ratio</h2>

                    <p>This vertical bar chart displays the overall attack success ratio for the particular honeypot system.</p>

                    <p><a href="include/export.php?type=Success">CSV of all successful attacks</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/success_ratio.png" alt=""/></div>
                <div class="clear"></div>
            </div>
            <?php } ?>
            <!-- ############################# -->
            <?php if (file_exists(DIR_ROOT . '/generated-graphs/top10_successful_combinations.png')) { ?>
            <div class="portfolio">
                <div class="fl_left">
                    <h2>Top 10 successful user-pass combos</h2>

                    <p>This vertical bar chart displays the top 10 username and password combinations that attackers were
                        successfully able to use to login to the system with.</p>

                    <p><a href="include/export.php?type=ComboSuccess">CSV of all successful distinct combinations</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/top10_successful_combinations.png" alt=""/></div>
                <div class="fl_left">
                    <p>This pie chart displays the top 10 username and password combinations that attackers try when
                        attacking the system.</p>

                </div>
                <div class="fl_right"><img src="generated-graphs/top10_successful_combinations_pie.png" alt=""/></div>
                <div class="clear"></div>
            </div>
            <?php } ?>
            <!-- ############################# -->
            <?php if (file_exists(DIR_ROOT . '/generated-graphs/most_successful_logins_per_day.png')) { ?>
            <div class="portfolio">
                <div class="fl_left">
                    <h2>Successes per day/week</h2>

                    <p>This vertical bar chart displays the most successful break-ins per day (Top 20) for the
                        particular honeypot system. The numbers indicate how many times correct credentials were given
                        by attackers.</p>

                    <p><a href="include/export.php?type=SuccessLogon">CSV of all successful logons</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/most_successful_logins_per_day.png" alt=""/></div>
                <div class="clear"></div>
                <div class="fl_left">
                    <p>This line chart displays the daily successes on the honeypot system. Spikes indicate successful
                        entries over a weekly period.<br /><br /><strong>Warning:</strong> Dates with zero successes are
                        not displayed.</p>

                    <p><a href="include/export.php?type=SuccessDay">CSV of daily successes</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/successes_per_day.png" alt=""/></div>
                <div class="clear"></div>
                <div class="fl_left">
                    <p>This line chart displays the weekly successes on the honeypot system. Curves indicate successful
                        entries over a weekly period.</p>

                    <p><a href="include/export.php?type=SuccessWeek">CSV of weekly successes</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/successes_per_week.png" alt=""/></div>
                <div class="clear"></div>
            </div>
            <?php } ?>
            <!-- ############################# -->
            <?php if (file_exists(DIR_ROOT . '/generated-graphs/connections_per_ip.png')) { ?>
            <div class="portfolio">
                <div class="fl_left">
                    <h2>Connections per IP</h2>

                    <p>This vertical bar chart displays the top 10 unique IPs ordered by the number of overall
                        connections to the system.</p>

                    <p><a href="include/export.php?type=IP">CSV of all connections per IP</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/connections_per_ip.png" alt=""/></div>
                <div class="fl_left">
                    <p>This pie chart displays the top 10 unique IPs ordered by the number of overall connections to the system.</p>
                </div>
                <div class="fl_right"><img src="generated-graphs/connections_per_ip_pie.png" alt=""/></div>
                <div class="clear"></div>
            </div>
            <?php } ?>
            <!-- ############################# -->
            <?php if (file_exists(DIR_ROOT . '/generated-graphs/logins_from_same_ip.png')) { ?>
            <div class="portfolio">
                <div class="fl_left">
                    <h2>Successful logins from the same IP</h2>

                    <p>This vertical bar chart displays the number of successful logins from the same IP address (Top
                        20). The numbers indicate how many times the particular source opened a successful session.</p>

                    <p><a href="include/export.php?type=SuccessIP">CSV of all successful IPs</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/logins_from_same_ip.png" alt=""/></div>
                <div class="clear"></div>
            </div>
            <?php } ?>
            <!-- ############################# -->
            <?php if (file_exists(DIR_ROOT . '/generated-graphs/most_probes_per_day.png')) { ?>
            <div class="portfolio">
                <div class="fl_left">
                    <h2>Probes per day/week</h2>

                    <p>This horizontal bar chart displays the most probes per day (Top 20) against the honeypot system.</p>
                </div>
                <div class="fl_right"><img src="generated-graphs/most_probes_per_day.png" alt=""/></div>
                <div class="fl_left">
                    <p>This line chart displays the daily activity on the honeypot system. Spikes indicate hacking
                        attempts.<br /><br /><strong>Warning:</strong> Dates with zero probes are not displayed.</p>

                    <p><a href="include/export.php?type=ProbesDay">CSV of all probes per day</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/probes_per_day.png" alt=""/></div>
                <div class="fl_left">
                    <p>This line chart displays the weekly activity on the honeypot system. Curves indicate hacking
                        attempts over a weekly period.</p>

                    <p><a href="include/export.php?type=ProbesWeek">CSV of all probes per week</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/probes_per_week.png" alt=""/></div>
                <div class="clear"></div>
            </div>
            <?php } ?>
            <!-- ############################# -->
            <?php if (file_exists(DIR_ROOT . '/generated-graphs/top10_ssh_clients.png')) { ?>
            <div class="portfolio">
                <div class="fl_left">
                    <h2>Top 10 SSH clients</h2>

                    <p>This vertical bar chart displays the top 10 SSH clients used by attackers during their hacking attempts.</p>

                    <p><a href="include/export.php?type=SSH">CSV of all SSH clients</a></p>
                </div>
                <div class="fl_right"><img src="generated-graphs/top10_ssh_clients.png" alt=""/></div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <?php } ?>
            <!-- ############################# -->
        </div>
    </div>
</div>

<?php
require('include/footer.php');
?>
