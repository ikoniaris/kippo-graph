<?php

class QInflector {
	
	const open = "(?:";
	const close = ")";
	const pipe = "|";
	
	private static $instance;
	
	protected $pluralized = array();
	protected $singularized = array();
	
	protected $pluralRules;
	protected $singularRules;
	
	public static function init(){
		if (!isset(self::$instance)){
			self::$instance = new QInflector();
		}
		return self::$instance;
	}

	function pluralize($word) {

		if (!isset($this->pluralRules) || empty($this->pluralRules)) {
			$this->setPluralRules();
		}

		foreach($this->pluralRules as $rule => $replacement) {
			if (preg_match($rule, $word)) {
				$this->pluralized[$word] = preg_replace($rule, $replacement, $word);
				return $this->pluralized[$word];
			}
		}
		return $word;
	}
	
	private function setPluralRules() {
		$pluralRules = array(
			'/(nt)bus$/i' => '\1\2busses',
			'/(s)tatus$/i' => '\1\2tati',
			'/(th)is$/i' => '\1ose', // this
			'/(quiz|jazz)$/i' => '\1zes',
			'/^(ox)$/i' => '\1\2en', // ox
			'/([m|l])ouse$/i' => '\1ice', // mouse, louse
			'/(matr|vert|ind)(ix|ex)$/i'  => '\1ices', // matrix, vertex, index
			'/(x|ch|ss|sh)$/i' => '\1es', // search, switch, fix, box, process, address
			'/([^aeiouy]|qu)y$/i' => '\1ies', // query, ability, agency
			'/(hive)$/i' => '\1s', // archive, hive
			'/(?:([^f])fe|([lr])f)$/i' => '\1\2ves', // half, safe, wife
			'/sis$/i' => 'ses', // basis, diagnosis
			'/([ti])um$/i' => '\1a', // datum, medium
			'/(p)erson$/i' => '\1eople', // person, salesperson
			'/(m)an$/i' => '\1en', // man, woman, spokesman
			'/(c)hild$/i' => '\1hildren', // child
			'/(buffal|tomat)o$/i' => '\1\2oes', // buffalo, tomato
			'/us$/' => 'i', // us
			'/(alias)/i' => '\1es', // alias
			'/(octop|vir)us$/i' => '\1i', 
			'/(ax|cri|test)is$/i' => '\1es',
			'/s$/' => 's',  // no change (compatibility)
			'/$/' => 's',
		);
		
		$this->pluralRules = $pluralRules;
		
	}

	
	public function singularize($word) {
		
		if (!isset($this->singularRules) || empty($this->singularRules)) {
			$this->setSingularRules();
		}

		foreach($this->singularRules as $rule => $replacement) {
			if (preg_match($rule, $word)) {
				$this->singularized[$word] = preg_replace($rule, $replacement, $word);
				return $this->singularized[$word];
			}
		}
		return $word;
	}	

	protected function setSingularRules() {

		$coreSingularRules = array('/(s)tatuses$/i' => '\1\2tatus',
									'/(quiz)zes$/i' => '\\1',
									'/(matr)ices$/i' => '\1ix',
									'/(vert|ind)ices$/i' => '\1ex',
									'/^(ox)en/i' => '\1',
									'/(alias)es$/i' => '\1',
									'/([octop|vir])i$/i' => '\1us',
									'/(cris|ax|test)es$/i' => '\1is',
									'/(shoe)s$/i' => '\1',
									'/(o)es$/i' => '\1',
									'/ouses$/' => 'ouse',
									'/uses$/' => 'us',
									'/([m|l])ice$/i' => '\1ouse',
									'/(x|ch|ss|sh)es$/i' => '\1',
									'/(m)ovies$/i' => '\1\2ovie',
									'/(s)eries$/i' => '\1\2eries',
									'/([^aeiouy]|qu)ies$/i' => '\1y',
									'/([lr])ves$/i' => '\1f',
									'/(tive)s$/i' => '\1',
									'/(th)ose$/i' => '\1is',
									'/(hive)s$/i' => '\1',
									'/(drive)s$/i' => '\1',
									'/([^f])ves$/i' => '\1fe',
									'/(^analy)ses$/i' => '\1sis',
									'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
									'/([ti])a$/i' => '\1um',
									'/(p)eople$/i' => '\1\2erson',
									'/(m)en$/i' => '\1an',
									'/(c)hildren$/i' => '\1\2hild',
									'/(n)ews$/i' => '\1\2ews',
									'/s$/i' => '');


		$this->singularRules = $coreSingularRules;
	}
	
}
?>