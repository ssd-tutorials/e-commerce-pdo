<?php

namespace SSD;

class Upload {
	
	private $_files = array();
	public $overwrite = false;
	public $errors = array();
	public $names = array();
	
	
	
	
	
	
	
	
	public function __construct() {
		$this->_getUploads();
	}
	
	
	
	
	
	
	
	
	
	private function _getUploads() {
		if (!empty($_FILES)) {
			foreach($_FILES as $key => $value) {
				$this->_files[$key] = $value;
			}
		}
	}
	
	
	
	
	
	
	
	
	public function upload($path = null) {
	
		if (!empty($path) && is_dir($path) && !empty($this->_files)) {
		
			foreach($this->_files as $key => $value) {
			
				$name = Helper::cleanString($value['name']);
				
				if ($this->overwrite == false && is_file($path.DS.$name)) {
					$prefix = date('YmdHis', time());
					$name = $prefix."-".$name;
				}
				
				if (!move_uploaded_file($value['tmp_name'], $path.DS.$name)) {
					$this->errors[] = $key;
				}
				
				$this->names[] = $name;
			
			}
			
			return empty($this->errors) ? true : false;
		
		}
		return false;
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	



}