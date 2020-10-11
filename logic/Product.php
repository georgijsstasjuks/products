<?php 

declare(strict_types=1);

session_start();

require_once 'connection_db.php';
require_once 'BaseOperations_Interface.php';
require_once 'DatabaseIntarface.php';


class Product implements base_operations{

    public $database;

    public function __construct(Database $database){
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
        mysqli_query($this->database->connect()) or die("ERROR " . mysqli_error($this->database->connect()));
    }

    //add products to the db
    private function addProduct(array $data){
        self::checkTable();
        $check=self::checkSku($data['SKU']);
        if($check==0){
            $this->database->save("products2",$data);
            $_SESSION['flash']="success";
            return true;
        } else {
            $_SESSION['flash']="fail";
        }
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
        $option = strip_tags($data['selectedCharacteristic']);  
        $characteristic = $option . ": ";
        $filtred_array = array_filter($data['characteristics']); 
        $checkFillData = true;

        foreach($data as $key =>$value){
            if($key=='selectedCharacteristic'){
                break;
            }
            $data[$key] = strip_tags(trim($value));
        }

        $characteristic = self::cleanExtraChar($filtred_array, $option,$characteristic);
        if(!$characteristic){
            return false;
        }

        $data['characteristics'] =  $characteristic;
        
        unset($data['action']);
        unset($data['selectedCharacteristic']);
        
        return $data;
    }


    private function cleanExtraChar(array $filtred_array, string $option, string $characteristic){

        switch ($option){
            case 'Dimension':
                if(count($filtred_array)==3){ 
                    array_walk($filtred_array,function(&$el){
                    $el=trim(strip_tags($el));
                });
                $characteristic .= implode($filtred_array,"x"); 
                } else {
                    $_SESSION['required']="all fields must be filled";
                    return false;
                }           
                break;
            case 'Size':
                if(count($filtred_array)==1){
                    $characteristic .= trim(strip_tags(implode($filtred_array))) ." MB";;    
                } else {
                    $_SESSION['required']="all fields must be filled";
                    return false;
                } 
                break;
            case 'Weight':
                if(count($filtred_array)==1){
                    $characteristic .= trim(strip_tags(implode($filtred_array))) ." Kg"; 
                } else {
                    $_SESSION['required']="all fields must be filled";
                    return false;
               } 
               break;
           }
    return  $characteristic;
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
        if(!$cleanData){
            return false;
        }
       $result = self::addProduct($cleanData);
       return $result;
    }

    //show all products on main page
    public function index(){
        self::checkTable();
        $query ="SELECT * FROM products2";
        $result = mysqli_query($this->database->connect(), $query) or die("ERROR " . mysqli_error($this->database->connect())); 
        return $result; 
    }
    
    //delete all selected products
    public function delete(array $data){
        foreach($data as $product){
            $query ="DELETE FROM products2 WHERE SKU ='$product'";
            mysqli_query($this->database->connect(), $query) or die("ERROR " . mysqli_error($this->database->connect()));
        }
        return true;
    }
        
}


?>