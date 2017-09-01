<?php

use SSD\Order;
use SSD\Business;


$id = $this->objUrl->get('id');

if (!empty($id)) {
	
	$objOrder = new Order();
	$order = $objOrder->getOrder($id);
	
	if (!empty($order)) {
		
		$items = $objOrder->getOrderItems($id);
		
		$objBusiness = new Business();
		$business = $objBusiness->getOne(Business::BUSINESS_ID);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Invoice</title>
<meta http-equiv="imagetoolbar" content="no" />
<link href="/css/invoice.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="wrapper">
	
	<h1>Invoice</h1>
	
	<div style="width:50%;float:left">		
		<p>
			<strong>Billing address:</strong>
			<?php
				echo $order['full_name'].'<br />';
				echo $order['address'].', '.$order['town'].'<br />';
				echo $order['county'].', '.$order['post_code'].'<br />';
				echo $order['country_name'];
			?>
		</p>
		<p>
			<strong>Shipping address:</strong>
			<?php
				echo $order['full_name'].'<br />';
				echo $order['ship_address'].', '.$order['ship_town'].'<br />';
				echo $order['ship_county'].', '.$order['ship_post_code'].'<br />';
				echo $order['ship_country_name'];
			?>
		</p>		
	</div>
	<div style="width:50%;float:right;text-align:right;">
		<p><strong><?php echo $business['name']; ?></strong><br />
		<?php echo nl2br($business['address']); ?><br />
		<?php echo $business['telephone']; ?><br />
		<?php echo $business['email']; ?><br />
		<?php echo $business['website']; ?>
		<?php 
			echo $order['vat_rate'] > 0 && !empty($order['vat_number']) ?
				'<br />VAT number: '.$order['vat_number'] : null;	
		?>
		</p>
	</div>
	
	<div class="dev">&#160;</div>
	
	<h3>Order number <?php echo $order['id']; ?> / Date <?php echo $order['date']; ?></h3>
	
	<table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">
		
		<tr>
			<th>Item</th>
			<th class="ta_r">Qty</th>
			<th class="ta_r col_15">Price</th>
		</tr>
		
		<?php foreach($items as $item) { ?>
		
			<tr>
				<td>
					<?php echo $item['name']; ?>
				</td>
				<td class="ta_r"><?php echo $item['qty']; ?></td>
				<td class="ta_r">
					<?php echo $this->objCurrency->display(number_format($item['price_total'], 2)); ?>
				</td>
			</tr>
		
		<?php } ?>
		
		<tbody class="summarySection">
		
			<tr>
				<td colspan="2" class="br_td">Items total:</td>
				<td class="ta_r br_td">
					<?php echo $this->objCurrency->display(number_format($order['subtotal_items'], 2)); ?>
				</td>
			</tr>
			
			<tr>
				<td colspan="2" class="br_td">Shipping: <?php echo $order['shipping_type']; ?></td>
				<td class="ta_r br_td">
					<?php echo $this->objCurrency->display(number_format($order['shipping_cost'], 2)); ?>
				</td>
			</tr>
			
				
			<tr>
				<td colspan="2" class="br_td">Sub-total:</td>
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
				<td colspan="2" class="br_td"><strong>Total:</strong></td>
				<td class="ta_r br_td">
					<strong><?php echo $this->objCurrency->display(number_format($order['total'], 2)); ?></strong>
				</td>
			</tr>
			
		</tbody>
		
	</table>
	
	<div class="dev br_td">&nbsp;</div>
	
	<p><a href="#" onclick="window.print(); return false;">Print this invoice</a></p>
	
</div>

</body>
</html>

<?php } } ?>