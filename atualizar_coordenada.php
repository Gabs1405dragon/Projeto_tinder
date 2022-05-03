<?php  
session_start();
if(!isset($_SESSION['id'])){
    die();
}
include('MySql.php');
$idUsuario = $_SESSION['id'];

$lat = $_POST['latitude'];
$long = $_POST['longitude'];
$sql = MySql::connect()->prepare("UPDATE usuarios SET lat = ? ,longe = ? WHERE id = $idUsuario");
$sql->execute(array($lat,$long));
