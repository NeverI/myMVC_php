<?php

//Helper::requireOnce('app/models/abstractUserAuthSQLModel.php');
//class UserAuthModel extends AbstractUserAuthSQLModel {
Helper::requireOnce('app/models/abstractUserAuthXMLModel.php');
class UserAuthModel extends AbstractUserAuthXMLModel {

    
    function __construct($action, $params=false) {
       
        parent::__construct();

        switch ($action) {
            
            case 'login':
                
                if (empty($params['email']) || empty($params['passw'])) {
                    $this->response = 'Empty email or password';
                    return;
                }   
                
                if (!preg_match(Helper::getEmailPattern(), $params['email'])) {
                    $this->response = 'The email address format is incorrect....';
                    return;
                }
                
                $passw = $this->convertToPassw($params['passw']);
                $data = $this->getValidIdAndEmail($params['email'], $passw);
                if (!$data) {
                    $this->response = 'Wrong email/password pair';
                    return;
                }
                
                $this->id = $data[0];
                $this->emailAddress = $data[1];
                
                $this->state = 'loggedIn';
                
                session_regenerate_id();
                $_SESSION['userId'] = $data[0];
                $_SESSION['userEmail'] = $data[1];
                $this->isSuccess = true;
                
                break;
                
            case 'register':
                
                if (empty($params['email'])) {
                    $this->response = 'Empty email address';
                    return;
                }

                if (!$this->newPasswIsValid($params['passw'], $params['passw2'])) return;
                
                if (!preg_match(Helper::getEmailPattern(), $params['email'])) {
                    $this->response = 'The email address format is incorrect....';
                    return;
                }
                
                $passw = $this->convertToPassw($params['passw']);
                if (!$this->createEntry($params['email'], $passw)) {
                    $this->response = 'The email address is reserved';
                    return;
                }
                
                $this->state = 'loggedIn';
                
                $this->emailAddress = $params['email'];
                
                session_regenerate_id();
                $_SESSION['userId'] = $this->id;
                $_SESSION['userEmail'] = $this->emailAddress;
                $this->isSuccess = true;
                
                break;
            
            case 'registration':
            case 'showAddAddress':            
                $this->state = $action;
                break;
            
            case 'logout':
                $this->destroy();
                $this->isSuccess = true;
                break;
                
            default:
                            
                if (isset($_SESSION['userId'])) {
                    $this->state = 'loggedIn';
                    
                    $this->id = $_SESSION['userId'];
                    $this->emailAddress = $_SESSION['userEmail'];
                    $this->isSuccess = true;
                }
                
                break;
        }
        
    }
    
    public function getJson() {
        return array(
            'isSuccess'=>$this->isSuccess,
            'email'=>$this->emailAddress,
            'response'=>$this->response,
            'key'=>Helper::formKey('loginKey', 'getNew'),
             
        );
    }
    
    public function isLoggedIn() { return $this->state == 'loggedIn'; }
    
    public function getId() { return $this->id; }
    public function getEmailAddress() { return $this->emailAddress; }
    public function getState() { return $this->state; }
    public function getResponse() { return $this->response; }
    
    public function updateData($email='', $passw='', $passw2='') {
        
        if (!$this->isLoggedIn()) 
            return 'Most be logged in';
        
        $updateStr = '';
        
        if ($email) {
            if ($email != $this->emailAddress) {
                if (preg_match(Helper::getEmailPattern(), $email))
                    $updateStr .= ($updateStr ? ', ' : '')."email='$email'";
                else
                    return 'The email address format is incorrect....';
                
            } else {
                $email = '';
            }

        }
        
        if ($passw) {
            if (!$this->newPasswIsValid($passw, $passw2)) return;
            
            $updateStr .= ($updateStr ? ', ' : '')."passw='".$this->convertToPassw($passw)."'";
        }
        
        if (!$updateStr) return;
        
        $this->isSuccess = $this->updateEntry($updateStr);

        if (!$this->isSuccess) {
            return 'The email address is reserved';
        }
        
        session_regenerate_id();
        if ($email) {
            $this->emailAddress = $email;
            $_SESSION['userEmail'] = $this->emailAddress;
        }
    }
   
    
    private function destroy(){
    	$this->state = 'loggedOut';
		
        $this->id = '';
        $this->emailAddress = '';
		unset($_SESSION['init']);
        unset($_SESSION['userId']);
        unset($_SESSION['userEmail']);
		session_regenerate_id();
    }
    
    
    private function newPasswIsValid($passw, $passw2) {
        if (strlen($passw) < 6) {
            $this->response = 'Must be at least 6 character long';
            return;
        }
        
        if ($passw != $passw2) {
            $this->response = 'The two password does not match';
           return; 
        }
        
        return true;
    }
    
    private function convertToPassw($str) {
        return  md5($str.'_salt');
    }
    
    static function startSession(){
        session_name('user');
        session_start();
        
        if (!isset($_SESSION['init'])) {
            session_regenerate_id();
            $_SESSION['init'] = true;
        }
    }
    
}

?>