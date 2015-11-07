<?php session_start();
header("Content-type: application/json");
$empresaid=$_SESSION["id_empresa"];
$term=$_GET["term"];
include("datos.php");

$usuario = $_POST['usuario'];
$nombre = $_POST['nombre'];
$password = $_POST['password'];

echo " 
                <script language=’JavaScript’> 
                alert(‘JavaScript dentro de PHP’); 
                </script>";

try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	
	
	$bd->query("insert into usuarios (usuario,password,nombre,categoria) values ('$usuario','$password','$nombre','administrador')");
	
	
	
}catch(PDOException $err){
	echo $err->getMessage();
}

echo json_encode($r);
?>