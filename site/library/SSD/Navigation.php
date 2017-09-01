<?php

namespace SSD;

class Navigation {

	public $objUrl;
	public $classActive = 'act';


	
	
	
	
	
	public function __construct($objUrl = null) {
		$this->objUrl = is_object($objUrl) ? $objUrl : new Url();
	}
	
	
	
	
	
	
	
	
	public function active($main = null, $pairs = null, $single = true) {
		if (!empty($main)) {
			if (empty($pairs)) {
				if ($main == $this->objUrl->main) {
					return !$single ? 
						' '.$this->classActive : 
						' class="'.$this->classActive.'"';
				}
			} else {
				$exceptions = array();
				foreach($pairs as $key => $value) {
					$paramUrl = $this->objUrl->get($key);
					if ($paramUrl != $value) {
						$exceptions[] = $key;
					}
				}
				if ($main == $this->objUrl->main && empty($exceptions)) {
					return !$single ? 
						' '.$this->classActive : 
						' class="'.$this->classActive.'"';
				}
			}
		}
	}
	
	
	
	
	
	









}



