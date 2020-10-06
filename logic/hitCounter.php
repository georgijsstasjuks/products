<?php 
require 'connection_db.php';
session_start();
$connect = new dbConnection();
$connect->connect('products2');
$day =  date("d m Y");
$query ='SELECT * FROM hits ORDER BY id DESC LIMIT 1;';
$result = mysqli_query($connect->connection, $query) or die("ERROR " . mysqli_error($connect->connection));
$object=mysqli_fetch_object($result);

if($result->num_rows==0||$object->currentDate!=$day){
    $query = "INSERT INTO hits(uniqueUsers, total, currentDate) VALUES (0,0,'$day')";
    $result = mysqli_query($connect->connection, $query) or die("ERROR " . mysqli_error($connect->connection)); 
    $query ='SELECT * FROM hits ORDER BY id DESC LIMIT 1;';
    $result = mysqli_query($connect->connection, $query) or die("ERROR " . mysqli_error($connect->connection));
    unset($_SESSION['visited']);
    if(isset($_COOKIE['visited']))
        $_COOKIE['visited']=false;
    $object=mysqli_fetch_object($result);
}

$total = (INT)$object->total;
$unique = (INT)$object->uniqueUsers;

if(!isset( $_SESSION['visited'])){
            $_SESSION['visited']=true;   
            $total =  ++$total;
            if(!isset($_COOKIE['visited'])||$_COOKIE['visited']==false){
                $unique = ++$unique;
                setcookie("visited",true,time()+3600*(24-date("G")));
            }
            $id= $object->id;
            $query = "UPDATE hits SET uniqueUsers='$unique', total='$total' WHERE id=$id";
            mysqli_query($connect->connection,$query) or die("ERROR " . mysqli_error($connect->connection)); ;
        }
    
$content = array('total' =>$total,'unique' =>$unique );

echo json_encode($content);
?>
