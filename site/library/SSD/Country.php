<?php

namespace SSD;


class Country extends Application {

	protected $_table = 'countries';



	
	public function getCountries() {
		$sql = "SELECT * 
				FROM `{$this->_table}`
				WHERE `include` = ?
				ORDER BY `name` ASC";
		return $this->_Db->fetchAll($sql, 1);
	}
	
	
	
	
	
	
	
	public function getCountry($id = null) {
		if (!empty($id)) {
			$sql = "SELECT * 
					FROM `{$this->_table}`
					WHERE `id` = ?
					AND `include` = ?";
			return $this->_Db->fetchOne($sql, array($id, 1));
		}
        return null;
	}
	
	
	
	
	
	
	
	
	
	
	public function getAllExceptLocal() {
		$sql = "SELECT *
				FROM `{$this->_table}`
				WHERE `id` != ?
				ORDER BY `name` ASC";
		return $this->_Db->fetchAll($sql, COUNTRY_LOCAL);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function getAll() {
		$sql = "SELECT *
				FROM `{$this->_table}`
				ORDER BY `name` ASC";
		return $this->_Db->fetchAll($sql);
	}
	
	
	

	
	
	
	
	
	
	
	
	
	



}












