<?php session_start();
include("datos.php");

$idCot=$_GET["id_cotizacion"];
$id_empresa=$_SESSION["id_empresa"];
$id_usuario=$_SESSION["id_usuario"];

try{
	$bd=new PDO($dsnw, $userw, $passw, $optPDO);
	$elementos="";
	
	//para saber los artículos---------------------->
	$sqlArt="SELECT
		gastos_art.id_gasto,
		gastos.nombre,
		cantidad,
		precio,
		cantidad*precio as total
	FROM gastos_art
	INNER JOIN gastos ON gastos_art.id_gasto=gastos.id_gasto
	WHERE id_gEve=$idCot;";	
	$res=$bd->query($sqlArt);
	
	//es el id para llenar los elementos en el log de los items de la cotización
	$id=1;
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $v){
		//hacer el select para los precios
		/*$precios='<select class="precios" onchange="darprecio(this);" style="margin-right:3px;">
			<option selected="selected" value="'.$v["precio"].'">$'.$v["precio"].'</option>
			<option disabled="disabled">------</option>
			<option value="'.$v["p1"].'">$'.$v["p1"].'</option>
		</select>';*/
		
		//$id es el id de cotizacion proveniente de la busqueda
		$elementos.='
		<tr id="'.$id.'" class="lista_articulos verde_ok">
			<td style="background-color:#FFF;"><input type="hidden" class="id_item" value="'.$v["id_gasto"].'" /><input type="hidden" class="id_cotizacion" value="'.$idCot.'" /><input type="hidden" class="id_articulo" value="'.$v["id_gasto"].'" /><input type="hidden" class="id_paquete" value="" /></td>
			<td><input class="cantidad" type="text" size="7" onkeyup="cambiar_cant('.$id.');" value="'.$v["cantidad"].'" /></td>
			<td><input class="articulo_nombre text_full_width" onkeyup="art_autocompletar('.$id.');" value="'.$v["nombre"].'" /></td>
			<td><input class="precio" value="'.$v["precio"].'"/></td>
			<td>$<span class="total">'.$v["total"].'</span></td>
			<td><span class="guardar_articulo" onclick="guardar_art('.$id.')"></span><span class="eliminar_articulo" onclick="eliminar_art('.$id.')"></span></td>
		</tr>';
		$id++;
	}
	
	//escribe los elementos de tabla correspondientes
	echo $elementos;
}catch(PDOException $err){
	echo $err->getMessage();
}
?>