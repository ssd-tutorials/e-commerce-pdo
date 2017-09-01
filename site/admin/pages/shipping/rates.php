<?php

use SSD\Country;

$zid = $this->objUrl->get('zid');
$call = $this->objUrl->get('call');

switch($type['local']) {
	case 1:
	$zone = $objShipping->getZoneById($zid);
	if (!empty($zone)) {
		require_once('rates'.DS.'local.php');
	}
	break;
	default:
	$objCountry = new Country();
	$country = $objCountry->getCountry($zid);
	if (!empty($country)) {
		require_once('rates'.DS.'international.php');
	}
}