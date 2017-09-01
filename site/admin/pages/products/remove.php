<?php

use SSD\Catalogue;
use SSD\Helper;
use SSD\Paging;


$id = $this->objUrl->get('id');

if (!empty($id)) {

	$objCatalogue = new Catalogue();
	$product = $objCatalogue->getProduct($id);
	
	if (!empty($product)) {
		
		$yes = $this->objUrl->getCurrent().'/remove/1';
		$no = 'javascript:history.go(-1)';
		
		$remove = $this->objUrl->get('remove');
		
		if (!empty($remove)) {
			
			$objCatalogue->removeProduct($id);
			
			Helper::redirect($this->objUrl->getCurrent(array('action', 'id', 'remove', 'srch', Paging::$key)));
			
		}
		
		require_once('_header.php'); 
?>
<h1>Products :: Remove</h1>
<p>Are you sure you want to remove this record?<br />
There is no undo!<br />
<a href="<?php echo $yes; ?>">Yes</a> | <a href="<?php echo $no; ?>">No</a></p>
<?php 
		require_once('_footer.php'); 
	}	
}
?>