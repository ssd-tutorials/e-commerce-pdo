<?php

use SSD\Form;
use SSD\Validation;
use SSD\Catalogue;
use SSD\Helper;


$objForm = new Form();
$objValid = new Validation($objForm);

if ($objForm->isPost('name')) {
	
	$objValid->expected = array(
		'name',
		'identity',
		'meta_title',
		'meta_description'
	);	
	$objValid->required = array(
		'name',
		'identity',
		'meta_title',
		'meta_description'
	);
	
	$objCatalogue = new Catalogue();
	
	$name = $objForm->getPost('name');
	$identity = Helper::cleanString($objForm->getPost('identity'));
	
	if ($objCatalogue->duplicateCategory($name)) {
		$objValid->add2Errors('name_duplicate');
	}
	
	if ($objCatalogue->isDuplicateCategory($identity)) {
		$objValid->add2Errors('duplicate_identity');
	}
	
	if ($objValid->isValid()) {
	
		$objValid->post['identity'] = $identity;
		
		if ($objCatalogue->addCategory($objValid->post)) {
		
			Helper::redirect($this->objUrl->getCurrent(array('action', 'id')).'/action/added');
			
		} else {
			Helper::redirect($this->objUrl->getCurrent(array('action', 'id')).'/action/added-failed');
		}
		
	}
	
}

require_once('_header.php'); 
?>

<h1>Categories :: Add</h1>

<form action="" method="post">
	
	<table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
		
		<tr>
			<th><label for="name">Name: *</label></th>
			<td>
				<?php 
					echo $objValid->validate('name'); 
					echo $objValid->validate('name_duplicate'); 
				?>
				<input type="text" name="name" id="name" 
					value="<?php echo $objForm->stickyText('name'); ?>" 
					class="fld" />
			</td>
		</tr>
		
		<tr>
			<th><label for="identity">Identity: *</label></th>
			<td>
				<?php 
					echo $objValid->validate('identity'); 
					echo $objValid->validate('duplicate_identity'); 
				?>
				<input type="text" name="identity" id="identity" 
					value="<?php echo $objForm->stickyText('identity'); ?>" 
					class="fld" />
			</td>
		</tr>
		
		<tr>
			<th><label for="meta_title">Meta title: *</label></th>
			<td>
				<?php echo $objValid->validate('meta_title'); ?>
				<input type="text" name="meta_title" id="meta_title" 
					value="<?php echo $objForm->stickyText('meta_title'); ?>" 
					class="fld" />
			</td>
		</tr>
		
		<tr>
			<th><label for="meta_description">Meta description: *</label></th>
			<td>
				<?php echo $objValid->validate('meta_description'); ?>
				<textarea name="meta_description" id="meta_description"
					class="tar_fixed"><?php echo $objForm->stickyText('meta_description'); ?></textarea>
			</td>
		</tr>
				
		<tr>
			<th>&nbsp;</th>
			<td>
				<label for="btn" class="sbm sbm_blue fl_l">
					<input type="submit" id="btn" class="btn" value="Add" />
				</label>
			</td>
		</tr>
		
		
	</table>
	
</form>

<?php require_once('_footer.php'); ?>



