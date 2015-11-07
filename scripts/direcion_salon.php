<?php 
	include_once("datos.php");
	$name = $_POST["nombre"];
	$bd=new PDO($dsnw,$userw,$passw,$optPDO);
	$sql = "select direccion from salones where nombre = '$name'";
	$res = $bd->query($sql);
	$res = $res->fetchAll(PDO::FETCH_ASSOC);
	$r = $res[0]["direccion"];
	echo $r;
?>