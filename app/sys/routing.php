<?php 

/**
 * MOD_REWRITE engine atrribute setup !!!!!!!!
 */
class Router {
	
	private static $LANGS;
    private static $BASELANG = 'hu';	
    private static $ISMODREWRITE = true;
	private static $CONTROLLERS;
	private static $BASEPATH;

	private $uri;
	private $controller;
	private $function;
	private $params;
    private $lang;
	private $isFile = false;
	
	function __construct($uri, $baseDir=false, $lang=false, $basePath=false){
		
		self::_setControllers();
		self::_setBasePath();
		self::_setLangs();

		//if (preg_match('/\/?\w+\.\w+$/', $uri)) $this->isFile = true;
		if ($baseDir !== false) $this->isFile = true;

		$basePath = $basePath ? $basePath : self::$BASEPATH;

		if ($basePath != '/')
			$uri = str_replace($basePath, '', $uri);
		else 
			$uri = preg_replace('/^\//', '', $uri);


		if (!self::$ISMODREWRITE && !$baseDir)
			$this->uri = explode('/', str_replace('index.php/', '', preg_replace('/(.+)\/$/', '$1', $uri)));
	        else if (!self::$ISMODREWRITE && $baseDir) {
	            $uri = strpos($uri, '/') === 0 ? $uri : '/'.$uri;
	            $this->uri = explode('/', '/app/'.$baseDir.$uri);
        	} else if ($baseDir) 
	            $this->uri = explode('/', $uri);
        	else 
		   $this->uri = explode('/', preg_replace('/(.+)\/$/', '$1', $uri));


		// check url for any paramter
        	if (!isset($this->uri[0])) {
	            $this->controller = 'base';
        	    return true;
	        };

	        // set lang
        	if ($lang) $this->setLang($lang);
	        else if (in_array(reset($this->uri), self::$LANGS)) 
            	$this->lang = strtolower(array_shift($this->uri));
        

	        // set controller
        	if (isset($this->uri[0]) && in_array($this->uri[0], self::$CONTROLLERS)){
			$this->controller = array_shift($this->uri);
		} else {
			$this->controller = 'base';
		};

		// set function if it available
		if (isset($this->uri[0]) && $this->uri[0] != 'index.php'){
			$this->function = array_shift($this->uri);
		};

		// set params if they available
		if (isset($this->uri[0])) {
            		$this->params = $this->uri;
		};

 
	}
	
	public function isBaseController(){
		if ($this->controller === 'base') return true;
		else return false;
	}
	
	public function getController(){
		return $this->controller;
	}
	
	public function getFunction(){
		return $this->function;
	}
	
	public function getParams(){
		return $this->params;
	}

    public function getLang() {
        if (!$this->lang) return self::$BASELANG;

        return $this->lang;
    }
   
    public function setLang($value) {
        
        if ($value == self::$BASELANG) $this->lang = null;
        else $this->lang = $value;
        
        return $this;
	}

	public function getURL($context='semiAbsolute'){
		$url = '';
		
		switch($context){
			case 'relative':
				$url = '/';
				break;
			case 'absolute':
				$url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
				$url.= '://'.$_SERVER['SERVER_NAME']; 
			default:
				
				if (!self::$ISMODREWRITE && !$this->isFile)
					$url .= $_SERVER['SCRIPT_NAME'].'/';
				else if (self::$ISMODREWRITE && $this->isFile)
					$url .= '';
				else 
					$url .= $this->getBasePath();
				
					
				break;
		};
        

        $url .= !$this->lang ? '' : $this->lang.'/'; 

		$url .= $this->isBaseController() ? '' : $this->controller.'/';

		$url .= $this->function ? $this->function.'/' : '';

        if ($this->params) {
            $url .= implode('/', $this->params);
		    $url .= strpos(end($this->params), '#') === false ? '/' : '';
        };

        //$url = preg_replace('/\/\//', '/', $url);
		
		if (preg_match('/\.\w{1,4}\/$/', $url) || $this->isFile) $url = preg_replace('/\/$/', '', $url);
		
		
		$url = preg_replace('/\/\//', '/', $url);

		return $url;
	}
	
	public function __tostring(){
		return $this->getURL();
	}
	
	public static function getLangs() { return self::$LANGS; }
    public static function getBaseLang() { return self::$BASELANG; }
    public static function getBasePath() { return self::$BASEPATH; }
	public static function isModRewrite() { return self::$ISMODREWRITE; }
	/**
	 * walk /app/controllers folder for all php file
	 * and get their name
	 */
	static private function _setControllers(){
		
		if (self::$CONTROLLERS) return true; 
		
		$controllers = glob('app/controllers/*.php');
		
		for ($i = 0; $i<count($controllers); $i++){
			
			$contPath = array_reverse(explode('/', $controllers[$i]));
			$contName = explode('.',$contPath[0]);
			$controllers[$i] = $contName[0];	
		};
	
		self::$CONTROLLERS = $controllers;
	   
		return true;
	} 
	
	static private function _setBasePath(){
		
		if (self::$BASEPATH) return true;

		$basePath = explode('/', $_SERVER['SCRIPT_NAME']);

		array_pop($basePath);

		self::$BASEPATH = implode('/', $basePath);
		self::$BASEPATH .= '/';

		return true;
	}
	
	static private function _setLangs(){
		
		if (self::$LANGS) return true;
		
        if (is_file('app/data/Footer.xml')) 
            $xml = simplexml_load_file('app/data/Footer.xml');
        else if (is_file('../../../data/Footer.xml')) 
            $xml = simplexml_load_file('../../../data/Footer.xml');
        else if (is_file('../../../../data/Footer.xml')) 
            $xml = simplexml_load_file('../../../../data/Footer.xml');
        else if (is_file('../../../../../data/Footer.xml')) 
            $xml = simplexml_load_file('../../../../../data/Footer.xml');
        else if (is_file('../../../../../../data/Footer.xml')) 
            $xml = simplexml_load_file('../../../../../../data/Footer.xml');
        else if (is_file('../../../../../../data/Footer.xml')) 
            $xml = simplexml_load_file('../../../../../../data/Footer.xml');
        else if (is_file('../../../../../../data/Footer.xml')) 
            $xml = simplexml_load_file('../../../../../../data/Footer.xml');
        else if (is_file('../../../../../../data/Footer.xml')) 
            $xml = simplexml_load_file('../../../../../../data/Footer.xml');
        else if (is_file('../../../../../../../data/Footer.xml')) 
            $xml = simplexml_load_file('../../../../../../../data/Footer.xml');
        else if (is_file('../../../../../../../data/Footer.xml')) 
            $xml = simplexml_load_file('../../../../../../../data/Footer.xml');
        else if (is_file('../../../../../../../data/Footer.xml')) 
            $xml = simplexml_load_file('../../../../../../../data/Footer.xml');
        else 
            $xml = simplexml_load_file('../../../../../../../../../../data/Footer.xml');
       
		self::$LANGS = array();
		
		foreach($xml->langs->lang as $lang) {
			self::$LANGS[] = (string) $lang;
		}
		
	}
	
	
}

?>
