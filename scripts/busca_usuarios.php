<?php session_start();
header("Content-type: application/json");
$empresaid=$_SESSION["id_empresa"];
$term=$_GET["term"];
include("datos.php");

try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	//sacar los campos para acerlo más autoámtico
	$campos=array();
	$res=$bd->query("DESCRIBE usuarios;");
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $a=>$c){
		$campos[$a]=$c["Field"];
	}
	
	$res=$bd->query("SELECT * FROM usuarios WHERE nombre LIKE '%$term%';");
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $i=>$v){
		$r[$i]["label"]=$v["nombre"];
		$r[$i]["form"]="#f_usuarios";
		foreach($campos as $campo){
			$r[$i][$campo]=$v[$campo];
		}
		
		$aidi=$bd->query("SELECT id_usuario FROM usuarios WHERE nombre LIKE '%$term%';");
		
	}
	$res=$bd->query("DESCRIBE usuarios_contacto;");
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $a=>$c){
		$campos[$a]=$c["Field"];
	}
		$res=$bd->query("SELECT * FROM usuarios_contacto where id_usuario = " . $r[0]["id_usuario"]);
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $i=>$v){
		foreach($campos as $campo){
			$r[$i][$campo]=$v[$campo];
		}
		
	}
	 // $res=$bd->query("select clave,direccion,colonia,ciudad,estado,cp,telefono,celular,email from usuarios_contacto
                // where id_usuario = $aidi;");
			   		   
		// foreach($res->fetchAll(PDO::FETCH_ASSOC) as $i=>$v){
		// foreach($campos as $campo){
			// $r[$i][$campo]=$v[$campo];
		// }
	
	
	
	/*
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
	*/
}catch(PDOException $err){
	$r["error"] = $err->getMessage();
}

echo json_encode($r);
?>