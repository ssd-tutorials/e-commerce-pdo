<?php

use SSD\Basket;

$objBasket = is_object($objBasket) ? $objBasket : new Basket();
?>
<h2>Your Basket</h2>
<dl id="basket_left">
	<dt>No. of items:</dt>
	<dd class="bl_ti"><span><?php echo $objBasket->number_of_items; ?></span></dd>
	<dt>Sub-total:</dt>
	<dd class="bl_st"><span><?php echo $data['objCurrency']->display(number_format($objBasket->sub_total, 2)); ?></span></dd>
</dl>
<div class="dev br_td">&#160;</div>
<p><a href="<?php echo $data['objUrl']->href('basket'); ?>">View Basket</a> | <a href="<?php echo $data['objUrl']->href('checkout'); ?>">Checkout</a></p>
<div class="dev br_td">&#160;</div>