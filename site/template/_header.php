<?php

use SSD\Catalogue;
use SSD\Business;
use SSD\Login;
use SSD\Session;
use SSD\Helper;
use SSD\Plugin;


$objCatalogue = new Catalogue();
$cats = $objCatalogue->getCategories();

$objBusiness = new Business();
$business = $objBusiness->getOne(Business::BUSINESS_ID);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $this->meta_title; ?></title>
<meta name="description" content="<?php echo $this->meta_description; ?>" />
<meta http-equiv="imagetoolbar" content="no" />
<link href="/css/core.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
	<div id="header_in">
		<h5><a href="/"><?php echo $business['name']; ?></a></h5>
		<?php
			if (Login::isLogged(Login::$login_front)) {
				echo '<div id="logged_as">Logged in as: <strong>';
				echo Login::getFullNameFront(Session::getSession(Login::$login_front));
				echo '</strong> | <a href="';
				echo $this->objUrl->href('orders');
				echo '">My orders</a>';
				echo ' | <a href="';
				echo $this->objUrl->href('logout');
				echo '">Logout</a></div>';				
			} else {
				echo '<div id="logged_as"><a href="';
				echo $this->objUrl->href('login');
				echo '">Login</a></div>';
			}
		?>
	</div>
</div>
<div id="outer">
	<div id="wrapper">
		<div id="left">
			<?php 
				if ($this->objUrl->cpage != 'summary') {
					echo Plugin::get('front'.DS.'basket_left', array(
                        'objUrl' => $this->objUrl,
                        'objCurrency' => $this->objCurrency
                    ));
				}
			?>
			<?php if (!empty($cats)) { ?>
			<h2>Categories</h2>
			<ul id="navigation">
				<?php 
					foreach($cats as $cat) {
						echo '<li><a href="';
						echo $this->objUrl->href('catalogue', array('category', $cat['identity']));
						echo '"';
						echo $this->objNavigation->active('catalogue', array('category' => $cat['identity']));
						echo '>';
						echo Helper::encodeHtml($cat['name']);
						echo '</a></li>';
					}
				?>
				</ul>
			<?php } ?>					
		</div>
		<div id="right">
		
		
		
		