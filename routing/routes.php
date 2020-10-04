<?php 

require '../logic/Product.php';

$products = new Product();
$action = $_POST['action'];  

switch($action){

    case "delete":
        if(isset($_POST['card'])){ 
           Product::delete($_POST['card']);  
        echo '';
        }
        break;
    case "add":
        echo 'views/addProducts.php'; 
        break;
    case "save":
        $result = Product::save($_POST);
        echo '';
        break;
}
 