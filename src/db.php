<?php
/**
 * Class to connect a mysql database
 * 
 * @copyright Lellys InformÃ¡ica
 * @author Ã�talo Lelis de Vietro
 * @link http://www.lellysinformatica.com
 * 
 * @version 1.0
 * 
 * @license GNU General Public License, version 3 (GPL-3.0)
 * @see http://www.opensource.org/licenses/gpl-3.0.html
 */
class db {
	
	/**
	 * Mysql username
	 * @var string 
	 */
	private $username;
	/**
	 * Mysql password
	 * @var string 
	 */
	private $password;
	/**
	 * Connection link
	 * @var resource 
	 */
	private $link;
	/**
	 * Database name
	 * @var string
	 */
	private $database;
	/**
	 * Database name of userName
	 * @var string
	 */
	public $db_user;
	/**
	 * Server name
	 * @var string 
	 */
	private $server;
	public $tbl_fix = '';
	
	public function getUsername() {
		return $this->username;
	}
	
	public function setUsername($username) {
		$this->username = $username;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function setPassword($password) {
		$this->password = $password;
	}
	
	public function getLink() {
		return $this->link;
	}
	
	public function getDatabase() {
		return $this->database;
	}
	
	public function setDatabase($database) {
		$this->database = $database;
	}
	
	public function getServer() {
		return $this->server;
	}
	
	public function setServer($server) {
		$this->server = $server;
	}
	
	/**
	* Connect to the database
	**/
	public function connect() {
		$this->link = mysqli_connect( $this->server, $this->username, $this->password, $this->database ) or exit ( mysqli_connect_errno () );
		$this->setNames ();
	}
	
	/**
	 * Close the connection
	 * @return resource 
	**/
	public function close() {
		mysqli_close ( $this->link );
	}
	
	public function mysqli_insert_id() {
		return mysqli_insert_id ( $this->link );
	}

	public function mysqli_affected_rows() {
		return mysqli_affected_rows ( $this->link );
	}

	public function mysqli_num_rows( $result) {
		return mysqli_num_rows ( $result );
	}
	
	/**
	 * Set all fields to the desired type of encoding
	 * @param string $encode 
	 */
	private function setNames( $encode = 'utf8') {
		$this->link->set_charset($encode);
	}
	
	/**
	 * Executes a sql instruction
	 * 
	 * @param string $sql
	 * @param resource $return_format
	 * @return type 
	 */
	public function executeQuery($sql, $return_format = 0) {
		
		// echo $sql;
		
		if ($this->link == NULL)	$this->connect ();
		if (mysqli_connect_errno()){
			$this->connect();
		}
		
		$query = mysqli_query ( $this->link, $sql ) or exit( mysqli_error($this->link)  );
		switch ($return_format) {
			case 1 :
				$query = mysqli_fetch_assoc ( $query );
				break;
			case 2 :
				$query = mysqli_fetch_array ( $query );
				break;
			case 3 :
				$query = mysqli_fetch_row ( $query );
				return $query [0];
			case 4 :
				$query = mysqli_num_rows ( $query );
				return $query;
			default :
				return $query;
		}
		return $query;
		$this->close ();
	}
	
	public function record_insert($table_name, $data)   {
        $queryString = "INSERT INTO " . $table_name . " (";
        $columns = array();
        $values = array();

        foreach ($data as $key => $value) {
            $columns[] = '`' . $key . '`';
            $values[] = "'" . mysqli_real_escape_string($this->link, htmlspecialchars_decode($value)). "'";
        }
        $queryString .= implode(',', $columns) . ") VALUES (" . implode(',', $values) . ") ";
        // echo $queryString;
        return $this->executeQuery($queryString);
    }

    public function record_update($table_name, $data, $where = false)    {
        $queryString = "UPDATE ". $table_name . " SET ";
        $fields = array();
        foreach ($data as $key => $value) {
            $fields[] = " `$key`= '" . mysqli_real_escape_string($this->link, htmlspecialchars_decode($value)) . "'"; 
        }
		if ($where) $where_and = " WHERE " . $where;		
        $queryString .= implode(',', $fields) . $where_and;
        return $this->executeQuery($queryString);
    }
    
    public function record_delete($table_name, $where='')    {
        if ($where)	$whereand = " WHERE ".$where;
        $queryString = "DELETE FROM " . $table_name . $whereand;
        return $this->executeQuery($queryString);
    }
    
    public function adminlog($username,$script,$action) {
    	if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '')
        	$ip = $_SERVER['HTTP_CLIENT_IP'];
	    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '')
	        $ip = $_SERVER['REMOTE_ADDR'];
    	$data["username"] = $username;
    	$data["script"] = $script;
    	$data["action"] = $action;
    	$data["dateline"] = time();
    	$data["ipaddress"] = $ip;
    	$this->record_insert($this->tbl_fix."adminlog", $data);
    }

    public function executeQuery_list($sql) {
		$rsl = $this->executeQuery($sql);
		$kq = array();
		while($row = mysqli_fetch_assoc( $rsl )){
			$kq[] = $row;
		}
		return $kq;
	}

	public function executeQuery_list_id($sql) {
		$rsl = $this->executeQuery($sql);
		$kq = array();
		while($row = mysqli_fetch_assoc( $rsl )){
			$kq[$row['id']] = $row;
		}
		return $kq;
	}
}
