<?php

use SSD\Order;
use SSD\Form;
use SSD\Validation;
use SSD\User;
use SSD\Catalogue;
use SSD\Helper;


$id = $this->objUrl->get('id');

if (!empty($id)) {
	
	$objOrder = new Order();
	$order = $objOrder->getOrder($id);
	
	if (!empty($order)) {
	
		$objForm = new Form();
		$objValid = new Validation($objForm);
				
		$objUser = new User();
		$user = $objUser->getUser($order['client']);
		
		$objCatalogue = new Catalogue();
		
		$items = $objOrder->getOrderItems($id);	
		
		$status = $objOrder->getStatuses();
		
			
			
		if ($objForm->isPost('status')) {
			
			$objValid->expected = array('status', 'notes');
			$objValid->required = array('status');
			
			$vars = $objForm->getPostArray($objValid->expected);
			
			if ($objValid->isValid()) {
				
				if ($objOrder->updateOrder($id, $vars)) {				
					Helper::redirect($this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'edited')));					
				} else {
					Helper::redirect($this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'edited-failed')));
				}
				
			}
			
		}
		
		require_once('_header.php'); 
?>
	
	<h1>Orders :: View</h1>
	
	<form action="" method="post">
		
		<table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
			
			<tr>
				<th>Date:</th>
				<td colspan="4"><?php echo Helper::setDate(2, $order['date']); ?></td>
			</tr>
			
			<tr>
				<th>Order no:</th>
				<td colspan="4"><?php echo $order['id']; ?></td></td>
			</tr>
			
			<?php if (!empty($items)) { ?>
			
				<tr>
					<th rowspan="<?php echo count($items) + 1; ?>">Items:</th>
					<td class="col_5">Id</td>
					<td>Item</td>
					<td class="col_5 ta_r">Qty</td>
					<td class="col_15 ta_r">Amount</td>
				</tr>
				
				<?php 
					foreach($items as $item) { 
						$product = $objCatalogue->getProduct($item['product']);
				?>
				
					<tr>
						<td><?php echo $product['id']; ?></td>
						<td><?php echo Helper::encodeHtml($product['name']); ?></td>
						<td class="ta_r"><?php echo $item['qty']; ?></td>
						<td class="ta_r"><?php echo $this->objCurrency->display(number_format(($item['price'] * $item['qty']), 2)); ?></td>
					</tr>
				
				<?php } ?>
			
			<?php } ?>
			
			<tr>
				<th>Shipping:</th>
				<td colspan="3">
					<?php echo Helper::encodeHtml($order['shipping_type']); ?>
				</td>
				<td class="ta_r">
					<?php echo $this->objCurrency->display(number_format($order['shipping_cost'], 2)); ?>
				</td>
			</tr>
			
			<tr>
				<th>Sub-total:</th>
				<td colspan="4" class="ta_r">
					<?php echo $this->objCurrency->display(number_format($order['subtotal'], 2)); ?>
				</td>
			</tr>
			
			<tr>
				<th>VAT (<?php echo $order['vat_rate']; ?>%):</th>
				<td colspan="4" class="ta_r">
					<?php echo $this->objCurrency->display(number_format($order['vat'], 2)); ?>
				</td>
			</tr>
			
			<tr>
				<th>Total:</th>
				<td colspan="4" class="ta_r">
					<strong><?php echo $this->objCurrency->display(number_format($order['total'], 2)); ?></strong>
				</td>
			</tr>
			
			<tr>				
				<th>Client:</th>
				<td colspan="4">
					<?php
						echo '<p>';
						echo Helper::encodeHtml($order['full_name']).'<br />';
						echo '<a href="mailto:';
						echo $user['email'];
						echo '">';
						echo $user['email'];
						echo '</a>';
						echo '</p>';
					?>
				</td>				
			</tr>
			
			<tr>
				<th>Billing:</th>
				<td colspan="4">
					<?php
						echo '<p>';
						echo Helper::encodeHtml($order['address']).'<br />';
						echo Helper::encodeHtml($order['town']).'<br />';
						echo Helper::encodeHtml($order['county']).'<br />';
						echo Helper::encodeHtml($order['post_code']).'<br />';
						echo Helper::encodeHtml($order['country_name']);
						echo '</p>';
					?>
				</td>
			</tr>
			
			<tr>
				<th>Shipping:</th>
				<td colspan="4">
					<?php
						echo '<p>';
						echo Helper::encodeHtml($order['ship_address']).'<br />';
						echo Helper::encodeHtml($order['ship_town']).'<br />';
						echo Helper::encodeHtml($order['ship_county']).'<br />';
						echo Helper::encodeHtml($order['ship_post_code']).'<br />';
						echo Helper::encodeHtml($order['ship_country_name']);
						echo '</p>';
					?>
				</td>
			</tr>
			
			<tr>
				<th>PP status:</th>
				<td colspan="4">
				<?php 
					echo !empty($order['payment_status']) ?
						Helper::encodeHtml($order['payment_status']) :
						"Pending";
				?>
				</td>
			</tr>
			
			<tr>
				<th><label for="status">Order status:</label></th>
				<td colspan="4">
					<?php $objValid->validate('status'); ?>
					<?php if (!empty($status)) { ?>
					<select name="status" id="status" class="sel">
						<?php foreach($status as $stat) { ?>
						<option value="<?php echo $stat['id']; ?>"
						<?php echo $objForm->stickySelect('status', $stat['id'], $order['status']); ?>><?php echo Helper::encodeHtml($stat['name']); ?></option>
						<?php } ?>
					</select>
					<?php } ?>
				</td>
			</tr>
			
			<tr>
				<th><label for="notes">Notes:</label></th>
				<td colspan="4">
					<textarea name="notes" id="notes" cols="" rows=""
					class="tar"><?php echo $objForm->stickyText('notes', $order['notes']); ?></textarea>
				</td>
			</tr>
			
			<tr>
				<th>&nbsp;</th>
				<td colspan="4">
				
					<div class="sbm sbm_blue fl_r">
						<a href="<?php echo $this->objUrl->getCurrent(array('action'), false, array('action', 'invoice')); ?>" class="btn" target="_blank">Invoice</a>
					</div>
					
					<div class="sbm sbm_blue fl_l mr_r4">
						<a href="<?php echo $this->objUrl->getCurrent(array('action', 'id')); ?>" class="btn">Go back</a>
					</div>
					
					<label for="btn_update" class="sbm sbm_blue fl_l">
						<input type="submit" id="btn_update" class="btn" value="Update" />
					</label>
					
				</td>
			</tr>
			
		</table>
		
	</form>

<?php 
		require_once('_footer.php'); 
	}
}
?>



