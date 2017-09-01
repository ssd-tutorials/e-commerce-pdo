<?php

namespace SSD;


class Catalogue extends Application {

	protected $_table = 'categories';
    protected $_table_2 = 'products';
	
	
	
	
	
	
	
	
	
	public function __construct() {

		parent::__construct();

	}
	
	
	
	
	
	
	
	
	
	
	public function getCategoryByIdentity($identity = null) {
		if (!empty($identity)) {
			$sql = "SELECT *
			        FROM `{$this->_table}`
					WHERE `identity` = ?";
			return $this->_Db->fetchOne($sql, $identity);
		}
        return null;
	}
	
	
	
	
	
	
	
	
	
	
	public function getProductByIdentity($identity = null) {
		if (!empty($identity)) {
			$sql = "SELECT *
			        FROM `{$this->_table_2}`
					WHERE `identity` = ?";
			return $this->_Db->fetchOne($sql, $identity);
		}
        return null;
	}
	
	
	
	
	
	
	
	
	
	
	public function getCategories() {
		$sql = "SELECT *
                FROM `{$this->_table}`
				ORDER BY `name` ASC";
		return $this->_Db->fetchAll($sql);
	}
	
	
	
	
	
	
	
	
	public function getCategory($id = null) {
		if (!empty($id)) {
			$sql = "SELECT `c`.*,
					(
						SELECT COUNT(`id`)
						FROM `{$this->_table_2}`
						WHERE `category` = `c`.`id`
					) AS `products_count`
					FROM `{$this->_table}` `c`
					WHERE `c`.`id` = ?";
			return $this->_Db->fetchOne($sql, $id);
		}
        return null;
	}
	
	
	
	
	
	
	
	
	public function addCategory($array = null) {
		if (!Helper::isArrayEmpty($array)) {
            return $this->_Db->insert($this->_table, array(
                'name' => $array['name'],
                'identity' => $array['identity'],
                'meta_title' => $array['meta_title'],
                'meta_description' => $array['meta_description']
            ));
		}
        return false;
	}
	
	
	
	
	
	
	
	
	
	public function updateCategory($array = null, $id = null) {
        if (!Helper::isArrayEmpty($array) && !empty($id)) {
            return $this->_Db->update($this->_table, array(
                'name' => $array['name'],
                'identity' => $array['identity'],
                'meta_title' => $array['meta_title'],
                'meta_description' => $array['meta_description']
            ), $id);
        }
        return false;
	}
	
	
	
	
	
	
	
	
	
	
	public function duplicateCategory($name = null, $id = null) {
		if (!empty($name)) {
            $array = array($name);
			$sql = "SELECT *
			        FROM `{$this->_table}`
					WHERE `name` = ?";
			if (!empty($id)) {
                $array[] = $id;
			    $sql .= " AND `id` != ?";
            }
			return $this->_Db->fetchOne($sql, $array);
		}
		return false;
	}
	
	
	
	
	
	
	
	
	
	public function removeCategory($id = null) {
		return $this->delete($id);
	}
	
	
	
	
	
	
	
	
	
	
	
	public function getProducts($cat = null) {
		$sql = "SELECT *
		        FROM `{$this->_table_2}`
				WHERE `category` = ?
				ORDER BY `date` DESC";
		return $this->_Db->fetchAll($sql, $cat);
	}
	
	
	
	
	
	
	
	
	
	public function getProduct($id = null) {
		return $this->_Db->selectOne($this->_table_2, $id);
	}
	
	
	
	
	
	
	
	
	public function getAllProducts($srch = null) {
        $array = array();
		$sql = "SELECT *
		        FROM `{$this->_table_2}`";
		if (!empty($srch)) {
			$sql .= " WHERE `name` LIKE ? || `id` = ?";
            $array[] = "%{$srch}%";
            $array[] = "%{$srch}%";
		}
		$sql .= " ORDER BY `date` DESC";
		return $this->_Db->fetchAll($sql, $array);
	}
	
	
	
	
	
	public function addProduct($params = null) {
		if ($this->_Db->insert($this->_table_2, $params)) {
            $this->id = $this->_Db->id;
            return true;
        }
        return false;
	}
	
	
	
	
	
	
	
	public function updateProduct($params = null, $id = null) {
		return $this->_Db->update($this->_table_2, $params, $id);
	}
	
	
	
	
	
	
	
	
	public function removeProduct($id = null) {
		if (!empty($id)) {
			$product = $this->getProduct($id);
			if (!empty($product)) {
				if (is_file(CATALOGUE_PATH.DS.$product['image'])) {
					unlink(CATALOGUE_PATH.DS.$product['image']);
				}
				return $this->_Db->delete($this->_table_2, $id);
			}
			return false;
		}
		return false;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function isDuplicateProduct($identity = null, $id = null) {
		if (!empty($identity)) {
            $array = array($identity);
			$sql = "SELECT *
					FROM `{$this->_table_2}`
					WHERE `identity` = ?";
			if (!empty($id)) {
				$sql .= " AND `id` != ?";
                $array[] = $id;
			}
			$result = $this->_Db->fetchAll($sql, $array);
			return !empty($result) ? true : false;
		}
		return false;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function isDuplicateCategory($identity = null, $id = null) {
		if (!empty($identity)) {
            $array = array($identity);
			$sql = "SELECT *
					FROM `{$this->_table}`
					WHERE `identity` = ?";
			if (!empty($id)) {
				$sql .= " AND `id` != ?";
                $array[] = $id;
			}
			$result = $this->_Db->fetchAll($sql, $array);
			return !empty($result) ? true : false;
		}
		return false;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

}