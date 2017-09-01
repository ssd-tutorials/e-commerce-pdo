<?php

use \Exception;

use SSD\Helper;


$active = $type['active'] == 1 ? 0 : 1;

if ($objShipping->updateType(array('active' => $active), $type['id'])) {
	
	$replace  = '<a href="#" data-url="';
	$replace .= $this->objUrl->getCurrent();
	$replace .= '" class="clickReplace">';
	$replace .= $active == 1 ? 'Yes' : 'No';
	$replace .= '</a>';
	
	echo Helper::json(array('error' => false, 'replace' => $replace));
	
} else {
	throw new Exception('Record could not be updated');
}