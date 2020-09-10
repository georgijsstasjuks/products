<?php 
session_start();
require_once 'connection_db.php';

class Product{

private $SKU;
private $name;
private $price;
private $characteristics;

    public function __construct($SKU='',$name='',$price='',$characteristics=''){
        $this->SKU=$SKU;
        $this->name=$name;
        $this->price=$price;
        $this->characteristics=$characteristics;        
    }

    //create table if not created yet
    public function createTable(){
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
        mysqli_query($db->db, $query); 
    }

    //show all products on main page
    public function index(){
        $db = new dbConnection();
        $db->connect('products2');
        $query ="SELECT * FROM products2";
        $result = mysqli_query($db->db, $query) or die("ERROR " . mysqli_error($db->db)); 
        return $result; 
    }

    //add products to the db
    public function addProduct(){
        $db = new dbConnection();
        $db->connect('products2');
        $this->createTable();
        $check=$this->checkSku($this->SKU);
        if($check==0){
            $query ="INSERT INTO products2 (SKU, name, price, characteristics) VALUES ('$this->SKU','$this->name','$this->price','$this->characteristics')";
            $result = mysqli_query($db->db, $query) or die("ERROR " . mysqli_error($db->db)); 
            $db->close();
            $_SESSION['flash']="success";
        }else 
            $_SESSION['flash']="fail";
    }
    //checking for unique SKU 
    public function checkSku($SKU){
        $db = new dbConnection();
        $db->connect('products2');
        $query ="SELECT * FROM products2 WHERE SKU='$SKU'";
        $result = mysqli_query($db->db, $query) or die("ERROR " . mysqli_error($db->db)); 
        return $result->num_rows;
    }
    //delete all selected products
    public function deleteProduct($products){
        $db = new dbConnection();
        $db->connect('products2');
        foreach($products as $product){
            $query ="DELETE FROM products2 WHERE SKU ='$product'";
            mysqli_query($db->db, $query) or die("ERROR " . mysqli_error($link));
        } 
    }

    

}


?>