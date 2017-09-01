<?php


use \Exception;
use SSD\Helper;


$call = $this->objUrl->get('call');

try {


    switch($call) {

        case 'basket':
            require_once(ROOT_PATH.DS.MOD_DIR.DS.'basket.php');
            break;
        case 'basket-left':
            require_once(ROOT_PATH.DS.MOD_DIR.DS.'basket-left.php');
            break;
        case 'basket-qty':
            require_once(ROOT_PATH.DS.MOD_DIR.DS.'basket-qty.php');
            break;
        case 'basket-remove':
            require_once(ROOT_PATH.DS.MOD_DIR.DS.'basket-remove.php');
            break;
        case 'paypal':
            require_once(ROOT_PATH.DS.MOD_DIR.DS.'paypal.php');
            break;
        case 'resend':
            require_once(ROOT_PATH.DS.MOD_DIR.DS.'resend.php');
            break;
        case 'summary-update':
            require_once(ROOT_PATH.DS.MOD_DIR.DS.'summary-update.php');
            break;
        default:
            throw new Exception('Invalid request');

    }


} catch (Exception $e) {

    echo Helper::json(array(
        'error' => true,
        'message' => $e->getMessage()
    ));

}












