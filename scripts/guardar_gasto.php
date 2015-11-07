<?php
	include("datos.php");
	$empleado  = $_POST["empleado"];
	$evento  = $_POST["evento"];
	$direccion  = $_POST["direccion"];
	$nombre  = $_POST["nombre"];
	$telefono  = $_POST["telefono"];
	$fecha1  = $_POST["fecha1"];
	$fecha2  = $_POST["fecha2"];
	$fecha3  = $_POST["fecha3"];
	
try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	$sql = "insert into gastos_eventos(id_evento, empleado, fecha1, fecha2, fecha3, direccion, nombre, telefono)
								values($evento, '$empleado', '$fecha1', '$fecha2', '$fecha3', '$direccion', '$nombre', '$telefono')";
	$bd->query($sql);
	$r["continuar"] = true;
}catch(PDOException $err){
	echo $err->getMessage();
	$r["continuar"] = false;
}
echo json_encode($r);
?>