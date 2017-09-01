<?php

namespace SSD;

class Plugin {
	
	
	
	
	
	
	
	public static function get($file = null, $data = null) {
		
		$path = PLUGIN_PATH.DS.$file.'.php';
		
		if (!empty($file) && is_file($path)) {
			
			ob_start();
			@include($path);
			return ob_get_clean();
			
		}

        return null;
		
	}
	
	
	
	
	
	
	
	
}