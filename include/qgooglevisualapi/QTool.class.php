<?php
include_once("QInflector.class.php");

/**
 * Toolkit
 * holds common constants and functions
 */
class QTool {

	const eq = "=";
	const plus = "+";
	const minus = "+";
	const hash = "#";
	const colon = ",";
	const dot = ".";
	const semicolon = ";";
	const pipe = "|";
	const ddot = ":";
	const ddot2 = "::";
	const uscore = "_";
	const blank = " ";
	const space = " ";
	const tab = "\t";
	const string = "";
	const amp = "&";
	const lb = "\n";
	const ob = "(";
	const cb = ")";
	const hc = '"';
	const ap = "'";
	
	const openbrace = "[";
	const closebrace = "]";

	private static $separator = null;
	private static $instance;
	private static $mode = false;
	private static $working;
	
	/**
	 * returns and/or creates instance
	 *
	 * @return object
	 */
	public static function init($bMode=false){
		self::$mode = $bMode;
		self::$separator = $bMode;
		if (!isset(self::$instance)){
			self::$instance = new QTool();
		}
		return self::$instance;
	}

	/**
	 * facade for fluent interface instance
	 * @return object
	 */
	public static function make(){
		return self::init(true);
	}

	/*
	 * return the result
	 */
	public function get(){
		return self::$working;
	}
	
	/**
	 * working setter
	 *
	 * @param mixed $value
	 * @return object
	 */
	public function set($value){
		self::$working = $value;
		return $this;
	}
	/**
	 * camelize string
	 *
	 * @param string $lower_case_and_underscored_word
	 * @return string
	 */
	public function toCamelCase($lower_case_and_underscored_word=null)
	{
		if(self::$mode == true){
			$string = is_string($lower_case_and_underscored_word) 
					? $lower_case_and_underscored_word
					: self::$working;
			self::set(str_replace(self::blank, self::string, ucwords(str_replace(self::uscore, self::blank, $string))));
			return $this;
		} else {
			$replace = str_replace(self::blank, self::string, ucwords(str_replace(self::uscore, self::blank, $lower_case_and_underscored_word)));
			return $replace;
		}
	}

	/**
	 * to dot
	 * 
	 * @param string $camelCasedWord
	 * @return mixed
	 */
	public function toDot($camelCasedWord=null) {
		if(self::$mode==true){
			$string = is_string($camelCasedWord) 
					? $camelCasedWord
					: self::$working;			
			self::set(strtolower(preg_replace('/(?<=\\w)([A-Z])/', '.\\1', $string)));
			return $this;			
		} else {
			return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '.\\1', $camelCasedWord));
		}
	}
	
	/**
	 * to variable
	 * like toCamelCase but lowercases first letter
	 *
	 * @param string $var
	 * @return mixed
	 */
	public function toVariable($var=null) {
		if(self::$mode==true){
			$string = is_string($var)
					? $var 
					: self::$working;
			$string = QTool::init()->toCamelCase(QTool::init()->toUnderscore($string));
			
			$replace = strtolower(substr($string, 0, 1));
			self::set(preg_replace('/\\w/', $replace, $string, 1));
			return $this;
		} else {
			if(is_string($var)){
				$string = QTool::init()->toCamelCase(QTool::init()->toUnderscore($var));
				$replace = strtolower(substr($string, 0, 1));
				return preg_replace('/\\w/', $replace, $string, 1);
			}
		}
		
	}
	
	/**
	 * converts camel cased strings to underscore strings 
	 *
	 * @param string $camel_cased_word
	 * @return string
	 */
	public function toUnderscore($camel_cased_word=null)
	{
		$tmp = ($camel_cased_word and is_string($camel_cased_word))
				? $camel_cased_word
				: self::$working;
		$tmp = str_replace('::', '/', $tmp);
		$tmp = self::replace($tmp, array('/([A-Z]+)([A-Z][a-z])/' => '\\1_\\2', '/([a-z\d])([A-Z])/' => '\\1_\\2'));
		if(self::$mode == true){
			self::set(strtolower($tmp));
			return $this;
		} else {
		    return strtolower($tmp);
		}
	}

	public function toHuman($lower_case_and_underscored_word = null)
	{
		if(self::$mode == true){
			$string = ($lower_case_and_underscored_word and is_string($lower_case_and_underscored_word))
					? $lower_case_and_underscored_word
					: self::$working;
			if (substr($string, -3) === '_id'){
				$string = substr($string, 0, -3);
    		}
			self::set(ucfirst(str_replace(self::uscore, self::space, $string)));
			return $this;
		} else {
			if (substr($lower_case_and_underscored_word, -3) === '_id'){
				$lower_case_and_underscored_word = substr($lower_case_and_underscored_word, 0, -3);
    		}
			return ucfirst(str_replace(self::uscore, self::space, $lower_case_and_underscored_word));
		}
	}
	
	/**
	 * replaces value pairs
	 *
	 * @param array $search
	 * @param array $replacePairs
	 * @return string
	 */
	protected function replace($search, $replacePairs)
	{
		return preg_replace(array_keys($replacePairs), array_values($replacePairs), $search);
	}
	
	/**
	 * builds underscored string from array
	 *
	 * @param array $array
	 * @param string $separator
	 * @return unknown
	 */
	public function fromArray($array=null, $separator = self::uscore){
		if(self::$mode == true){
			$array = is_array($array) 
					? $array
					: self::$working;
			if(!is_array($array)){
				$array = array();
			}
			self::set(implode($separator, $array));
			return $this;
		} else {
			if(is_array($array)){
				return implode($separator, $array);
			}
		}
	}
		
	/**
	 * returns an array by splitting a string
	 *
	 * @param string $string
	 * @param string $separator
	 * @return array
	 */
	public function toArray($string=null, $separator = self::uscore){
		$delim = self::$separator ? self::$separator : $separator;
		if(self::$mode == true){
			if($string and is_string($string)){
				if(strpos(self::$string, $delim)){
					self::set(explode($delim, $string));
				} else {
					self::set(explode($this->separator, $string));
				}
				if(self::$separator==self::dot){
					self::$separator = self::uscore;
				}
				return $this;				
			} elseif(is_string(self::$working)) {
				if(strpos(self::$working, $separator)){
					self::set(explode($separator, self::$working));
				} else {
					self::set(explode(self::$separator, self::$working));
				}
				if(self::$separator==self::dot){
					self::$separator = self::uscore;
				}
				return $this;
			}
		} else {
			if(strpos($string, $delim)){
				return explode($delim, $string);
			}
		}
	}

	public function toAbsPath(){
		if(is_array(self::$working)){
			$array = self::$working;
			unset($array[count($array)-1]);
			$c = count($array);
			if($c > 2) {
				unset($array[count($array)-1]);				
			}
			$ns = implode(self::uscore, $array);
			self::set(str_replace(self::uscore, DS, $ns));
		}
		return $this;
	}
	
	/**
	 * helper method
	 *
	 * @param string $value
	 * @return string
	 */
	public static function cutLast($value){
		if(self::$mode == true){
			self::set(substr($value,0,-1));
			return $this;
		} else {
			return substr($value,0,-1);
		}
	}

	public static function startsWith($value){
		return substr($value,0,1);
	}

	public static function endsWith($value){
		return substr($value,-1,1);
	}

	public static function find($value, $search){
		return strpos($value, $search);
	}

	public static function rfind($value, $search){
		return strrpos($value, $search);
	}

	public static function cut($value, $start=0, $end = false){
		if($start and $end){
			return substr($value, $start, $end);
		} elseif($start and !$end){
			return substr($value, $start);
		} 
	}

	public function classType($name=null) {
		if(self::$mode == true){
			$name = ($name and is_string($name))
				? $name
				: self::$working;
			$array = QTool::init()->toArray(QTool::init()->toUnderscore($name));
			self::set($array[(count($array)-1)]);
			return $this;
		} else {
			$array = QTool::init()->toArray(QTool::init()->toUnderscore($name));
			return $array[(count($array)-1)];
		}
	}

	
	public function toNamespace($className=null, $separator = self::dot, $reduce = 1) {
		self::$separator = $separator;
		if(self::$mode==true){
			$className 	= is_string($className)
						? $className
						: self::$working;				
			if(strstr($className,self::$separator)){
				$arrClassName = array_reverse(explode(self::$separator, $className));
			} else {
				$underscore = QTool::init()->toUnderscore($className);
				$array = QTool::init()->toArray($underscore, self::uscore);
				self::$mode = true;
				$arrClassName = array_reverse($array);
			}
			$arrNamespace = array();
			foreach($arrClassName as $key => $strName) {
				$arrNamespace[] = ($key < count($arrClassName) - 1 ) 
								? QInflector::init()->pluralize($strName)
								: $strName;
			}
			self::set(implode(self::dot, $arrNamespace));
			self::$separator = self::dot;
			return $this;	
		} else {
			$arrClassName 	= (strstr($className,self::$separator)) 
							? array_reverse(explode(self::$separator, $className))
							: array_reverse(QTool::init()->toArray(QTool::init()->toUnderscore($className)), self::$separator);
			$arrNamespace = array();
			foreach($arrClassName as $key => $strName) {
				$arrNamespace[] = ($key < count($arrClassName) - $reduce ) 
								? QInflector::init()->pluralize($strName)
								: $strName;
			}
			return implode(self::dot, $arrNamespace);
		}
	}
	
	/**
	 * find context in array
	 *
	 * @param string $name
	 * @param array $data
	 * @return mixed
	 */
	public function findContext($name, $data){
		if(self::$mode == true)
		{
			foreach($data as $context => $contexts) {
				if(in_array($name, $contexts)){
					self::set($context);
					break;
				} 
			}
			return $this;
		} else {
			foreach($data as $context => $contexts) {
				if(in_array($name, $contexts)){
					return $context;
				}
			} 
			return false;
		}
	}	
		
}

?>