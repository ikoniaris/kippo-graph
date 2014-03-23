<?php
#CSV Export for Kippo Graph
#Author: Kevin Breen
#Website: techanarchy.net

require_once('../config.php');
require_once('../include/sql.php'); // this has all the SQL Statements named as per Query String below
require_once('../include/misc/xss_clean.php');

//Valid queries should be usernames, passwords, IP's optional query should be Limit.


//Let's connect to the database
$db_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); //host, username, password, database, port

if(mysqli_connect_errno()) {
	echo 'Error connecting to the database: '.mysqli_connect_error();
	exit();
}


// create the varaiable name from the URL Query string which should match SQL.php, than pass it as db_query
$db_query = ${"db_" . xss_clean($_GET['type'])};


$result = $db_conn->query($db_query);

$first = true; // flag for column titeles

//Set Headers to create download instead of a page
$fileName = "Export_" . $_GET['type'] . ".csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="'.$fileName.'"');
header('Cache-Control: max-age=0');

// open file without writing to disk
$out = fopen('php://output', 'w');

while ($row = $result->fetch_array(MYSQLI_NUM)) {
    if($first){
        $titles = array();
        foreach($row as $key=>$val){
            $titles[] = $key;
        }
        fputcsv($out, $titles); // write the titles
        $first = false; // no longer on the column titles
    }
    fputcsv($out, $row); // write all other rows
}
fclose($out);

?>