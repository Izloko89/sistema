<?php session_start();
include("datos.php");
header("Content-type: application/json");
$id_eve=$_GET["term"];
$id_user=$_SESSION["id_usuario"];
try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	$sql="SELECT 
	gastos_eventos.empleado,
	gastos_eventos.fecha1,
	gastos_eventos.fecha2,
	gastos_eventos.fecha3,
	gastos_eventos.direccion,
	gastos_eventos.nombre as np,
	gastos_eventos.telefono,
	eventos.nombre,
	eventos.id_evento,
	eventos.id_cliente
	FROM gastos_eventos
	INNER JOIN eventos ON eventos.id_evento=gastos_eventos.id_evento 
	WHERE gastos_eventos.id = $id_eve;";
	
	$res=$bd->query($sql);
	$filas=$res->rowCount();
	$res=$res->fetchAll(PDO::FETCH_ASSOC);
	
	if($filas>0){
		//arreglar fechas
		if($res[0]["fecha1"]!=0){
			$res[0]["fecha1"]=date("d/m/Y h:i a",strtotime($res[0]["fecha1"]));
		}
		if($res[0]["fecha2"]!=0){
			$res[0]["fecha2"]=date("d/m/Y h:i a",strtotime($res[0]["fecha2"]));
		}
		if($res[0]["fecha3"]!=0){
			$res[0]["fecha3"]=date("d/m/Y h:i a",strtotime($res[0]["fecha3"]));
		}
		//se escribe el row obtenido
		$res[0]["bool"]=true;
		echo json_encode($res[0]);
	}else{
		$res=array(
			"bool"=>false,
			"id_empresa"=>$_SESSION["id_empresa"],
			"id_usuario"=>$_SESSION["id_usuario"]
		);
		echo json_encode($res);
	}
	
}catch(PDOException $err){
	echo json_encode($err);
}

?>