<?php

use \Exception;

use SSD\Form;
use SSD\Validation;
use SSD\Helper;
use SSD\Plugin;


$objForm = new Form();
$objValid = new Validation($objForm);
$objValid->expected = array('weight', 'cost');
$objValid->required = array('weight', 'cost');

try {
	
	if ($objValid->isValid()) {
	
		if ($objShipping->isDuplicateLocal($id, $zid, $objValid->post['weight'])) {
			$objValid->add2Errors('weight', 'Duplicate weight');
			throw new Exception('Duplicate weight');
		}
		
		$objValid->post['type'] = $id;
		$objValid->post['zone'] = $zid;
		$objValid->post['country'] = COUNTRY_LOCAL;
		
		if ($objShipping->addShipping($objValid->post)) {
			
			$replace = array();
			
			$shipping = $objShipping->getShippingByTypeZone($id, $zid);
			
			$replace['#shippingList'] = Plugin::get('admin'.DS.'shipping-cost', array(
				'rows' => $shipping,
				'objUrl' => $this->objUrl,
                'objCurrency' => $this->objCurrency
			));
			
			echo Helper::json(array('error' => false, 'replace' => $replace));
			
		} else {
			$objValid->add2Errors('weight', 'Record could not be added');
			throw new Exception('Record could not be added');
		}
		
		
		
	} else {
		throw new Exception('Invalid request');
	}
	
} catch (Exception $e) {
	echo Helper::json(array('error' => true, 'validation' => $objValid->errorsMessages));
}









