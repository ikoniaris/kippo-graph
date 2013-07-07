QGoogleVisualizationAPI 2009 (new)
http://www.phpclasses.org/browse/package/5646.html

More features...












preview of my QChartBuilder for QGoogleVisualizationAPI at www.query4u.de/QChartBuilder

############################
** QVisualisationGoogleGraph Extraction from QLib**
@license BSD
@author Thomas Schäfer
@since 2008-30-06
@version 0.2

== Introduction ==
Google Visualisation API produces javascript code visualising data in different formats.
QVisualisationGoogleGraph provides some wrapper classes to dynamically produces
javascript code using the google jsapi lib.

provided chart types:
* Annotated timeline (Flash)
* Area chart
* Bar chart
* Column chart
* Gauge
* Intensity Map
* Map
* Motion chart (Flash)
* Pie chart
* Scatter chart
* Tables
* Word clouds (user def.)
* Organisation chart

* Mash-Up

Additional classes used by QVisualisationGoogleGraph:
* QInflector (inflecting for namespace)
* QConfig (debug, config properties)
* QTracer (debug trace)
* QTool (context namespace)


== methods ==

=== setColumns ===
This method adds column names to a chart. The method expects an array of arrays.
The first entry of column data array defines the data type.
The second entry holds the value. If you choose an intensity map chart then
a third parameters is required. In this very case the third parameter holds the
country name flag.
You may define columns as many as you want, but you have to structure it as described.

e.g. for intensity map
$chart->addColumns(
array(
array('string', '', 'Country'),
array('number', 'Population (mil)', 'a'),
array('number', 'Area (km2)', 'b'),
)
);

e.g. for other chart types
$chart->addColumns(
array(
array('date', 'Date'),
array('number', 'Sold Pencils'),
array('number', 'Sold Pens'),
array('string', 'title'),
array('string', 'text'),
)
);


=== setValues ===
The setValues method pushes the data into the chart object where
it will be rendered contextually. If a chart type needs the
Google API setCell method it switches automatically.

While setting the values the addRows method will be rendered, too.


e.g. setValue for annotated timeline charts
$chart->setValues(
array(
array(0, 0, 'new Date(2008, 1 ,1)'),
array(0, 1, 30000),
array(0, 2, 40645),
)
);


=== drawProperties ===
The setDrawProperties allows you to change the default chart property values.
You have to visit the Google Visualization Web Api reference for getting an
image of what can be done.

** Contextual Reference Link **
$chart = new QAnnotatedtimelineGoogleGraph();
echo $chart->getReferenceLink();

This command provides a link to the chapter where annotated timelines are explained.

Usually the chart class checks the draw properties against configuration properties.
The configuration property is part of each chart class. Each has an individual repertoire.
This class property holds the supported features with their data types, and optionally value ranges.

=== render ===
The last method you need to know is the render command.
This method collects, merges and returns the script.


== conclusion ==
Making chart types with the Google Visualization API is pretty simple.
You need not to know anything about javascript. PHP does it for you.

If you are mature with both languages you can write your own classes
to enhance the functionality.