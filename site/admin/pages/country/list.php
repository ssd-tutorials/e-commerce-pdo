<?php

use SSD\Plugin;


$countries = $objCountry->getAll();

require_once('_header.php');

?>

<h1>Countries</h1>

<form method="post" class="ajax" data-action="<?php echo $this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'add')); ?>">

	<table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
	
		<tr>
			<th><label for="name" class="valid_name">Country name: *</label></th>
		</tr>
		
		<tr>
			<td>
				<input 
					type="text"
					name="name"
					id="name"
					class="fld"
				/>
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="btn_add" class="sbm sbm_blue fl_l">
					<input type="submit" id="btn_add" class="btn" value="Add" />
				</label>
			</td>
		</tr>
	
	</table>

</form>

<div class="dev br_td">&nbsp;</div>

<form method="post" data-url="<?php echo $this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'update', 'id')).'/'; ?>">
	
	<div id="countryList">
		<?php echo Plugin::get('admin'.DS.'country', array('rows' => $countries, 'objUrl' => $this->objUrl)); ?>
	</div>
	
</form>

<?php require_once('_footer.php'); ?>















