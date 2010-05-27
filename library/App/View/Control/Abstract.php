<?php
abstract class App_View_Control_Abstract
{
    protected $_view;
    protected $_viewScript;
    protected $_viewVars = array();
    
    protected $_defaultViewScript = '_default.phtml';
    
    public function __construct($options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        } elseif ($options instanceof Zend_Config) {
            $this->setConfig($options);
        }
    }
    
    public function __toString()
    {
        try {
            $return = $this->render();
            return $return;
        } catch (Exception $e) {
            $message = "Exception caught by view control: " . $e->getMessage()
                     . "\nStack Trace:\n" . $e->getTraceAsString();
            trigger_error($message, E_USER_WARNING);
            return '';
        }
    }
    
    /**
     * Set form state from config object
     *
     * @param  Zend_Config $config
     * @return App_Grid
     */
    public function setConfig(Zend_Config $config)
    {
        return $this->setOptions($config->toArray());
    }
    
    /**
     * Set form state from options array
     *
     * @param  array $options
     * @return App_Grid
     */
    public function setOptions(array $options)
    {
        $forbidden = array('Options', 'Config');

        foreach ($options as $key => $value) {
            $normalized = ucfirst($key);
            if (in_array($normalized, $forbidden)) {
                continue;
            }

            $method = 'set' . $normalized;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        
        return $this;
    }
    
    public function setViewScript($viewScript)
    {
        $this->_viewScript = $viewScript;
        return $this;
    }
    
    public function getViewScript()
    {
        if($this->_viewScript == null){
            $view = $this->getView();
            $view->addScriptPath(realpath(APPLICATION_PATH . '/../library/App/View/Control/views'));
            $this->setViewScript($this->_defaultViewScript);
        }
        
        return $this->_viewScript;
    }
    
    public function setViewVars(array $viewVars)
    {
        $this->_viewVars = $viewVars;
        return $this;
    }
    
    /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_Form
     */
    public function setView(Zend_View_Interface $view = null)
    {
        $this->_view = $view;
        return $this;
    }
    
    /**
     * Retrieve view object
     *
     * If none registered, attempts to pull from ViewRenderer.
     *
     * @return Zend_View_Interface|null
     */
    public function getView()
    {
        if (null === $this->_view) {
            require_once 'Zend/Controller/Action/HelperBroker.php';
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            $this->setView($viewRenderer->view);
        }

        return $this->_view;
    }
    
    public function preRender()
    {
        // override
    }
    
    public function render()
    {
        $this->preRender();
        
        $view = $this->getView();
        $result = $view->partial($this->getViewScript(), $this->_viewVars);
        
        return $result;
    }
}