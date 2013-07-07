<?php

/**
 * Wrapper for Google Visualisation API
 * Visualisation type: motion chart
 * @author Thomas Schäfer
 * @since 2008-06-29
 *
 */
class QMotionchartGoogleGraph extends QVizualisationGoogleGraph {

	/**
	 * visualisation type holder
	 *
	 * @var string
	 */
	protected $vizualisationType = "MotionChart";

	/**
	 * default property holder
	 *
	 * @var array
	 */
	protected $drawProperties = array("width"=>600,"height"=>400);

	/**
	 * holder for google api package name
	 *
	 * @var string
	 */
	protected $package = "";

	/**
	 * holder for default properties
	 *
	 * @var array
	 */
	protected $configuration = array(
		"width" => array("datatype" => "integer"),	
		"height" => array("datatype" => "integer"),	
	);

}
