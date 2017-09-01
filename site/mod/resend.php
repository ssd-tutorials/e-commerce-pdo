<?php

use SSD\User;
use SSD\Email;
use SSD\Helper;


$id = $this->objUrl->get('id');

if (!empty($id)) {
	
	$objUser = new User($this->objUrl);
	$user = $objUser->getUser($id);
	
	if (!empty($user)) {
		
		$objEmail = new Email($this->objUrl);
		if ($objEmail->process(1, array(
			'email' => $user['email'],
			'first_name' => $user['first_name'],
			'last_name' => $user['last_name'],
			'hash' => $user['hash']
		))) {
			echo Helper::json(array('error' => false));
		} else {
			echo Helper::json(array('error' => true));
		}
		
	} else {
		echo Helper::json(array('error' => true));
	}
	
} else {
	echo Helper::json(array('error' => true));
}