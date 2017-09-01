<?php

use \Exception;

use SSD\Plugin;
use SSD\Helper;


$rid = $this->objUrl->get('rid');

if (!empty($rid)) {
	
	$record = $objShipping->getShippingByIdTypeZone($rid, $id, $zid);
	
	if (empty($record)) {
		throw new Exception('Record does not exist');
	}
	
	if ($objShipping->removeShipping($record['id'])) {
		
		$replace = array();
		
		$shipping = $objShipping->getShippingByTypeZone($id, $zid);
		
		$replace['#shippingList'] = Plugin::get('admin'.DS.'shipping-cost', array(
			'rows' => $shipping,
			'objUrl' => $this->objUrl,
            'objCurrency' => $this->objCurrency
		));
		
		echo Helper::json(array('error' => false, 'replace' => $replace));
		
	} else {
		throw new Exception('Record could not be removed');
	}
	
} else {
	throw new Exception('Missing parameter');
}