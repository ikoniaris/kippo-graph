<?php

class KippoIP
{
    private $db_conn;

    function __construct()
    {
        //Let's connect to the database
        $this->db_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); //host, username, password, database, port

        if (mysqli_connect_errno()) {
            echo 'Error connecting to the database: ' . mysqli_connect_error();
            exit();
        }
    }

    function __destruct()
    {
        $this->db_conn->close();
    }

    public function printOverallIpActivity()
    {
        $db_query = 'SELECT * FROM (SELECT ip, MAX(starttime), COUNT(DISTINCT sessions.id) '
            . "FROM sessions GROUP BY ip) A "
            . "LEFT JOIN (SELECT sessions.ip, MAX(success) "
            . "FROM sessions, auth "
            . "WHERE sessions.id = auth.session "
            . "GROUP BY ip) B on A.ip = B.ip "
            . "ORDER BY A.ip";
        $result = $this->db_conn->query($db_query);
        //echo 'Found '.$result->num_rows.' records';

        if ($result->num_rows > 0) {

            echo '<p>Click column heads to sort data, rows to display attack details.</p>';

            //We create a skeleton for the table
            echo '<table id="Overall-IP-Activity" class="tablesorter"><thead>';
            echo '<tr class="dark">';
            echo '<th colspan="4">Total identified IP addresses: ' . $result->num_rows . '</th>';
            echo '</tr>';
            echo '<tr class="dark">';
            echo '<th>IP address</th>';
            echo '<th>Sessions count</th>';
            echo '<th>Success</th>';
            echo '<th>Last seen</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            while ($row = $result->fetch_array(MYSQLI_BOTH)) {

                $success = is_null($row['MAX(success)']) ? 'N/A' : $row['MAX(success)'];
                $timestamp = is_null($row['MAX(starttime)']) ? 'N/A' : $row['MAX(starttime)'];

                echo '<tr class="light word-break" onclick=\'getIPinfo("' . $row['0'] . '")\'>';
                echo '<td>' . $row['0'] . '</td>';
                echo '<td>' . $row['COUNT(DISTINCT sessions.id)'] . '</td>';
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