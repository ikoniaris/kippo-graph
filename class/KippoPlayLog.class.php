<?php
require_once(DIR_ROOT . '/include/rb.php');

class KippoPlayLog
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

    public function printLogs()
    {
        $db_query = "SELECT * FROM (
            SELECT ttylog.session, timestamp, ROUND(LENGTH(ttylog)/1024, 2) AS size
            FROM ttylog
            JOIN auth ON ttylog.session = auth.session
            WHERE auth.success = 1
            GROUP BY ttylog.session
            ORDER BY timestamp DESC
            ) s
            WHERE size > " . PLAYBACK_SIZE_IGNORE;

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            $counter = 1;
            echo '<p>The following table displays a list of all the logs recorded by Kippo.
                     Click on column heads to sort data.</p>';
            echo '<table id="Playlog-List" class="tablesorter"><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Timestamp</th>';
            echo '<th>Size</th>';
            echo '<th>Play the log</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we create a new table row with the data as columns
            foreach ($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . $row['timestamp'] . '</td>';
                echo '<td>' . $row['size'] . 'kb' . '</td>';
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
