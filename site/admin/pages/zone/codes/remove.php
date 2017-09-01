<?php

use \Exception;

use SSD\Plugin;
use SSD\Helper;


if ($objShipping->removePostCode($code['id'])) {
	
	$replace = array();
	
	$postCodes = $objShipping->getPostCodes($zone['id']);
	
	$replace['#postCodeList'] = Plugin::get('admin'.DS.'post-code', array(
		'rows' => $postCodes,
		'objUrl' => $this->objUrl
	));
	
	echo Helper::json(array('error' => false, 'replace' => $replace));
	
} else {
	throw new Exception('Record could not be removed');
}