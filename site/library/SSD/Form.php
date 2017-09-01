<?php

namespace SSD;

class Form {





	public function isPost($field = null) {
		if (!empty($field)) {
			if (isset($_POST[$field])) {
				return true;
			}
			return false;			
		} else {
			if (!empty($_POST)) {
				return true;
			}
			return false;
		}
	}
	
	
	
	
	
	
	
	
	public function getPost($field = null) {
		if (!empty($field)) {
			return $this->isPost($field) ? 
					strip_tags($_POST[$field]) : 
					null;
		}
	}
	
	
	
	
	
	
	
	
	
	
	public function stickySelect($field, $value, $default = null) {
		if ($this->isPost($field) && $this->getPost($field) == $value) {
			return " selected=\"selected\"";
		} else {
			return !empty($default) && $default == $value ?
					" selected=\"selected\"" :
					null;
		}
	}
	
	
	
	
	
	
	
	
	public function stickyText($field, $value = null) {
		if ($this->isPost($field)) {
			return stripslashes($this->getPost($field));
		} else {
			return !empty($value) ? $value : null;
		}
	}
	
	
	
	
	
	
	
	
	public function stickyRadio($field = null, $value = null, $data = null) {
		
		$post = $this->getPost($field);
		
		if (!Helper::isEmpty($post)) {
			
			if ($post == $value) {
				return ' checked="checked"';
			}
			
		} else {
			return !Helper::isEmpty($data) && $value == $data ? ' checked="checked"' : null;
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	public function stickyRemoveClass(
		$field = null, $value = null, $data = null, $class = null, $single = false
	) {
		
		$post = $this->getPost($field);
		
		if (!Helper::isEmpty($post)) {
			
			if ($post != $value) {
				return $single ? ' class="'.$class.'"' : ' '.$class;
			}
			
		} else {
			
			if ($value != $data) {
				return $single ? ' class="'.$class.'"' : ' '.$class; 
			}
			
		}
		
	}
	
		
	







	public function getCountriesSelect($record = null, $name = 'country', $selectOption = false) {
		$objCountry = new Country();
		$countries = $objCountry->getCountries();
		if (!empty($countries)) {
			$out = "<select name=\"{$name}\" id=\"{$name}\" class=\"sel\">";
			if (empty($record) || $selectOption == true) {
				$out .= "<option value=\"\">Select one&hellip;</option>";
			}
			foreach($countries as $country) {
				$out .= "<option value=\"";
				$out .= $country['id'];
				$out .= "\"";
				$out .= $this->stickySelect($name, $country['id'], $record);
				$out .= ">";
				$out .= $country['name'];
				$out .= "</option>";
			}
			$out .= "</select>";
			return $out;
		}
	}
	
	
	
	
	
	
	
	
	public function getPostArray($expected = null) {
		$out = array();
		if ($this->isPost()) {
			foreach($_POST as $key => $value) {
				if (!empty($expected)) {
					if (in_array($key, $expected)) {
						$out[$key] = strip_tags($value);
					}
				} else {
					$out[$key] = strip_tags($value);
				}
			}
		}
		return $out;
	}
	
	
	
	
	
	
	




}