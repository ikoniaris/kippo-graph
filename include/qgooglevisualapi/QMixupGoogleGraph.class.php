<?php


/**
 * 
 * QMixupGoogleGraph
 * @package
 * @subpackage
 * @author Thomas Schäfer
 * @since 29.06.2008 14:51:10
 * @desc
*/
class QMixupGoogleGraph {

	/**
	 * for custom package
	 *
	 * @var bool
	 */
	public static $usePackage = true;
	
	/**
	 * class version number
	 *
	 * @var float
	 */
	private $version = 0.3;
	
	/**
	 * holder of package name
	 *
	 * @var string
	 */
	protected $package = "";
	
	/**
	 * google visualization api version
	 *
	 * @var integer
	 */
	protected $vizApi = 1;

	const vizApi = 1;
	
	/**
	 * array of chart object 
	 *
	 * @var array
	 */
	protected $members;

	/**
	 * holder object for charts data
	 *
	 * @var object of type stdClass
	 */
	protected $charts;
		
	protected $results;
	
	/**
	 * contructor
	 *
	 */
	public function __construct() {
		$this->charts = new stdClass();		
		$this->ignoreContainer = true;
	}

	/**
	 * add a member to the mixup
	 *
	 * @param object $member
	 * @return self
	 */
	public function addMember($member) {
		
		if ($member instanceof QVisualizationGoogleGraph ) {
			$this->members[] = $member;
			if($this->vizApi<$member->getVizApi()) {
				$this->vizApi = $member->getVizApi();
			}
		}
		return $this;
	}
	
	/**
	 * add members draw properties
	 *
	 * @param array $properties
	 * @return self
	 */
	public function addDrawProperties($properties) {
		foreach($this->members as $memberKey => $member) {
			$member->setId();
			if(isset($properties[strtolower($member->getContext())])) {
				$accumulatedMember = $member->setDrawProperties($properties[strtolower($member->getContext())]);
				$accumulatedMember->initDrawProperties();
				$this->members[$memberKey] = $accumulatedMember;
			} else {
				$this->members[$memberKey] = $member;
			}
		}
		return $this;
	}
	
	/**
	 * facade for adding the columns into the member object
	 *
	 * @param array $array
	 * @return self
	 */
	public function addColumns($array) {
		if(is_array($array)){
			$this->charts->columns = $array;
		}
		return $this;
	}
	
	public function noPackage() {
		self::$usePackage = false;
		return $this;
	}

	/**
	 * facade to add the data into the member object
	 *
	 * @param array $array
	 * @return self
	 */
	public function setValues($array) {
		if(is_array($array)){
			$this->charts->values = $array;
		}
		return $this;
	}
	
	/**
	 * render the mixup
	 * prepares the mixup
	 */
	public function render() {
		
		$this->results = new stdClass();
		
		$xml = '<?xml version="1.0" encoding="UTF-8"?><root></root>';
		
		$customPackages = array();
		$packages = array();
		
		$this->charts->charts = array();
				
		/**
		 * build member properties and view container
		 */
		$bMembers = count($this->members)==1 ? false : true;
		
		$bCustomPackages = false;
		$sCustomPackages = array();
		// iterate members
		foreach($this->members as $key => $member){
			// init member script element
			$scriptlet = new SimpleXMLElement($xml);
			$ref = explode('_', $member->getId()); // compute member's drop zone id
			if(count($ref)>1){
				$reference = $ref[1];
			} else {
				$reference = "ref";
			}
			$packages[] = strtolower($member->getVisualizationType()); // register member to package
			// push member's CTOP
			$this->charts->charts["data"][] = '[{scope:"'.$member->getScope().'",name:"'.$member->getVisualizationType().'",ref:"'.$reference.'",props:'.stripslashes($member->buildProperties()).'}]';
			if($member->getVisualizationType()=="Map"){
				$apiscript = $scriptlet->addChild('script');
				$apiscript->addAttribute("type", "text/javascript");
				$apiscript->addAttribute("src", QApikeyGoogleGraph::KEY);
			}
			// build member's drop zone
			$apidiv = $scriptlet->addChild('div');
			$apidiv->addAttribute('id',$member->getVisualizationType()."_".$reference); // drop zone id
			if($member->getVisualizationType()=="Map"){
				$apidiv->addAttribute('style','width:400px;height:300px'); // drop zone id
			}
			// parse additional CTOP
			switch($member->getVisualizationType()){
				case "AnnotatedTimeLine":
					$style="";
					if(isset($this->additionalProperties[strtolower($member->getVisualizationType())]["width"])) {
						$style.='width:'.$this->additionalProperties[strtolower($member->getVisualizationType())]["width"]."px;";
					}
					if(isset($this->additionalProperties[strtolower($member->getVisualizationType())]["height"])) {
						$style.='height:'.$this->additionalProperties[strtolower($member->getVisualizationType())]["height"]."px;";
					}
					$apidiv->addAttribute('style',$style);
					break;
			}
			$this->results->{$member->getVisualizationType()} = self::toString($scriptlet);
			
			// if a member is not a native google visualization chart object
			if($member->getCustomPackage()) {
				$bCustomPackages = true;
				$sCustomPackages[$member->getPackage()] = true;

				foreach($member->getPackageSetup() as $element => $values) {
					if(isset($values[0])) {
						foreach($values as $key => $attr){
							// register basic api script reference once
							if($bMembers and $element=="script" and $attr["src"]==QVisualizationGoogleGraph::APISCRIPT) {
								$bCustomPackages = true;
							}
							// build custom member script
							$scriptlet = new SimpleXMLElement($xml);
							if(isset($attr["text"])) {
								$usePackChild = $scriptlet->addChild($element, $attr["text"]); 
							} else {
								$usePackChild = $scriptlet->addChild($element);
							} 
							foreach($attr as $attrName => $attrValue){
								if($attrName!="text") {
									$usePackChild->addAttribute($attrName, $attrValue);
								}
							}
							$customPackages[] = $scriptlet;
						}
					} else {
						// same as above but flat
						$scriptlet = new SimpleXMLElement($xml);
						$usePackChild = $scriptlet->addChild($element);						
						foreach($values as $attrName => $attrValue){
							if($attrName!="text") {
								$usePackChild->addAttribute($attrName, $attrValue);
							}
						}
						$customPackages[] = $scriptlet;
					}
				}
				// if a pacakge has a load method then call it and render a new script element
				if(method_exists($member,"loadCustomPackage")){
					$scriptlet = new SimpleXMLElement($xml);
					$usePackChild = $scriptlet->addChild("script", $member->loadCustomPackage());
					$usePackChild->addAttribute("type", "text/javascript");
					$customPackages[] = $scriptlet;
				}
			}
		}
		
		// render sources
		
		$_scripts = self::getScripts();
		$_scripts = str_replace("{vizapi.table.format}","", $_scripts);
		
		$source = "\n";
		
		$source .= self::getGoogleLoad($packages, $bCustomPackages, $sCustomPackages);
		$source .= self::getChartObject($charts);		
		$source .= $_scripts; 
		
		// head scripts
		$scriptlet = new SimpleXMLElement($xml);
			
		if(empty($bCustomPackages)) {
			$apiscript = $scriptlet->addChild('script');
			$apiscript->addAttribute("type", "text/javascript");
			$apiscript->addAttribute("src", "http://www.google.com/jsapi");
		}
			
		$apisource = $scriptlet->addChild('script', $source);
		$apisource->addAttribute("type", "text/javascript");
		$this->results->script = '';
		
		// prepend custom packages
		// @TODO command for enabling variable positioning
		// like jQuery's insertBefore/insertAfter/after/before
		if($bCustomPackages) {
			foreach($customPackages as $customPackage) {
				$this->results->script .= self::toString($customPackage);
			}
		}		
		$this->results->script .= self::toString($scriptlet);		
		return $this->results;
	}

	public static function getGoogleLoad($packages, $sCustomPackages, $bCustomPackages) {
		$source = '';
		if($bCustomPackages) {
			$globalPackages = array();
			if(self::$usePackage==true) {
				$source .= '	google.load("visualization", "'.self::vizApi.'", {packages:["';
				foreach($packages as $package) {
					if(empty($sCustomPackages[$package])) {
						$globalPackages[] = $package;
					}
				}
				$source .= implode('","', $globalPackages);
				$source .= '"]});';
			} else {
				$source .= '	google.load("visualization", "'.self::vizApi.'");';
			}
		} else {
			if(self::$usePackage==true) {
				$source .= '	google.load("visualization", "'.self::vizApi.'", {packages:["'.implode('","', $packages).'"]});';
			} else {
				$source .= '	google.load("visualization", "'.self::vizApi.'");';
			}
		}
		return $source;
	}
	
	/**
	 * convert SimpleXMLElement to string, replace root nodes
	 *
	 * @param SimpleXMLElement $scriptlet
	 * @return string
	 */
	public static function toString($scriptlet){
		
		$dom_api_node = dom_import_simplexml($scriptlet);
		$dom = new DomDocument();
		$dom_api_node = $dom->importNode($dom_api_node, true);
		$dom->appendChild($dom_api_node);

		$output = str_replace("<root>","", $dom->saveHTML());
		$output = str_replace("</root>","", $output);
		$output = str_replace("</script>","</script>\n", $output);
		return $output;
	}
	
	/**
	 * main javascript chart caller function
	 *
	 * @return string
	 */
	public static function getScripts(){
		$string = '
	google.setOnLoadCallback(drawMix);
	function drawMix(){var chart=chartObject.charts.data;for(var i=0;i<chart.length;i++){for(var j in chart[i]){drawChartType(chart[i][j].scope, chart[i][j].name, chart[i][j].ref, chart[i][j].props, chartObject.column.data, chartObject.value.data);}};};
	function drawChartType(scope,type,ref,props,col,data){
    	var cdat=new google.visualization.DataTable();
    	for(var i=0;i<col.length;i++){cdat.addColumn(col[i][0],col[i][1]);}
    	switch(type){
    		case "AnnotatedTimeLine":
    		case "BioHeatMap":
    			cdat.addRows(data.length);break;default:cdat.addRows(data.length/col.length);
    		break;
		}
    	for(var i=0;i<data.length;i++){
    		switch(type){
    			case "Map":
    			case "OrgChart":
    			case "BioHeatMap":
    			case "Table":if(data[0].length==3){cdat.setCell(data[i][0],data[i][1],data[i][2]);}else{cdat.setCell(data[i][0],data[i][1]);};break;
    			default:if(data[0].length==3){cdat.setValue(data[i][0],data[i][1],data[i][2]);}else{cdat.setValue(data[i][0],data[i][1]);};break;
    		}
    	}
    	var chart = eval(\'new \'+scope+\'.\'+type+\'(document.getElementById("\'+type+\'_\'+ref+\'"))\');
    	chart.draw(cdat, props);
	}
';
		return $string;		
	}

	/**
	 * escape chart values by type
	 *
	 * @param mixed $value
	 * @return string
	 */
	public static function escapeValue($value) {
		$resultString = null;
		if(substr($value,0,3)=='new'){
			$resultString = $value;	
		} elseif(is_null($value)){
			$resultString = "null";
		} elseif(is_bool($value)){
			$resultString = (empty($value)?"false":"true");
		} elseif(is_string($value)){
			$resultString = "'".$value."'";
		} else {
			$resultString = $value;
		}
		return $resultString;
	}
	
	/**
	 * transform chart member properties to json array
	 *
	 * @param stdClass $charts
	 * @return string
	 */
	public static function getChartObjectData(stdClass $charts) {		
		return 'data:['.implode(",", $charts->charts["data"])."]";
	}
	
	/**
	 * tranform chart values to json array
	 *
	 * @param stdClass $charts
	 * @return string
	 */
	public static function getChartObjectValues(stdClass $charts) {
		$values = array();
		foreach($charts->values as $value) {
			$vals = array();
			switch(count($value)) {
				case 3:
					$vals[] = self::escapeValue($value[0]);
					$vals[] = self::escapeValue($value[1]);
					$vals[] = self::escapeValue($value[2]);
					break;
				default:
					$vals[] = self::escapeValue($value[0]);
					$vals[] = self::escapeValue($value[1]);
					break;
			}
			$data = '['. implode(",", $vals) .']';
			$values[] = $data;
		}
		return implode(',', $values);
			
		
	}
	
	/**
	 * transform column data to json array
	 *
	 * @param stdClass $charts
	 * @return string
	 */
	public static function getChartObjectColumns(stdClass $charts) {
		$columns = array();
		foreach($charts->columns as $column) {
			$cols = array();
			foreach($column as $name) {
				$cols[] = "'".$name."'";
			}
			$columns[] = '['. implode(",", $cols) .']';
		}
		return implode(',', $columns);
	}
	
	/**
	 * transform chart data to json object
	 *
	 * @return string
	 */
	public function getChartObject() {
		$string = '
	var chartObject={
		charts:{'.self::getChartObjectData($this->charts).'},
		value:{data:['.self::getChartObjectValues($this->charts).']},
		column:{data:['.self::getChartObjectColumns($this->charts).']}
	};
';
		return $string;
	}

}
