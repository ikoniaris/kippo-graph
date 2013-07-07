<?php

/**
 * Wrapper for Google Visualisation API
 * Visualisation Class
 * 
 * wraps all Google Visualisation Types dynamically and 
 * in a generic way
 * 
 * holds common functions for API to generate Javascript source code
 *
 * @author Thomas Sch&#65533;fer
 * @since 2008-06-29
 * 
 */
class QVizualisationGoogleGraph extends QGoogleGraph {
	
	/**
	 * version
	 *
	 * @var integer
	 */
	private $vizApi = 1;
	
	protected $vizualisationScope = "google.visualization";
	
	/**
	 * basic reference link
	 *
	 * @var string
	 */
	private $vizReference = "http://code.google.com/apis/visualization/documentation/gallery/";
	
	/**
	 * holder of context node => name of visualisation type
	 *
	 * @var string
	 */
	protected $context;
	
	
	/**
	 * holder of xml root element
	 *
	 * @var string
	 */
	protected $rootNode = 'root';
	
	/**
	 * holder of visualisation package name
	 *
	 * @var unknown_type
	 */
	private $package = "";
	
	/**
	 * holder of data columns
	 *
	 * @var array
	 */
	protected $columns;
	
	/**
	 * offset of context name in context array
	 *
	 * @var integer
	 */
	protected $contextNode = 1;
	
	/**
	 * flag for prevent output of div container
	 *
	 * @var unknown_type
	 */
	
	protected $ignoreContainer = false;
	
	/**
	 * package usage 
	 *
	 * @var bool
	 */
	private $usePackage = true;
	
	/**
	 * Holder for additional scripts and css
	 * while render process the add. elements
	 * will be transfered from a classes package
	 * property to the register of packages
	 *
	 * @var array
	 */
	protected $registerPackage = array();

	protected $methodName;
	
	/**
	 * constructor:
	 * - initializes xml objects
	 * - adds package info
	 * - binds methods to visualisation type
	 * - sets default property values
	 * @return void
	 */
	public function __construct(){
		
		
		
		// identifies context part of class name
		$this->context = QTool::make()
			->toUnderscore(get_class($this))
			->toArray()
			->get();
			
		// package = context
		$this->package = $this->getContext();
		
		// xml root node
		$xml = '<?xml version="1.0" encoding="UTF-8"?><'.$this->rootNode.'></'.$this->rootNode.'>';
				
		// set api section
		$this->object["api"] = new SimpleXMLElement($xml);
		$api = $this->object["api"];
		$apiscript = $api->addChild('script');
		$apiscript->addAttribute("type", "text/javascript");
		$apiscript->addAttribute("src", "http://www.google.com/jsapi");
		
		// set base section 
		$this->object["base"] = new SimpleXMLElement($xml);
		$object = $this->object["base"];
		$script = $object->addChild("script");
		$script->addAttribute("type", "text/javascript");
		
		// do ignore if container must not be rendered
		if(empty($this->ignoreContainer)){
			$this->object["div"] = new SimpleXMLElement($xml);
			$object = $this->object["div"];
			$object->addChild("div");
			
			// add attributes for local requirements
			switch($this->getContext()){
				case "map":
				case "annotatedtimeline":
					$object->div->addAttribute("style", 'width:'.$this->drawProperties["width"].";height:".$this->drawProperties["height"].";");
					break;
			}
		}
		$this->initDrawProperties();
		
	}
	
	public function setMethod($name){
		$this->methodName = $name;
		return $this;
	}

	/**
	 * if set then the div container will not be renderer
	 *
	 * @return self
	 */
	public function ignoreContainer(){
		
		$this->ignoreContainer = true;
		return $this;
	}

	/**
	 * no package usage 
	 *
	 * @return self
	 */
	public function noPackage(){
		
		$this->usePackage = false;
		return $this;
	}
	
	/**
	 * use package
	 *
	 * @return self
	 */
	public function usePackage(){
		
		$this->usePackage = true;
		return $this;
	}

	/**
	 * get package name
	 *
	 * @return string
	 */
	public function getPackage(){
		
		return $this->package;	
	}
	/**
	 * get state of package usage
	 *
	 * @return boolean
	 */
	public function getUsePackage(){
		
		return $this->usePackage;
	}
	
	public function getScope() {
		return $this->vizualisationScope;
	}
	
	/**
	 * return visualization api version
	 *
	 * @return integer
	 */
	public function getVizApi(){
		
		return $this->vizApi; 
	}
	
	public function getVisualizationType(){
		
		return $this->vizualisationType; 
	}
	
	/**
	 * sets default properties into working array
	 *
	 * @return void
	 */
	public function initDrawProperties(){
		
		foreach($this->drawProperties as $key => $value){
			$this->putProperty("drawproperties", array(	$key => $value));
		}
		
	}
		
	/**
	 * context/package getter
	 *
	 * @return array
	 */
	public function getContexts(){
		
		return $this->context;
	}

	/**
	 * returns the class context name (2nd part of splitted class name) 
	 *
	 * @return string
	 */
	public function getContext(){
		
		return $this->context[$this->contextNode];	
	}
	
	
	/**
	 * adds package lines to working array
	 * 
	 * @return void
	 */
	public function addPackage($localPackage=true){
		
		
		
		$this->putProperty("package","");
		
		if($localPackage) { // usually used by mash ups
			if($this->getUsePackage()==true && !isset($this->members)) {
				$this->putProperty("package", 'google.load("visualization", "'.$this->getVizApi().'", {packages:["'.$this->package.'"]});');
			} elseif($this->getUsePackage()==true && isset($this->members)) {
				$packages = array();
				foreach($this->members as $member) {
					$packages[] = $member->getPackage();
				}
				$packages = implode('","', $packages);
				$this->putProperty("package", 'google.load("visualization", "'.$this->getVizApi().'", {packages:["'.$packages.'"]});');
			} else {
				$this->putProperty("package", 'google.load("visualization", "'.$this->getVizApi().'");');
			}
		}
		if(isset($this->methodName)) {
			$this->putProperty("package", 'google.setOnLoadCallback(draw'.ucfirst($this->methodName).');');
		} else {
			$this->putProperty("package", 'google.setOnLoadCallback(draw'.ucfirst($this->getPackage()).');');
		}
	}
	
	/**
	 * puts and names function outlines for working array
	 *
	 * @return void
	 */
	public function addFunction(){
		
		$this->putProperty("openfunction","");
		if(isset($this->methodName)) {
			$this->putProperty("openfunction", 'function draw'.ucfirst($this->methodName).'() {');
		} else {
			$this->putProperty("openfunction", 'function draw'.ucfirst($this->getPackage()).'() {');
		}
		$this->putProperty("closefunction", '}');
		$this->putProperty("closefunction", "\n");
	}
	
	/**
	 * adds columns source to working array
	 * counts columns
	 *
	 * @param array $columns
	 * @return self
	 */
	public function addColumns($columns=array()){
		
		$this->columns = count($columns);
		$this->putProperty("function", "var data = new google.visualization.DataTable();");
		foreach($columns as $column){
			switch($this->getContext()){
				case "intensitymap":
					$this->putProperty("function", "data.addColumn('".$column[0]."','".$column[1]."','".$column[2]."');");					
					break;
				default:
					$this->putProperty("function", "data.addColumn('".$column[0]."','".$column[1]."');");
					break;
			}
		}
		return $this;
	}

	/**
	 * internal: count rows on different contexts
	 * @param array $values
	 * 
	 * @return void
	 */
	private function setAddRows($values){
		switch($this->getContext()) {
			case "annotatedtimeline":
				$i=0;
				foreach($values as $value){
					if(substr($value[2],0,3)=='new'){
						$i++;
					}
				}
				$this->putProperty("function", "data.addRows(".$i.");");
			break;
		}

		switch($this->getContext()){
			case "orgchart":
			case "piechart":
				$this->putProperty("function", "data.addRows(".count($values).");");
				break;
			case "linechart":
			case "barchart":
			case "columnchart":
			case "areachart":
			case "table":
			case "scatterchart":
			case "gauge":
			case "map":
				$this->putProperty("function", "data.addRows(".(count($values)/$this->columns).");");
				break;
			case "intensitymap":
			case "motionchart":
				$this->putProperty("function", "data.addRows(".ceil(count($values)/$this->columns).");");
				break;
		}	
	}
	
	/**
	 * set values into working array
	 *
	 * @param array $values
	 * @return self
	 */
	public function setValues($values=array()){
		
		
		$this->putProperty("function","");
		
		$this->setAddRows($values); // add the row counter
		
		foreach($values as $value){
			
			if(is_string($value[2])) {
				$value3 = "'".$value[2]."'" ;
			} elseif(is_bool($value[2])) {
				$value3 = $value[2]?"true":"false"; 
			} else {
				$value3 = (is_null($value[2])?'null':$value[2]);
			}
			
			switch($this->getContext()){
				case "annotatedtimeline":
				case "motionchart":
					if(substr($value[0],0,3)=='new'){
						$value0 = $value[0];	
					} elseif(is_string($value[0])||is_bool($value[0])){
						$value0 = "'".$value[0]."'";
					} else {
						$value0 = $value[0];
					}
					if(substr($value[1],0,3)=='new'){
						$value1 = $value[1];	
					} elseif(is_string($value[1])||is_bool($value[1])){
						$value1 = "'".$value[1]."'";
					} else {
						$value1 = $value[1];
					}
					if(substr($value[2],0,3)=='new'){
						$value2 = $value[2];	
						$i++;
					} elseif(is_string($value[2])||is_bool($value[2])){
						$value2 = "'".$value[2]."'";
					} else {
						$value2 = (is_null($value[2])?'null':$value[2]);
					}
								
					$this->putProperty("function", "data.setValue(".$value0.",".$value1.",".$value2.");");
					break;
				case "map":
				case "table":
				case "orgchart":
					$this->putProperty("function", "data.setCell(".$value[0].",".$value[1].",".$value3.");");
					break;
				default:
					$this->putProperty("function", "data.setValue(".$value[0].",".$value[1].",".$value3.");");
					break;
			}
		}
		return $this;
	}


	/**
	 * set selection script function
	 *
	 * @param string $type
	 * @param string $option
	 * @param string $startObject
	 * @param string $endObject
	 * @return string
	 */
	public function getSelection($type, $option, $startObject, $endObject) {
		$so = (strpos($startObject,'Control')?$startObject:strtolower($type).$startObject);
		$eo = (strpos($endObject,'Control')?$endObject:strtolower($type).$endObject);
		return $so.',"'.$option.'",function(){'.$eo.'.setSelection('.$so.'.getSelection());}';
	}
	
	/**
	 * add listener script line
	 *
	 * @param string $object
	 * @param string $listener
	 * @return self
	 */
	public function addListener($object, $listener="filterControls") {
		$string = array();
		$string[] = 'google.visualization.events.addListener(';
		$string[] = $object;
		$string[] = ');';		
		$this->putProperty($listener,implode($string),"listen");
		return $this;
	}
	
	/**
	 * build javascript data property object 
	 * @return void
	 */
	private function buildPropertyObject() {
	
		$array = array();
	
		$string = "chart.draw(data, {";
		switch($this->getContext()){
			case "annotatedtimeline":
				$drawproperties = array_reverse($this->getProperty("drawproperties"));
				$checkArray = array();
				foreach($drawproperties as $row){
					foreach($row as $attribute => $value){
						$keys = array_keys($this->configuration);
						
						if(in_array($attribute, $keys) && !in_array($attribute, $checkArray)){
							$check = $this->checkProperties($attribute, $value);
							switch($check){
								case "array":
								case "literal":
									$array[] = $attribute .':'. trim($value);
									break;
								case "bool":
								case "integer":
								case "float":
									$array[] = $attribute . ":".$value;
									break;
								default:
									switch($attribute) {
										// not checked for the moment for correctness
										case "zoomEndTime":
										case "zoomStartTime":
											$array[] = $attribute . ":".$value;
											break;
										default:
											$array[] = $attribute . ":'".$value."'";
											break;
									}
									break;
							}
							$checkArray[] = $attribute;
						}
					}
				}
				$string .= implode(", ", $array);
				$string .= "});";
				$this->putProperty("function", $string);
				break;
			default:
				$drawproperties = array_reverse($this->getProperty("drawproperties"));
				$checkArray = array();
				foreach($drawproperties as $row){
					foreach($row as $attribute => $value){
						$keys = array_keys($this->configuration);
						if(in_array($attribute, $keys) && !in_array($attribute, $checkArray)){
							$check = $this->checkProperties($attribute, $value);
							switch($check){
								case "literal":
									$array[] =$attribute .':'. trim($value);;
									break;
								case "bool":
								case "integer":
								case "float":
									$array[] = $attribute . ":".$value;
									break;
								default:
									switch($attribute) {
										// not checked for the moment for correctness
										case "zoomEndTime":
										case "zoomStartTime":
											$array[] = $attribute . ":".$value;
											break;
										default:
											$array[] = $attribute . ":'".$value."'";
											break;
									}
									break;
							}
						}
						$checkArray[] = $attribute;
					}
				}
				$string .= implode(", ", $array);
				$string .= "});";
				$this->putProperty("function", $string);
				break;
		}
	}		

	public function setDrawProperties($array=array()) {
		$this->drawProperties = $array;
		return $this;	
	}
	
	public function buildProperties() {
		$string = "{";
		$drawproperties = array_reverse($this->getProperty("drawproperties"));
		$checkArray = array();
		foreach($drawproperties as $row){
			foreach($row as $attribute => $value){
				$keys = array_keys($this->configuration);
				if(in_array($attribute, $keys) && !in_array($attribute, $checkArray)){
					$check = $this->checkProperties($attribute, $value);
					switch($check){
						case "literal":
							$array[] =$attribute .':'. trim($value);;
							break;
						case "bool":
						case "integer":
						case "float":
							$array[] = $attribute . ":".$value;
							break;
						default:
							switch($attribute) {
								// not checked for the moment for correctness
								case "zoomEndTime":
								case "zoomStartTime":
									$array[] = $attribute . ":".$value;
									break;
								default:
									$array[] = $attribute . ":'".$value."'";
									break;
							}
							break;
					}
				}
				$checkArray[] = $attribute;
			}
		}
		$string .= implode(", ", $array);
		$string .= "}";
		return $string;
	}
	
	/**
	 * prepares source to be rendered
	 * @return void
	 */
	public function prepare($usePackage=true){
		
		
		$this->addPackage($usePackage);
		$this->addFunction();
		
		if(isset($this->packageSetup)){
			$xml = '<?xml version="1.0" encoding="UTF-8"?><'.$this->rootNode.'></'.$this->rootNode.'>';
			
			foreach($this->packageSetup as $element => $attributes){
				if(is_array($attributes) and isset($attributes[0])) {
					$this->object["addon"][$element] = new SimpleXMLElement($xml);
					$this->registerPackage = $element; // register package by name
					foreach($attributes as $elm => $attr) {
						// new addon 
						$usePack = $this->object["addon"][$element];
						if(isset($attr["text"])) {
							$usePackChild = $usePack->addChild($element, $attr["text"]); 
						} else {
							$usePackChild = $usePack->addChild($element); 
						}
						foreach($attr as $attrName => $attrValue){
							if($attrName!="text") {
								$usePackChild->addAttribute($attrName, $attrValue);
							}
						}
							
					}
					if(!empty($customPackage)) {
						$this->object["api"]["script"] = null;
					}
				} else {
					$this->registerPackage = $element; // register package by name
					// new addon 
					$this->object["addon"][$element] = new SimpleXMLElement($xml);
					$usePack = $this->object["addon"][$element];
					$usePackChild = $usePack->addChild($element); // attributes to the element
					foreach($attributes as $attrName => $attrValue){
						$usePackChild->addAttribute($attrName, $attrValue);
					}
				}
			}		
			
			if(method_exists($this,"loadCustomPackage")) {
				$this->object["customPackage"] = new SimpleXMLElement($xml);
				$customPackage = $this->object["customPackage"];
				$customScript = $customPackage->addChild("script", $this->loadCustomPackage());				
				$customScript->addAttribute("type", "text/javascript");
			}
		}
		
		// build container id
		if(!$this->hasProperty("id") and empty($this->ignoreContainer)){
			$this->setId();
		} else {
			$this->setId($this->getContext());
		}
		
		if($this->getUsePackage()){
			$this->putProperty("function", "");
			$this->putProperty("function", "var chart = new google.visualization.".$this->vizualisationType."(document.getElementById('".$this->getProperty("id")."'));");
			$this->buildPropertyObject();	
		}
		
		$output = implode("\n", $this->getProperty("package"));
		$output .= "\n";
		$output .= implode("\n", $this->getProperty("openfunction"));
		$output .= "\n";
		
		// append custom javascript code
		if(in_array("customAppend", get_class_methods($this))){
			$this->customAppend();
		}		
		$output .= implode("\n", $this->getProperty("function"));
		
		$output .= "\n";
		$output .= implode("\n", $this->getProperty("closefunction"));
		
		unset($this->properties);
		
		$object = $this->object["base"];
		$object->script = $output;

		return $this;
	}
	
	/**
	 * rendering
	 * - prepares js source
	 * - builds xml
	 * - converts xml to xhtml
	 * 
	 * @param string $name
	 * @param boolean $return
	 * @return mixed
	 */
	public function render($name = false, $return = false){
		
		$this->prepare();
		
		$dom_api_node = dom_import_simplexml($this->object["api"]);
		
		if(empty($this->ignoreContainer)){
			// build chart type browser container
			$dom_div_node = dom_import_simplexml($this->object["div"]);
		}

		// use base or alt. container as root
		$dom_node 	= !empty($name)
					? dom_import_simplexml($this->object[$name])
					: dom_import_simplexml($this->object["base"]);
		
		$dom = new DomDocument();
		
		// append add-ons
		if(empty($this->usePackage) and isset($this->packageSetup)){
			$addons = $this->object["addon"];
			if(is_array($addons)) {
				foreach($addons as $addon){
					$dom_addon = $dom->importNode(dom_import_simplexml($addon), true);
					$dom->appendChild($dom_addon);
				}
			}
			if(method_exists($this,"loadCustomPackage") and isset($this->object["customPackage"])) {
				$dom_addon = $dom->importNode(dom_import_simplexml($this->object["customPackage"]), true);
				$dom->appendChild($dom_addon);
			}
		} elseif(isset($this->packageSetup)){
			$addons = $this->object["addon"];
			if(is_array($addons)) {
				foreach($addons as $addon){
					$dom_addon = $dom->importNode(dom_import_simplexml($addon), true);
					$dom->appendChild($dom_addon);
				}
			}
			if(method_exists($this,"loadCustomPackage") and isset($this->object["customPackage"])) {
				$dom_addon = $dom->importNode(dom_import_simplexml($this->object["customPackage"]), true);
				$dom->appendChild($dom_addon);
			}
		}

		// append presentation container
		if(empty($this->ignoreContainer)){
			$dom_div_node = $dom->importNode($dom_div_node, true);
			$dom->appendChild($dom_div_node);
		}
		
		// append api source
		$dom_api_node = $dom->importNode($dom_api_node, true);
		$dom->appendChild($dom_api_node);
		
		// append defaults
		$dom_node = $dom->importNode($dom_node, true);
		$dom->appendChild($dom_node);
		
		
		// strip root node tags for they are not used
		$output = str_replace("<".$this->rootNode.">","", $dom->saveHTML());
		$output = str_replace("</".$this->rootNode.">","", $output);
		
		if($return) {
			return $output;
		} else {
			echo $output;
		}
		return false;
	}
				
		
	public function getReferenceLink() {
		$link = '<a href="'.$this->vizReference;
		$link .= strtolower($this->getContext());
		$link .= '.html" target="_blank">Goto Google Visualization Web API Gallery</a>';
		return $link;
	}
	
}

