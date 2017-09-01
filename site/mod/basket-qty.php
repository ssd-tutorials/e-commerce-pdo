<?php

use SSD\Form;
use SSD\Catalogue;
use SSD\Session;
use SSD\Basket;
use SSD\Helper;
use SSD\Plugin;


$objForm = new Form();

$array = $objForm->getPostArray();

if (!empty($array)) {

    $objCatalogue = new Catalogue();

    foreach($array as $key => $value) {

        $identity = explode('-', $key);

        if (count($identity) == 2 && $identity[0] == 'qty') {

            $product = $objCatalogue->getProduct($identity[1]);

            if (empty($product)) {
                continue;
            }

            if (empty($value)) {

                Session::removeItem($product['id']);

            } else {

                Session::setItem($product['id'], $value);

            }

        }

    }


    $objBasket = is_object($objBasket) ? $objBasket : new Basket();

    $out['replace_values']['.bl_ti'] = $objBasket->number_of_items;
    $out['replace_values']['.bl_st'] = $this->objCurrency->display(number_format($objBasket->sub_total, 2));
    $out['replace_values']['#big_basket'] = Plugin::get('front'.DS.'basket_view', array(

        'objUrl' => $this->objUrl,
        'objCurrency' => $this->objCurrency

    ));


    echo Helper::json($out);

}















