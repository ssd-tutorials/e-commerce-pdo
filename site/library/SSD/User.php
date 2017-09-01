<?php

namespace SSD;


class User extends Application {
	
	public $objUrl;
	
	protected $_table = "clients";
	
	
	
	
	
	
	
	public function __construct($objUrl = null) {
		parent::__construct();
		$this->objUrl = is_object($objUrl) ? $objUrl : new Url();
	}
	
	



    private function _isEmailPasswordEmpty($email = null, $password = null) {

        return (empty($email) || empty($password));

    }

	
	
	
	
	
	
	public function isUser($email = null, $password = null) {

        if (!$this->_isEmailPasswordEmpty($email, $password)) {

            $password = Login::string2hash($password);

            $sql = "SELECT *
                    FROM `{$this->_table}`
                    WHERE `email` = ?
                    AND `password` = ?
                    AND `active` = ?";

            $result = $this->_Db->fetchOne($sql, array($email, $password, 1));

            if (!empty($result)) {

                $this->id = $result['id'];
                return true;

            }

            return false;

        }

        return false;

	}
	
	
	



    private function _areAddUserParamsValid($params = null, $password = null) {

        return (!empty($params) && !empty($password));

    }
	
	
	
	
	
	
	public function addUser($params = null, $password = null) {
	
		if (
            $this->_areAddUserParamsValid($params, $password) &&
            $this->insert($params)
        ) {
				
            // send email
            $objEmail = new Email();

            if ($objEmail->process(1, array(
                'email'			=> $params['email'],
                'first_name'	=> $params['first_name'],
                'last_name'		=> $params['last_name'],
                'password'		=> $password,
                'hash'			=> $params['hash']
            ))) {

                return true;

            }

            return false;

		}

		return false;
	
	}
	
	
	
	
	
	
	
	
	
	public function getUserByHash($hash = null) {
		return $this->getOne($hash, 'hash');
	}
	
	
	
	
	
	
	
	public function makeActive($id = null) {
		return $this->update(array('active' => 1), $id);
	}
	
	
	
	
	
	
	
	public function getByEmail($email = null) {
		return $this->getOne($email, 'email');
	}
	
	
	
	
	
	
	
	public function getUser($id = null) {
		return $this->getOne($id);
	}
	
	
	
	
	
	
	
	
	
	public function updateUser($array = null, $id = null) {
		return $this->update($array, $id);
	}
	
	
	
	
	
	
	
	
	public function getUsers($srch = null) {
        $array = array(1);
		$sql = "SELECT *
                FROM `{$this->_table}`
				WHERE `active` = ?";
		if (!empty($srch)) {
			$sql .= " AND (`first_name` LIKE ? || `last_name` LIKE ?)";
            $array[] = "%{$srch}%";
            $array[] = "%{$srch}%";
		}
		$sql .= " ORDER BY `last_name`, `first_name` ASC";
		return $this->_Db->fetchAll($sql, $array);
	}
	
	
	
	
	
	
	
	public function removeUser($id = null) {
		return $this->delete($id);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	

}