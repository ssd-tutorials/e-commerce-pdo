<?php

use \Exception;


$cid = $this->objUrl->get('cid');
$call = $this->objUrl->get('call');

switch($call) {
	
	case 'add':
	require_once('codes'.DS.'add.php');
	break;
	
	case 'remove':
	if (!empty($cid)) {
		$code = $objShipping->getPostCode($cid, $zone['id']);
		if (!empty($code)) {
			require_once('codes'.DS.'remove.php');
		} else {
			throw new Exception('Record not found');
		}
	} else {
		throw new Exception('Missing parameter');
	}
	break;
	
	default:
	require_once('codes'.DS.'list.php');
	
}