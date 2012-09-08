<?php 

abstract class AbstractXMLModel {

    
    protected $xml;

    public function __construct($viewName) {
        
        $this->setXML($viewName);
    }

    protected function setXML($name) {
         $this->xml = simplexml_load_file("app/data/{$name}.xml", NULL, LIBXML_NOBLANKS);
    }

    protected function cleanUpAsXML($xml){
    	if (!$xml) return '';		
        return htmlspecialchars_decode(preg_replace('/<\/?'.$xml->getName().'.*?>/', '', $xml->asXML()), ENT_QUOTES);
    }
    
	protected function getLangDependContent($xml, $lang='') {
	
        if (!$lang) $lang = $this->lang;
		
        $node = $xml->xpath("*[@lang='{$lang}']");
        
        if ($node)
            return $this->cleanUpAsXML($node[0]);
        else 
            return "";
    }
    
    protected function getAttribute($xml, $name){
        if (!$xml || !($xml instanceof SimpleXMLElement) ) return '';

        $attrs = $xml->attributes();
        if (isset($attrs[$name]))
           return (String) $attrs[$name];
        else return '';

	}
   
}

?>
