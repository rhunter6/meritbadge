<?php
class db
{

	//error_reporting(E_ALL);
	
	var $host	= 'localhost';
	var $user	= 'mb';
	var $pass	= '1234';
	var $db		= 'mb_counselors';
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