<?php
		include("datos.php");
		$bd=new PDO($dsnw,$userw,$passw,$optPDO);
		$id = $_POST["id_evento"];
		try
		{
			$sql= "delete from eventos where id_evento = $id";
			$bd->query($sql);
			$r["continuar"] = true;
		}
		catch(PDOException $err)
		{
			$r["continuar"]=false;
			$r["info"]="Error: ".$err->getMessage();
		}
		
	echo json_encode($r);
?>