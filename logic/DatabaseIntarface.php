<?php 
declare(strict_types=1);

interface DatabaseInterface {
    public function connect();
    public function save($table, $data);
    public function delete($table, $id);
}