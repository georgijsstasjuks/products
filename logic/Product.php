<?php 

declare(strict_types=1);


session_start();

require_once 'connection_db.php';
require_once 'ProductInterface.php';



class Product implements base_operations{

private $SKU;
private $name;
private $price;
private $characteristics;

    public function __construct($SKU='',$name='',$price='',$characteristics=''){
              
    }

/* *************************** PRIVATE FUNCTIONS ********************************* */


    //create table if not created yet
    private function createTable(){
        $db = new dbConnection();
        $db->connect('products2');
        $query ="CREATE TABLE IF NOT EXISTS products2 
        (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            SKU VARCHAR(200) NOT NULL,
            name VARCHAR(200) NOT NULL,
            price FLOAT(30) NOT NULL,
            characteristics VARCHAR(200) NOT NULL
        )";
        mysqli_query($db->connection, $query) or die("ERROR " . mysqli_error($db->connection));
        $db->close();
    }

    //add products to the db
    private function addProduct($data){
        $db = new dbConnection();
        $db->connect('products2');
        self::createTable();
        $data = (object) $data;
        $check=self::checkSku($data->SKU);
        if($check==0){
            $query ="INSERT INTO products2 (SKU, name, price, characteristics) VALUES ('$data->SKU','$data->name','$data->price','$data->characteristics')";
            $result = mysqli_query($db->connection, $query) or die("ERROR " . mysqli_error($db->connection)); 
            $_SESSION['flash']="success";
            $db->close();
            return true;
        } else {
            $_SESSION['flash']="fail";
        }
    $db->close();
    return false;
    }

    //checking for unique SKU 
    private function checkSku($SKU){
        $db = new dbConnection();
        $db->connect('products2');
        $query ="SELECT * FROM products2 WHERE SKU='$SKU'";
        $result = mysqli_query($db->connection, $query) or die("ERROR " . mysqli_error($db->connection)); 
        $db->close();
        return $result->num_rows;
    }


    //clean data before saving(deleting all extraspaces and HTML tags)
    private function cleanData($data){
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
        return $data;
    }


    private function cleanExtraChar($filtred_array, $option,$characteristic){

        switch ($option){
            case 'Dimension':
                if(count($filtred_array)==3){ 
                    array_walk($filtred_array,function(&$el){
                    $el=trim($el);
                });
                $characteristic .= implode($filtred_array,"x"); 
                } else {
                    $_SESSION['required']="all fields must be filled";
                    return false;
                }           
                break;
            case 'Size':
                if(count($filtred_array)==1){
                    $characteristic .= trim(implode($filtred_array)) ." MB";;    
                } else {
                    $_SESSION['required']="all fields must be filled";
                    return false;
                } 
                break;
            case 'Weight':
                if(count($filtred_array)==1){
                    $characteristic .= trim(implode($filtred_array)) ." Kg"; 
                } else {
                    $_SESSION['required']="all fields must be filled";
                    return false;
               } 
               break;
           }

    return  $characteristic;
    }


/* *************************** PUBLIC FUNCTIONS ********************************* */

    //save data
    static public function save($data){
        $cleanData =self::cleanData($data);
        if(!$cleanData){
            return false;
        }
        $result = self::addProduct($cleanData);
        return $result;
    }

    //show all products on main page
    public function index(){
        $db = new dbConnection();
        $db->connect('products2');
        self::createTable();
        $query ="SELECT * FROM products2";
        $result = mysqli_query($db->connection, $query) or die("ERROR " . mysqli_error($db->connection)); 
        return $result; 
    }
    
    //delete all selected products
    static public function delete($data){
        $db = new dbConnection();
        $db->connect('products2');
        foreach($data as $product){
            $query ="DELETE FROM products2 WHERE SKU ='$product'";
            mysqli_query($db->connection, $query) or die("ERROR " . mysqli_error($db->connection));
        } 
        $db->close();
    }
        
}


?>