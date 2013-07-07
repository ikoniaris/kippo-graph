<?php

/**
 * Wrapper for Google Visualisation API
 * Visualisation type: Orgchart
 *
 */
class QOrgchartGoogleGraph extends QVizualisationGoogleGraph {

	/**
	 * visualisation type holder
	 *
	 * @var string
	 */
	protected $vizualisationType = "OrgChart";
	
	/**
	 * holder for default properties
	 *
	 * @var array
	 */
	protected $drawProperties = array();
	
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
		"size" => array(
			"values" => array("small", "medium", "large"), 
			"datatype" => "string"
			) 
		);
	
}
