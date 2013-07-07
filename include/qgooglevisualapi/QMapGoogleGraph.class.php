<?php

/**
 * Wrapper for Google Visualisation API
 * Visualisation type: Map
 * @author Thomas Schäfer
 * @since 2008-06-29
 *
 */
class QMapGoogleGraph extends QVizualisationGoogleGraph {

	protected $apiKey = QApikeyGoogleGraph::KEY;
	
	/**
	 * visualisation type holder
	 *
	 * @var string
	 */
	protected $vizualisationType = "Map";
	
	/**
	 * holder for default properties
	 *
	 * @var array
	 * 
	 */
	protected $drawProperties = array(
		"width"=>"740px", 
		"height"=>"240px", 
		"showTip"=> 'true',
		
	);

	/**
	 * holder for google api package name
	 *
	 * @var string
	 */
	protected $package = "";

	/**
	 * package setup info
	 *
	 * @var array
	 */
	protected $packageSetup = array(
		"script" => array(
			 "type" => "text/javascript",
			 "src" => QApikeyGoogleGraph::KEY,
		) 
	);
	
	/**
	 * holder for registered api methods
	 *
	 * @var array
	 */
	protected $configuration = array(
		"enableScrollWheel" => array("datatype" => "bool"),
		"showTip" => array("datatype" => "bool"),
		"showLine" => array("datatype" => "bool"),
		"lineColor" => array("datatype" => "string"),
		"lineWidth" => array("datatype" => "integer"),
	);

	
}
