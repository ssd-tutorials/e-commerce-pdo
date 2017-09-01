<?php

use SSD\Catalogue;
use SSD\Form;
use SSD\Validation;
use SSD\Helper;
use SSD\Upload;


$id = $this->objUrl->get('id');

if (!empty($id)) {
	
	$objCatalogue = new Catalogue();
	$product = $objCatalogue->getProduct($id);
	
	if (!empty($product)) {
	
		$objForm = new Form();
		$objValid = new Validation($objForm);
		$categories = $objCatalogue->getCategories();
			
		if ($objForm->isPost('name')) {
			
			$objValid->expected = array(
				'category',
				'name',
				'description',
				'price',
				'weight',
				'identity',
				'meta_title',
				'meta_description'
			);
			
			$objValid->required = array(
				'category',
				'name',
				'description',
				'price',
				'weight',
				'identity',
				'meta_title',
				'meta_description'
			);
			
			if ($objValid->isValid()) {
			
				$objValid->post['identity'] = Helper::cleanString($objValid->post['identity']);
		
				if ($objCatalogue->isDuplicateProduct($objValid->post['identity'], $id)) {
					$objValid->add2Errors('duplicate_identity');
				} else {
				
					if ($objCatalogue->updateProduct($objValid->post, $id)) {
					
						$objUpload = new Upload();
						
						if ($objUpload->upload(CATALOGUE_PATH)) {
						
							if (is_file(CATALOGUE_PATH.DS.$product['image'])) {
								unlink(CATALOGUE_PATH.DS.$product['image']);
							}
						
							$objCatalogue->updateProduct(array('image' => $objUpload->names[0]), $id);
							Helper::redirect($this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'edited')));
						} else {
							Helper::redirect($this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'edited-no-upload')));
						}
						
					} else {
						Helper::redirect($this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'edited-failed')));
					}
					
				}
				
			}
			
		}
		
		require_once('_header.php'); 
?>
	
	<h1>Products :: Edit</h1>
	
	<form action="" method="post" enctype="multipart/form-data">
		
		<table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
			
			<tr>
				<th><label for="category">Category: *</label></th>
				<td>
					<?php echo $objValid->validate('category'); ?>
					<select name="category" id="category" class="sel">
						<option value="">Select one&hellip;</option>
						<?php if (!empty($categories)) { ?>
							<?php foreach($categories as $cat) { ?>
							<option value="<?php echo $cat['id']; ?>"
							<?php echo $objForm->stickySelect('category', $cat['id'], $product['category']); ?>>
							<?php echo Helper::encodeHtml($cat['name']); ?>
							</option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>
			</tr>
			
			<tr>
				<th><label for="name">Name: *</label></th>
				<td>
					<?php echo $objValid->validate('name'); ?>
					<input type="text" name="name" id="name" 
						value="<?php echo $objForm->stickyText('name', $product['name']); ?>" class="fld" />
				</td>
			</tr>
			
			<tr>
				<th><label for="description">Description: *</label></th>
				<td>
					<?php echo $objValid->validate('description'); ?>
					<textarea name="description" id="description" cols="" rows=""
						class="tar_fixed"><?php echo $objForm->stickyText('description', $product['description']); ?></textarea>
				</td>
			</tr>
			
			<tr>
				<th><label for="price">Price: *</label></th>
				<td>
					<?php echo $objValid->validate('price'); ?>
					<input type="text" name="price" id="price" 
						value="<?php echo $objForm->stickyText('price', $product['price']); ?>" class="fld_price" />
				</td>
			</tr>
			
			<tr>
				<th><label for="weight">Weight: *</label></th>
				<td>
					<?php echo $objValid->validate('weight'); ?>
					<input type="text" name="weight" id="weight" 
						value="<?php echo $objForm->stickyText('weight', $product['weight']); ?>" class="fld_price" />
				</td>
			</tr>
			
			<tr>
				<th><label for="identity">Identity: *</label></th>
				<td>
					<?php echo $objValid->validate('identity'); ?>
					<?php echo $objValid->validate('duplicate_identity'); ?>
					<input type="text" name="identity" id="identity" 
						value="<?php echo $objForm->stickyText('identity', $product['identity']); ?>" class="fld" />
				</td>
			</tr>
			
			<tr>
				<th><label for="meta_title">Meta title: *</label></th>
				<td>
					<?php echo $objValid->validate('meta_title'); ?>
					<input type="text" name="meta_title" id="meta_title" 
						value="<?php echo $objForm->stickyText('meta_title', $product['meta_title']); ?>" class="fld" />
				</td>
			</tr>
			
			<tr>
				<th><label for="meta_description">Meta description: *</label></th>
				<td>
					<?php echo $objValid->validate('meta_description'); ?>
					
					<textarea name="meta_description" id="meta_description"
						class="tar_fixed"><?php echo $objForm->stickyText('meta_description', $product['meta_description']); ?></textarea>
				</td>
			</tr>
			
			<tr>
				<th><label for="image">Image:</label></th>
				<td>
					<?php echo $objValid->validate('image'); ?>
					<input type="file" name="image" id="image" size="30" />
				</td>
			</tr>
			
			<tr>
				<th>&nbsp;</th>
				<td>
					<label for="btn" class="sbm sbm_blue fl_l">
						<input type="submit" id="btn" class="btn" value="Update" />
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