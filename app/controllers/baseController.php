<?php 

/*
 * the controller filename will be used in the url and the class name must be 
 * ending 'Controller'
 * 
 */
class BaseController {
		
	private $load;
	private $router;
	private $model;
	private $user;
	private $isJson;
    private $isAjax;
    
	function __construct(){
		
		$this->load = new Load();
				
		$this->router = new Router($_SERVER['REQUEST_URI']);
		
        if (isset($_POST['json'])) $this->isJson = true;
        else if (isset($_POST['ajax'])) $this->isAjax = true;
        
		if ($this->router->isBaseController()) {
		    
			$function = $this->router->getFunction();
            
            switch ($function) {
                case '':
                case 'Homepage':
                    Helper::requireOnce('app/models/baseModel.php');
                    $this->model = new BaseModel($this->router->getLang(), 'Homepage');
                    
                    break;
                    
                default:
                    $this->load404();
                    return false;
            }

         $this->loadPage();

      } else if ($this->router->getController() == 'SendMessage') {
          
          Helper::requireOnce('app/controllers/sendMessageController.php');
          $sender = new SendMessageController($this->router->getLang());
          
          if ($this->isAjax) {
            $this->load->getJson($sender->getData());
            return true;
         };

         Helper::requireOnce('app/models/baseModel.php');
         $this->model = new BaseModel($this->router->getLang(), 'Homepage');

         $data = $this->model->getData();
         $data['response'] = $sender->getResponse();
         
         $this->load->view('Homepage', $data);
         
      };
		
	}

    public static function isAjax() { return $this->isAjax; }
    public static function isJson() { return $this->isJson; }
    
    private function loadPage($template="default") {
        
        if ($this->model->isValidURL($this->router->getParams()))     
            
            if ($this->isAjax) 
                $this->load->ajax($this->model->getViewName(), $this->model->getData(), $template);
            else if ($this->isJson) 
                $this->load->getJson($this->model->getData(isset($_POST['content']) ? $_POST['content'] : false ));
            else 
                $this->load->view($this->model->getViewName(), $this->model->getData(), $template);
            
         else 
            $this->load404($template);
        
    }
    
    private function load404($template='default') {
        
        if ($template == 'editor') {
		
			Helper::requireOnce('app/models/editorBaseModel.php');
			$this->model = new EditorBaseModel($this->router->getLang(), '404', $this->user);
			
		} else {
		
			Helper::requireOnce('app/models/baseModel.php');
			$this->model = new BaseModel($this->router->getLang(), '404');
			
		}
		
        if ($this->isAjax)
            $this->load->ajax('404', $this->model->getData(), $template);
        else 
            $this->load->view('404', $this->model->getData(), $template);
        
    }
  	
}

?>
