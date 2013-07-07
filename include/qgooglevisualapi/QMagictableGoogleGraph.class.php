<?php

/**
 * Wrapper for Google Visualisation API
 * Visualisation type: area chart
 * @author Thomas Schäfer
 * @since 2008-06-29
 *
 */
class QMagictableGoogleGraph extends QVisualizationGoogleGraph {

	/**
	 * visualisation type holder
	 *
	 * @var string
	 */
	protected $vizualisationType = "MagicTable";

	/**
	 * scope
	 *
	 * @var string
	 */
	protected $vizualisationScope = "greg.ross.visualisation";
	
	/**
	 * default property holder
	 *
	 * @var array
	 */
	protected $drawProperties = array(
		"tableTitle"=>"bar-fill",
		"enableFisheye"=>true,
		"enableBarFill"=>true,
		"defaultRowHeight"=>25,
		"defaultColumnWidth"=>25,
		"rowHeaderCount"=>0,
		"columnHeaderCount"=>0,
		"tablePositionX"=>0,
		"tablePositionY"=>0,
		"tableHeight"=>403,
		"tableWidth"=>315
	);

	/**
	 * for alternative package implementation
	 *
	 * @var bool
	 */
	protected $customPackage = true;

	/**
	 * holder for default properties
	 *
	 * @var array
	 */
	protected $configuration = array(
			"tableTitle" => array("datatype" => "string", "description"=>"The title that appears above the table."), 
			"enableFisheye" => array("datatype" => "bool", "description"=>"This switches the fisheye function on or off."), 
			"enableBarFill" => array("datatype" => "bool", "description"=>"This switches the bar-fill function on or off. "), 
			"defaultRowHeight" => array("datatype" => "integer", "description"=>"The default height of rows when the fisheye function is switched off."), 
			"defaultColumnWidth" => array("datatype" => "integer", "description"=>"The default width of columns when the fisheye function is switched off."), 
			"rowHeaderCount" => array("datatype" => "integer", "description"=>"This specifies the number of vertical scales, i.e. the row headers. "), 
			"columnHeaderCount" => array("datatype" => "integer", "description"=>"This specifies the number of horizontal scales, i.e. the column headers. "), 
			"tablePositionX" => array("datatype" => "integer", "description"=>"The x-position of the table, relative to the containing element. "), 
			"tablePositionY" => array("datatype" => "integer", "description"=>"The y-position of the table, relative to the containing element. "), 
			"tableHeight" => array("datatype" => "integer", "description"=>"The height of the table."), 
			"tableWidth" => array("datatype" => "integer", "description"=>"The width of the table."), 
	);
	
	
	protected $packageSetup = array(
		"link" =>
			array(
				"rel"=>"stylesheet", 
				"type"=>"text/css",
				"href"=>"http://magic-table.googlecode.com/svn/trunk/magic-table/google_visualisation/example.css"
		),
		"script" => array(
			array(
		 		"type"=>"text/javascript",
		 		"src"=>"http://www.google.com/jsapi"
		 	),
			array(
		 		"type"=>"text/javascript",
		 		"text"=>'
		google.load("visualization", "1");
		 		'
		 	),
			array(
		 		"type"=>"text/javascript",
		 		"src"=>"http://magic-table.googlecode.com/svn/trunk/magic-table/javascript/magic_table.js"
		 	)
		)
	);

	/**
	 * set for package setup script and css
	 * @param array $array
	 * @return self
	 */
	public function setPackageSetup($array) {
		$this->packageSetup = $array;
		return $this;
	}
	
	public function loadCustomPackage(){
		return '';
	}
	
	/**
	 * custom append method
	 *
	 * @return self
	 */
	public function customAppend(){
		$this->putProperty("function", "var oc = document.getElementById('".$this->getProperty("id")."');");
        $this->putProperty("function", "var vt = new ".$this->vizualisationScope.".".$this->vizualisationType."(oc);");
        $this->putProperty("function", "vt.draw(data, ".$this->buildProperties().");");
	}
	
	public function setValues($values=array()){
		
		$this->putProperty("function","");
		$this->putProperty("function", "data.addRows(".(count($values)/$this->columns).");");
		
		foreach($values as $value){
			
			if(is_string($value[2])) {
				$value3 = "'".$value[2]."'" ;
			} elseif(is_bool($value[2])) {
				$value3 = $value[2]?"true":"false"; 
			} else {
				$value3 = (is_null($value[2])?'null':$value[2]);
			}
			$this->putProperty("function", "data.setCell(".$value[0].",".$value[1].",".$value3.");");
		}
		return $this;
	}
	
	public function getReferenceLink() {
		$link = '<a href="http://magic-table.googlecode.com/svn/trunk/magic-table/google_visualisation/example_1.html" target="_blank">Goto MagicTable Project Home</a>';
		return $link;
	}
	
	
}
