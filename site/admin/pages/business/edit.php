<?php

use SSD\Business;
use SSD\Country;
use SSD\Form;
use SSD\Validation;
use SSD\Helper;


$objBusiness = new Business();
$business = $objBusiness->getOne(Business::BUSINESS_ID);

$objCountry = new Country();
$countries = $objCountry->getCountries();

if (!empty($business)) {

	$objForm = new Form();
	$objValid = new Validation($objForm);
	
	if ($objForm->isPost('name')) {
	
		$objValid->expected = array(
			'name',
			'address',
			'country',
			'telephone',
			'email',
			'website',
			'vat_rate',
			'vat_number'
		);
		
		$objValid->required = array(
			'name',
			'address',
			'country',
			'telephone',
			'email',
			'vat_rate'
		);
		
		$objValid->special = array(
			'email' => 'email'
		);
		
		$vars = $objForm->getPostArray($objValid->expected);
		
		if ($objValid->isValid()) {
			if ($objBusiness->update($vars, Business::BUSINESS_ID)) {
				Helper::redirect($this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'edited')));
			} else {
				Helper::redirect($this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'edited-failed')));
			}
		}
		
	}
		
	require_once('_header.php'); 
?>
	
	<h1>Business</h1>
	
	<form action="" method="post">
		<table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
			
			<tr>
				<th><label for="name">Name: *</label></th>
				<td>
					<?php echo $objValid->validate('name'); ?>
					<input type="text" name="name"
						id="name" class="fld" 
						value="<?php echo $objForm->stickyText('name', $business['name']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="address">Address: *</label></th>
				<td>
					<?php echo $objValid->validate('address'); ?>
					<textarea name="address" id="address" class="tar" 
						cols="" rows=""><?php echo $objForm->stickyText('address', $business['address']); ?></textarea>
				</td>
			</tr>
			
			<?php if (!empty($countries)) { ?>
			
				<tr>
					<th><label for="country">Country: *</label></th>
					<td>
						<?php echo $objValid->validate('country'); ?>
						<select name="country" id="country" class="sel">
							<?php foreach($countries as $row) { ?>
								<option value="<?php echo $row['id']; ?>"
								<?php echo $objForm->stickySelect('country', $row['id'], $business['country']); ?>>
									<?php echo $row['name']; ?>
								</option>
							<?php } ?>
						</select>
					</td>
				</tr>
			
			<?php } ?>
			
			<tr>
				<th><label for="telephone">Telephone: *</label></th>
				<td>
					<?php echo $objValid->validate('telephone'); ?>
					<input type="text" name="telephone"
						id="telephone" class="fld" 
						value="<?php echo $objForm->stickyText('telephone', $business['telephone']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="email">Email: *</label></th>
				<td>
					<?php echo $objValid->validate('email'); ?>
					<input type="text" name="email"
						id="email" class="fld" 
						value="<?php echo $objForm->stickyText('email', $business['email']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="website">Website:</label></th>
				<td>
					<?php echo $objValid->validate('website'); ?>
					<input type="text" name="website"
						id="website" class="fld" 
						value="<?php echo $objForm->stickyText('website', $business['website']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="vat_rate">VAT Rate: *</label></th>
				<td>
					<?php echo $objValid->validate('vat_rate'); ?>
					<input type="text" name="vat_rate"
						id="vat_rate" class="fld" 
						value="<?php echo $objForm->stickyText('vat_rate', $business['vat_rate']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="vat_number">VAT Number:</label></th>
				<td>
					<?php echo $objValid->validate('vat_number'); ?>
					<input 
						type="text" 
						name="vat_number"
						id="vat_number"
						class="fld"
						value="<?php echo $objForm->stickyText('vat_number', $business['vat_number']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th>&nbsp;</th>
				<td>
					<label for="btn" class="sbm sbm_blue fl_l">
					<input type="submit"
						id="btn" class="btn" value="Update" />
					</label>
				</td>
			</tr>
			
		</table>
	</form>

<?php require_once('_footer.php'); } ?>



