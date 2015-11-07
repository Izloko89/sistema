<?php session_start();
header("Content-type: application/json");
$empresaid=$_SESSION["empresaid"];
$term=$_GET["term"];
include("datos.php");

try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	//sacar los campos para acerlo más autoámtico	
	$sqlArt="SELECT 
		nombre as label,
		articulos.id_articulo,
		precio1 as precio
	FROM articulos
	INNER JOIN listado_precios ON articulos.id_articulo=listado_precios.id_articulo
	WHERE articulos.id_empresa=$empresaid AND nombre LIKE '%$term%';";
	
	$sqlPaq="SELECT 
		nombre as label,
		paquetes.id_paquete,
		precio1 as precio
	FROM paquetes
	INNER JOIN listado_precios ON paquetes.id_paquete=listado_precios.id_paquete
	WHERE paquetes.id_empresa=$empresaid AND nombre LIKE '%$term%';";
	
	$i=0;
	$res=$bd->query($sqlArt);
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $v){
		$r[$i]=$v;
		$i++;
	}
	
	$res=$bd->query($sqlPaq);
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $v){
		$r[$i]=$v;
		$i++;
	}
	
}catch(PDOException $err){
	echo $err->getMessage();
}

echo json_encode($r);
?>