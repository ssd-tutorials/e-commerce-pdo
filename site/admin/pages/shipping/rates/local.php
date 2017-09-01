<?php

switch($call) {
	
	case 'add':
	require_once('local'.DS.'add.php');
	break;
	case 'remove':
	require_once('local'.DS.'remove.php');
	break;
	default:
	require_once('local'.DS.'list.php');
	
}