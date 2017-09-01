<?php

use SSD\Session;
use SSD\Order;
use SSD\Helper;


Session::clear('basket');

$token = $this->objUrl->get('token');

if (!empty($token)) {

	$objOrder = new Order();
	$order = $objOrder->getOrderByToken($token);
	
	if (!empty($order)) {
	
		$items = $objOrder->getOrderItems($order['id']);

		require_once('_header.php'); 
?>
<h1>Thank you</h1>
<p>
	Your order has been received successfully and we are now processing it.<br />
	The following is the summary of your order.
</p>

<div class="dev br_td">&nbsp;</div>

<p>
	<strong>Your goods will be delivered to:</strong><br />
	<?php echo $order['full_name']; ?>, 
	<?php echo $order['ship_address']; ?>, 
	<?php echo $order['ship_town']; ?>, 
	<?php echo $order['ship_county']; ?>, 
	<?php echo $order['ship_post_code']; ?>, 
	<?php echo $order['ship_country_name']; ?>
</p>

<p><strong>Order number <?php echo $order['id']; ?> / Date <?php echo $order['date_formatted']; ?></strong></p>

<table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat br_bd">

	<tr>
		<th>Item</th>
		<th class="ta_r">Qty</th>
		<th class="ta_r col_15">Price</th>
	</tr>
	
	<?php foreach($items as $item) { ?>
		
		<tr>
			<td><?php echo $item['name']; ?></td>
			<td class="ta_r"><?php echo $item['qty']; ?></td>
			<td class="ta_r"><?php echo $this->objCurrency->display(number_format($item['price_total'], 2)); ?></td>
		</tr>
		
	<?php } ?>
	
	<tbody class="rowHighlight">
		
		<tr>
			<td colspan="2" class="br_td">
				<i>Items total:</i>
			</td>
			<td class="ta_r br_td">
				<i><?php echo $this->objCurrency->display(number_format($order['subtotal_items'], 2)); ?></i>
			</td>
		</tr>
		
		<tr>
			<td colspan="2" class="br_td">
				Shipping: <?php echo $order['shipping_type']; ?>
			</td>
			<td class="ta_r br_td">
				<?php echo $this->objCurrency->display(number_format($order['shipping_cost'], 2)); ?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2" class="br_td">
				Sub-total:
			</td>
			<td class="ta_r br_td">
				<?php echo $this->objCurrency->display(number_format($order['subtotal'], 2)); ?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2" class="br_td">
				VAT (<?php echo $order['vat_rate']; ?>%):
			</td>
			<td class="ta_r br_td">
				<?php echo $this->objCurrency->display(number_format($order['vat'], 2)); ?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2" class="br_td">
				Total:
			</td>
			<td class="ta_r br_td">
				<strong><?php echo $this->objCurrency->display(number_format($order['total'], 2)); ?></strong>
			</td>
		</tr>
		
	</tbody>

</table>

<p><a href="#" onclick="window.print(); return false;">Print confirmation</a></p>

<?php 
		require_once('_footer.php'); 
	} else {
		Helper::redirect($this->objUrl->href('error'));
	}		
} else {
	Helper::redirect($this->objUrl->href('error'));
}
?>














