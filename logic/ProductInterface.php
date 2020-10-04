<?php 

interface base_operations{

//show all records
public function index();

//save record
public static function save($data);

//delete record
public static function delete($data);


}


?>