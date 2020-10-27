<?php 
declare(strict_types=1);

interface DatabaseInterface {
    //all classes must have connect, save and delete methods
    public function connect();
    public function save($table, $data);
    public function delete($table, $id);
}