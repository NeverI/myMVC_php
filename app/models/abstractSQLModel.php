<?php 

abstract class AbstractSQLModel {

    
    private static $dbHost = 'localhost';
    private static $dbName = 'dbName';
    private static $dbUser = 'userName';
    private static $dbPassw = 'password';

    protected static $mysql;

    public function __construct($viewName) {
        
    }

    function __destruct(){
        if (self::$mysql && !self::$mysql->connect_errno) {
            self::$mysql->close();
            self::$mysql = null;
        };
        
    }

    protected function insert($what, $to) {
        self::connectToDB();
        return self::$mysql->query("INSERT INTO {$to} VALUES ({$what})");
    }
    
    protected function update($what, $from, $where) {
        self::connectToDB();
        return self::$mysql->query("UPDATE {$from} SET {$what} WHERE {$where}");
    }
    
	protected function count($what, $from) {
        self::connectToDB();
		return self::$mysql->query("SELECT COUNT({$what}) FROM {$from}");
	}
	
    protected function select($what, $from, $params=array()) {
        self::connectToDB();
        return self::$mysql->query("SELECT {$what} FROM {$from}".
             (isset($params['join']) ? " INNER JOIN {$params['join']}" : '').
             (isset($params['leftJoin']) ? " LEFT JOIN {$params['leftJoin']}" : '').
             (isset($params['rightJoin']) ? " RIGHT JOIN {$params['rightJoin']}" : '').
             (isset($params['fullJoin']) ? " FULL JOIN {$params['fullJoin']}" : '').
             (isset($params['joinValue']) ? " ON {$params['joinValue']}" : '').
             (isset($params['where']) ? " WHERE {$params['where']}" : '').
			 (isset($params['order']) ? " ORDER BY {$params['order']} ".
			     (isset($params['orderType']) ? $params['orderType'] : 'ASC') : '').
             (isset($params['group']) ? " GROUP BY {$params['group']}" : '').
             (isset($params['limit']) ? " LIMIT {$params['limit']}": '') );
    }
    
    protected function delete($from, $where) {
        self::connectToDB();
        return self::$mysql->query("DELETE FROM {$from} WHERE {$where}");
    }
    
    protected function filterString($str) { return mysql_escape_string(strip_tags($str)); }
   
    
    private static function connectToDB() {
        
        if (!self::$mysql) {
        
            self::$mysql = new mysqli(self::$dbHost, self::$dbUser, self::$dbPassw, self::$dbName);
            
            if (mysqli_connect_errno()) 
                die ('Connect failed: '.mysqli_connect_error());
            
            self::$mysql->set_charset('utf8');
            
        }
    }
}

?>