<?php

error_reporting(E_ALL); // [DEVELOPMENT]
//error_reporting(E_ALL ^ E_NOTICE); // [RELEASE]

require('app/sys/helper.php');

Helper::requireOnce('app/sys/routing.php');
Helper::requireOnce('app/sys/load.php');

Helper::requireOnce('app/models/abstractPageModel.php');
Helper::requireOnce('app/controllers/baseController.php');

new BaseController();

?>
