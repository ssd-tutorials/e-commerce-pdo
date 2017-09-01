<?php

use \Exception;

use SSD\Login;
use SSD\Shipping;
use SSD\Helper;


Login::restrictAdmin();

$objShipping = new Shipping();

$id = $this->objUrl->get('id');
$action = $this->objUrl->get('action');

try {
	
	switch($action) {
		
		case 'update':
		case 'remove':
		case 'codes':
		if (!empty($id)) {
			$zone = $objShipping->getZoneById($id);
			if (!empty($zone)) {
				switch($action) {
					case 'update':
					require_once('zone'.DS.'update.php');
					break;
					case 'remove':
					require_once('zone'.DS.'remove.php');
					break;
					case 'codes':
					require_once('zone'.DS.'codes.php');
					break;
				}
			} else {
				throw new Exception('Record does not exist');
			}
		} else {
			throw new Exception('Missing parameter');
		}
		break;
		
		case 'add':
		require_once('zone'.DS.'add.php');
		break;
		
		default:
		require_once('zone'.DS.'list.php');
		
		
	}
	
} catch (Exception $e) {
	echo Helper::json(array('error' => true, 'message' => $e->getMessage()));
}