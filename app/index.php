<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/***** ERROR ******/
//error_reporting(E_ALL ^ E_NOTICE);
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);

/***** PATHS ******/
/*
-|
 |-app
 |-protected
 |-framework
*/
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/../protected/config/main.php';

/*
-|
 |-app-|
       |-protected
       |-framework
*/
//$yii=dirname(__FILE__).'/framework/yii.php';
//$config=dirname(__FILE__).'/protected/config/main.php';


/****** DEBUG *****/
// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

/***** GLOBAL VARIABLES *****/

// Enquiry->type
define ('GENERIC', 0);
define ('BUDGETARY', 1);

// Enquiry->state
define ('ENQUIRY_PENDING_VALIDATION', 1);
define ('ENQUIRY_ASSIGNED', 2);
define ('ENQUIRY_REJECTED', 3);
define ('ENQUIRY_ACCEPTED', 4);
define ('ENQUIRY_AWAITING_REPLY', 5);
define ('ENQUIRY_REPLY_PENDING_ASSESSMENT', 6);
define ('ENQUIRY_REPLY_SATISFACTORY', 7);
define ('ENQUIRY_REPLY_INSATISFACTORY', 8);

define ('ADMINISTRATION', 0);
define ('OBSERVATORY', 1);

// Vault->type
define ('LOCAL', 0);
define ('REMOTE', 1);
// Vault->state
define ('CREATED', 0);
define ('VERIFIED', 1);
define ('READY', 2);
define ('LOADED', 3);
define ('BUSY', 4);

// Backup->state
define ('FAIL', 0);
define ('SUCCESS', 1);

require_once($yii);
Yii::createWebApplication($config)->run();
