<?php

// local country id
defined("COUNTRY_LOCAL")
	|| define("COUNTRY_LOCAL", 229);

// whether the vat should apply to sales outside of the local country
defined("INTERNATIONAL_VAT")
	|| define("INTERNATIONAL_VAT", false);

defined("PAGE_EXT")
	|| define("PAGE_EXT", ".html");

// site domain name with http
defined("SITE_URL")
	|| define("SITE_URL", "http://".$_SERVER['SERVER_NAME']);

// root path
defined("ROOT_PATH")
	|| define("ROOT_PATH", realpath(dirname(__FILE__) . DS."..".DS));
	
// classes folder
defined("CLASSES_DIR")
	|| define("CLASSES_DIR", "library");
	
// classes path
defined("CLASSES_PATH")
	|| define("CLASSES_PATH", ROOT_PATH.DS.CLASSES_DIR);

// plugin path
defined("PLUGIN_PATH")
	|| define("PLUGIN_PATH", ROOT_PATH.DS."plugin");

// pages directory
defined("PAGES_DIR")
	|| define("PAGES_DIR", "pages");

// modules folder
defined("MOD_DIR")
	|| define("MOD_DIR", "mod");
	
// inc folder
defined("INC_DIR")
	|| define("INC_DIR", "inc");
	
// templates folder
defined("TEMPLATE_DIR")
	|| define("TEMPLATE_DIR", "template");
	
// emails path
defined("EMAILS_PATH")
	|| define("EMAILS_PATH", ROOT_PATH.DS."emails");




// catalogue images directory
defined("CATALOGUE_DIR")
    || define("CATALOGUE_DIR", "media".DS."catalogue");

// catalogue images path
defined("CATALOGUE_PATH")
	|| define("CATALOGUE_PATH", ROOT_PATH.DS.CATALOGUE_DIR);




# SMTP

defined("SMTP_USE")
    || define("SMTP_USE", false);

defined("SMTP_HOST")
    || define("SMTP_HOST", '');

defined("SMTP_USERNAME")
    || define("SMTP_USERNAME", '');

defined("SMTP_PASSWORD")
    || define("SMTP_PASSWORD", '');

defined("SMTP_PORT")
    || define("SMTP_PORT", '');

defined("SMTP_SSL")
    || define("SMTP_SSL", '');





# DATABASE

defined("DB_HOST")
    || define("DB_HOST", 'localhost');

defined("DB_NAME")
    || define("DB_NAME", '');

defined("DB_USER")
    || define("DB_USER", '');

defined("DB_PASS")
    || define("DB_PASS", '');






	
// add all above directories to the include path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(ROOT_PATH.DS.INC_DIR),
    realpath(CLASSES_PATH),
	get_include_path()
)));











