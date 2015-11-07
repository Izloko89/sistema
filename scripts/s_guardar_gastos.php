<?php 
	session_start();
	include("../scripts/datos.php");
	$emp = $_SESSION["id_empresa"];
	$name = $_POST["term"];
	if(isset($name))
	{
		$sql = "";
		try{
			$bd=new PDO($dsnw,$userw,$passw,$optPDO);
			$sql ="insert into gastos(id_empresa, nombre) values($emp, '$name')";
			$bd->query($sql);
			$r["continuar"] = true;
		}
		catch(PDOException $err)
		{
			unset($r);
		}
		echo json_encode($r);
	}
?>