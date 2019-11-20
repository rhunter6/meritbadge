<?php
class db
{
    //error_reporting(E_ALL);

    public $host = 'localhost';
    public $user = 'nsdbsa_mb_c';
    public $pass = 'scouts101#';
    public $db = 'nsdbsa_mb_counselors';
    public $connection;
    public $data;

    public function __construct()
    {
        $this->data = $_POST;
        $this->connection = mysqli_connect($this->host, $this->user, $this->pass)
        or die(mysqli_connect_error());
        mysqli_select_db($this->connection, $this->db);
    }

    public function __destructor()
    {
        mysqli_close($this->connection);
    }

}
