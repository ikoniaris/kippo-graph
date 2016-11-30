<?php
require_once(DIR_ROOT . '/include/rb.php');

class KippoPlayLog
{

    function __construct()
    {
        // Let's connect to the database
        R::setup('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    }

    function __destruct()
    {
        R::close();
    }

    public function printLogs()
    {
        echo '<p>Hiding all entries which are smaller than ' . PLAYBACK_SIZE_IGNORE . 'kb.</p>';

        if (strtoupper(BACK_END_ENGINE) === 'COWRIE')
            $db_size = "size";
        else
            $db_size = "LENGTH(ttylog)";

        $db_query = "SELECT * FROM (SELECT ttylog.session, auth.timestamp, ROUND($db_size/1024, 2) AS size, COUNT(input) as input
                     FROM ttylog
                     JOIN auth ON ttylog.session = auth.session
                     JOIN input ON ttylog.session = input.session
                     WHERE auth.success = 1
                     GROUP BY ttylog.session
                     ORDER BY auth.timestamp ASC) s
                     WHERE size > " . PLAYBACK_SIZE_IGNORE;

        $rows = R::getAll($db_query);

        echo '<table id="Total-Logs"><thead><tr class="dark"><th>
              Total logs: ' . count($rows) . '</th></tr></thead></table>';

        echo '<table id="Playlog-List" class="tablesorter"><thead>';
        echo '<tr class="dark">';
        echo '<th>ID</th>';
        echo '<th>Timestamp</th>';
        echo '<th>Size</th>';
        echo '<th>Input Commands</th>';
        echo '<th>Action</th>';
        echo '</tr></thead><tbody>';

        if (count($rows)) {
            // We create a skeleton for the table
            $counter = 1;

            // For every row returned from the database we create a new table row with the data as columns
            foreach ($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . $row['timestamp'] . '</td>';
                echo '<td>' . $row['size'] . 'kb' . '</td>';
                echo '<td>' . $row['input'] . '</td>';
                echo '<td><a href="kippo-play.php?f=' . $row['session'] . '" target="_blank"><img class="icon" src="images/play.ico"/>Play TTY Log</a></td>';
                echo '</tr>';
                $counter++;
            }
        }

        // Close tbody and table element, it's ready.
        echo '</tbody></table>';

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
        echo '        <option value="25">20</option>';
        echo '        <option value="50">50</option>';
        echo '        <option value="75">20</option>';
        echo '        <option value="100">100</option>';
        echo '        <option value="500">100</option>';
        echo '     </select>';
        echo '  </form>';
        echo '</div>';

        echo '<hr><br />';
    }
}

?>
