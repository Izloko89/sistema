<?php //script para eliminar articulos desde la tabla de articulos
include("datos.php");
header("Content-type: application/json");
$id_item=$_POST["id_item"];
$cant=$_POST["cantidad"]; //cantidad
$precio=$_POST["precio"]; //cantidad
$total=$cant*$precio;
$cot=$_POST["id_gasto"]; //cotizacion
$art=$_POST["id_articulo"]; //articulo id
$eve=$_POST["id_eve"]; //articulo id
try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	$sql = "";
	$sqlBuscar="";
	if($art!=""){//si es articulo
		if($id_item!=""){//si ya está guardado previamente
			$sql="UPDATE gastos_art SET id_gasto=$art, cantidad=$cant, precio=$precio, total=$total WHERE id_item=$id_item;";
			$r["info"]="Modificacion al <strong>articulo</strong> realizada exitosamente";
		}else{//registro nuevo
			$sql="INSERT INTO 
				gastos_art (id_gEve, id_gasto, cantidad, precio, total)
			VALUES ($eve, $art, $cant, $precio, $total);";
			$sqlBuscar="SELECT id FROM gastos_art WHERE id_gasto=$art AND total=$total LIMIT 1;";
			$r["info"]="<strong>Articulo</strong> guardado exitosamente";
		}
	}
	
	$bd->query($sql);
	
	if($sqlBuscar!=""){
		$res=$bd->query($sqlBuscar);
		$res=$res->fetchAll(PDO::FETCH_ASSOC);
		$id_item=$res[0]["id"];
	}
	
	$r["id"]=$id_item;
	$r["continuar"]=true;
}catch(PDOException $err){
	$r["continuar"]=false;
	$r["info"]="Error encontrado: ".$err->getMessage();
}
//0084609

echo json_encode($r);
?>