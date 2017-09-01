<?php

use SSD\Login;

Login::restrictAdmin();

$action = $this->objUrl->get('action');

switch($action) {
	
	case 'edit':
	require_once('clients'.DS.'edit.php');
	break;
	
	case 'edited':
	require_once('clients'.DS.'edited.php');
	break;
	
	case 'edited-failed':
	require_once('clients'.DS.'edited-failed.php');
	break;
	
	case 'remove':
	require_once('clients'.DS.'remove.php');
	break;
	
	default:
	require_once('clients'.DS.'list.php');

}







