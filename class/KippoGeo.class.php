<?php

require_once(DIR_ROOT . '/include/libchart/classes/libchart.php');
require_once(DIR_ROOT . '/include/qgooglevisualapi/config.inc.php');
require_once(DIR_ROOT . '/include/geoplugin/geoplugin.class.php');
require_once(DIR_ROOT . '/include/misc/ip2host.php');

class KippoGeo
{
    private $db_conn;
    private $geoplugin;

    function __construct()
    {
        $this->geoplugin = new geoPlugin();

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

    public function printKippoGeoData()
    {
        $db_query = 'SELECT ip, COUNT(ip) '
            ."FROM sessions "
            ."GROUP BY ip "
            ."ORDER BY COUNT(ip) DESC "
            ."LIMIT 10 ";

        $result = $this->db_conn->query($db_query);
        //echo 'Found '.$result->num_rows.' records';

        if($result->num_rows > 0)
        {
            //We create a new vertical bar chart, a new pie chart and initialize the dataset
            $verticalChart = new VerticalBarChart(600, 300);
            $pieChart = new PieChart(600, 300);
            $dataSet = new XYDataSet();

            //We create a "intensity" pie chart as well along with a dataset
            $intensityPieChart = new PieChart(600,300);
            $intensityDataSet = new XYDataSet();

            //We create a new Google Map and initialize its columns,
            //where the decoded geolocation data will be entered in the format
            //of Lat(number), Lon(number) and IP(string)
            $gMapTop10 = new QMapGoogleGraph;
            $gMapTop10->addColumns(
                array(
                    array('number', 'Lat'),
                    array('number', 'Lon'),
                    array('string', 'IP')
                )
            );

            //We create a new Intensity Map and initialize its columns,
            //where the decoded geolocation data will be entered in the format
            //of Country(2 letter string), #Probes(number)
            $intensityMap = new QIntensitymapGoogleGraph;
            $intensityMap->addDrawProperties(
                array(
                    "title"=>'IntensityMap',
                )
            );
            $intensityMap->addColumns(
                array(
                    array('string', '', 'Country'),
                    array('number', '#Probes', 'a'),
                )
            );
            //We create a temporary table in the database where we will store the IPs along with their #probes
            //and the corresponding country code, otherwise the Intensity Map won't work, because we need to
            //GROUP BY country code and SUM the #Probers per country
            $temp_table = 'CREATE TEMPORARY TABLE temp_ip (ip VARCHAR(12), counter INT, country VARCHAR(2))';
            $temp_table_execute = $this->db_conn->query($temp_table);

            //We create a dummy counter to use for the markers' tooltip inside Google Map like: IP 3/10
            //We use the same counter for the IP <table> as well
            $counter = 1;

            //We create a skeleton for the table
            echo '<p>The following table displays the top 10 IP addresses connected to the system (ordered by volume of connections).</p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo 	'<th>ID</th>';
            echo 	'<th>IP Address</th>';
            echo 	'<th>Probes</th>';
            echo 	'<th>City</th>';
            echo 	'<th>Region</th>';
            echo 	'<th>Country Name</th>';
            echo 	'<th>Code</th>';
            echo 	'<th>Latitude</th>';
            echo 	'<th>Longitude</th>';
            echo	'<th>Hostname</th>';
            echo	'<th colspan="3">Lookup</th>';
            echo '</tr></thead><tbody>';

            //We need to add data on the correct Map columns. The columns are always 0 or 1 or 2 for every repetition
            //so we can hardcode it into our code, but we need a way to automatically increase the row index. So we
            //create a dummy index variable to be increased after every repetition (as many db results we have)
            $col = 0;

            //For every row returned from the database...
            while($row = $result->fetch_array(MYSQLI_BOTH))
            {
                //We call the geoplugin service to get the geolocation data for the ip
                $this->geoplugin->locate($row['ip']);

                //We prepare the label for our vertical bar chart and add the point
                $geoip = $row['ip']." - ".$this->geoplugin->countryCode;
                $dataSet->addPoint(new Point($geoip, $row['COUNT(ip)']));

                //We next prepare the marker's tooltip inside Google Map
                $geostats = "<strong>TOP $counter/10:</strong> ".$row['ip']."<br />"
                    ."<strong>Probes:</strong> ".$row['COUNT(ip)']."<br />"
                    ."<strong>City:</strong> ".$this->geoplugin->city."<br />"
                    ."<strong>Region:</strong> ".$this->geoplugin->region."<br />"
                    ."<strong>Country:</strong> ".$this->geoplugin->countryName."<br />"
                    //."<strong>Country Code:</strong> ".$geoplugin->countryCode."<br />"
                    ."<strong>Latitude:</strong> ".$this->geoplugin->latitude."<br />"
                    ."<strong>Longitude:</strong> ".$this->geoplugin->longitude."<br />";

                //And add the marker to the map
                $gMapTop10->setValues(
                    array(
                        array($col, 0, (float)$this->geoplugin->latitude),
                        array($col, 1, (float)$this->geoplugin->longitude),
                        array($col, 2, $geostats)
                    )
                );

                //We prepare the data that will be inserted in our temporary table
                $ip = $row['ip']; $ip_count = $row['COUNT(ip)']; $CC = $this->geoplugin->countryCode;
                $country_query = "INSERT INTO temp_ip VALUES('$ip', '$ip_count', '$CC')";
                $country_query_execute = $this->db_conn->query($country_query);

                //For every row returned from the database we create a new table row with the data as columns
                echo '<tr class="light">';
                echo 	'<td>'.$counter.'</td>';
                echo 	'<td>'.$row['ip'].'<!--<a href="http://www.ip-adress.com/ip_tracer/'.$row['ip'].'" target="_blank"><img class="icon" src="images/ip_tracer.png"/></a>-->'.'</td>';
                echo	'<td>'.$row['COUNT(ip)'].'</td>';
                echo	'<td>'.$this->geoplugin->city.'</td>';
                echo	'<td>'.$this->geoplugin->region.'</td>';
                echo	'<td>'.$this->geoplugin->countryName.'</td>';
                echo	'<td>'.$this->geoplugin->countryCode.'</td>';
                echo	'<td>'.$this->geoplugin->latitude.'</td>';
                echo	'<td>'.$this->geoplugin->longitude.'</td>';
                echo	'<td>'.get_host($row['ip']).'</td>';
                echo	'<td class="icon"><a href="http://www.dshield.org/ipinfo.html?ip='.$row['ip'].'" target="_blank"><img class="icon" src="images/dshield.ico"/></a></td>';
                echo    '<td class="icon"><a href="http://www.ipvoid.com/scan/'.$row['ip'].'" target="_blank"><img class="icon" src="images/ipvoid.png"/></a></td>';
                echo    '<td class="icon"><a href="http://www.robtex.com/ip/'.$row['ip'].'.html" target="_blank"><img class=icon" src="images/robtex.ico"/></a></td>';
                echo '</tr>';

                //Lastly, we increase the index used by maps to indicate the next row,
                //and the dummy counter that indicates the next IP index (out of 10)
                $col++;
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
            echo '<hr /><br />';

            //While still inside the if($result->num_rows > 0) clause (otherwise the dataSet will be empty),
            //we set the bar chart's dataset, render the graph and display it (we're inside html code!)
            $verticalChart->setDataSet($dataSet);
            $verticalChart->setTitle(NUMBER_OF_CONNECTIONS_PER_UNIQUE_IP_CC);
            //For this particular graph we need to set the corrent padding
            $verticalChart->getPlot()->setGraphPadding(new Padding(5, 50, 100, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $verticalChart->render("generated-graphs/connections_per_ip_geo.png");
            echo '<p>The following vertical bar chart visualizes the top 10 IPs ordered by the number of connections to the system.'
                .'<br/>Notice the two-letter country code to after each IP get a quick view of the locations where the attacks are coming from.</p>';
            echo '<img src="generated-graphs/connections_per_ip_geo.png">';

            //We set the pie chart's dataset, render the graph and display it (we're inside html code!)
            $pieChart->setDataSet($dataSet);
            $pieChart->setTitle(NUMBER_OF_CONNECTIONS_PER_UNIQUE_IP_CC);
            $pieChart->render("generated-graphs/connections_per_ip_geo_pie.png");
            echo '<p>The following pie chart visualizes the top 10 IPs ordered by the number of connections to the system.'
                .'<br/>Notice the two-letter country code to after each IP get a quick view of the locations where the attacks are coming from.</p>';
            echo '<img src="generated-graphs/connections_per_ip_geo_pie.png">';
            echo '<hr /><br />';

            //Charts are ready, so is Google Map, let's render it below
            echo '<p>The following zoomable world map marks the geographic locations of the top 10 IPs according to their latitude and longitude values. '
                .'Click on them to get the full information available from the database.<p>';
            //echo '<div align=center>';
            echo $gMapTop10->render();
            //echo '</div>';
            echo '<br/><hr /><br />';

            //Lastly, we prepare the data for the Intesity Map
            $db_query_map = 'SELECT country, SUM(counter) '
                ."FROM temp_ip "
                ."GROUP BY country "
                ."ORDER BY SUM(counter) DESC ";
            //."LIMIT 10 ";

            $result = $this->db_conn->query($db_query_map);
            //echo 'Found '.$result->num_rows.' records';

            if($result->num_rows > 0) {
                $col = 0; //Dummy row index
                //For every row returned from the database add the values to Intensity Map's table and intensityPieChart
                while($row = $result->fetch_array(MYSQLI_BOTH))
                {
                    $countryProbes = $row['country']." - ".$row['SUM(counter)'];
                    $intensityDataSet->addPoint(new Point($countryProbes, $row['SUM(counter)']));
                    $intensityMap->setValues(
                        array(
                            array($col, 0, (string)$row['country']),
                            array($col, 1, (int)$row['SUM(counter)']),
                        )
                    );
                    $col++;
                }
            }
            //Intensity Map is ready, render it
            echo '<p>The following Intensity Map shows the volume of attacks per country by summarising probes originating from the same nation, using the same IP or not.</p>';
            echo $intensityMap->render();
            echo '<br/>';

            //We set the "intensity" pie chart's dataset, render the graph and display it (we're inside html code!)
            $intensityPieChart->setDataSet($intensityDataSet);
            $intensityPieChart->setTitle(NUMBER_OF_CONNECTIONS_PER_COUNTRY);
            $intensityPieChart->render("generated-graphs/connections_per_country_pie.png");
            echo '<p>The following pie chart visualizes the volume of attacks per country by summarising probes originating from the same nation, using the same IP or not.</p>';
            echo '<img src="generated-graphs/connections_per_country_pie.png">';
            echo '<hr /><small><a href="http://www.geoplugin.com/" target="_new" title="geoPlugin for IP geolocation">Geolocation by geoPlugin</a><small><br />';

        } //END IF
    }
}

?>