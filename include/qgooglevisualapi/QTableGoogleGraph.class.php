<?php

/**
 * Wrapper for Google Visualisation API
 * Visualisation type: Table
 * @author Thomas Schäfer
 * @since 2008-06-29
 *
 */
class QTableGoogleGraph extends QVizualisationGoogleGraph {
	
	/**
	 * visualisation type holder
	 *
	 * @var string
	 */
	protected $vizualisationType = "Table";
	
	/**
	 * default property holder
	 *
	 * @var array
	 */
	protected $drawProperties = array("showRowNumber"=>"true");
	
	/**
	 * holder for registered visualisation methods
	 *
	 * @var array
	 */
	protected $configuration = array(
	
		"allowHtml" => array("datatype" => "bool"),
		"page" => array(
			"string" => "string,object",
			"values" => array("enable","event","disable")
			),
		"pageSize" => array("datatype" => "integer"),
		"sort" => array("datatype" => "integer"),
		"showRowNumber" => array(
			"string" => "string,object",
			"values" => array("enable","event","disable"),
		)
	);
	
	protected $methods = array(
			"draw"=>array("data","options"),
			"getSelection"=>null,
			"setSelection"=>array("selection")
	);
	
	protected $events = array(
		"select" => null,
		"page" => array("number"=>"integer"),
		"sort" => array("column"=>"integer","ascending"=>"bool")
	
	);
	
}
