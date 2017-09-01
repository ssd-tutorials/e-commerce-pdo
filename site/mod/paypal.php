<?php

use \Exception;

use SSD\Session;
use SSD\Form;
use SSD\Login;
use SSD\User;
use SSD\Order;
use SSD\Basket;
use SSD\Catalogue;
use SSD\PayPal;
use SSD\Country;
use SSD\Helper;



// tokens
$token2 = Session::getSession('token2');
$objForm = new Form();
$token1 = $objForm->getPost('token');

if ($token2 == Login::string2hash($token1)) {

    $objUser = new User();
    $user = $objUser->getUser(Session::getSession(Login::$login_front));

    // create order
    $objOrder = new Order();

    if (!empty($user) && $objOrder->createOrder($user)) {

        // populate order details
        $order = $objOrder->getOrder();
        $items = $objOrder->getOrderItems();


        if (!empty($order) && !empty($items)) {

            $objBasket = new Basket($user);
            $objCatalogue = new Catalogue();
            $objPayPal = new PayPal();


            foreach($items as $item) {
                $product = $objCatalogue->getProduct($item['product']);
                $objPayPal->addProduct(
                    $item['product'],
                    $product['name'],
                    $item['price'],
                    $item['qty']
                );
            }


            $objPayPal->tax_cart = $objBasket->final_vat;
            $objPayPal->shipping = $objBasket->final_shipping_cost;


            // get user country record
            $objCountry = new Country();
            $country = $objCountry->getCountry($user['country']);


            // pass client's details to the PayPal instance
            $objPayPal->populate = array(
                'address1'		=> $user['address_1'],
                'address2'		=> $user['address_2'],
                'city'			=> $user['town'],
                'state'			=> $user['county'],
                'zip'			=> $user['post_code'],
                'country'		=> $country['code'],
                'email'			=> $user['email'],
                'first_name'	=> $user['first_name'],
                'last_name'		=> $user['last_name']
            );


            // redirect client to PayPal
            $form = $objPayPal->run($order['token']);
            echo Helper::json(array('error' => false, 'form' => $form));


        } else {
            throw new Exception('There was a problem retrieving your order');
        }

    } else {
        throw new Exception('Order could not be created');
    }

} else {
    throw new Exception('Invalid request');
}
	
	
	
	
	