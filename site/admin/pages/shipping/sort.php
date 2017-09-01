<?php

use \Exception;

use SSD\Helper;


if (!empty($_POST)) {
	
	$errors = array();
	
	foreach($_POST as $row) {
		foreach($row as $order => $id) {
			$order++;
			if (!$objShipping->updateType(array('order' => $order), $id)) {
				$errors[] = $id;
			}
		}
	}
	
	if (empty($errors)) {
		
		echo Helper::json(array('error' => false));
		
	} else {
		throw new Exception(count($errors) . ' records could not be updated');
	}
	
} else {
	throw new Exception('Missing parameter');
}