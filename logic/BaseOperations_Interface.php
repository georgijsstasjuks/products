<?php 
declare(strict_types=1);
interface base_operations{

//show all records
public function index();

//save record
public static function save(array $data);

//delete record
public static function delete(array $data);


}


?>