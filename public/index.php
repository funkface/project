<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
if(!defined('APPLICATION_ENV')){
    if(getenv('APPLICATION_ENV')){
        define('APPLICATION_ENV', getenv('APPLICATION_ENV'));
    }else{
        // SetEnv only works in .htaccess for Apache 1.3.7 and later
        $appEnv = trim(file_get_contents(APPLICATION_PATH . '/configs/application_env.txt'));
        if(!empty($appEnv)){
            define('APPLICATION_ENV', $appEnv);
        }else{
            define('APPLICATION_ENV', 'production');
        }
    }
}

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path()
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();