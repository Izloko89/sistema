<?php //script para eliminar articulos desde la tabla de articulos
include("datos.php");
header("Content-type: application/json");
$id_item=$_POST["id_item"];
try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	$sql = "select total, id_evento from eventos_articulos WHERE id_item=$id_item;";
	$res = $bd->query($sql);
	$res = $res->fetchAll(PDO::FETCH_ASSOC);
	$total = $res[0]["total"];
	$eve = "1_" . $res[0]["id_evento"];
	
	$sql = "select total from eventos_total";
	$res = $bd->query($sql);
	$res = $res->fetchAll(PDO::FETCH_ASSOC);
	$totalEve = $res[0]["total"];
	
	$ttl = $totalEve - $total;
	$sql = "update eventos_total set total = $ttl where id_evento = '$eve'";
	$res = $bd->query($sql);
	
	$sql="DELETE FROM eventos_articulos WHERE id_item=$id_item;";
	
	$bd->query($sql);
	
	//quitar el articulo de las entradas y salidas usando el id_item
	$sql="DELETE FROM almacen_entradas WHERE id_item=$id_item;";
	$bd->query($sql);
	$sql="DELETE FROM almacen_salidas WHERE id_item=$id_item;";
	$bd->query($sql);
	
	$r["continuar"]=true;
	$r["info"]="Articulo eliminado satisfactoriamente";
}catch(PDOException $err){
	$r["continuar"]=false;
	$r["info"]="Error encontrado: ".$err->getMessage();
}

echo json_encode($r);
?>