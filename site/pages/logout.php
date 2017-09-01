<?php

use SSD\Login;

Login::logout(Login::$login_front);
Login::restrictFront($this->objUrl);