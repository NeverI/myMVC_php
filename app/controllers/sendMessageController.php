<?php
    
class SendMessageController {
    
    private $model;
    private $isSuccess = false;
    
    function __construct($lang){
      
      if (isset($_POST['sendMessageData'])) $d = $_POST['sendMessageData'];
      else $d = $_POST;
      
      Helper::requireOnce('app/models/sendMessageModel.php'); 
      $this->model = new SendPartnerRequestModel($d, $lang);
      
      $data = $this->model->getData();
      if (!isset($data['pageData']['missing'])) $this->sendMail();
       
    }
    
    public function getResponse() {
        $data = $this->model->getData();
        $data = $data['pageData'];
        
        if (isset($data['missing']))
            return $this->model->getResponse();
        
        else if ($this->isSuccess) return $data['success'];
        
        return $data['faild'];
    }
    
    public function getData() {
        
        $data = $this->model->getData();
        $data = $data['pageData'];
        
        $d = array();
        
        $d['response'] = $this->getResponse();
        
        if (isset($data['missing'])) $d['missing'] = $data['missing'];
        if (!$this->isSuccess) $d['isError'] = true;
        
        return $d;
    }
    
    private function sendMail() {
    
      $toMail = $this->model->getEmail();  
      $fromName = $this->model->getFromName();
      $fromMail = $this->model->getFromMail();
      $header = "To: \"info\" <$toMail>\n".
                "From: \"$fromName\" ".($fromMail ? "<$fromMail>" : '')."\n".
                "Return-Path: <$fromMail>\n".
                "Content-type: text/plain; charset=utf-8";
                         
      $this->isSuccess = mail (
                         $this->model->getEmail(),
                         "Message from: Partner Request",
                         $this->model->getMessage(),
                         $header
                     );
       
     
    }

}
    
?>
