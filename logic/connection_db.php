<?php 
declare(strict_types=1);
require_once 'DatabaseIntarface.php';
class dbConnection implements Database{

    private string $host;
    private string $user; 
    private string $password;
    private string $dbName;

    //read from file per line 
    private function getLines(string $path){
        $file = fopen($path,'r') or die("no such file or directory");
        while($line = fgets($file)){
            $string = explode('=',$line);
            array_walk($string,
            function(&$el){
                $el = trim(str_replace('"','',$el));
            });
            yield $string;
        }
    }

    //open connection to db
    public function connect(){
        $request = explode("/",$_SERVER['REQUEST_URI']);
      //  var_dump($request);
      if($_SERVER['REQUEST_URI']!="/"){
        if($request[2] == "index.php" || $request[2]=="")
            $path = 'config/env';
        else 
            $path = '../config/env';
        foreach(self::getLines($path) as $value){
            $this->{$value[0]} = $value[1];         
        }
    }else {
        $path = 'config/env';
    }
        $connect = mysqli_connect($this->host, $this->user, $this->password, $this->dbName) or die("ERROR " . mysqli_error($link));
        $connect->set_charset("utf-8");
        return $connect;
    }
}

$obj = new dbConnection();
$obj->connect();

?>