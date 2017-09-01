<?php

use SSD\Catalogue;
use SSD\Helper;
use SSD\Basket;


$id = $this->objUrl->get('item');

if (!empty($id)) {

	$objCatalogue = new Catalogue();
	$product = $objCatalogue->getProductByIdentity($id);
	
	if (!empty($product)) {
	
		$this->meta_title = $product['meta_title'];
		$this->meta_description = $product['meta_description'];
	
		$category = $objCatalogue->getCategory($product['category']);
		
		require_once('_header.php');
		
		echo "<h1>Catalogue :: ".$category['name']."</h1>";
		
		$image = !empty($product['image']) ? 
			$product['image'] : 
			'unavailable.png';
		
		$width = Helper::getImgSize(CATALOGUE_PATH.DS.$image, 0);
		$width = $width > 120 ? 120 : $width;
		echo "<div class=\"fl_l\">";
		echo "<div class=\"lft\"><img src=\"";		
		echo DS.CATALOGUE_DIR.DS.$image;
		echo "\" alt=\"";
		echo Helper::encodeHTML($product['name'], 1);
		echo "\" width=\"{$width}\" /></div>";
		
		echo "<div class=\"rgt\"><h3>".$product['name']."</h3>";
		echo "<h4><strong>";
        echo $this->objCurrency->display($product['price']);
        echo "</strong></h4>";
		echo Basket::activeButton($product['id']);
		echo "</div></div>";
		echo "<div class=\"dev\">&#160;</div>";
		echo "<p>".Helper::encodeHTML($product['description'])."</p>";
		echo "<div class=\"dev br_td\">&#160;</div>"; 
		echo "<p><a href=\"javascript:history.go(-1)\">Go back</a></p>";
		
		
		require_once('_footer.php');
		
		
	} else {
		require_once('error.php');	
	}

} else {
	require_once('error.php');
}