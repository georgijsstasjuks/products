<?php 
declare(strict_types=1);
interface base_operations{

//show all records
public function index();

//save record
public function save(array $data);

//delete record
public function delete(array $data);


}


?>

