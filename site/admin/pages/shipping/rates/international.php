<?php

switch($call) {
	
	case 'add':
	require_once('international'.DS.'add.php');
	break;
	case 'remove':
	require_once('international'.DS.'remove.php');
	break;
	default:
	require_once('international'.DS.'list.php');
	
}