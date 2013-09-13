<?php
#CSV Export for Kippo Graph
#Author: Kevin Breen
#Website: techanarchy.net

require_once "config.php";

//Get Query Strings to create dynamic queries
parse_str($_SERVER['QUERY_STRING']);

//Valid queries should be usernames, passwords, IP's optional query should be Limit.


//Let's connect to the database
$db_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); //host, username, password, database, port

if(mysqli_connect_errno()) {
	echo 'Error connecting to the database: '.mysqli_connect_error();
	exit();
}

if($_GET['type'] == 'ip') { // Get All Distinct IP's
$db_query = 'SELECT ip '
			."FROM session "
			."GROUP BY ip ";
} elseif($_GET['type'] == 'pass') { // Get all Distcint Passwords
$db_query = 'SELECT password '
			."FROM auth "
			."WHERE password <> '' "
			."GROUP BY password ";
} elseif($_GET['type'] == 'user') { // Get All Distinct / UserNames
$db_query = 'SELECT username '
			."FROM auth "
			."WHERE username <> '' "
			."GROUP BY username ";
} elseif($_GET['type'] == 'combo') { // get all Distinct User / Pass Combos
$db_query = 'SELECT username, password '
			."FROM auth "
			."WHERE username <> '' AND password <> '' "
			."GROUP BY username, password ";
} else {
	echo 'Invalid Query String: '.$type;
	exit();
}

$result = $db_conn->query($db_query);

$first = true;

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="export.csv"');
header('Cache-Control: max-age=0');

$out = fopen('php://output', 'w');

while ($row = $result->fetch_array(MYSQLI_NUM)) {
    if($first){
        $titles = array();
        foreach($row as $key=>$val){
            $titles[] = $key;
        }
        fputcsv($out, $titles);
        $first = false;
    }
    fputcsv($out, $row);
}
fclose($out);
?>