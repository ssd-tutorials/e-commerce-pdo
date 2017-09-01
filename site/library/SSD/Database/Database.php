<?php

namespace SSD\Database;

use \PDO;
use \PDOException;

use SSD\Helper;

abstract class Database {

    protected $_schema;
    protected $_hostname;
    protected $_database;
    protected $_username;
    protected $_password;

    private $_persistent = true;
    private $_fetchMode = PDO::FETCH_ASSOC;
    private $_driverOptions = array();
    private $_connectionString = null;

    private $_pdoObject = null;

    public $affectedRows;
    public $id;






    public function __construct() {
        $this->_connect();
    }





    private function _exceptionOutput($e = null, $message = null) {

        if (is_object($e)) {

            if (ENVIRONMENT == 1) {

                return $e->getMessage();

            } else {

                return '<p>'.$message.'</p>';

            }

        }

    }







    public function setDriverOption($key = null, $value = null) {

        $this->_driverOptions[$key] = $value;

    }







    private function _setConnection() {

        switch($this->_schema) {

            case 'mysql':
                $this->_connectionString = "mysql:dbname={$this->_database};host={$this->_hostname}";
                break;
            case 'sqlite':
                $this->_connectionString = "sqlite:{$this->_database}";
                break;
            case 'pgsql':
                $this->_connectionString = "pgsql:dbname={$this->_database};host={$this->_hostname}";
                break;

        }

        $this->setDriverOption(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
        $this->setDriverOption(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->_persistent) {
            $this->setDriverOption(PDO::ATTR_PERSISTENT, true);
        }

    }











   private function _connect() {

       $this->_setConnection();

       try {

           $this->_pdoObject = new PDO(
               $this->_connectionString,
               $this->_username,
               $this->_password,
               $this->_driverOptions
           );


       } catch (PDOException $e) {

           echo $this->_exceptionOutput($e, 'There was a problem with the Database connection');

       }

   }







    private function _query($sql = null, $params = null) {

        if (!empty($sql)) {

            if (!is_object($this->_pdoObject)) {

                $this->_connect();

            }

            $statement = $this->_pdoObject->prepare($sql, $this->_driverOptions);

            if (!$statement) {

                $errorInfo = $this->_pdoObject->errorInfo();

                throw new PDOException("Database error [{$errorInfo[0]}]: {$errorInfo[2]}, driver error code is {$errorInfo[1]}");

            }

            $paramsConverted = array();


            if (get_magic_quotes_gpc() === true) {

                if (is_array($params)) {

                    foreach($params as $key => $value) {

                        $paramsConverted[$key] = stripcslashes($value);

                    }

                } else {

                    $paramsConverted[] = stripcslashes($params);

                }

            } else {

                $paramsConverted = is_array($params) ? $params : array($params);

            }


            if (!$statement->execute($paramsConverted) || $statement->errorCode() != '00000') {

                $errorInfo = $statement->errorInfo();

                throw new PDOException("Database error [{$errorInfo[0]}]: {$errorInfo[2]}, driver error code is {$errorInfo[1]}<br />SQL: {$sql}");

            }

            $this->affectedRows = $statement->rowCount();

            return $statement;



        }

    }















    public function setFetchMode($fetchMode = null) {

        if (!empty($fetchMode)) {
            $this->_fetchMode = $fetchMode;
        }

    }







    public function getLastInsertId($sequenceName = null) {

        return $this->_pdoObject->lastInsertId($sequenceName);

    }









    public function fetchAll($sql = null, $params = null) {

        if (!empty($sql)) {

            try {

                $statement = $this->_query($sql, $params);
                return $statement->fetchAll($this->_fetchMode);

            } catch (PDOException $e) {

                echo $this->_exceptionOutput($e, 'Something went wrong trying to fetch records');

            }

        }

        return false;

    }









    public function fetchOne($sql = null, $params = null) {

        if (!empty($sql)) {

            try {

                $statement = $this->_query($sql, $params);
                return $statement->fetch($this->_fetchMode);

            } catch (PDOException $e) {

                echo $this->_exceptionOutput($e, 'Something went wrong while fetching the record');

            }

        }

        return false;

    }








    public function execute($sql = null, $params = null) {

        if (!empty($sql)) {

            try {

                return $this->_query($sql, $params);

            } catch (PDOException $e) {

                echo $this->_exceptionOutput($e, 'Something went wrong when executing the sql statement');

            }

        }

        return false;

    }










    private function _insertArray($array = null, $pre = null) {

        if (!empty($array) && is_array($array)) {

            $fields = array();
            $holders = array();
            $values = array();

            foreach($array as $key => $value) {

                $fields[] = !empty($pre) ? "`{$pre}.{$key}`" : "`{$key}`";
                $holders[] = "?";
                $values[] = $value;

            }

            return array($fields, $holders, $values);

        }

    }








    private function _updateArray($array = null, $pre = null) {

        if (!empty($array) && is_array($array)) {

            $fields = array();
            $values = array();

            foreach($array as $key => $value) {

                $fields[] = !empty($pre) ? "`{$pre}.{$key}` = ?" : "`{$key}` = ?";
                $values[] = $value;

            }

            return array($fields, $values);

        }

    }







    public function insert($table = null, $array = null) {

        $array = $this->_insertArray($array);

        if (!empty($table) && !empty($array)) {

            $sql  = "INSERT INTO `{$table}` (";
            $sql .= implode(", ", $array[0]);
            $sql .= ") VALUES (";
            $sql .= implode(", ", $array[1]);
            $sql .= ")";

            $return = $this->execute($sql, $array[2]);

            if ($return) {

                $this->id = $this->getLastInsertId();
                return true;

            }

            return false;

        }

        return false;

    }








    private function _areUpdateParametersValid($table = null, $array = null, $value = null, $field = null) {

        return (!empty($table) && !empty($array) && !Helper::isEmpty($value) && !empty($field));

    }










    public function update($table = null, $array = null, $value = null, $field = 'id') {

        $array = $this->_updateArray($array);

        if ($this->_areUpdateParametersValid($table, $array, $value, $field)) {

            $sql  = "UPDATE `{$table}` SET ";
            $sql .= implode(", ", $array[0]);
            $sql .= " WHERE `{$field}` = ?";

            $array[1][] = $value;

            return $this->execute($sql, $array[1]);

        }

        return false;

    }







    private function _areDeleteParametersValid($table = null, $value = null, $field = null) {

        return (!empty($table) && !Helper::isEmpty($value) && !empty($field));

    }








    public function delete($table = null, $value = null, $field = 'id') {

        if ($this->_areDeleteParametersValid($table, $value, $field)) {

            $sql = "DELETE FROM `{$table}`
                    WHERE `{$field}` = ?";

            return $this->execute($sql, $value);

        }

        return false;

    }







    private function _isSelectOneValid($table = null, $value = null, $field = null) {

        return (!empty($table) && !Helper::isEmpty($value) && !empty($field));

    }









    public function selectOne($table = null, $value = null, $field = 'id') {

        if ($this->_isSelectOneValid($table, $value, $field)) {

            $sql = "SELECT *
                    FROM `{$table}`
                    WHERE `{$field}` = ?";

            return $this->fetchOne($sql, $value);

        }

        return null;

    }









    public function beginTransaction() {

        if (!is_object($this->_pdoObject)) {

            $this->_connect();

        }

        $this->_pdoObject->beginTransaction();

    }






    public function commit() {

        if (!is_object($this->_pdoObject)) {

            $this->_connect();

        }

        $this->_pdoObject->commit();

    }




    public function rollBack() {

        if (!is_object($this->_pdoObject)) {

            $this->_connect();

        }

        $this->_pdoObject->rollBack();

    }






    public function executeTransaction($sql = null, $params = null) {

        if (!empty($sql)) {

            return $this->_query($sql, $params);

        }

        return false;

    }






    public function insertTransaction($table = null, $array = null) {

        $array = $this->_insertArray($array);

        if (!empty($table) && !empty($array)) {

            $sql  = "INSERT INTO `{$table}` (";
            $sql .= implode(", ", $array[0]);
            $sql .= ") VALUES (";
            $sql .= implode(", ", $array[1]);
            $sql .= ")";

            $return = $this->executeTransaction($sql, $array[2]);

            if ($return) {
                $this->id = $this->getLastInsertId();
                return true;
            }

            return false;

        }

        return false;

    }









    public function updateTransaction($table = null, $array = null, $value = null, $field = 'id') {

        $array = $this->_updateArray($array);

        if ($this->_areUpdateParametersValid($table, $array, $value, $field)) {

            $sql  = "UPDATE `{$table}` SET ";
            $sql .= implode(", ", $array[0]);
            $sql .= " WHERE `{$field}` = ?";

            $array[1][] = $value;

            return $this->executeTransaction($sql, $array[1]);

        }

        return false;

    }









    public function deleteTransaction($table = null, $value = null, $field = 'id') {

        if ($this->_areDeleteParametersValid($table, $value, $field)) {

            $sql = "DELETE FROM `{$table}`
                    WHERE `{$field}` = ?";

            return $this->executeTransaction($sql, $value);

        }

        return false;

    }









    public function getOneTransaction($sql = null, $params = null) {

        if (!empty($sql)) {

            $statement = $this->_query($sql, $params);
            return $statement->fetch($this->_fetchMode);

        }

        return null;

    }









    public function selectOneTransaction($table = null, $value = null, $field = 'id') {

        if ($this->_isSelectOneValid($table, $value, $field)) {

            $sql = "SELECT *
                    FROM `{$table}`
                    WHERE `{$field}` = ?";

            return $this->getOneTransaction($sql, $value);

        }

        return null;

    }




















}




