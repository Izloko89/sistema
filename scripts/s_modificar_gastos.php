<?php
	session_start();
	include("../scripts/datos.php");
	$emp = $_SESSION["id_empresa"];
	$term = $_POST["term"];
	$name = $_POST["name"];
	$r["continuar"] = false;
	if(isset($term))
	{
		try
		{
			$bd=new PDO($dsnw,$userw,$passw,$optPDO);
			$sql ="update gastos set nombre = '$name' where id_gasto = $term";
			$bd->query($sql);
			$r["continuar"] = true;
			$r["info"] = "Articulo editado";
		}
		catch(PDOException $err)
		{
			$r["continuar"] = false;
			$r["info"] = $err->getMessage();
		}
	}
		echo json_encode($r);
?>