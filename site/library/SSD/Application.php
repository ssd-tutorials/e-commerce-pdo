<?php

namespace SSD;

use SSD\Database\Extension\MySQL;


abstract class Application {

    protected $_Db;

    protected $_table;

    public $id;






	
	public function __construct() {
		$this->_Db = new MySQL();
	}




    public function getOne($id = null, $field = 'id') {
        return $this->_Db->selectOne($this->_table, $id, $field);
    }








    public function insert($array = null) {

        if ($this->_Db->insert($this->_table, $array)) {

            $this->id = $this->_Db->id;
            return true;

        }

        return false;

    }








    public function update($array = null, $id = null) {

        return $this->_Db->update($this->_table, $array, $id);

    }









    public function delete($id = null) {

        return $this->_Db->delete($this->_table, $id);

    }










	
}












