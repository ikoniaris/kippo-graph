<?php

/**
 * Wrapper for Google Visualisation API
 * Visualisation type: Gauge
 * @author Thomas Schäfer
 * @since 2008-06-29
 *
 */
class QGaugeGoogleGraph extends QVizualisationGoogleGraph {

	/**
	 * visualisation type holder
	 *
	 * @var string
	 */
	protected $vizualisationType = "Gauge";
	
	/**
	 * holder for default properties
	 *
	 * @var array
	 * {width: 400, height: 120, redFrom: 90, redTo: 100,
            yellowFrom:75, yellowTo: 90, minorTicks: 5}
	 */
	protected $drawProperties = array(
		"width"=>400,
		"height"=>120,
		"redFrom"=>90,
		"redTo"=>100,
		"yellowFrom"=>75,
		"yellowTo"=>90,
		"minorTicks"=>5,
	);

	/**
	 * holder for google api package name
	 *
	 * @var string
	 */
	protected $package = "";

	/**
	 * holder for registered api methods
	 *
	 * @var array
	 */
	protected $configuration = array(
		"width" => array("datatype" => "integer"),
		"height" => array("datatype" => "integer"),
		"redFrom" => array("datatype" => "integer"),
		"redTo" => array("datatype" => "integer"),
		"yellowFrom" => array("datatype" => "integer"),
		"yellowTo" => array("datatype" => "integer"),
		"greenFrom" => array("datatype" => "integer"),
		"greenTo" => array("datatype" => "integer"),
		"min" => array("datatype" => "integer"),
		"max" => array("datatype" => "integer"),
		"majorTicks" => array("datatype" => "integer"),
		"minorTicks" => array("datatype" => "integer"),
	);

	
}
