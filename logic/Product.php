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
            return true;
        }
        $_SESSION["SKU_empty"]=true;
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


    private function validate_data($data){
        $errors = 0;
        if($data['SKU']==""){
            $_SESSION["SKU_empty"]=true;
            $errors++;
        } else {
            $_SESSION["SKU"]=htmlspecialchars($data['SKU']);
        }

        if($data['name']==""){
            $_SESSION["name_empty"]=true;;
            $errors++;
        } else {
            $_SESSION["name"]=htmlspecialchars($data['name']);
        }

        if($data['price']==""){
            $_SESSION["price_empty"]=true;;
            $errors++;
        } else {
            $_SESSION["price"]=htmlspecialchars($data['price']);
        }

        if($errors!=0){
            $_SESSION['required']=true;
            return false;
        }
        return true;
    }

    private function cleanExtraChar(array $filtred_array, string $option, string $characteristic){
        switch ($option){
            case 'Dimension':
                $_SESSION['option_furniture'] = true;
                if(count($filtred_array)==3){ 
                    array_walk($filtred_array,function(&$el){
                    $el=trim(strip_tags($el));
                });
                $characteristic .= implode($filtred_array,"x"); 
                $_SESSION['height'] = htmlspecialchars($filtred_array[2]);
                $_SESSION['width'] = htmlspecialchars($filtred_array[3]);
                $_SESSION['length'] = htmlspecialchars($filtred_array[4]);
                } else {
                    if(!isset($filtred_array[2])){
                        $_SESSION['height_empty'] = true;
                    }else{
                        $_SESSION['height'] = htmlspecialchars($filtred_array[2]);
                    }
                    if(!isset($filtred_array[3])){
                        $_SESSION['width_empty'] = true;
                    }else{
                        $_SESSION['width'] = htmlspecialchars($filtred_array[3]);
                    }
                    if(!isset($filtred_array[4])){
                        $_SESSION['length_empty'] = true;
                    }
                    else{
                        $_SESSION['length'] = htmlspecialchars($filtred_array[4]);
                    }
                    $_SESSION['required'] = true;
                    return false ; 
                }      
                break;
            case 'Size':
                $_SESSION['option_disc'] = true;
                if(count($filtred_array)==1){
                    $characteristic .= trim(strip_tags(implode($filtred_array))) ." MB";
                    $_SESSION['disc'] =htmlspecialchars($filtred_array[0]);
                } else {
                    $_SESSION['required']=true;
                    $_SESSION['disc_empty'] = true;
                    return false;
                } 
                break;
            case 'Weight':
                $_SESSION['option_book'] = true;
                if(count($filtred_array)==1){
                    $characteristic .= trim(strip_tags(implode($filtred_array))) ." Kg"; 
                    $_SESSION['book'] = htmlspecialchars($filtred_array[1]);
                } else {
                    $_SESSION['required']=true;
                    $_SESSION['book_empty'] = true;
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
        $validate = self::validate_data($data);
       
        $cleanData =self::cleanData($data);
        if(!$cleanData || !$validate){
            return false;
        }
       
        $result = self::addProduct($cleanData);
        if($result){
            $_SESSION = [];
            $_SESSION['flash']="success";
        }else {
            $_SESSION['flash']="fail";
        }
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