<?php
require_once('../config.php');
require_once(DIR_ROOT . '/include/rb.php');
require_once(DIR_ROOT . '/include/misc/xss_clean.php');

$ip = xss_clean($_POST['ip']);

if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    echo "Error parsing IP address.";
    exit();
}

R::setup('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

$db_query = "SELECT timestamp, ip, session, username, password, success
FROM sessions, auth
WHERE sessions.id = auth.session AND sessions.ip='$ip'
ORDER BY auth.timestamp ASC";

$rows = R::getAll($db_query);

if (count($rows)) {
    //We create a skeleton for the table
    echo '<table id="IP-attemps" class="tablesorter"><thead>';
    echo '<tr class="dark">';
    echo '<th colspan="6">Total connection attempts from ' . $ip . ': ' . count($rows) . ' </th>';
    echo '</tr>';
    echo '<tr class="dark">';
    echo '<th>Timestamp</th>';
    echo '<th>IP</th>';
    echo '<th>Session</th>';
    echo '<th>Username</th>';
    echo '<th>Password</th>';
    echo '<th>Success</th>';
    echo '</tr></thead><tbody>';

    //For every row returned from the database we add a new point to the dataset,
    //and create a new table row with the data as columns
    foreach ($rows as $row) {
        echo '<tr class="light word-break">';
        echo '<td>' . $row['timestamp'] . '</td>';
        echo '<td>' . $row['ip'] . '</td>';
        echo '<td>' . $row['session'] . '</td>';
        echo '<td>' . xss_clean($row['username']) . '</td>';
        echo '<td>' . xss_clean($row['password']) . '</td>';
        echo '<td>' . $row['success'] . '</td>';
        echo '</tr>';
    }

    //Close tbody and table element, it's ready.
    echo '</tbody></table>';


    echo '<div id="pager2" class="pager">';
    echo '  <form>';
    echo '     <img src="images/first.png" class="first"/>';
    echo '     <img src="images/prev.png" class="prev"/>';
    echo '     <span class="pagedisplay"></span>';
    echo '     <img src="images/next.png" class="next"/>';
    echo '     <img src="images/last.png" class="last"/>';
    echo '     <select class="pagesize">';
    echo '        <option selected="selected" value="10">10</option>';
    echo '        <option value="20">20</option>';
    echo '        <option value="30">30</option>';
    echo '	      <option value="40">40</option>';
    echo '     </select>';
    echo '  </form>';
    echo '</div>';

    echo '<hr /><br />';
} else {
    echo '<p>No attempt records were found</p>';
}

$db_query = "SELECT * FROM
  (SELECT DISTINCT sessions.id FROM SESSIONS WHERE sessions.ip='$ip') A
  JOIN (SELECT * FROM INPUT) B on A.id=B.session ORDER BY TIMESTAMP";

$rows = R::getAll($db_query);

if (count($rows)) {
    //We create a skeleton for the table
    echo '<table id="IP-commands" class="tablesorter"><thead>';
    echo '<tr class="dark">';
    echo '<th colspan="4">Total input activity from ' . $ip . ': ' . count($rows) . ' </th>';
    echo '</tr>';
    echo '<tr class="dark">';
    echo '<th>Timestamp</th>';
    echo '<th>Session</th>';
    echo '<th>Success</th>';
    echo '<th>Input</th>';
    echo '</tr></thead><tbody>';

    //For every row returned from the database we add a new point to the dataset,
    //and create a new table row with the data as columns
    foreach ($rows as $row) {
        echo '<tr class="light word-break">';
        echo '<td>' . $row['timestamp'] . '</td>';
        echo '<td>' . $row['session'] . '</td>';
        echo '<td>' . $row['success'] . '</td>';
        echo '<td>' . xss_clean($row['input']) . '</td>';
        echo '</tr>';
    }
    //Close tbody and table element, it's ready.
    echo '</tbody></table>';

    echo '<div id="pager3" class="pager">';
    echo '  <form>';
    echo '     <img src="images/first.png" class="first"/>';
    echo '     <img src="images/prev.png" class="prev"/>';
    echo '     <span class="pagedisplay"></span>';
    echo '     <img src="images/next.png" class="next"/>';
    echo '     <img src="images/last.png" class="last"/>';
    echo '     <select class="pagesize">';
    echo '        <option selected="selected" value="10">10</option>';
    echo '        <option value="20">20</option>';
    echo '        <option value="30">30</option>';
    echo '        <option value="40">40</option>';
    echo '     </select>';
    echo '  </form>';
    echo '</div>';

    echo '<hr /><br />';
} else {
    echo '<p>No activity records were found</p>';
}

R::close();

?>