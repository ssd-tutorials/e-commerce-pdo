<?php

use SSD\Login;
use SSD\Session;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Ecommerce website project</title>
<meta name="description" content="Ecommerce website project" />
<meta http-equiv="imagetoolbar" content="no" />
<link href="/css/core.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
	<div id="header_in">
		<h5><a href="/">Content Management System</a></h5>
		<?php
			if (Login::isLogged(Login::$login_admin)) {
				echo '<div id="logged_as">Logged in as: <strong>';
				echo $this->objAdmin->getFullNameAdmin(Session::getSession(Login::$login_admin));
				echo '</strong> | <a href="/panel/logout">Logout</a></div>';				
			} else {
				echo '<div id="logged_as"><a href="/panel/">Login</a></div>';
			}
		?>
	</div>
</div>
<div id="outer">
	<div id="wrapper">
		<div id="left">
			<?php if (Login::isLogged(Login::$login_admin)) { ?>
			<h2>Navigation</h2>
			<div class="dev br_td">&nbsp;</div>
			<ul id="navigation">
				<li>
					<a href="/panel/products"
					<?php echo $this->objNavigation->active('products'); ?>>
					products
					</a>
				</li>
				<li>
					<a href="/panel/categories"
					<?php echo $this->objNavigation->active('categories'); ?>>
					categories
					</a>
				</li>
				<li>
					<a href="/panel/orders"
					<?php echo $this->objNavigation->active('orders'); ?>>
					orders
					</a>
				</li>
				<li>
					<a href="/panel/clients"
					<?php echo $this->objNavigation->active('clients'); ?>>
					clients
					</a>
				</li>
				<li>
					<a href="/panel/business"
					<?php echo $this->objNavigation->active('business'); ?>>
					business
					</a>
				</li>
				<li>
					<a href="/panel/shipping"
					<?php echo $this->objNavigation->active('shipping'); ?>>
					shipping
					</a>
				</li>
				<li>
					<a href="/panel/zone"
					<?php echo $this->objNavigation->active('zone'); ?>>
					zones
					</a>
				</li>
				<li>
					<a href="/panel/country"
					<?php echo $this->objNavigation->active('country'); ?>>
					countries
					</a>
				</li>
			</ul>				
			<?php } else { ?>
				&nbsp;
			<?php } ?>
		</div>
		<div id="right">
		
		
		
		
		
		
		
		
		