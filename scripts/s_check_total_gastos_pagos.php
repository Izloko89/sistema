<?php session_start();
include("datos.php");

$id=$_POST["id"];

try{
	$sql="";
	$bd=new PDO($dsnw,$userw,$passw,$optPDO);
	
	$sql="SELECT SUM(cantidad) as total FROM `gastos_pagos` WHERE `id_evento` = $id;";
	
	$res=$bd->query($sql);
	$res=$res->fetchAll(PDO::FETCH_ASSOC);
	
	$r["pagado"]=$res[0]["total"];
}catch(PDOException $err){
	$r=0;
}

echo json_encode($r);
?>