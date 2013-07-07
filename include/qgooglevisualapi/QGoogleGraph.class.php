<?php

/**
 * TODO
 *
 */
interface QIGoogleGraph{
	
	/**
	 * interface design for render method
	 *
	 */
	public function render();
	
	/**
	 * interface design for columns setter
	 *
	 * @param array $columns
	 */
	public function setColumns($columns = array());

	/**
	 * interface design for values setter
	 *
	 * @param array $values
	 */
	public function setValues($values = array());
	
}

/**
 * Wrapper for Google Visualisation API
 * Visualisation Class
 *
 * holds generic settings for all google apis
 * @author Thomas Schäfer
 * @since 2008-06-29
 *
 */
abstract class QGoogleGraph implements QIGoogleGraph {

	/**
	 * curly opening brace
	 */
	const OC = '{';
	/**
	 * curly closing brace
	 */
	const CC = '}';
	/**
	 * line break
	 */
	const LB = "\n";

	/**
	 * offset for context part of class's name
	 *
	 * @var integer
	 */
	private $contextNode = 1;

	/**
	 * context holder
	 *
	 * @var string
	 */
	protected $context;

	/**
	 * holder of id
	 *
	 * @var string
	 */
	protected $id;
	
	/**
	 * working properties holder
	 *
	 * @var array
	 */
	protected $properties = array();

	/**
	 * holder of xml objects
	 *
	 * @var SimpleXMLElement
	 */
	protected $object;

	/**
	 * constructor
	 *
	 */
	public function __construct(){
		
	}

	/**
	 * setter for visualisation method type function
	 *
	 * @param string $functionname
	 * @return self
	 */
	public function setFunctionName($functionname=null){
		
		$this->setProperty("common", "function", ($functionname?$functionname:strtolower($this->vizualisationType)));
		return $this;
	}

	/**
	 * setter for properties
	 *
	 * @param  array $array
	 * @return self
	 */
	public function setDrawProperties($array=array()){
		
		$this->setProperty("drawproperties", $array);
		return $this;
	}
	
	/**
	 * return object
	 *
	 * @return array
	 */
	public function getObject() {
		
		return $this->object;
	}

	/**
	 * adds properties into working object
	 *
	 * @param unknown_type $array
	 * @return unknown
	 */
	public function addDrawProperties($array=array()){
		// last set property overwrites default
		
		$array = array_merge($this->drawProperties, $array);
		foreach($array as $key => $value){
			$this->putProperty("drawproperties", array(	$key => $value));
		}
		return $this;
	}

	/**
	 * checks attributes on existance and values on datatype
	 *
	 * @param string $propAttr
	 * @param mixed $propValue
	 * @return mixed
	 */
	protected function checkProperties($propAttr, $propValue){
		
		if(strpos($this->configuration[$propAttr]["datatype"],",")){
			$checkProps = explode(",", $this->configuration[$propAttr]["datatype"]);
		} else {
			$checkProps = false;
		}

		if($checkProps) {
			foreach($checkProps as $checkProp){
				$check 	= function_exists("is_" . $checkProp)
					? call_user_func("is_" . $checkProp,$propValue)
					: false;				
				if(empty($check) && $checkProp=="object"){
					$check = $this->isLiteralObject($propValue);
					if($check){
						return "literal";
					}
				}
				if($check 
					and array_key_exists($propAttr, $this->configuration) 
					and isset($this->configuration[$propAttr]["values"])
					and !is_array($this->configuration[$propAttr]["values"])
					and in_array($propValue, array($this->configuration[$propAttr]["values"]))) {
					return $this->configuration[$propAttr]["values"];
				} elseif($check 
					and array_key_exists($propAttr, $this->configuration) 
					and isset($this->configuration[$propAttr]["values"])
					and is_array($this->configuration[$propAttr]["values"]) 
					and in_array($propValue, $this->configuration[$propAttr]["values"])) {
					return $this->configuration[$propAttr]["values"];
				}
			}
			return false;
				
		} else {
			
			$check 	= function_exists("is_" . $this->configuration[$propAttr]["datatype"])
					? call_user_func("is_" . $this->configuration[$propAttr]["datatype"], $propValue)
					: false;
			if($this->configuration[$propAttr]["datatype"]=='bool') {
				return $this->configuration[$propAttr]["datatype"];
			}
					
			if(empty($check) && $this->configuration[$propAttr]["datatype"]=="array"){
				$check = $this->isLiteralArray($propValue);
				if($check){
					return "literal";
				}
			}
			if($check 
				and array_key_exists($propAttr, $this->configuration) 
				and isset($this->configuration[$propAttr]["values"])
				and is_array($this->configuration[$propAttr]["values"]) 
				and in_array($propValue, $this->configuration[$propAttr]["values"])) 
			{
				return $this->configuration[$propAttr]["datatype"];
			} elseif($check) {
				return $this->configuration[$propAttr]["datatype"];
			} else {
				return false;
			}
		}
	}

	/**
	 * check if value is a javascript literal object
	 *
	 * @param string $propValue
	 * @return bool
	 */
	protected function isLiteralObject($propValue){
		
		$c = substr(trim($propValue),0,1)=="{"?1:0;
		$c += substr(trim($propValue),-1,1)=="}"?1:0;
		$c += substr_count(trim($propValue),':')>0?1:0;
		if($c==3){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * check if values is a javascript array
	 *
	 * @param string $propValue
	 * @return bool
	 */
	protected function isLiteralArray($propValue){
		
		$c = substr(trim($propValue),0,1)=="["?1:0;
		$c += substr(trim($propValue),-1,1)=="]"?1:0;
		$c += substr_count(trim($propValue),',')>0?1:0;
		if($c==3){
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Sets an id attribute for presentation container.
	 * If not set, an randomly created id will be set
	 * while preparation.
	 * If the self::ignoreContainer method is set then
	 * the context name will be set only.
	 *
	 * @param string $id
	 */
	public function setId($id=null){
		
		$id = (string) $id;
		$newId = (strlen($id)?$id:strtolower($this->getContext()) . '_'.substr(md5(microtime()),0,4));
		if(empty($this->ignoreContainer)){
			$this->setProperty("id", $newId);
			$this->object["div"]->div->addAttribute("id",$this->getProperty("id"));
		} else {
			$this->setProperty("id", $newId);
		}
		$this->id = $newId;
	}

	/**
	 * get id
	 *
	 * @return string
	 */
	public function getId() {
		return $this->id;	
	}
	
	/**
	 * getter of properties
	 *
	 * @param string $offset
	 * @param string $name
	 * @return mixed
	 */
	public function getProperty($offset, $name=null){
		
		if($name) {
			return $this->properties[$offset][$name];
		} else {
			return $this->properties[$offset];
		}
	}


	/**
	 * set property
	 *
	 * @param string $offset
	 * @param mixed $value
	 * @param string $type
	 */
	public function setProperty($offset, $value, $type=null){
		
		if($type){
			$this->properties[$offset][$type] = $value;
		} else {
			$this->properties[$offset] = $value;
		}
	}

	/**
	 * check property
	 *
	 * @param string $offset
	 * @return bool
	 */
	public function hasProperty($offset){
		
		if(empty($this->properties[$offset])){
			return false;
		} else {
			return true;
		}
	}

	/**
	 * attach value to property (concatenate)
	 *
	 * @param string $offset
	 * @param mixed $value
	 * @param string $type
	 */
	public function addProperty($offset, $value, $type = null){
		
		if($type){
			$this->properties[$offset][$type] .= $value;
		} else {
			$this->properties[$offset] .= $value;
		}
	}

	/**
	 * put property as new array entry
	 *
	 * @param string $offset
	 * @param mixed $value
	 * @param string $type
	 */
	public function putProperty($offset, $value, $type = null){
		
		if($type){
			$this->properties[$offset][$type][] = $value;
		} else {
			$this->properties[$offset][] = $value;
		}
	}
	
	public function render(){}

	public function setColumns($columns = array()){}

	public function setValues($values = array()){}


}

