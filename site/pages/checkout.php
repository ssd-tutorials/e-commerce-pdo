<?php

use SSD\Login;
use SSD\User;
use SSD\Form;
use SSD\Validation;
use SSD\Session;
use SSD\Helper;


Login::restrictFront($this->objUrl);

$objUser = new User();
$user = $objUser->getUser(Session::getSession(Login::$login_front));

if (!empty($user)) {

	$objForm = new Form();
	$objValid = new Validation($objForm);
	
	if ($objForm->isPost('first_name')) {
		
		$objValid->expected = array(
			'first_name',
			'last_name',
			'address_1',
			'address_2',
			'town',
			'county',
			'post_code',
			'country',
			'email',
			
			'same_address',
			'ship_address_1',
			'ship_address_2',
			'ship_town',
			'ship_county',
			'ship_post_code',
			'ship_country'
		);
		
		$objValid->required = array(
			'first_name',
			'last_name',
			'address_1',
			'town',
			'county',
			'post_code',
			'country',
			'email',
			'same_address'
		);
		
		$objValid->special = array(
			'email' => 'email'
		);
		
		
		$array = $objForm->getPostArray($objValid->expected);
		
		if (empty($array['same_address'])) {
			
			$objValid->required[] = 'ship_address_1';
			$objValid->required[] = 'ship_town';
			$objValid->required[] = 'ship_county';
			$objValid->required[] = 'ship_post_code';
			$objValid->required[] = 'ship_country';
			
		} else {
			
			$array['ship_address_1'] = null;
			$array['ship_address_2'] = null;
			$array['ship_town'] = null;
			$array['ship_county'] = null;
			$array['ship_post_code'] = null;
			$array['ship_country'] = null;
			
		}
		
		
		if ($objValid->isValid($array)) {
			
			if ($objUser->updateUser($objValid->post, $user['id'])) {
				Helper::redirect($this->objUrl->href('summary'));
			} else {
				$mess  = "<p class=\"red\">There was a problem updating your details.<br />";
				$mess .= "Please contact administrator.</p>";
			}
			
		}
		
	}
	
	require_once('_header.php'); 
	?>
	
	<h1>Checkout</h1>
	
	<p>Please check your details and click <strong>Next</strong>.</p>
	
	<?php echo !empty($mess) ? $mess : null; ?>
	
	<form action="" method="post">
		<table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
			
			<tbody id="billingAddress">
				
			<tr>
				<th><label for="first_name">First name: *</label></th>
				<td>
					<?php echo $objValid->validate('first_name'); ?>
					<input type="text" name="first_name"
						id="first_name" class="fld" 
						value="<?php echo $objForm->stickyText('first_name', $user['first_name']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="last_name">Last name: *</label></th>
				<td>
					<?php echo $objValid->validate('last_name'); ?>
					<input type="text" name="last_name"
						id="last_name" class="fld" 
						value="<?php echo $objForm->stickyText('last_name', $user['last_name']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="address_1">Address 1: *</label></th>
				<td>
					<?php echo $objValid->validate('address_1'); ?>
					<input type="text" name="address_1"
						id="address_1" class="fld" 
						value="<?php echo $objForm->stickyText('address_1', $user['address_1']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="address_2">Address 2:</label></th>
				<td>
					<?php echo $objValid->validate('address_2'); ?>
					<input type="text" name="address_2"
						id="address_2" class="fld" 
						value="<?php echo $objForm->stickyText('address_2', $user['address_2']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="town">Town: *</label></th>
				<td>
					<?php echo $objValid->validate('town'); ?>
					<input type="text" name="town"
						id="town" class="fld" 
						value="<?php echo $objForm->stickyText('town', $user['town']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="county">County: *</label></th>
				<td>
					<?php echo $objValid->validate('county'); ?>
					<input type="text" name="county"
						id="county" class="fld" 
						value="<?php echo $objForm->stickyText('county', $user['county']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="post_code">Post code: *</label></th>
				<td>
					<?php echo $objValid->validate('post_code'); ?>
					<input type="text" name="post_code"
						id="post_code" class="fld" 
						value="<?php echo $objForm->stickyText('post_code', $user['post_code']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="country">Country: *</label></th>
				<td>
					<?php echo $objValid->validate('country'); ?>
					<?php echo $objForm->getCountriesSelect($user['country']); ?>
				</td>
			</tr>
			
			<tr>
				<th><label for="email">Email address: *</label></th>
				<td>
					<?php echo $objValid->validate('email'); ?>
					<input type="text" name="email"
						id="email" class="fld" 
						value="<?php echo $objForm->stickyText('email', $user['email']); ?>" />
				</td>
			</tr>
			
			<tr>
				<th>&nbsp;</th>
				<td>Is delivery address the same?: *</td>
			</tr>
			
			<tr>
				<th>&nbsp;</th>
				<td>
					<?php echo $objValid->validate('same_address'); ?>
					<label for="same_address_1">
						<input 
							type="radio"
							name="same_address"
							id="same_address_1"
							value="1"
							class="showHideRadio"
							<?php echo $objForm->stickyRadio('same_address', 1, $user['same_address']); ?> /> Yes
					</label>
					<label for="same_address_0">
						<input 
							type="radio"
							name="same_address"
							id="same_address_0"
							value="0"
							class="showHideRadio"
							<?php echo $objForm->stickyRadio('same_address', 0, $user['same_address']); ?> /> No
					</label>
				</td>
			</tr>
			
			</tbody>
			
			<tbody id="deliveryAddress" class="same_address<?php echo $objForm->stickyRemoveClass('same_address', 0, $user['same_address'], 'dn'); ?>">
			
				<tr>
					<th><label for="ship_address_1">Address 1: *</label></th>
					<td>
						<?php echo $objValid->validate('ship_address_1'); ?>
						<input type="text" name="ship_address_1"
							id="ship_address_1" class="fld" 
							value="<?php echo $objForm->stickyText('ship_address_1', $user['ship_address_1']); ?>" />
					</td>
				</tr>
				
				<tr>
					<th><label for="ship_address_2">Address 2:</label></th>
					<td>
						<?php echo $objValid->validate('ship_address_2'); ?>
						<input type="text" name="ship_address_2"
							id="ship_address_2" class="fld" 
							value="<?php echo $objForm->stickyText('ship_address_2', $user['ship_address_2']); ?>" />
					</td>
				</tr>
				
				<tr>
					<th><label for="ship_town">Town: *</label></th>
					<td>
						<?php echo $objValid->validate('ship_town'); ?>
						<input type="text" name="ship_town"
							id="ship_town" class="fld" 
							value="<?php echo $objForm->stickyText('ship_town', $user['ship_town']); ?>" />
					</td>
				</tr>
				
				<tr>
					<th><label for="ship_county">County: *</label></th>
					<td>
						<?php echo $objValid->validate('ship_county'); ?>
						<input type="text" name="ship_county"
							id="ship_county" class="fld" 
							value="<?php echo $objForm->stickyText('ship_county', $user['ship_county']); ?>" />
					</td>
				</tr>
				
				<tr>
					<th><label for="ship_post_code">Post code: *</label></th>
					<td>
						<?php echo $objValid->validate('ship_post_code'); ?>
						<input type="text" name="ship_post_code"
							id="ship_post_code" class="fld" 
							value="<?php echo $objForm->stickyText('ship_post_code', $user['ship_post_code']); ?>" />
					</td>
				</tr>
				
				<tr>
					<th><label for="ship_country">Country: *</label></th>
					<td>
						<?php echo $objValid->validate('ship_country'); ?>
						<?php echo $objForm->getCountriesSelect($user['ship_country'], 'ship_country'); ?>
					</td>
				</tr>
			
			</tbody>
			
			<tr>
				<th>&nbsp;</th>
				<td>
					<label for="btn" class="sbm sbm_blue fl_l">
					<input type="submit"
						id="btn" class="btn" value="Next" />
					</label>
				</td>
			</tr>
			
		</table>
	</form>

<?php 
	require_once('_footer.php'); 
} else {

	Helper::redirect($this->objUrl->href('error'));

}
	
?>