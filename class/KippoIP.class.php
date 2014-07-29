<?php
require_once(DIR_ROOT . '/include/rb.php');

class KippoIP
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

    public function printOverallIpActivity()
    {
        $db_query = "SELECT A.*, B.success FROM (
          SELECT ip, MAX(starttime) as starttime, COUNT(DISTINCT sessions.id) as sessions
          FROM sessions GROUP BY ip) A
          LEFT JOIN (
            SELECT sessions.ip, MAX(success) as success
            FROM sessions, auth
            WHERE sessions.id = auth.session
            GROUP BY ip) B on A.ip = B.ip
          ORDER BY A.ip";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            echo '<p>Click column heads to sort data, rows to display attack details.</p>';

            //We create a skeleton for the table
            echo '<table id="Overall-IP-Activity" class="tablesorter"><thead>';
            echo '<tr class="dark">';
            echo '<th colspan="4">Total identified IP addresses: ' . count($rows) . '</th>';
            echo '</tr>';
            echo '<tr class="dark">';
            echo '<th>IP address</th>';
            echo '<th>Sessions count</th>';
            echo '<th>Success</th>';
            echo '<th>Last seen</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach ($rows as $row) {
                $success = is_null($row['success']) ? 'N/A' : $row['success'];
                $timestamp = is_null($row['starttime']) ? 'N/A' : $row['starttime'];

                echo '<tr class="light word-break" onclick=\'getIPinfo("' . $row['ip'] . '")\'>';
                echo '<td>' . $row['ip'] . '</td>';
                echo '<td>' . $row['sessions'] . '</td>';
                echo '<td>' . $success . '</td>';
                echo '<td>' . $timestamp . '</td>';
                echo '</tr>';
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
        }

        echo '<div id="pager1" class="pager">';
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
        echo '  <a id="allActivityLink" href="include/export.php?type=allActivity">CSV of all recent IP activity</a>';
        echo '</div>';

        echo '<hr /><br />';

    }
}

?>