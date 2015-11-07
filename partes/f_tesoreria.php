<?php session_start(); 
include("../scripts/funciones.php");
include("../scripts/func_form.php");
include("../scripts/datos.php");
?>
<style>
#f_tipo_evento .guardar_individual{
	position:relative;
}
#f_tipo_evento .modificar{
	position:relative;
}
.salon{
	padding:5px 10px;
	margin-right:10px;
	margin-bottom:10px;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
	border-radius: 6px;
	display:inherit;
	font-weight:bold;
}
.eliminar_tevento{
	background: blue url('img/cruz.png') left center no-repeat;
	background-size:contain;
	cursor:pointer;
	width:20px;
	height:20px;
	display:inherit;
	margin-right:10px;
}
</style>
<form id="f_tipo_evento" class="formularios">
	<h3 class="titulo_form">TESORERIA</h3>
	<div class="formularios">
	<h4 class="titulo_form">Ingresos</h4><br>
	<center>
	<table>
		<tr>
			<td>Evento</td>
			<td>Fecha</td>
			<td>Monto</td>
			<td>Movimiento</td>
		</tr>
		<?php 
			$bd=new PDO($dsnw,$userw,$passw,$optPDO);
			$sql = "select ep.*, e.nombre from eventos_pagos ep
			INNER JOIN eventos e ON e.id_evento = CONCAT('1_', ep.id_evento)";
			$res = $bd->query($sql);
			$res = $res->fetchAll(PDO::FETCH_ASSOC);
				$cant = count($res);
			for($cont = 0; $cont < $cant; $cont++)
			{
				echo '<tr><td>' . $res[$cont]["nombre"].'</td><td>' . $res[$cont]["fecha"].'</td><td>' . $res[$cont]["cantidad"].'</td><td>' . $res[$cont]["modo_pago"].'</td></tr>';
			}			
		?>
	</table>
	</center>
	</div>
	<div class="formularios">
	<h4 class="titulo_form">Egresos</h4><br>
	<center>
		<table>		
		<tr>
			<td>Fecha</td>
			<td>Monto</td>
			<td>Movimiento</td>
		</tr>
		<?php 
			$bd=new PDO($dsnw,$userw,$passw,$optPDO);
			$sql = "select * from bancos_movimientos";
			$res = $bd->query($sql);
			$res = $res->fetchAll(PDO::FETCH_ASSOC);
				$cant = count($res);
			for($cont = 0; $cont < $cant; $cont++)
			{
				echo '<tr><td>' . $res[$cont]["fecha"].'</td><td>' . $res[$cont]["monto"].'</td><td>' . $res[$cont]["movimiento"].'</td></tr>';
			}			
		?>
		</table>
	</center>
	</div>
</form>
<?php /*
<table id="tableEve">
<tr><td><h2>Tipo de Gasto</h2></td></tr>
<?php
	try{
		$bd=new PDO($dsnw,$userw,$passw,$optPDO);
		$id_empresa=$_SESSION["id_empresa"];
		$res=$bd->query("SELECT * FROM gastos WHERE id_empresa=$id_empresa;");
		$cont = 1;
		foreach($res->fetchAll(PDO::FETCH_ASSOC) as $v){
			echo '<tr class="salon fondo_azul" ><td ><div align="left" >'.$v["nombre"].'</div></td><td colspan="2" align="right"><span class="eliminar_tevento" onclick="eliminar_gasto('. $cont .',' . $v["id_gasto"] . ')"/></td></tr>';
			$cont++;
		}
	}catch(PDOException $err){
		echo '<tr><td colspan="20">Error encontrado: '.$err->getMessage().'</td></tr>';
	}
?>
</table>*/?>
<div align="right">
    <input type="button" class="volver" value="VOLVER">
</div>