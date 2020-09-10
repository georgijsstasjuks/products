<?php
require 'products.php';

$option = strip_tags($_POST['selectedCharacteristic']);  
$characteristic = $option . ": ";
$filtred_array = array_filter($_POST['characteristics']); 

if($option=='Dimension'){
    if(count($filtred_array)==3){ 
      array_walk($filtred_array,function(&$el){
          $el=trim($el);
      });
      $characteristic .= implode($filtred_array,"x"); 
  }else $_SESSION['required']="all fields must be filled";
  }else if ($option=='Size'){
    if(count($filtred_array)==1){
      $characteristic .= trim(implode($filtred_array)) ." MB";;    
    }else $_SESSION['required']="all fields must be filled";   
  }else if ($option=='Weight'){
    if(count($filtred_array)==1){
      $characteristic .= trim(implode($filtred_array)) ." Kg"; 
  }else $_SESSION['required']="all fields must be filled";
}
    //create a new object of product
if(!isset($_SESSION['required'])){
    $product = new Product(strip_tags(trim($_POST['SKU'])),strip_tags(trim($_POST['name'])),strip_tags(trim($_POST['price'])),strip_tags($characteristic));
    //save created object
    $result = $product->addProduct(); 
}
   