<?php
class App_Mail extends Zend_Mail
{
    protected $_config;
    protected $_view;
    protected $_viewScript;
    protected $_viewVars;
    
    public function __construct($charset = 'iso-8859-1')
    {
        parent::__construct($charset);
        
        $this->_config = Zend_Registry::get('config')->email;
        $this->_init();
    }
    
    protected function _init()
    {
        
    }
    
    public function send($transport = null)
    {
        $this->_render();
        
        if($transport == null){
            $transport = new Zend_Mail_Transport_Sendmail('-f' . $this->getFrom());
        }
        
        parent::send($transport);      
    }
    
    protected function _render()
    {
        $this->_preRender();
        
        $view = $this->getView();
        $filter = new App_Filter_HtmlToFormattedPlainText();
        
        $html = $view->partial($this->getViewScript(), $this->getViewVars());
        $text = $filter->filter($html);
        
        $subject = $view->placeholder('subject')->toString();
        $subject = $filter->filter($subject);
        
        $this->setBodyHtml($html);
        $this->setBodyText($text);
        $this->setSubject($subject);
    }
    
    protected function _preRender()
    {
        
    }
    
    protected function setViewVars(array $viewVars)
    {
        return $this->addViewVars($array);
    }
    
    public function getViewVars()
    {
        if($this->_viewVars == null){
            $this->_viewVars = array('mail' => $this);
        }
        return $this->_viewVars;
    }
    
    public function addViewVars(array $viewVars)
    {
        $this->_viewVars = $this->getViewVars() + $viewVars;
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
            throw new App_Exception('A view script must be set to use App_Mail.');
        }
        
        return $this->_viewScript;
    }
    
    /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return App_Mail
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
    
    /**
     * Set Reply-To Header
     *
     * @param string $email
     * @param string $name
     * @return App_Mail
     * @throws null. Changed from superclass, now simply overwrites.
     */
    /*public function setReplyTo($email, $name = null)
    {
        $email = $this->_filterEmail($email);
        $name  = $this->_filterName($name);
        $this->_replyTo = $email;
        $this->_storeHeader('Reply-To', $this->_formatAddress($email, $name), false);

        return $this;
    }*/
    
}