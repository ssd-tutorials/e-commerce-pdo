<?php

use SSD\Login;
use SSD\User;
use SSD\Session;
use SSD\Basket;
use SSD\Shipping;
use SSD\Catalogue;
use SSD\Helper;



Login::restrictFront($this->objUrl);

$objUser = new User();
$user = $objUser->getUser(Session::getSession(Login::$login_front));

if (!empty($user)) {

	$objBasket = new Basket($user);

    if (!$objBasket->empty_basket) {
	
        $objShipping = new Shipping($objBasket);
        $shipping = $objShipping->getShippingOptions($user);

        // clear all previous shipping sessions
        $objBasket->clearShipping();

        // get default shipping for the user's selected country
        $shippingDefault = $objShipping->getDefault($user);

        if (!empty($shipping) && !empty($shippingDefault)) {

            $shippingSelected = $objShipping->getShipping($user, $shippingDefault['id']);

            if ($objBasket->addShipping($shippingSelected)) {

                $token1 = mt_rand();
                $token2 = Login::string2hash($token1);
                Session::setSession('token2', $token2);


                $out = array();

                $session = Session::getSession('basket');

                if (!empty($session)) {
                    $objCatalogue = new Catalogue();
                    foreach($session as $key => $value) {
                        $out[$key] = $objCatalogue->getProduct($key);
                    }
                }

                require_once("_header.php");
?>

<h1>Order summary</h1>

<?php if (!empty($out)) { ?>

<div id="big_basket">

	<form action="" method="post" id="frm_basket">
		
		<table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat br_bd">
		
			<tr>
				<th>Item</th>
				<th class="ta_r">Qty</th>
				<th class="ta_r col_15">Price</th>
			</tr>
			
			<?php foreach($out as $item) { ?>
			
				<tr>
					<td><?php echo $item['name']; ?></td>
					<td class="ta_r"><?php echo $session[$item['id']]['qty']; ?></td>
				<td class="ta_r"><?php echo $this->objCurrency->display(number_format($objBasket->itemTotal($item['price'], $session[$item['id']]['qty']), 2)); ?></td>
				</tr>				
			<?php } ?>
			
			<tr class="rowHighlight">
				<td colspan="2" class="br_td">
					<i>Items total:</i>
				</td>
				<td class="ta_r br_td">
					<i><?php echo $this->objCurrency->display(number_format($objBasket->sub_total, 2)); ?></i>
				</td>
			</tr>
			
			<tr>
				<th colspan="3">Shipping</th>
			</tr>
			
			<?php foreach($shipping as $srow) { ?>
			
				<tr>
					<td colspan="2">
						<label for="shipping_<?php echo $srow['id']; ?>">
							<input 
								type="radio"
								name="shipping"
								id="shipping_<?php echo $srow['id']; ?>"
								value="<?php echo $srow['id']; ?>"
								class="shippingRadio"
								<?php echo $srow['id'] == $shippingDefault['id'] ? ' checked="checked"' : null; ?>
							/> <?php echo $srow['name']; ?>
						</label>
					</td>
					<td class="ta_r">
						<?php echo $this->objCurrency->display(number_format($srow['cost'], 2)); ?>
					</td>
				</tr>
			
			<?php } ?>
			
		<tbody class="rowHighlight">
			
			<tr>
				<td colspan="2" class="br_td">
					Sub-total:
				</td>
				<td class="ta_r br_td" id="basketSubTotal">
					<?php echo $this->objCurrency->display(number_format($objBasket->final_sub_total, 2)); ?>
				</td>
			</tr>
			
			<tr>
				<td colspan="2" class="br_td">
					VAT (<?php echo $objBasket->vat_rate; ?>%)
				</td>
				<td class="ta_r br_td" id="basketVat">
					<?php echo $this->objCurrency->display(number_format($objBasket->final_vat, 2)); ?>
				</td>
			</tr>
			
			<tr>
				<td colspan="2" class="br_td">
					<strong>Total:</strong>
				</td>
				<td class="ta_r br_td">
					<strong id="basketTotal"><?php echo $this->objCurrency->display(number_format($objBasket->final_total, 2)); ?></strong>
				</td>
			</tr>
			
		</tbody>
			
		</table>
		
		<div class="sbm sbm_blue fl_r paypal" 
			id="<?php echo $token1; ?>">
			<span class="btn">Proceed to PayPal</span>
		</div>
		
		<div class="sbm sbm_blue fl_l">
			<a href="<?php echo $this->objUrl->href('basket'); ?>" class="btn">Amend order</a>
		</div>
		
	</form>
	
	<div class="dev">&#160;</div>
	
</div>

<div class="dn">
	<img src="/images/loadinfo.net.gif" alt="Proceeding to PayPal" />	
</div>

<?php } else { ?>

	<p>Your basket is currently empty.</p>

<?php } ?>


<?php
                require_once("_footer.php");

            } else {
                require_once('error-shipping.php');
            }

        } else {
            require_once('error-shipping.php');
        }

    } else {
        Helper::redirect($this->objUrl->href('error'));
    }
	
} else {
	Helper::redirect($this->objUrl->href('error'));
}

?>