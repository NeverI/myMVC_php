<?php

class Helper {
	
	static private $requiredFiles = array();
	static function requireOnce($file) {

		if (!isset (self::$requiredFiles[$file])) {
			require($file);
			self::$requiredFiles[$file] = true;
		}
	
	}
	
	static function formKey($name, $action='validate') {
        
        switch ($action) {
            case 'validate':
                
                if (isset($_SESSION[$name])) 
                    $old = $_SESSION[$name];
                
                 if (isset($_POST[$name]) && isset($old) && $old == $_POST[$name]) 
                    return true;
                 else 
                    return false;
                
            case 'getNew':
                
                 $key = md5($_SERVER['REMOTE_ADDR'].mt_rand(1, 1000).'abc');
                 $_SESSION[$name] = $key;
        
                return $key;
        }
        
    }
    
    static function deleteFolders($dir) {
        $files = glob( $dir .'/*', GLOB_MARK ); 
        foreach( $files as $file ){ 
            if( substr( $file, -1 ) == '/' ) 
                Helper::deleteFolders( $file ); 
            else 
                unlink( $file ); 
        } 
        
        if (is_dir($dir)) rmdir( $dir ); 
   }
   
    private static $changeAbleChars = array('á','Á','é','É','ű','Ű','ü','Ü','ú','Ú','ó','Ó','ő','Ő','ö','Ö','í','Í',' ',',');
    private static $changedChars =    array('a','A','e','E','u','U','u','U','u','U','o','O','o','O','o','O','i','I','_', '');
    static function convertToID($str) {
        return trim(str_replace(self::$changeAbleChars, self::$changedChars, $str));
    }

    static function getEmailPattern() {
        return '/^[a-zA-Z0-9._-]{2,}@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/';
    }
    
}
 
?>
