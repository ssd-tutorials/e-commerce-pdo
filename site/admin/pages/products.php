<?php

use SSD\Login;

Login::restrictAdmin();

$action = $this->objUrl->get('action');

switch($action) {
	
	case 'add':
	require_once('products'.DS.'add.php');
	break;
	
	case 'added':
	require_once('products'.DS.'added.php');
	break;
	
	case 'added-failed':
	require_once('products'.DS.'added-failed.php');
	break;
	
	case 'added-no-upload':
	require_once('products'.DS.'added-no-upload.php');
	break;
	
	case 'edit':
	require_once('products'.DS.'edit.php');
	break;
	
	case 'edited':
	require_once('products'.DS.'edited.php');
	break;
	
	case 'edited-failed':
	require_once('products'.DS.'edited-failed.php');
	break;
	
	case 'edited-no-upload':
	require_once('products'.DS.'edited-no-upload.php');
	break;
	
	case 'remove':
	require_once('products'.DS.'remove.php');
	break;
	
	default:
	require_once('products'.DS.'list.php');

}







