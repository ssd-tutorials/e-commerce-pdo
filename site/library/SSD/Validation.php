<?php

namespace SSD;

class Validation {

	// form object
	private $objForm;
	
	// for storing all error ids
	private $_errors = array();
	
	// collected error messages
	public $errorsMessages = array();
	
	// validation messages
	public $message = array(
		"first_name"			=> "Please provide your first name",
		"last_name"				=> "Please provide your last name",
		"address_1"				=> "Please provide the first line of your address",
		"address_2"				=> "Please provide the second line of your address",
		"town"					=> "Please provide your town name",
		"county"				=> "Please provide your county name",
		"post_code"				=> "Please provide your post code",
		"country"				=> "Please select your country",
		
		"same_address"			=> "Please select one option",
		"ship_address_1"		=> "Please provide the first line of the address",
		"ship_address_2"		=> "Please provide the second line of the address",
		"ship_town"				=> "Please provide a town name",
		"ship_county"			=> "Please provide a county name",
		"ship_post_code"		=> "Please provide a post code",
		"ship_country"			=> "Please select a country",
		
		"email"					=> "Please provide your valid email address",
		"email_duplicate"		=> "This email address is already taken",
		"login"					=> "Username and / or password were incorrect",
		"password"				=> "Please choose your password",
		"confirm_password"		=> "Please confirm your password",
		"password_mismatch"		=> "Passwords did not match",
		"category"				=> "Please select the category",
		"name"					=> "Please provide a name",
		"description"			=> "Please provide a description",
		"price"					=> "Please provide a price",
		"name_duplicate"		=> "This name is already taken",
		
		"identity"				=> "Please provide the identity",
		"duplicate_identity"	=> "This identity is already taken",
		"meta_title"			=> "Please provide the meta title",
		"meta_description"		=> "Please provide the meta description",
		"meta_keywords"			=> "Please provide the meta keywords",
		
		"weight"				=> "Please provide the weight",
		"cost"					=> "Please provide the cost"
		
	);
	
	// list of expected fields
	public $expected = array();
	
	// list of require fields
	public $required = array();
	
	// list of special validation fields
	// array('field_name' => 'format')
	public $special = array();
	
	// post array
	public $post = array();
	
	// fields to be removed from the $_post array
	public $post_remove = array();
	
	// fields to be specifically formatted
	// array('field_name' => 'format'
	public $post_format = array();
	
	
	
	
	
	
	public function __construct($objForm = null) {
		$this->objForm = is_object($objForm) ? $objForm : new Form();
	}
	
	
	
	
	
	
	
	
	public function process() {
		if ($this->objForm->isPost()) {
			// get only expected fields
			$this->post = !empty($this->post) ?
				$this->post : $this->objForm->getPostArray($this->expected);
			if (!empty($this->post)) {
				foreach($this->post as $key => $value) {
					$this->check($key, $value);
				}
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	public function add2Errors($key = null, $value = null) {
		if (!empty($key)) {
			$this->_errors[] = $key;
			if (!empty($value)) {
				$this->errorsMessages['valid_'.$key] = $this->wrapWarn($value);
			} else if (array_key_exists($key, $this->message)) {
				$this->errorsMessages['valid_'.$key] = $this->wrapWarn($this->message[$key]);
			}
		}		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function check($key, $value) {
		if (!empty($this->special) && array_key_exists($key, $this->special)) {
			$this->checkSpecial($key, $value);
		} else {
			if (in_array($key, $this->required) && Helper::isEmpty($value)) {
				$this->add2Errors($key);
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	public function checkSpecial($key, $value) {
		switch($this->special[$key]) {
			case 'email':
			if (!$this->isEmail($value)) {
				$this->add2Errors($key);
			}
			break;
		}
	}
	
	
	
	
	
	
	
	
	
	
	public function isEmail($email = null) {
		if (!empty($email)) {
			$result = filter_var($email, FILTER_VALIDATE_EMAIL);
			return !$result ? false : true;
		}
		return false;
	}
	
	
	
	
	
	
	
	
	
	public function isValid($array = null) {
		if (!empty($array)) {
			$this->post = $array;
		}
		$this->process();
		if (empty($this->_errors) && !empty($this->post)) {
			// remove all unwanted fields
			if (!empty($this->post_remove)) {
				foreach($this->post_remove as $value) {
					unset($this->post[$value]);
				}
			}
			// format all required fields
			if (!empty($this->post_format)) {
				foreach($this->post_format as $key => $value) {
					$this->format($key, $value);
				}
			}
			return true;
		}
		return false;
	}
	
	
	
	
	
	
	
	
	
	
	
	public function format($key, $value) {
		switch($value) {
			case 'password':
			$this->post[$key] = Login::string2hash($this->post[$key]);
			break;
		}
	}
	
	
	
	
	
	
	
	
	
	public function validate($key) {
		if (!empty($this->_errors) && in_array($key, $this->_errors)) {
			return $this->wrapWarn($this->message[$key]);
		}
	}
	
	
	
	
	
	
	
	
	public function wrapWarn($mess = null) {
		if (!empty($mess)) {
			return "<span class=\"warn\">{$mess}</span>";
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	




}