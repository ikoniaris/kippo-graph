<?php

# Removing the autoloader function for the time being because it messes up
# the namespaces in KippoGeo.class.php when used with MaxMind GeoIP2-php which has its own.
/*
function __autoload($className = null)
{
    $classes = array(
        'QConfig.class.php',
        'QInflector.class.php',
        'QTool.class.php',
        'QGoogleGraph.class.php',
        'QVizualisationGoogleGraph.class.php',
        'QApikeyGoogleGraph.class.php',
    );

    foreach ($classes as $class) {
        include_once($class);
    }

    include_once($className . ".class.php");

}
*/

# Manually requiring QGoogleVisualizationAPI classes for KippoGeo.class.php,
# hopefully in the correct order!
require_once('QGoogleGraph.class.php');
require_once('QTool.class.php');
require_once('QVizualisationGoogleGraph.class.php');
require_once('QApikeyGoogleGraph.class.php');
require_once('QMapGoogleGraph.class.php');
require_once('QIntensitymapGoogleGraph.class.php');