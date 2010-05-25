<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected static $doctrineAutoloadDirs = array();

    protected function _initAutoload()
    {
       /* Necessary to allow Model_Foo to map to models/fo.php,
        * or Admin_Form_Foo to modules/admin/forms/foo.php
        * (also requires "resources.modules.admin = admin" in config)
        * for eg Admin_Model_Foo admin module must also have a bootstrap
        */
        $loader = new Zend_Application_Module_Autoloader(array(
            'basePath'  => APPLICATION_PATH,
            'namespace' => '', // no resource namespace
        ));
       return $loader;
    }
    
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }

    protected function _initRequest()
    {
        $this->bootstrap('FrontController');

        $front = $this->getResource('FrontController');
        $request = $front->getRequest();

        if($request === null){
            $request = new Zend_Controller_Request_Http();
            $request->setBaseUrl($front->getBaseUrl());
            $front->setRequest($request);
        }

        return $request;
    }

    protected function _initView()
    {
        $resources = $this->getOption('resources');
        $view = new Zend_View($resources['view']);

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        $view->setEncoding('UTF-8');
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        $view->headLink()
                ->appendStylesheet($view->baseUrl('css/style.css'), 'screen,projection')
                ->appendStylesheet($view->baseUrl('css/ie.css'), 'screen,projection', 'lte IE 7');
                
        $view->headScript()->appendFile($view->baseUrl('/js/jquery-1.3.2.min.js'));

        return $view;
    }

    protected function _initDoctrine()
    {
        $this->bootstrap('autoload');

        $doctrineConfig = $this->getOption('doctrine');

        $autoloader = $this->getApplication()->getAutoloader();
        $autoloader->pushAutoloader(array('Doctrine_Core', 'autoload'));
        if($doctrineConfig['cli_mode']){

            $tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . getmypid() . DIRECTORY_SEPARATOR;
            self::$doctrineAutoloadDirs = array(
                $tmpDir . 'model_toprfx_doctrine_tmp_dirs' . DIRECTORY_SEPARATOR,
                $tmpDir . 'fromprfx_doctrine_tmp_dirs' . DIRECTORY_SEPARATOR
            );

            $autoloader->pushAutoLoader(array('Bootstrap', 'doctrineCliAutoload'));
        }

        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(Doctrine_Core::ATTR_MODEL_CLASS_PREFIX, 'Model_');
        $manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        $manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
        $manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, true);
        $manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
        $manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_PEAR);

        Doctrine_Core::loadModels($doctrineConfig['models_path']);

        /*
        // load doctrine dsn from resources.db
        $resources = $this->getOption('resources');
        $dbParams = $resources['db']['params'];
		/*/
		$dbParams = $doctrineConfig['dsn'];
        //*/
        
        $dsn = 'mysql://' . urlencode($dbParams['username']) . ':' . urlencode($dbParams['password']) .
            '@' . urlencode($dbParams['host']) . '/' . urlencode($dbParams['dbname']);
        
        $conn = Doctrine_Manager::connection($dsn, 'doctrine');
        $conn->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);

        return $conn;
    }
    
    /*
     * HOOKS
     */
    
    public static function doctrineCliAutoload($className)
    {
        foreach(self::$doctrineAutoloadDirs as $dir){
            $file = $dir . $className . '.php';
            if(file_exists($file)){
                require $file;
                return true;
            }
        }

        return false;
    }
    
}