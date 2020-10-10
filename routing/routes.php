<?php 
declare(strict_types=1);
    require '../logic/connection_db.php';
    require '../logic/Product.php';
    $obj = new dbConnection();
    $product = new Product($obj);
    $action = $_POST['action'];  

switch($action){

    case "delete":
        if(isset($_POST['card'])){ 
           $result=$product->delete($_POST['card']);  
        }
        break;
    case "add":
        echo 'views/addProducts.php'; 
        break;
    case "save":
        $result = $product->save($_POST);
        break;
}
 