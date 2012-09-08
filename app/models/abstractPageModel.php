<?php

//abstract class AbstractPageModel extends AbstractSQLModel {
abstract class AbstractPageModel extends AbstractXMLModel {
    
    protected $viewName;
    protected $lang;
    protected $mainUrl;
    protected $data = Array( 'pageData' => array() );

    function __construct($lang, $viewName) {
        
        parent::__construct($type);

        $this->viewName = $viewName;
        $this->lang = $lang;
        
        $this->mainUrl = (string) new Router('/', false, $lang);
    }
    
    
    public function getData($content=false){
        
        if (!isset($_POST['ajax'])) {
            $this->setFooter();
            $this->setHeader();
        }
        
        if ($content && isset($this->data['pageDate'][$content]))
            return $this->data['pageDate'][$content];
        else if ($content && !isset($this->data['pageDate'][$content]))
            return array();
        
        return $this->data;
    }

    public function getViewName() { return $this->viewName; }
    
    public function isValidURL($params ) {if (!$params) return true; return false; } 
    
    protected function setData($name, $value, $isNext=false) {
        
        if ($isNext) {
            if (!isset($this->data['pageData'][$name])) $this->data['pageData'][$name] = Array();
             
            $this->data['pageData'][$name][] = $value;
            
        } else
            $this->data['pageData'][$name] = $value;
            
    }
    
    protected function getHref($href) {
        $href = (string) $href;
        switch ($href) {
            case (strpos($href, '#') !==  false):
            case (strpos($href, 'http://') === 0):
                return $href;
                
            default:
                
                return (string) new Router($href, false, $this->lang);                    
        }
    }
   
    protected function setHeader() {
      
    }
    
    protected function setFooter() {
       
    }

    
}

?>