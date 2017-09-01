<?php

use \Exception;

use SSD\Form;
use SSD\Validation;
use SSD\Plugin;
use SSD\Helper;


$objForm = new Form();
$objValid = new Validation($objForm);
$objValid->expected = array('name');
$objValid->required = array('name');

try {
	
	if ($objValid->isValid()) {
	
		if ($objShipping->addZone($objValid->post)) {
			
			$replace = array();
			
			$zones = $objShipping->getZones();
			$replace['#zoneList'] = Plugin::get('admin'.DS.'zone', array(
				'rows' => $zones,
				'objUrl' => $this->objUrl
			));
			
			echo Helper::json(array('error' => false, 'replace' => $replace));
			
		} else {
			$objValid->add2Errors('name', 'Record could not be added');
			throw new Exception('Record could not be added');
		}
		
	} else {
		throw new Exception('Invalid entry');
	}
	
} catch (Exception $e) {
	
	echo Helper::json(array('error' => true, 'validation' => $objValid->errorsMessages));
	
}