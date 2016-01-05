<?php

# Used for <title></title>
$page_title = "Geolocation Information | Fast Visualization for your Kippo Based SSH Honeypot";

# Used for nav menu
$page_file = "kippo-geo.php";

require('include/header.php');
?>

<div class="wrapper">
    <div class="container">
        <div class="whitebox">
            <!-- ####################################################################################################### -->
            <h2>Geolocation information gathered from the <b>top 10</b> IP addresses probing the system</h2>
            <hr>

            <?php
            # Author: ikoniaris

            require_once('config.php');

            if (!is_writable(DIR_ROOT . '/generated-graphs/')) {
                echo '<h3>WARNING: ' . DIR_ROOT . '/generated-graphs/' . ' <b>is not writeable</b>. Images will not be generated.</h3>';
                echo "<br /><hr>";
            }

            require_once(DIR_ROOT . '/class/KippoGeo.class.php');

            $kippoGeo = new KippoGeo();

            //-----------------------------------------------------------------------------------------------------------------
            //KIPPO-GEO DATA
            //-----------------------------------------------------------------------------------------------------------------
            $kippoGeo->printKippoGeoData();
            //-----------------------------------------------------------------------------------------------------------------
            //END
            //-----------------------------------------------------------------------------------------------------------------

            ?>
            <!-- ####################################################################################################### -->
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php
require('include/footer.php');
?>
