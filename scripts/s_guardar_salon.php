<?php
	$nombre = $_POST["nombre"];
	$direccion = $_POST["direccion"];
	session_start();
	$emp = $_SESSION["id_empresa"];
	include("datos.php");
try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	//sacar los campos para acerlo más autoámtico	
	$sql="insert into salones(id_empresa, nombre, Direccion) values ($emp, '$nombre', '$direccion')";
	
	$bd->query($sql);
	$r["info"] = "El Salon se agrego";
	$r["continuar"] = true;
	
}catch(PDOException $err){
	$r["info"] = "Ocurrio un error al agregar el salon";
	$r["continuar"] = false;
}

echo json_encode($r);
?>