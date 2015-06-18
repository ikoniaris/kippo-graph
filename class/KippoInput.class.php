<?php
require_once(DIR_ROOT . '/include/rb.php');
require_once(DIR_ROOT . '/include/libchart/classes/libchart.php');
require_once(DIR_ROOT . '/include/misc/xss_clean.php');

class KippoInput
{

    function __construct()
    {
        //Let's connect to the database
        R::setup('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    }

    function __destruct()
    {
        R::close();
    }

    public function printOverallHoneypotActivity()
    {
        echo '<h3>Overall post-compromise activity</h3>';

        //TOTAL NUMBER OF COMMANDS
        $db_query = "SELECT COUNT(*) as total, COUNT(DISTINCT input) as uniq FROM input";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th colspan="2">Post-compromise human activity</th>';
            echo '</tr>';
            echo '<tr class="dark">';
            echo '<th>Total number of commands</th>';
            echo '<th>Distinct number of commands</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach ($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $row['total'] . '</td>';
                echo '<td>' . $row['uniq'] . '</td>';
                echo '</tr>';
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
        }

        //TOTAL DOWNLOADED FILES
        $db_query = "SELECT COUNT(*) as files, COUNT(DISTINCT input) as uniq_files
          FROM input WHERE input LIKE '%wget%' AND input NOT LIKE 'wget'";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th colspan="2">Downloaded files</th>';
            echo '</tr>';
            echo '<tr class="dark">';
            echo '<th>Total number of downloads</th>';
            echo '<th>Distinct number of downloads</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach ($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $row['files'] . '</td>';
                echo '<td>' . $row['uniq_files'] . '</td>';
                echo '</tr>';
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
        }

        echo '<hr /><br />';
    }

    public function printHumanActivityBusiestDays()
    {
        $db_query = "SELECT COUNT(input), timestamp
          FROM input
          GROUP BY DAYOFYEAR(timestamp)
          ORDER BY COUNT(input) DESC
          LIMIT 20 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart_vertical = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['timestamp'])), $row['COUNT(input)']));
            }

            //We set the vertical bar chart's dataset and render the graph
            $chart_vertical->setDataSet($dataSet);
            $chart_vertical->setTitle(HUMAN_ACTIVITY_BUSIEST_DAYS);
            $chart_vertical->render(DIR_ROOT . "/generated-graphs/human_activity_busiest_days.png");
            echo '<h3>Human activity inside the honeypot</h3>';
            echo '<p>The following vertical bar chart visualizes the top 20 busiest days of real human activity, by counting the number of input to the system.</p>';
            echo '<img src="generated-graphs/human_activity_busiest_days.png">';
            echo '<br />';
        }
    }

    public function printHumanActivityPerDay()
    {
        $db_query = "SELECT COUNT(input), timestamp
          FROM input
          GROUP BY DAYOFYEAR(timestamp)
          ORDER BY timestamp ASC ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new line chart and initialize the dataset
            $chart = new LineChart(600, 300);
            $dataSet = new XYDataSet();

            //This graph gets messed up for large DBs, so here is a simple way to limit some of the input
            $counter = 1;
            //Display date legend only every $mod rows, 25 distinct values being the optimal for a graph
            $mod = round(count($rows) / 25);
            if ($mod == 0) $mod = 1; //otherwise a division by zero might happen below
            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                if ($counter % $mod == 0) {
                    $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['timestamp'])), $row['COUNT(input)']));
                } else {
                    $dataSet->addPoint(new Point('', $row['COUNT(input)']));
                }
                $counter++;
            }

            //We set the line chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(HUMAN_ACTIVITY_PER_DAY);
            $chart->render(DIR_ROOT . "/generated-graphs/human_activity_per_day.png");
            echo '<p>The following line chart visualizes real human activity per day, by counting the number of input to the system for each day of operation.
			<br/><strong>Warning:</strong> Dates with zero input are not displayed.</p>';
            echo '<img src="generated-graphs/human_activity_per_day.png">';
            echo '<br />';
        }
    }

    public function printHumanActivityPerWeek()
    {
        $db_query = "SELECT COUNT(input), MAKEDATE(
          CASE
          WHEN WEEKOFYEAR(timestamp) = 52
          THEN YEAR(timestamp)-1
          ELSE YEAR(timestamp)
          END, (WEEKOFYEAR(timestamp) * 7)-4) AS DateOfWeek_Value
          FROM input
          GROUP BY WEEKOFYEAR(timestamp)
          ORDER BY timestamp ASC";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new line chart and initialize the dataset
            $chart = new LineChart(600, 300);
            $dataSet = new XYDataSet();

            //This graph gets messed up for large DBs, so here is a simple way to limit some of the input
            $counter = 1;
            //Display date legend only every $mod rows, 25 distinct values being the optimal for a graph
            $mod = round(count($rows) / 25);
            if ($mod == 0) $mod = 1; //otherwise a division by zero might happen below
            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                if ($counter % $mod == 0) {
                    $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['DateOfWeek_Value'])), $row['COUNT(input)']));
                } else {
                    $dataSet->addPoint(new Point('', $row['COUNT(input)']));
                }
                $counter++;

                //We add 6 "empty" points to make a horizontal line representing a week
                for ($i = 0; $i < 6; $i++) {
                    $dataSet->addPoint(new Point('', $row['COUNT(input)']));
                }
            }

            //We set the line chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(HUMAN_ACTIVITY_PER_WEEK);
            $chart->render(DIR_ROOT . "/generated-graphs/human_activity_per_week.png");
            echo '<p>The following line chart visualizes real human activity per week, by counting the number of input to the system for each day of operation.</p>';
            echo '<img src="generated-graphs/human_activity_per_week.png">';
            echo '<br /><hr /><br />';
        }
    }

    public function printTop10OverallInput()
    {
        $db_query = "SELECT input, COUNT(input)
          FROM input
          GROUP BY input
          ORDER BY COUNT(input) DESC
          LIMIT 10";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>Top 10 input (overall)</h3>';
            echo '<p>The following table displays the top 10 commands (overall) entered by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=Input">CSV of all input commands</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Input</th>';
            echo '<th>Count</th>';
            echo '</tr></thead><tbody>';


            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['input'], $row['COUNT(input)']));

                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . xss_clean($row['input']) . '</td>';
                echo '<td>' . $row['COUNT(input)'] . '</td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';


            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_INPUT_OVERALL);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 90, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render(DIR_ROOT . "/generated-graphs/top10_overall_input.png");
            echo '<p>This vertical bar chart visualizes the top 10 commands (overall) entered by attackers in the honeypot system.</p>';
            echo '<img src="generated-graphs/top10_overall_input.png">';
            echo '<hr /><br />';
        }
    }

    public function printTop10SuccessfulInput()
    {
        $db_query = "SELECT input, COUNT(input)
          FROM input
          WHERE success = 1
          GROUP BY input
          ORDER BY COUNT(input) DESC
          LIMIT 10";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>Top 10 successful input</h3>';
            echo '<p>The following table displays the top 10 successful commands entered by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=Successinput">CSV of all successful commands</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Input (success)</th>';
            echo '<th>Count</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach($rows as $row) {
                $dataSet->addPoint(new Point($row['input'], $row['COUNT(input)']));

                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . xss_clean($row['input']) . '</td>';
                echo '<td>' . $row['COUNT(input)'] . '</td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_SUCCESSFUL_INPUT);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 90, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render(DIR_ROOT . "/generated-graphs/top10_successful_input.png");
            echo '<p>This vertical bar chart visualizes the top 10 successful commands entered by attackers in the honeypot system.</p>';
            echo '<img src="generated-graphs/top10_successful_input.png">';
            echo '<hr /><br />';
        }
    }

    public function printTop10FailedInput()
    {
        $db_query = "SELECT input, COUNT(input)
          FROM input
          WHERE success = 0
          GROUP BY input
          ORDER BY COUNT(input) DESC
          LIMIT 10";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>Top 10 failed input</h3>';
            echo '<p>The following table displays the top 10 failed commands entered by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=FailedInput">CSV of all failed commands</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Input (fail)</th>';
            echo '<th>Count</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach($rows as $row) {
                $dataSet->addPoint(new Point($row['input'], $row['COUNT(input)']));

                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . xss_clean($row['input']) . '</td>';
                echo '<td>' . $row['COUNT(input)'] . '</td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_FAILED_INPUT);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 40, 120, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render(DIR_ROOT . "/generated-graphs/top10_failed_input.png");
            echo '<p>This vertical bar chart visualizes the top 10 failed commands entered by attackers in the honeypot system.</p>';
            echo '<img src="generated-graphs/top10_failed_input.png">';
            echo '<hr /><br />';
        }
    }

    public function printPasswdCommands()
    {
        $db_query = "SELECT timestamp, input, session
          FROM input
          WHERE realm like 'passwd'
          GROUP BY input
          ORDER BY timestamp DESC";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>passwd commands</h3>';
            echo '<p>The following table displays the latest "passwd" commands entered by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=passwd">CSV of all "passwd" commands</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Timestamp</th>';
            echo '<th>Input</th>';
            echo '<th>Play Log</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we create a new table row with the data as columns
            foreach($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . date('l, d-M-Y, H:i A', strtotime($row['timestamp'])) . '</td>';
                echo '<td>' . xss_clean($row['input']) . '</td>';
                echo '<td><a href="include/play.php?f=' . $row['session'] . '" target="_blank"><img class="icon" src="images/play.ico"/>Play</a></td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
            echo '<hr /><br />';
        }
    }

    public function printWgetCommands()
    {
        $db_query = "SELECT input, TRIM(LEADING 'wget' FROM input) as file, timestamp, session
          FROM input
          WHERE input LIKE '%wget%' AND input NOT LIKE 'wget'
          ORDER BY timestamp DESC";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>wget commands</h3>';
            echo '<p>The following table displays the latest "wget" commands entered by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=wget">CSV of all "wget" commands</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Timestamp</th>';
            echo '<th>Input</th>';
            echo '<th>File link</th>';
            echo '<th>Play Log</th>';
            echo '<th>Kippo-Scanner</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we create a new table row with the data as columns
            foreach($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . $row['timestamp'] . '</td>';
                echo '<td>' . xss_clean($row['input']) . '</td>';
                $file_link = explode(" ", trim($row['file']))[0];
                // If the link has no "http://" in front, then add it
                if (substr(strtolower($file_link), 0, 4) !== 'http') {
                    $file_link = 'http://' . $file_link;
                }
                echo '<td><a href="http://anonym.to/?' . $file_link . '" target="_blank"><img class="icon" src="images/warning.png"/>http://anonym.to/?' . $file_link . '</a></td>';
                echo '<td><a href="include/play.php?f=' . $row['session'] . '" target="_blank"><img class="icon" src="images/play.ico"/>Play</a></td>';
                echo '<td><a href="kippo-scanner.php?file_url=' . $file_link . '" target="_blank">Scan File</a></td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
            echo '<hr /><br />';
        }
    }

    public function printExecutedScripts()
    {
        $db_query = "SELECT timestamp, input, session
          FROM input
          WHERE input like './%'
          GROUP BY input
          ORDER BY timestamp DESC";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>Executed scripts</h3>';
            echo '<p>The following table displays the latest executed scripts by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=Scripts">CSV of all scripts executed</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Timestamp</th>';
            echo '<th>Input</th>';
            echo '<th>Play Log</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we create a new table row with the data as columns
            foreach($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . date('l, d-M-Y, H:i A', strtotime($row['timestamp'])) . '</td>';
                echo '<td>' . xss_clean($row['input']) . '</td>';
                echo '<td><a href="include/play.php?f=' . $row['session'] . '" target="_blank"><img class="icon" src="images/play.ico"/>Play</a></td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
            echo '<hr /><br />';
        }
    }

    public function printInterestingCommands()
    {
        $db_query = "SELECT timestamp, input, session
          FROM input
          WHERE (input like '%cat%' OR input like '%dev%' OR input like '%man%' OR input like '%gpg%'
          OR input like '%ping%' OR input like '%ssh%' OR input like '%scp%' OR input like '%whois%'
          OR input like '%unset%' OR input like '%kill%' OR input like '%ifconfig%' OR input like '%iwconfig%'
          OR input like '%traceroute%' OR input like '%screen%' OR input like '%user%')
          AND input NOT like '%wget%' AND input NOT like '%apt-get%'
          GROUP BY input
          ORDER BY timestamp DESC";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>Interesting commands</h3>';
            echo '<p>The following table displays other interesting commands executed by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=Interesting">CSV of all interesting commands</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Timestamp</th>';
            echo '<th>Input</th>';
            echo '<th>Play Log</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we create a new table row with the data as columns
            foreach($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . date('l, d-M-Y, H:i A', strtotime($row['timestamp'])) . '</td>';
                echo '<td>' . xss_clean($row['input']) . '</td>';
                echo '<td><a href="include/play.php?f=' . $row['session'] . '" target="_blank"><img class="icon" src="images/play.ico"/>Play</a></td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
            echo '<hr /><br />';
        }
    }

    public function printAptGetCommands()
    {
        $db_query = "SELECT timestamp, input, session
          FROM input
          WHERE (input like '%apt-get install%' OR input like '%apt-get remove%'
          OR input like '%aptitude install%' OR input like '%aptitude remove%')
          AND input NOT LIKE 'apt-get' AND input NOT LIKE 'aptitude'
          GROUP BY input
          ORDER BY timestamp DESC";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>apt-get commands</h3>';
            echo '<p>The following table displays the latest "apt-get"/"aptitude" commands entered by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=aptget">CSV of all "apt-get"/"aptitude" commands</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Timestamp</th>';
            echo '<th>Input</th>';
            echo '<th>Play Log</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we create a new table row with the data as columns
            foreach($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . date('l, d-M-Y, H:i A', strtotime($row['timestamp'])) . '</td>';
                echo '<td>' . xss_clean($row['input']) . '</td>';
                echo '<td><a href="include/play.php?f=' . $row['session'] . '" target="_blank"><img class="icon" src="images/play.ico"/>Play</a></td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
            echo '<hr /><br />';
        }
    }

}

?>
