<?php

/**
 * Wrapper for Google Visualisation API
 * Visualisation type: Annotated Time Line
 * @author Thomas Schäfer
 * @since 2008-06-29
 *
 */
class QAnnotatedtimelineGoogleGraph extends QVizualisationGoogleGraph {

	/**
	 * visualisation type holder
	 *
	 * @var string
	 */
	protected $vizualisationType = "AnnotatedTimeLine";

	/**
	 * holder for default properties
	 *
	 * @var array
	 */
	protected $drawProperties = array("width"=>"740px", "height"=>"240px", "displayAnnotations" => "true");

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
		"allowHtml" => array("datatype" => "bool"),
		"annotationsWidth" => array("datatype" => "number"),
		"allowHtml" => array("datatype" => "bool"),
		"colors" => array("datatype" => "array"),
		"displayAnnotations" => array("datatype" => "bool"),
		"displayAnnotationsFilters" => array("datatype" => "bool"),
		"displayExactValues" => array("datatype" => "bool"),
		"min" => array("datatype" => "number"),
		"legend" => array(
			"values" => array("fixed", "maximize"), 
			"datatype" => "string"
		), 
		"wmode" => array(
			"values" => array("opaque", "window", "transparent"), 
			"datatype" => "string"
		), 
		"zoomEndTime" => array("datatype" => "date"),
		"zoomStartTime" => array("datatype" => "date"),
	);

}
