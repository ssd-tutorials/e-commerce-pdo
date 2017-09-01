<?php

use \Exception;

use SSD\Form;
use SSD\Validation;
use SSD\Helper;
use SSD\Plugin;


$objForm = new Form();
$objValid = new Validation($objForm);
$objValid->expected = array('name');
$objValid->required = array('name');

try {
	
	if ($objValid->isValid()) {
	
		if ($objCountry->insert($objValid->post)) {
			
			$replace = array();
			
			$countries = $objCountry->getAll();
			
			$replace['#countryList'] = Plugin::get('admin'.DS.'country', array(
				'rows' => $countries,
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