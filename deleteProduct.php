<?php 

require 'products.php';

$products = new Product();
        
        if($_POST['action']=="Mass delete")
            if(isset($_POST['card'])){ 
                 $products->deleteProduct($_POST['card']);  
               echo '';
            }
        if($_POST['action']=="Add new product"){
            echo 'addProducts.php';  
        } 
        