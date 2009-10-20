<?php

require_once "includes/conf/mysql.conf.php";

class Database
{
	// implement singleton pattern
	static private $instance = null;
	
	private $conn;

	static public function getInstance()
    {
		if (null === self::$instance) 
		{
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	// ctor
	private function __construct()
	{
		global $conf;
		$this->conn = mysql_connect( $conf['mysql_hostname'], 
			$conf['mysql_username'], 
			$conf['mysql_password'] ) 
			or die ("Connection to database failed!" . mysql_error());
			
		mysql_select_db( $conf['mysql_database'], $this->conn )
			or die ("Selection of database failed! " . mysql_error());
	}
	
	private function checkConnect()
	{
		if (!isset($this->conn))
		{
			die("Not connected to database");
		}
	}
	
	// returns the value in the first row and column
	public function getValue( $sql )
	{
		$this->checkConnect();
		
		$res = mysql_query( $sql, $this->conn );
		if (!$res) 
		{
			die('Error while calling database: ' . mysql_error());
		}
		$vals = mysql_fetch_row( $res );
		mysql_free_result( $res );
		return $vals[0];
	}
	
	// executes some sql and returns affected rows
	public function exec( $sql )
	{
		$this->checkConnect();
		
		$res = mysql_query( $sql, $this->conn );
		if (!$res) 
		{
			die('Error while calling database: ' . mysql_error());
		}
		$numrows = mysql_affected_rows( $this->conn );
		return $numrows;
	}
	
	public function escape( $string )
	{
		$this->checkConnect();
		
		return mysql_real_escape_string( $string, $this->conn );
	}
	
	public function disconnect()
	{
		if ( mysql_ping( $this->conn ) )
		{
			mysql_close( $this->conn );
		}
	}
	
}


?>