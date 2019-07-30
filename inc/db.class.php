<?php
class db
{

	//error_reporting(E_ALL);
	
	var $host	= 'localhost';
	var $user	= 'nsdbsa_mb_c';
	var $pass	= 'scouts101#';
	var $db		= 'nsdbsa_mb_counselors';
	var $connection; 	
	var $data;	

	
	function __construct()
	{
		$this->data = $_POST;
		$this->connection = mysqli_connect($this->host, $this->user, $this->pass) or die(mysql_error()); 
		mysqli_select_db($this->connection, $this->db); 						
	}
		
	function __destructor()
	{
		mysqli_close($this->connection);
	}
	
}


?>