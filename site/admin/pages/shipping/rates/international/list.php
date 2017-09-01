<?php

use SSD\Plugin;


$shipping = $objShipping->getShippingByTypeCountry($id, $zid);

require_once('_header.php');

?>

<h1>Rates for : <?php echo $country['name']; ?> : <?php echo $type['name']; ?></h1>

<form method="post" class="ajax" data-action="<?php echo $this->objUrl->getCurrent('call', false, array('call', 'add')); ?>">

	<table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
		
		<tr>
			<th><label for="weight" class="valid_weight">Weight up to: *</label></th>
			<th><label for="cost" class="valid_cost">Cost: *</label></th>
		</tr>
		
		<tr>
			<td>
				<input
					type="text"
					name="weight"
					id="weight"
					class="fld fldSmall"
				/>
			</td>
			<td>
				<input
					type="text"
					name="cost"
					id="cost"
					class="fld fldSmall"
				/>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<label for="btn_add" class="sbm sbm_blue fl_l">
					<input type="submit" id="btn_add" class="btn" value="Add" />
				</label>
			</td>
		</tr>
		
	</table>

</form>

<div class="dev br_td">&nbsp;</div>

<div id="shippingList">
	<?php echo Plugin::get('admin'.DS.'shipping-cost', array('rows' => $shipping, 'objUrl' => $this->objUrl, 'objCurrency' => $this->objCurrency)); ?>
</div>

<?php require_once('_footer.php'); ?>









