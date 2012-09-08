<?php 

abstarct class AbstractUserAuthSQLModel extends AbstarctSQLModel {

 	protected $id;
    protected $response;
    protected $emailAddress;
    protected $state = 'loggedOut';
    protected $isSuccess = false;

	function __construct() {

	}

	protected function getValidIdAndEmail($email, $passw) {

		$dbData = $this->select('id, email', 'users', array('where'=>"email='$email' AND passw='$passw'"));

        if ($dbData->num_rows == 0)  return;
        
        return $dbData->fetch_row();
	}

	protected function createEntry($email, $passw) {
		
        if (!$this->insert("'email', '$passw'", 'users (email, passw)'))
            return false;
        

        $this->id = self::$mysql->insert_id;

        return true;
	}

	protected function updateEntry($updateStr) {
		return $this->update($updateStr,  'users',  "id={$this->id}");
	}
}

?>