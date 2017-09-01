<?php

use SSD\Session;
use SSD\Basket;
use SSD\Helper;
use SSD\Plugin;


if (isset($_POST['id'])) {
	$id = $_POST['id'];
	Session::removeItem($id);
}


$objBasket = is_object($objBasket) ? $objBasket : new Basket();

$out = array();

$out['replace_values']['.bl_ti'] = $objBasket->number_of_items;
$out['replace_values']['.bl_st'] = $this->objCurrency->display(number_format($objBasket->sub_total, 2));
$out['replace_values']['#big_basket'] = Plugin::get('front'.DS.'basket_view', array(

    'objUrl' => $this->objUrl,
    'objCurrency' => $this->objCurrency

));

echo Helper::json($out);



















