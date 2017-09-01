<?php

use \Exception;

use SSD\Helper;


if ($objShipping->removeZone($zone['id'])) {
	
	echo Helper::json(array('error' => false));
	
} else {
	throw new Exception('Record could not be removed');
}