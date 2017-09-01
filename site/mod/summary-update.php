<?php

use SSD\Login;
use SSD\User;
use SSD\Session;
use SSD\Basket;
use SSD\Shipping;
use SSD\Helper;

$shipping = $this->objUrl->get('shipping');
	
if (!empty($shipping)) {

    Login::restrictFront();

    $objUser = new User();
    $user = $objUser->getUser(Session::getSession(Login::$login_front));

    if (!empty($user)) {

        $objBasket = new Basket($user);

        $objShipping = new Shipping($objBasket);

        $shippingSelected = $objShipping->getShipping($user, $shipping);

        if (!empty($shippingSelected)) {

            if ($objBasket->addShipping($shippingSelected)) {

                $out = array();

                $out['basketSubTotal'] = $this->objCurrency->display(number_format($objBasket->final_sub_total, 2));
                $out['basketVat'] = $this->objCurrency->display(number_format($objBasket->final_vat, 2));
                $out['basketTotal'] = $this->objCurrency->display(number_format($objBasket->final_total, 2));

                echo Helper::json(array('error' => false, 'totals' => $out));

            } else {
                throw new Exception('Shipping could not be added');
            }

        } else {
            throw new Exception('Shipping could not be found');
        }

    } else {
        throw new Exception('User could not be found');
    }

} else {
    throw new Exception('Invalid request');
}



