<?php 

declare(strict_types=1);

session_start();

require_once 'Database.php';
require_once 'BaseOperations_Interface.php';
require_once 'DatabaseIntarface.php';


class Product implements base_operations{

    public $database;

    public function __construct(DatabaseInterface $database){
        $this->database = $database;
    }

/* *************************** PRIVATE FUNCTIONS ********************************* */


    //create table if not created yet
    private function createTable(){
      
        $query ="CREATE TABLE IF NOT EXISTS products2 
        (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            SKU VARCHAR(200) NOT NULL,
            name VARCHAR(200) NOT NULL,
            price FLOAT(30) NOT NULL,
            characteristics VARCHAR(200) NOT NULL
        )";
        mysqli_query($this->database->connect(), $query) or die("ERROR " . mysqli_error($this->database->connect()));
    }

    //add products to the db
    private function addProduct(array $data){
        self::checkTable();    
        $check=self::checkSku($data['SKU']);      
        if($check==0){        
            $this->database->save("products2",$data); 
            $_SESSION["flash"]="success";   
            return true;
        }
        $_SESSION["flash"]="ERROR";
    return false;
    }

    //checking for unique SKU 
    private function checkSku(string $SKU){
        $query ="SELECT * FROM products2 WHERE SKU='$SKU'";
        $result = mysqli_query($this->database->connect(), $query) or die("ERROR " . mysqli_error($this->database->connect()));
        return $result->num_rows;
    }


    //clean data before saving(deleting all extraspaces and HTML tags)
    private function cleanData(array $data){

        foreach($data as $key =>$value){
            if(is_array($value)) break;
            $data[$key] = strip_tags(trim($value));
        }
        $characteristic = self::cleanExtraChar($data['characteristics']);
        $data['characteristics'] =  $characteristic;   
        unset($data['action']);
        unset($data['type']);
        
        return $data;
    }

    //remove html entities from special characteristics and prepare data to be saved in the database
    private function cleanExtraChar(array $filtred_array){
        $_POST['characteristics'] = array_filter($_POST['characteristics']);
        $characteristic = "";
        //remove spaces and HTML entities
        array_walk($_POST['characteristics'], 
            function(&$el){
                return trim(strip_tags($el));
        });
        $value = implode('x',$_POST['characteristics']);


        
        $unitOfMeasurement = array_keys($_POST['characteristics']);
        $unitOfMeasurement = array_filter($unitOfMeasurement,
            function($el){
                return !is_numeric($el);
        });
        $unitOfMeasurement = implode("",$unitOfMeasurement);
        $characteristic  .= trim($_POST['type'] . ": " . $value . " " . $unitOfMeasurement);
        return $characteristic;
    }

    //check if table already exists
    private function checkTable(){
        if(!mysqli_query($this->database->connect(), "DESCRIBE `products2`")) {
            self::createTable();
        }
    }


/* *************************** PUBLIC FUNCTIONS ********************************* */  

    //save data
    public function save(array $data){
        $cleanData =self::cleanData($data); 
        $result = self::addProduct($cleanData);
        return true;
    }

    //show all products on main page
    public function index(){
        self::checkTable();
        return $this->database->get_all("products2");
    }
    
    //delete all selected products
    public function delete(array $data){
        foreach($data as $SKU){
            $this->database->delete("products2",$SKU);
        }
        return true;
    }
        
}


?>