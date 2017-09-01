<?php

namespace SSD;


class Admin extends Application {

	
	protected $_table = 'admins';
	



    private function _isEmailPasswordEmpty($email = null, $password = null) {

        return (empty($email) || empty($password));

    }

	
	
	
	
	public function isUser($email = null, $password = null) {

		if (!$this->_isEmailPasswordEmpty($email, $password)) {

			$password = Login::string2hash($password);

			$sql = "SELECT *
			        FROM `{$this->_table}`
					WHERE `email` = ?
					AND `password` = ?";

			$result = $this->_Db->fetchOne($sql, array($email, $password));

			if (!empty($result)) {

				$this->id = $result['id'];
				return true;

			}

			return false;

		}

        return false;

	}
	
	
	
	
	
	
	public function getFullNameAdmin($id = null) {
		
		if (!empty($id)) {
			
			$sql = "SELECT *,
					CONCAT_WS(' ', `first_name`, `last_name`) AS `full_name`
					FROM `{$this->_table}`
					WHERE `id` = ?";

			$result = $this->_Db->fetchOne($sql, $id);

			if (!empty($result)) {

				return $result['full_name'];

			}

            return null;
			
		}

        return null;
		
	}
	
	
	
	
	
	
	
	
	




}