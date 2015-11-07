<?php session_start();
include("datos.php");
include("func_form.php");
$eve=$_POST["eve"];

try{
	$rId = 0;
	$sql="SELECT * FROM `gastos_pagos` WHERE id_evento='$eve';";
	$bd=new PDO($dsnw,$userw,$passw,$optPDO);
	$res=$bd->query($sql);
	
	$tabla="<table class=table><tr><th>No Pago</th><th>Fecha</th><th>Cantidad</th></tr>";
	$id=1;
	$total=0;
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $d){
		$rId = $d["id_pago"];
		$tabla.='<tr>';
		$tabla.="<td>".$id.'</td>';
		$tabla.="<td>".varFechaExtensa($d["fecha"]).'</td>';
		$tabla.='<td>'.$d["cantidad"] .'</td>';
		$tabla.="<td></td>";
		$tabla.='</tr>';
		$id++;
		$total+=$d["cantidad"];
	}
	$tabla.='<tr><td></td><td style="text-align:right;">Total=</td><td>'.$total.'</td></tr>';
	$tabla.="</table>";
	echo $tabla;
}catch(PDOException $err){
	echo "Error: ".$err->getMessage();
}
?>