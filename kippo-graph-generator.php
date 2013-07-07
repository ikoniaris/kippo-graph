<?php
#Package: Kippo-Graph
#Version: 0.7.6
#Author: ikoniaris
#Website: bruteforce.gr/kippo-graph

include_once('include/libchart/classes/libchart.php');
require_once('config.php');

//Let's connect to the database
$db_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); //host, username, password, database, port

if(mysqli_connect_errno()) {
	echo 'Error connecting to the database: '.mysqli_connect_error();
	exit();
}

//-----------------------------------------------------------------------------------------------------------------
//TOP 10 PASSWORDS
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT password, COUNT(password) '
			."FROM auth "
			."WHERE password <> '' "
			."GROUP BY password "
			."ORDER BY COUNT(password) DESC "
			."LIMIT 10 ";

$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new vertical bar chart and initialize the dataset
	$chart = new VerticalBarChart(600, 300);
	$dataSet = new XYDataSet();
	
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		$dataSet->addPoint(new Point($row['password'], $row['COUNT(password)']));
	}
	
	//We set the bar chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(TOP_10_PASSWORDS);
	$chart->render("generated-graphs/top10_passwords.png");
}

//-----------------------------------------------------------------------------------------------------------------
//TOP 10 USERNAMES
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT username, COUNT(username) '
			."FROM auth "
			."WHERE username <> '' "
			."GROUP BY username "
			."ORDER BY COUNT(username) DESC "
			."LIMIT 10 ";

$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new vertical bar chart and initialize the dataset
	$chart = new VerticalBarChart(600, 300);
	$dataSet = new XYDataSet();
	
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		$dataSet->addPoint(new Point($row['username'], $row['COUNT(username)']));
	}
	
	//We set the bar chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(TOP_10_USERNAMES);
	$chart->render("generated-graphs/top10_usernames.png");
}

//-----------------------------------------------------------------------------------------------------------------
//TOP 10 USERNAME-PASSWORD COMBINATIONS
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT username, password, COUNT(username) '
			."FROM auth "
			."WHERE username <> '' AND password <> '' "
			."GROUP BY username, password "
			."ORDER BY COUNT(username) DESC "
			."LIMIT 10 ";

$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new vertical bar chart,a new pie chart and initialize the dataset
	$chart = new VerticalBarChart(600, 300);
	$pie_chart = new PieChart(600, 300);
	$dataSet = new XYDataSet();
	
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		$dataSet->addPoint(new Point($row['username'].'/'.$row['password'], $row['COUNT(username)']));
	}
	
	//We set the bar chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(TOP_10_COMBINATIONS);
	//For this particular graph we need to set the corrent padding
	$chart->getPlot()->setGraphPadding(new Padding(5, 40, 75, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
	$chart->render("generated-graphs/top10_combinations.png");
	
	//We set the pie chart's dataset and render the graph
	$pie_chart->setDataSet($dataSet);
	$pie_chart->setTitle(TOP_10_COMBINATIONS);
	$pie_chart->render("generated-graphs/top10_combinations_pie.png");
}

//-----------------------------------------------------------------------------------------------------------------
//SUCCESS RATIO
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT success, COUNT(success) '
			."FROM auth "
			."GROUP BY success "
			."ORDER BY success";

$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new vertical bar chart and initialize the dataset
	$chart = new VerticalBarChart(600, 300);
	$dataSet = new XYDataSet();

	//Database should return two rows, so we need two bars
	//If success = 0 or = 1 add point accordingly, else a new bar (in case of NULL/whatever)
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		if($row['success'] == 0)
			$dataSet->addPoint(new Point(AUTH_FAIL, $row['COUNT(success)']));
		else if($row['success'] == 1)
			$dataSet->addPoint(new Point(AUTH_SUCCESS, $row['COUNT(success)']));
		else
			$dataSet->addPoint(new Point($row['success'], $row['COUNT(success)']));
	}
	
	//We set the bar chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(OVERALL_SUCCESS_RATIO);
	$chart->render("generated-graphs/success_ratio.png");
}

//-----------------------------------------------------------------------------------------------------------------
//MOST SUCCESSFUL LOGINS PER DAY
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT COUNT(session), timestamp '
			."FROM auth "
			."WHERE success = 1 "
			."GROUP BY DAYOFYEAR(timestamp) "
			//."HAVING COUNT(session) >= XX "
			."ORDER BY COUNT(session) DESC "
			//."ORDER BY timestamp ASC ";
			."LIMIT 20 ";
			
$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new horizontal bar chart and initialize the dataset
	$chart = new VerticalBarChart(600, 300);
	$dataSet = new XYDataSet();
	
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		$dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['timestamp'])), $row['COUNT(session)']));
	}
	
	//We set the horizontal chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(MOST_SUCCESSFUL_LOGINS_PER_DAY);
	$chart->getPlot()->setGraphPadding(new Padding(5, 30, 50, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
	$chart->render("generated-graphs/most_successful_logins_per_day.png");
}

//-----------------------------------------------------------------------------------------------------------------
//SUCCESSES PER DAY
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT COUNT(session), timestamp '
			."FROM auth "
			."WHERE success = 1 "
			."GROUP BY DAYOFYEAR(timestamp) "
			."ORDER BY timestamp ASC ";
			
$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new horizontal bar chart and initialize the dataset
	$chart = new LineChart(600, 300);
	$dataSet = new XYDataSet();
	
	//This graph gets messed up for large DBs, so here is a simple way to limit some of the input
	$counter = 1;
	//Display date legend only every $mod rows, 25 distinct values being the optimal for a graph
	$mod = round($result->num_rows/25);
	if($mod == 0) $mod = 1; //otherwise a division by zero might happen below
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
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
	$chart->render("generated-graphs/successes_per_day.png");
}

//-----------------------------------------------------------------------------------------------------------------
//SUCCESSES PER WEEK
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT COUNT(session), MAKEDATE( '
			."CASE " 
				."WHEN WEEKOFYEAR(timestamp) = 52 "
                ."THEN YEAR(timestamp)-1 "
				."ELSE YEAR(timestamp) "
			."END, (WEEKOFYEAR(timestamp) * 7)-4) AS DateOfWeek_Value "
			."FROM auth "
			."WHERE success = 1 "
			."GROUP BY WEEKOFYEAR(timestamp) "
			."ORDER BY timestamp ASC";

$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new line chart and initialize the dataset
	$chart = new LineChart(600, 300);
	$dataSet = new XYDataSet();
	
	//This graph gets messed up for large DBs, so here is a simple way to limit some of the input
	$counter = 1;
	//Display date legend only every $mod rows, 25 distinct values being the optimal for a graph
	$mod = round($result->num_rows/25);
	if($mod == 0) $mod = 1; //otherwise a division by zero might happen below
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		if ($counter % $mod == 0) {
			$dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['DateOfWeek_Value'])), $row['COUNT(session)']));
		} else {
			$dataSet->addPoint(new Point('', $row['COUNT(session)']));
		}
		$counter++;
		
		//We add 6 "empty" points to make a horizontal line representing a week
		for($i=0; $i<6; $i++) {
			$dataSet->addPoint(new Point('', $row['COUNT(session)']));
		}
	}

	//We set the line chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(SUCCESSES_PER_WEEK);
	$chart->render("generated-graphs/successes_per_week.png");
}

//-----------------------------------------------------------------------------------------------------------------
//NUMBER OF CONNECTIONS PER IP (TOP 10)
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT ip, COUNT(ip) '
			."FROM sessions "
			."GROUP BY ip "
			."ORDER BY COUNT(ip) DESC "
			."LIMIT 10 ";

$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new vertical bar chart,a new pie chart and initialize the dataset
	$chart = new VerticalBarChart(600, 300);
	$pie_chart = new PieChart(600, 300);
	$dataSet = new XYDataSet();
	
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		$dataSet->addPoint(new Point($row['ip'], $row['COUNT(ip)']));
	}
	
	//We set the bar chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(NUMBER_OF_CONNECTIONS_PER_UNIQUE_IP);
	//For this particular graph we need to set the corrent padding
	$chart->getPlot()->setGraphPadding(new Padding(5, 40, 75, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
	$chart->render("generated-graphs/connections_per_ip.png");
	
	//We set the pie chart's dataset and render the graph
	$pie_chart->setDataSet($dataSet);
	$pie_chart->setTitle(NUMBER_OF_CONNECTIONS_PER_UNIQUE_IP);
	$pie_chart->render("generated-graphs/connections_per_ip_pie.png");
}

//-----------------------------------------------------------------------------------------------------------------
//SUCCESSFUL LOGINS FROM SAME IP
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT sessions.ip, COUNT(sessions.ip) '
			."FROM sessions INNER JOIN auth ON sessions.id = auth.session "
			."WHERE auth.success = 1 "
			."GROUP BY sessions.ip "
			."ORDER BY COUNT(sessions.ip) DESC "
			."LIMIT 20 ";

$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new vertical bar chart and initialize the dataset
	$chart = new VerticalBarChart(600, 300);
	$dataSet = new XYDataSet();
	
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		$dataSet->addPoint(new Point($row['ip'], $row['COUNT(sessions.ip)']));
	}
	
	//We set the bar chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(SUCCESSFUL_LOGINS_FROM_SAME_IP);
	//For this particular graph we need to set the corrent padding
	$chart->getPlot()->setGraphPadding(new Padding(5, 45, 80, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
	$chart->render("generated-graphs/logins_from_same_ip.png");
}

//-----------------------------------------------------------------------------------------------------------------
//MOST PROBES PER DAY
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT COUNT(session), timestamp '
			."FROM auth "
			."GROUP BY DAYOFYEAR(timestamp) "
			."ORDER BY COUNT(session) DESC "
			."LIMIT 20 ";
			
$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new horizontal bar chart and initialize the dataset
	$chart = new HorizontalBarChart(600, 300);
	$dataSet = new XYDataSet();
	
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		$dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['timestamp'])), $row['COUNT(session)']));
		//$dataSet->addPoint(new Point(date('l, d-m-Y', strtotime($row['timestamp'])), $row['COUNT(session)']));
	}
	
	//We set the horizontal chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(MOST_PROBES_PER_DAY);
	$chart->getPlot()->setGraphPadding(new Padding(5, 30, 50, 75 /*140*/)); //top, right, bottom, left | defaults: 5, 30, 50, 50
	$chart->render("generated-graphs/most_probes_per_day.png");
}

//-----------------------------------------------------------------------------------------------------------------
//PROBES PER DAY
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT COUNT(session), timestamp '
			."FROM auth "
			."GROUP BY DAYOFYEAR(timestamp) "
			."ORDER BY timestamp ASC ";

$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new line chart and initialize the dataset
	$chart = new LineChart(600, 300);
	$dataSet = new XYDataSet();
	
	//This graph gets messed up for large DBs, so here is a simple way to limit some of the input
	$counter = 1;
	//Display date legend only every $mod rows, 25 distinct values being the optimal for a graph
	$mod = round($result->num_rows/25);
	if($mod == 0) $mod = 1; //otherwise a division by zero might happen below
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
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
	$chart->render("generated-graphs/probes_per_day.png");
}

//-----------------------------------------------------------------------------------------------------------------
//PROBES PER WEEK
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT COUNT(session), MAKEDATE( '
			."CASE " 
				."WHEN WEEKOFYEAR(timestamp) = 52 "
                ."THEN YEAR(timestamp)-1 "
				."ELSE YEAR(timestamp) "
			."END, (WEEKOFYEAR(timestamp) * 7)-4) AS DateOfWeek_Value "
			."FROM auth "
			."GROUP BY WEEKOFYEAR(timestamp) "
			."ORDER BY timestamp ASC";


$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new line chart and initialize the dataset
	$chart = new LineChart(600, 300);
	$dataSet = new XYDataSet();
	
	//This graph gets messed up for large DBs, so here is a simple way to limit some of the input
	$counter = 1;
	//Display date legend only every $mod rows, 25 distinct values being the optimal for a graph
	$mod = round($result->num_rows/25);
	if($mod == 0) $mod = 1; //otherwise a division by zero might happen below
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		if ($counter % $mod == 0) {
			$dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['DateOfWeek_Value'])), $row['COUNT(session)']));
		} else {
			$dataSet->addPoint(new Point('', $row['COUNT(session)']));
		}
		$counter++;
		
		//We add 6 "empty" points to make a horizontal line representing a week
		for($i=0; $i<6; $i++) {
			$dataSet->addPoint(new Point('', $row['COUNT(session)']));
		}
	}

	//We set the line chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(PROBES_PER_WEEK);
	$chart->render("generated-graphs/probes_per_week.png");
}

//-----------------------------------------------------------------------------------------------------------------
//TOP 10 SSH CLIENTS
//-----------------------------------------------------------------------------------------------------------------
$db_query = 'SELECT clients.version, COUNT(client) '
			."FROM sessions INNER JOIN clients ON sessions.client = clients.id "
			."GROUP BY sessions.client "
			."ORDER BY COUNT(client) DESC "
			//."ORDER BY clients.version ASC"; //alphabetical sorting
			."LIMIT 10";
			
$result = $db_conn->query($db_query);
//echo 'Found '.$result->num_rows.' records';

if($result->num_rows > 0) {
	//We create a new vertical bar chart and initialize the dataset
	$chart = new HorizontalBarChart(600, 300);
	$dataSet = new XYDataSet();
	
	//For every row returned from the database we add a new point to the dataset
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		$dataSet->addPoint(new Point($row['version']." ", $row['COUNT(client)']));
	}
	//We set the bar chart's dataset and render the graph
	$chart->setDataSet($dataSet);
	$chart->setTitle(TOP_10_SSH_CLIENTS);
	//For this particular graph we need to set the corrent padding
	$chart->getPlot()->setGraphPadding(new Padding(5, 30, 50, 245)); //top, right, bottom, left | defaults: 5, 30, 50, 50
	//$chart->getPlot()->setGraphPadding(new Padding(5, 80, 140, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
	$chart->render("generated-graphs/top10_ssh_clients.png");
}

//-----------------------------------------------------------------------------------------------------------------
//END
//-----------------------------------------------------------------------------------------------------------------

//We close the connection
$db_conn->close();

//And redirect to the graph presentation page
header('location:kippo-graph.php');
?>