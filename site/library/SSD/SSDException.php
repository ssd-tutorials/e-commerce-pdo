<?php

namespace SSD;

use \Exception;


class SSDException extends Exception {





    private static function _isDevelopment() {

        return (ENVIRONMENT == 1);

    }

    public static function getOutput($e = null) {

        if (is_object($e) && ($e instanceof Exception)) {

            if (self::_isDevelopment()) {

                $out = array();
                $out[] = 'Message: '.$e->getMessage();
                $out[] = 'File: '.$e->getFile();
                $out[] = 'Line: '.$e->getLine();
                $out[] = 'Code: '.$e->getCode();

                echo '<ul><li>'.implode("</li><li>", $out).'</li></ul>';

                exit();

            } else {

                echo '<p>An error occurred.<br />';
                echo 'Please contact us explaining what has happened.<br />';
                echo 'We are sorry for any inconvenience.</p>';

                exit();

            }

        }

    }


















}






