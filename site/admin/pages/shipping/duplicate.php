<?php

use \Exception;

use SSD\Helper;


if ($objShipping->duplicateType($id)) {
	
	echo Helper::json(array('error' => false));
	
} else {
	throw new Exception('Record could not be duplicated');
}