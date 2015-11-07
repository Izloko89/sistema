<?php session_start();
header("Content-type: application/json");
$empresaid=$_SESSION["id_empresa"];
$term=$_GET["term"];
include("datos.php");

try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	//sacar los campos para acerlo más autoámtico
	$campos=array();
	$res=$bd->query("DESCRIBE clientes;");
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $a=>$c){
		$campos[$a]=$c["Field"];
	}
	
	$res=$bd->query("SELECT * FROM clientes WHERE id_empresa=$empresaid AND nombre LIKE '%$term%' OR clave = '$term';");
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $i=>$v){
		$r[$i]["label"]=$v["nombre"];
		$r[$i]["form"]="#f_clientes";
		foreach($campos as $campo){
			$r[$i][$campo]=$v[$campo];
		}
	}
	
	$sql = "select * from eventos_pagos where id_cliente = $term  order by id_evento";
	$res = $bd->query($sql);
	$i = 0;
	$id=1;
	$total=0;
	$tabla="<table class=table><tr><th>Evento</th><th>No Pago</th><th>Fecha</th><th>Cantidad</th></tr>";
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $d)
	{
		$rId = $d["id_pago"];
		$tabla.='<tr>';
		$eve1 = explode('_', $d['id_evento']) ;
		$eve = $eve1[1];
		$sql = "select nombre from eventos where id_evento = $eve";
		$res = $bd->query($sql);
		$res=$res->fetchAll(PDO::FETCH_ASSOC);
		$tabla.="<td>".$res[0]["nombre"].'</td>';
		$tabla.="<td>".$d["id_pago"].'</td>';
		$tabla.="<td>".$d["fecha"].'</td>';
		$tabla.='<td>'.$d["cantidad"] .'</td>';
		$tabla.="<td><form action=scripts/pago_pdf.php target=_blank> <input type=submit  value=Imprimir /><input type=hidden name=idPagoPdf id=idPagoPdf value=$rId><input type=hidden name=idEve id=idEve value=" . $d['id_evento'] . "></form></td>";
		$tabla.='</tr>';
	}
	$tabla.="</table>";
	
		$r['tabla'] = $tabla;
	
}catch(PDOException $err){
	echo $err->getMessage();
}

echo json_encode($r);
?>