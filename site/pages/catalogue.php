<?php

use SSD\Catalogue;
use SSD\Paging;
use SSD\Helper;
use SSD\Basket;


$cat = $this->objUrl->get('category');

if(empty($cat)) {
	require_once("error.php");
} else {

	$objCatalogue = new Catalogue();
	$category = $objCatalogue->getCategoryByIdentity($cat);
	
	if (empty($category)) {
		require_once("error.php");
	} else {
		
		$this->meta_title = $category['meta_title'];
		$this->meta_description = $category['meta_description'];
		
		$rows = $objCatalogue->getProducts($category['id']);
		
		// instantiate paging class
		$objPaging = new Paging($this->objUrl, $rows, 3);
		$rows = $objPaging->getRecords();
		
		require_once("_header.php");
?>

<h1>Catalogue :: <?php echo $category['name']; ?></h1>

<?php
		if(!empty($rows)) {
			foreach($rows as $row) {
?>

<div class="catalogue_wrapper">
	<div class="catalogue_wrapper_left">
		<?php
		
			$image = !empty($row['image']) ? 
				$row['image'] :
				'unavailable.png';
			
			$width = Helper::getImgSize(CATALOGUE_PATH.DS.$image, 0);
			$width = $width > 120 ? 120 : $width;
			
			$link = $this->objUrl->href('catalogue-item', array(
				'category',
				$category['identity'],
				'item',
				$row['identity']
			));
			
		?>
		<a href="<?php echo $link; ?>"><img src="<?php echo DS.CATALOGUE_DIR.DS.$image; ?>" alt="<?php echo Helper::encodeHtml($row['name'], 1); ?>" width="<?php echo $width; ?>" /></a>
	</div>
	<div class="catalogue_wrapper_right">
		<h4><a href="<?php echo $link; ?>"><?php echo Helper::encodeHtml($row['name'], 1); ?></a></h4>
		<h4>Price: <?php echo $this->objCurrency->display(number_format($row['price'], 2)); ?></h4>
		<p><?php echo Helper::shortenString(Helper::encodeHtml($row['description'])); ?></p>
		<p><?php echo Basket::activeButton($row['id']); ?></p>
	</div>
</div>


<?php
			}
			
			echo $objPaging->getPaging();
			
		} else {
?>
<p>There are no products in this category.</p>
<?php		
		}
		require_once("_footer.php");
	
	}
}
?>