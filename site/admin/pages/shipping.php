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
		
		case 'default':
		case 'active':
		case 'remove':
		case 'update':
		case 'duplicate':
		case 'rates':
		if (!empty($id)) {
			
			$type = $objShipping->getType($id);
			if (!empty($type)) {
				
				switch($action) {
					
					case 'default':
					require_once('shipping'.DS.'default.php');
					break;
					case 'active':
					require_once('shipping'.DS.'active.php');
					break;
					case 'remove':
					require_once('shipping'.DS.'remove.php');
					break;
					case 'update':
					require_once('shipping'.DS.'update.php');
					break;
					case 'duplicate':
					require_once('shipping'.DS.'duplicate.php');
					break;
					case 'rates':
					require_once('shipping'.DS.'rates.php');
					break;
					
				}
				
			} else {
				throw new Exception('Record not found');
			}
			
		} else {
			throw new Exception('Missing parameter');
		}
		break;
		
		case 'sort':
		require_once('shipping'.DS.'sort.php');
		break;
		
		case 'add':
		require_once('shipping'.DS.'add.php');
		break;
		
		default:
		require_once('shipping'.DS.'list.php');
		
	}
	
} catch (Exception $e) {
	
	echo Helper::json(array(
		'error' => true,
		'message' => $e->getMessage()
	));
	
}