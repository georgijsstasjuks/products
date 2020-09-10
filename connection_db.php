<?php 

class dbConnection{

    private $host = 'localhost';
    private $user = 'root'; 
    private $password = ''; 
    public $db;

        //open connection to db
        public function connect($database){
            $this->db = mysqli_connect($this->host, $this->user, $this->password, $database) or die("ERROR " . mysqli_error($link));
        }

        //close connection
        public function close(){
            mysqli_close($this->db);
        }

}

?>