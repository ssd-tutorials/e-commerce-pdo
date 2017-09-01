<?php

use \Exception;

use SSD\Helper;


if ($type['default'] == 1) {
	throw new Exception('Operation not permitted');
}

if ($objShipping->setTypeDefault($type['id'], $type['local'])) {
	
	echo Helper::json(array('error' => false));
	
} else {
	throw new Exception('Record could not be updated');
}