<?php 
declare(strict_types=1);
require_once 'DatabaseIntarface.php';
class Database implements DatabaseInterface{

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
        //path for *unix OS
        if($_SERVER['REQUEST_URI']=="/" || $_SERVER['REQUEST_URI']=="/index.php"){
            $path = 'config/env';
        }
        //path for windows
        else{
            $request = explode("/",$_SERVER['REQUEST_URI']);
    
            if($request[2] == "index.php" || $request[2]=="" || $request[1]=="")
                $path = 'config/env';
            else 
                $path = '../config/env';
        }
        //get all parametrs
        foreach(self::getLines($path) as $value){
            $this->{$value[0]} = $value[1];         
        }
        //connect to db
        $connect = mysqli_connect($this->host, $this->user, $this->password, $this->dbName) or die("ERROR " . mysqli_error($link));
        $connect->set_charset("utf-8");
        return $connect;
    }

    public function save($table, $data){
       $keys = implode(", ",array_keys($data));
       $values = implode("', '",array_values($data));
       $query ="INSERT INTO {$table} ({$keys}) VALUES ('$values')";  
       $result = mysqli_query($this->connect(),$query) or die("ERROR " . mysqli_error($this->connect()));   
       return $result;
    }

    public function delete($table, $SKU){
        $query ="DELETE FROM {$table} WHERE SKU ='{$SKU}'";
        mysqli_query($this->connect(), $query) or die("ERROR " . mysqli_error($this->connect()));
        return true;
    }

    public function get_all($table){
        $query = "SELECT * FROM {$table}";
        $result = mysqli_query($this->connect(), $query) or die ("ERROR " . mysqli_error($this->connect()));
        return $result;
    }
}
/*
$obj = new Database();
$obj->delete("products2","ADSAD");
*/
?>