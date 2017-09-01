<?php

namespace SSD;

class Url {

	public $key_page = 'page';
	public $key_modules = array('panel');
	public $module = 'front';
	public $main = 'index';
	public $cpage = 'index';
	public $c = 'login';
	public $a = 'index';
	public $params = array();
	public $paramsRaw = array();
	public $stringRaw;
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function __construct() {
		$this->_process();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	private function _process() {
		$uri = $_SERVER['REQUEST_URI'];
		if (!empty($uri)) {
			$uriQ = explode('?', $uri);
			$uri = $uriQ[0];
			if (count($uriQ) > 1) {
				$this->stringRaw = $uriQ[1];
				$uriRaw = explode('&', $uriQ[1]);
				if (count($uriRaw) > 1) {
					foreach($uriRaw as $key => $row) {
						$this->splitRaw($row);
					}
				} else {
					$this->splitRaw($uriRaw[0]);
				}
			}
			$uri = Helper::clearString($uri, PAGE_EXT);
			$firstChar = substr($uri, 0, 1);
			if ($firstChar == '/') {
				$uri = substr($uri, 1);
			}
			$lastChar = substr($uri, -1);
			if ($lastChar == '/') {
				$uri = substr($uri, 0, -1);
			}
			if (!empty($uri)) {
				$uri = explode('/', $uri);
				$first = array_shift($uri);
				if (in_array($first, $this->key_modules)) {
					$this->module = $first;
					$first = empty($uri) ? $this->main : array_shift($uri);
				}
				$this->main = $first;
				$this->cpage = $this->main;
				if (count($uri) > 1) {
					$pairs = array();
					foreach($uri as $key => $value) {
						$pairs[] = $value;
						if (count($pairs) > 1) {
							if (!Helper::isEmpty($pairs[1])) {
								if ($pairs[0] == $this->key_page) {
									$this->cpage = $pairs[1];
								} else if ($pairs[0] == 'c') {
									$this->c = $pairs[1];
								} else if ($pairs[0] == 'a') {
									$this->a = $pairs[1];
								}
								$this->params[$pairs[0]] = $pairs[1];
							}
							$pairs = array();
						}
					}
				}
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function splitRaw($item = null) {
		if (!empty($item) && !is_array($item)) {
			$itemRaw = explode('=', $item);
			if (count($itemRaw) > 1 && !Helper::isEmpty($itemRaw[1])) {
				$this->paramsRaw[$itemRaw[0]] = $itemRaw[1];
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function getRaw($param = null) {
		if (!empty($param) && array_key_exists($param, $this->paramsRaw)) {
			return $this->paramsRaw[$param];
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function get($param = null) {
		if (!empty($param) && array_key_exists($param, $this->params)) {
			return $this->params[$param];
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function href($main = null, $params = null) {
		if (!empty($main)) {
			$out = array($main);
			if (!empty($params) && is_array($params)) {
				foreach($params as $key => $value) {
					$out[] = $value;
				}
			}
			return '/'.implode('/', $out).PAGE_EXT;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function getCurrent($exclude = null, $extension = false, $add = null) {
		$out = array();
		if ($this->module != 'front') {
			$out[] = $this->module;
		}
		$out[] = $this->main;
		if (!empty($this->params)) {
			if (!empty($exclude)) {
				$exclude = Helper::makeArray($exclude);
				foreach($this->params as $key => $value) {
					if (!in_array($key, $exclude)) {
						$out[] = $key;
						$out[] = $value;
					}
				}
			} else {
				foreach($this->params as $key => $value) {
					$out[] = $key;
					$out[] = $value;
				}
			}
		}
		if (!empty($add)) {
			$add = Helper::makeArray($add);
			foreach($add as $item) {
				$out[] = $item;
			}
		}
		$url  = '/'.implode('/', $out);
		$url .= $extension ? PAGE_EXT : null;
		return $url;
	}
	
	
	
	
	
	
	
	
	public function __toString() {
		
		$out  = '<div>';
		$out .= '<strong>$module:</strong><br />'.$this->module;
		$out .= '<br />';
		$out .= '<strong>$main:</strong><br />'.$this->main;
		$out .= '<br />';
		$out .= '<strong>$cpage:</strong><br />'.$this->cpage;
		$out .= '<br />';
		$out .= '<strong>$params:</strong><br />';
		$out .= Helper::printArray($this->params);
		$out .= '<br />';
		$out .= '<strong>$paramsRaw:</strong><br />';
		$out .= Helper::printArray($this->paramsRaw);
		$out .= '<br />';
		$out .= '<strong>$stringRaw:</strong><br />'.$this->stringRaw;
		$out .= '</div>';
		
		return $out;
		
	}
	
	
	
	












}



