<?php
	
	$art=$_GET["art"];
	$cant=$_GET["cant"];
	$cot=$_GET["cot"];
	include("datos.php");

	try{
		$bd=new PDO($dsnw, $userw, $passw, $optPDO);
		$sql = "select almacen.cantidad, articulos.nombre from almacen
				INNER JOIN articulos ON articulos.id_articulo = almacen.id_articulo
				where almacen.id_articulo = $art";
		$res = $bd->query($sql);
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$cantidad =  $res[0]["cantidad"];
		if($cant > $cantidad)
		{
			$r = "La cantidad total en almacen es de " . $cantidad . " " . $res[0]["nombre"];
			echo $r;
			exit;
		}
		$sql = "select fechamontaje, fechadesmont from cotizaciones where id_cotizacion = $cot";
		$res = $bd->query($sql);
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$fechamontaje = $res[0]["fechamontaje"];
		$fechadesmont = $res[0]["fechadesmont"];
		$sql = "select 'no disponible' as ndisponible, eventos.nombre, cantidad from eventos
				INNER JOIN eventos_articulos ON eventos_articulos.id_evento =  eventos.id_evento
				where '$fechamontaje' BETWEEN fechamontaje AND fechadesmont OR '$fechadesmont' BETWEEN fechamontaje AND fechadesmont AND eventos_articulos.id_articulo = $art ";
		$res = $bd->query($sql);
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		if($res[0]["ndisponible"] != "")
		{
			$r = "Hay otro evento ocupando el articulo el mismo dia";
			echo $r;
			exit;
		}
	}catch(PDOException $err){
		echo $err->getMessage();
	}
?>