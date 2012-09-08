<?php

class BaseModel extends AbstractPageModel {
    
	public function __construct ($lang, $type) {

	    parent::__construct($lang, $type);

	    call_user_func(array($this, 'get'.$type));

	}

	private function get404(){

	    $this->data['pageData']['text'] = '404 error';

	}	

	private function getHomepage() {
	    $this->data['pageData']['text'] = 'homepage';	   
	}
	
};

?>
