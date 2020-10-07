<?php 
declare(strict_types=1);
class dbConnection{

    private string $host;
    private string  $user; 
    private string $password;
    public mysqli $connection;


    private function getLines(string $path){
        $file = fopen($path,'r') or die("no such file or directory");
        while($line = fgets($file)){
            $string = explode('=',$line);
            yield trim(str_replace('"','',$string[1]));
        }
    }

    //open connection to db
    public function connect(string $database){
        if($_SERVER['REQUEST_URI']!='/'&&$_SERVER['REQUEST_URI']!='/products/')
            $path = '../config/env';
        else 
            $path = 'config/env';

        foreach(self::getLines($path) as $value){
            $param[] = (string) $value; 
        }
   
        $this->host = $param[0];
        $this->user = $param[1];
        $this->password = $param[2];
        $this->connection = mysqli_connect($this->host, $this->user, $this->password, $database) or die("ERROR " . mysqli_error($link));
        $this->connection->set_charset("utf-8");
    }

        //close connection
    public function close(){
        mysqli_close($this->connection);
    }
        
}

?>