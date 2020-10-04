<?php 

class dbConnection{

    private $host = 'localhost';
    private $user = 'root'; 
    private $password = '12345678'; 
    public $connection;

        //open connection to db
        public function connect($database){
            $this->connection = mysqli_connect($this->host, $this->user, $this->password, $database) or die("ERROR " . mysqli_error($link));
        }

        //close connection
        public function close(){
            mysqli_close($this->connection);
        }

}

?>