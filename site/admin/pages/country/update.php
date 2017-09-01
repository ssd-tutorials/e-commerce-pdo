<?php

use \Exception;

use SSD\Form;
use SSD\Helper;


$objForm = new Form();

$value = $objForm->getPost('value');

if (!empty($value)) {
	
	if ($objCountry->update(array('name' => $value), $country['id'])) {
		
		echo Helper::json(array('error' => false));
		
	} else {
		throw new Exception('Record could not be updated');
	}
	
} else {
	throw new Exception('Invalid entry');
}