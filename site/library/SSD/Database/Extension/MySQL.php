<?php

namespace SSD\Database\Extension;

use SSD\Database\Database;


class MySQL extends Database {

    protected $_schema = 'mysql';
    protected $_hostname = DB_HOST;
    protected $_database = DB_NAME;
    protected $_username = DB_USER;
    protected $_password = DB_PASS;







    public function __construct(array $array = null) {

        if (!empty($array)) {

            foreach($array as $key => $value) {

                $this->{$key} = $value;

            }

        }

        parent::__construct();

    }








}











