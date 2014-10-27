<?php

defined('DATA_PATH') or define('DATA_PATH',__DIR__.'/../data');
defined('BASE_PATH') or define('BASE_PATH',dirname(__DIR__));

defined('RNTM_PATH') or define('RNTM_PATH',DATA_PATH.'/runtime');

defined('ASSETS_PATH') or define('ASSETS_PATH',DATA_PATH.'/assets');
defined('ASSETS_URL') or define('ASSETS_URL','/data/assets');

defined('DB_PATH') or define('DB_PATH',DATA_PATH.'/database');

// the entry script URL (without host info) for functional and acceptance tests
// PLEASE ADJUST IT TO THE ACTUAL ENTRY SCRIPT URL
defined('TEST_ENTRY_URL') or define('TEST_ENTRY_URL', BASE_PATH.'/index.php');

// the entry script file path for functional and acceptance tests
//defined('TEST_ENTRY_FILE') or define('TEST_ENTRY_FILE', dirname(__DIR__) . '/web/index-test.php');

defined('YII_DEBUG') or define('YII_DEBUG', true);

defined('YII_ENV') or define('YII_ENV', 'test');

require_once(BASE_PATH. '/vendor/autoload.php');
require_once(BASE_PATH . '/vendor/yiisoft/yii2/Yii.php');

//// set correct script paths
//$_SERVER['SCRIPT_FILENAME'] = TEST_ENTRY_FILE;
//$_SERVER['SCRIPT_NAME'] = TEST_ENTRY_URL;
//$_SERVER['SERVER_NAME'] = 'localhost';

Yii::setAlias('@tests', __DIR__);
