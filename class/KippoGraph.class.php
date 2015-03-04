<?php
require_once(DIR_ROOT . '/include/rb.php');
require_once(DIR_ROOT . '/include/libchart/classes/libchart.php');

class KippoGraph
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

    // "meta" function to generate all charts
    // this way kippo-graph.php can also be cron'd
    public function generateKippoGraphCharts()
    {
        $this->createTop10Passwords();
        $this->createTop10Usernames();
        $this->createTop10Combinations();
        $this->createSuccessRation();
        $this->createMostSuccessfulLoginsPerDay();
        $this->createSuccessesPerDay();
        $this->createSuccessesPerWeek();
        $this->createNumberOfConnectionsPerIP();
        $this->createSuccessfulLoginsFromSameIP();
        $this->createMostProbesPerDay();
        $this->createProbesPerDay();
        $this->createProbesPerWeek();
        $this->createTop10SSHClients();
    }

    public function generatedKippoGraphChartsExist()
    {
        $generated_graphs_path = DIR_ROOT . '/generated-graphs/';
        $generated_graphs_names_array = array(
            'top10_passwords.png',
            'top10_usernames.png',
            'top10_combinations.png',
            'top10_combinations_pie.png',
            'success_ratio.png',
            'most_successful_logins_per_day.png',
            'successes_per_day.png',
            'successes_per_week.png',
            'connections_per_ip.png',
            'connections_per_ip_pie.png',
            'logins_from_same_ip.png',
            'most_probes_per_day.png',
            'probes_per_day.png',
            'probes_per_week.png',
            'top10_ssh_clients.png',
        );

        foreach ($generated_graphs_names_array as $graph_name)
            if (!file_exists($generated_graphs_path . $graph_name))
                return false;

        return true;
    }

    public function printOverallHoneypotActivity()
    {
        //TOTAL LOGIN ATTEMPTS
        $db_query = "SELECT COUNT(*) AS logins FROM auth";
        $row = R::getRow($db_query);

        //echo '<strong>Total login attempts: </strong><h3>'.$row['logins'].'</h3>';
        echo '<table><thead>';
        echo '<tr>';
        echo '<th>Total login attempts</th>';
        echo '<th>' . $row['logins'] . '</th>';
        echo '</tr></thead><tbody>';
        echo '</tbody></table>';

        //TOTAL DISTINCT IPs
        $db_query = "SELECT COUNT(DISTINCT ip) AS IPs FROM sessions";
        $row = R::getRow($db_query);

        //echo '<strong>Distinct source IPs: </strong><h3>'.$row['IPs'].'</h3>';
        echo '<table><thead>';
        echo '<tr>';
        echo '<th>Distinct source IP addresses</th>';
        echo '<th>' . $row['IPs'] . '</th>';
        echo '</tr></thead><tbody>';
        echo '</tbody></table>';

        //OPERATIONAL TIME PERIOD
        $db_query = "SELECT MIN(timestamp) AS start, MAX(timestamp) AS end FROM auth";
        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th colspan="2">Active time period</th>';
            echo '</tr>';
            echo '<tr class="dark">';
            echo '<th>Start date (first attack)</th>';
            echo '<th>End date (last attack)</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach ($rows as $row) {
                echo '<tr class="light">';
                echo '<td>' . date('l, d-M-Y, H:i A', strtotime($row['start'])) . '</td>';
                echo '<td>' . date('l, d-M-Y, H:i A', strtotime($row['end'])) . '</td>';
                echo '</tr>';
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
        }
    }

    public function createTop10Passwords()
    {
        $db_query = "SELECT password, COUNT(password)
          FROM auth
          WHERE password <> ''
          GROUP BY password
          ORDER BY COUNT(password) DESC
          LIMIT 10 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['password'], $row['COUNT(password)']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_PASSWORDS);
            $chart->render(DIR_ROOT . "/generated-graphs/top10_passwords.png");
        }
    }

    public function createTop10Usernames()
    {
        $db_query = "SELECT username, COUNT(username)
          FROM auth
          WHERE username <> ''
          GROUP BY username
          ORDER BY COUNT(username) DESC
          LIMIT 10 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['username'], $row['COUNT(username)']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_USERNAMES);
            $chart->render(DIR_ROOT . "/generated-graphs/top10_usernames.png");
        }
    }

    public function createTop10Combinations()
    {
        $db_query = "SELECT username, password, COUNT(username)
          FROM auth
          WHERE username <> '' AND password <> ''
          GROUP BY username, password
          ORDER BY COUNT(username) DESC
          LIMIT 10 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart,a new pie chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $pie_chart = new PieChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['username'] . '/' . $row['password'], $row['COUNT(username)']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_COMBINATIONS);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 40, 75, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render(DIR_ROOT . "/generated-graphs/top10_combinations.png");

            //We set the pie chart's dataset and render the graph
            $pie_chart->setDataSet($dataSet);
            $pie_chart->setTitle(TOP_10_COMBINATIONS);
            $pie_chart->render(DIR_ROOT . "/generated-graphs/top10_combinations_pie.png");
        }
    }

    public function createSuccessRation()
    {
        $db_query = "SELECT success, COUNT(success)
          FROM auth
          GROUP BY success
          ORDER BY success";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //Database should return two rows, so we need two bars
            //If success = 0 or = 1 add point accordingly, else a new bar (in case of NULL/whatever)
            foreach ($rows as $row) {
                if ($row['success'] == 0)
                    $dataSet->addPoint(new Point(AUTH_FAIL, $row['COUNT(success)']));
                else if ($row['success'] == 1)
                    $dataSet->addPoint(new Point(AUTH_SUCCESS, $row['COUNT(success)']));
                else
                    $dataSet->addPoint(new Point($row['success'], $row['COUNT(success)']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(OVERALL_SUCCESS_RATIO);
            $chart->render(DIR_ROOT . "/generated-graphs/success_ratio.png");
        }
    }

    public function createMostSuccessfulLoginsPerDay()
    {
        $db_query = "SELECT COUNT(session), timestamp
          FROM auth
          WHERE success = 1
          GROUP BY DAYOFYEAR(timestamp)
          ORDER BY COUNT(session) DESC
          LIMIT 20 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new horizontal bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['timestamp'])), $row['COUNT(session)']));
            }

            //We set the horizontal chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(MOST_SUCCESSFUL_LOGINS_PER_DAY);
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 50, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render(DIR_ROOT . "/generated-graphs/most_successful_logins_per_day.png");
        }
    }

    public function createSuccessesPerDay()
    {
        $db_query = "SELECT COUNT(session), timestamp
          FROM auth
          WHERE success = 1
          GROUP BY DAYOFYEAR(timestamp)
          ORDER BY timestamp ASC ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new horizontal bar chart and initialize the dataset
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
                    $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['timestamp'])), $row['COUNT(session)']));
                } else {
                    $dataSet->addPoint(new Point('', $row['COUNT(session)']));
                }
                $counter++;
            }

            //We set the horizontal chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(SUCCESSES_PER_DAY);
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 50, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render(DIR_ROOT . "/generated-graphs/successes_per_day.png");
        }
    }

    public function createSuccessesPerWeek()
    {
        $db_query = "SELECT COUNT(session),
          MAKEDATE(CASE WHEN WEEKOFYEAR(timestamp) = 52
            THEN YEAR(timestamp)-1
            ELSE YEAR(timestamp)
            END, (WEEKOFYEAR(timestamp) * 7)-4) AS DateOfWeek_Value
          FROM auth
          WHERE success = 1
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
                    $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['DateOfWeek_Value'])), $row['COUNT(session)']));
                } else {
                    $dataSet->addPoint(new Point('', $row['COUNT(session)']));
                }
                $counter++;

                //We add 6 "empty" points to make a horizontal line representing a week
                for ($i = 0; $i < 6; $i++) {
                    $dataSet->addPoint(new Point('', $row['COUNT(session)']));
                }
            }

            //We set the line chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(SUCCESSES_PER_WEEK);
            $chart->render(DIR_ROOT . "/generated-graphs/successes_per_week.png");
        }
    }

    public function createNumberOfConnectionsPerIP()
    {
        $db_query = "SELECT ip, COUNT(ip)
          FROM sessions
          GROUP BY ip
          ORDER BY COUNT(ip) DESC
          LIMIT 10 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart,a new pie chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $pie_chart = new PieChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['ip'], $row['COUNT(ip)']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(NUMBER_OF_CONNECTIONS_PER_UNIQUE_IP);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 40, 75, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render(DIR_ROOT . "/generated-graphs/connections_per_ip.png");

            //We set the pie chart's dataset and render the graph
            $pie_chart->setDataSet($dataSet);
            $pie_chart->setTitle(NUMBER_OF_CONNECTIONS_PER_UNIQUE_IP);
            $pie_chart->render(DIR_ROOT . "/generated-graphs/connections_per_ip_pie.png");
        }
    }

    public function createSuccessfulLoginsFromSameIP()
    {
        $db_query = "SELECT sessions.ip, COUNT(sessions.ip)
          FROM sessions INNER JOIN auth ON sessions.id = auth.session
          WHERE auth.success = 1
          GROUP BY sessions.ip
          ORDER BY COUNT(sessions.ip) DESC
          LIMIT 20 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['ip'], $row['COUNT(sessions.ip)']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(SUCCESSFUL_LOGINS_FROM_SAME_IP);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 45, 80, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render(DIR_ROOT . "/generated-graphs/logins_from_same_ip.png");
        }
    }

    public function createMostProbesPerDay()
    {
        $db_query = "SELECT COUNT(session), timestamp
          FROM auth
          GROUP BY DAYOFYEAR(timestamp)
          ORDER BY COUNT(session) DESC
          LIMIT 20 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new horizontal bar chart and initialize the dataset
            $chart = new HorizontalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['timestamp'])), $row['COUNT(session)']));
                //$dataSet->addPoint(new Point(date('l, d-m-Y', strtotime($row['timestamp'])), $row['COUNT(session)']));
            }

            //We set the horizontal chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(MOST_PROBES_PER_DAY);
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 50, 75 /*140*/)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render(DIR_ROOT . "/generated-graphs/most_probes_per_day.png");
        }
    }

    public function createProbesPerDay()
    {
        $db_query = "SELECT COUNT(session), timestamp
          FROM auth
          GROUP BY DAYOFYEAR(timestamp)
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
                    $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['timestamp'])), $row['COUNT(session)']));
                } else {
                    $dataSet->addPoint(new Point('', $row['COUNT(session)']));
                }
                $counter++;
            }

            //We set the line chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(PROBES_PER_DAY);
            $chart->render(DIR_ROOT . "/generated-graphs/probes_per_day.png");
        }
    }

    public function createProbesPerWeek()
    {
        $db_query = "SELECT COUNT(session),
          MAKEDATE(CASE WHEN WEEKOFYEAR(timestamp) = 52
            THEN YEAR(timestamp)-1
            ELSE YEAR(timestamp)
            END, (WEEKOFYEAR(timestamp) * 7)-4) AS DateOfWeek_Value
          FROM auth
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
                    $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['DateOfWeek_Value'])), $row['COUNT(session)']));
                } else {
                    $dataSet->addPoint(new Point('', $row['COUNT(session)']));
                }
                $counter++;

                //We add 6 "empty" points to make a horizontal line representing a week
                for ($i = 0; $i < 6; $i++) {
                    $dataSet->addPoint(new Point('', $row['COUNT(session)']));
                }
            }

            //We set the line chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(PROBES_PER_WEEK);
            $chart->render(DIR_ROOT . "/generated-graphs/probes_per_week.png");
        }
    }

    public function createTop10SSHClients()
    {
        $db_query = "SELECT clients.version, COUNT(client)
          FROM sessions INNER JOIN clients ON sessions.client = clients.id
          GROUP BY sessions.client
          ORDER BY COUNT(client) DESC
          LIMIT 10";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new HorizontalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['version'] . " ", $row['COUNT(client)']));
            }
            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_SSH_CLIENTS);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 50, 245)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            //$chart->getPlot()->setGraphPadding(new Padding(5, 80, 140, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render(DIR_ROOT . "/generated-graphs/top10_ssh_clients.png");
        }
    }
}

?>