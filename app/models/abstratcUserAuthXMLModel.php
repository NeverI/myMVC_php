<?php

abstarct class AbstractUserAuthXMLModel {
    
    protected $xml;

    function __construct($action, $params=false) {
        
        $this->xml = simplexml_load_file('app/data/users.x');
        
    }
    
    protected function getValidIdAndEmail($email, $passw) {
        $user = $this->xml->xpath("//user[@name='$email' and @password='$passw']");
        if (!$user) return;

        return [$email, $email];
    }

    protected function createEntry($email, $passw) {
        
        $this->id = $emil;

        return false;
    }

    protected function updateEntry($updateStr) {
        return false;
    }

}

?>