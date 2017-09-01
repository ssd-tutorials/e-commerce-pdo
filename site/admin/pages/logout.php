<?php

use SSD\Login;

Login::logout(Login::$login_admin);
Login::restrictAdmin();