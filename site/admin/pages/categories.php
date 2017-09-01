<?php

use SSD\Login;

Login::restrictAdmin();

$action = $this->objUrl->get('action');

switch($action) {
	
	case 'add':
	require_once('categories'.DS.'add.php');
	break;
	
	case 'added':
	require_once('categories'.DS.'added.php');
	break;
	
	case 'added-failed':
	require_once('categories'.DS.'added-failed.php');
	break;
	
	case 'edit':
	require_once('categories'.DS.'edit.php');
	break;
	
	case 'edited':
	require_once('categories'.DS.'edited.php');
	break;
	
	case 'edited-failed':
	require_once('categories'.DS.'edited-failed.php');
	break;
	
	case 'remove':
	require_once('categories'.DS.'remove.php');
	break;
	
	default:
	require_once('categories'.DS.'list.php');

}







