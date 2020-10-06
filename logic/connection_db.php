<?php 

class dbConnection{

    private $host;
    private $user; 
    private $password;
    public $connection;

        //open connection to db
    public function connect($database){
        if($_SERVER['REQUEST_URI']!='/products/')
            $file = fopen('../config/env','r') or die();
        else 
            $file = fopen('config/env','r') or die();
        while(!feof($file)){
            $string = fgets($file);
            $field = explode("=",$string);
            $param[] = trim(str_replace('"', '',$field[1]));
        }
        $this->host = $param[0];
        $this->user = $param[1];
        $this->password = $param[2];
        $this->connection = mysqli_connect($this->host, $this->user, $this->password, $database) or die("ERROR " . mysqli_error($link));
        fclose($file);
        }

        //close connection
        public function close(){
            mysqli_close($this->connection);
        }
        
}


?>