<?php

/**
 * Wrapper for Google Visualisation API
 * Visualisation type: word cloud chart
 * @author Thomas Schäfer
 * @since 2008-06-29
 *
 */
class QWordcloudGoogleGraph extends QVizualisationGoogleGraph {

	/**
	 * visualisation type holder
	 *
	 * @var string
	 */
	protected $vizualisationType = "WordCloud";

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
	
	/**
	 * package setup info
	 * 
	 *
	 * @var array
	 */
	protected $packageSetup = array(
		"link" =>
			array(
				"rel"=>"stylesheet", 
				"type"=>"text/css",
				"href"=>"http://visapi-gadgets.googlecode.com/svn/trunk/wordcloud/wc.css"
			),
		"script" => array(
			 "type"=>"text/javascript",
			 "src"=>"http://visapi-gadgets.googlecode.com/svn/trunk/wordcloud/wc.js"
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
	
	/**
	 * custom append method
	 *
	 * @return self
	 */
	public function customAppend(){
		$this->putProperty("function", "var oc = document.getElementById('".$this->getProperty("id")."');");
        $this->putProperty("function", "var vt = new ".$this->vizualisationType."(oc);");
        $this->putProperty("function", "vt.draw(data, null);");
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
				$value3 = $value[2];
			}
			$this->putProperty("function", "data.setCell(".$value[0].",".$value[1].",".$value3.");");
		}
		return $this;
	}
	
	public function getReferenceLink() {
		$link = '<a href="http://visapi-gadgets.googlecode.com/svn/trunk/wordcloud/doc.html" target="_blank">Goto Google Visualization Web API Gallery</a>';
		return $link;
	}
	
}
