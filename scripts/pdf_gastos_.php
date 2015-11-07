<?php session_start();
setlocale(LC_ALL,"");
setlocale(LC_ALL,"es_MX");
include_once("datos.php");
require_once('../clases/html2pdf.class.php');
include_once("func_form.php");
$emp=$_SESSION["id_empresa"];

//funciones para usarse dentro de los pdfs
function mmtopx($d){
	$fc=96/25.4;
	$n=$d*$fc;
	return $n."px";
}
function pxtomm($d){
	$fc=96/25.4;
	$n=$d/$fc;
	return $n."mm";
}
function checkmark(){
	$url="http://".$_SERVER["HTTP_HOST"]."/img/checkmark.png";
	$s='<img src="'.$url.'" style="height:10px;" />';
	return $s;
}
function folio($digitos,$folio){
	$usado=strlen($folio);
	$salida="";
	for($i=0;$i<($digitos-$usado);$i++){
		$salida.="0";
	}
	$salida.=$folio;
	return $salida;
}
//tamaño carta alto:279.4 ancho:215.9
$heightCarta=960;
$widthCarta=660;
$celdas=12;
$widthCell=$widthCarta/$celdas;
$mmCartaH=pxtomm($heightCarta);
$mmCartaW=pxtomm($widthCarta);
ob_start();

//sacar los datos del cliente
$error="";
if(isset($_GET["id_evento"])){
	$obs=$_GET["obs"];
	$eve=$_GET["id_evento"];
	try{
		$bd=new PDO($dsnw,$userw,$passw,$optPDO);
		// para saber los datos del cliente
		$sql="SELECT
			t1.id_evento,
			t1.fechaevento,
			t1.fechamontaje,
			t1.fechadesmont,
			t1.id_cliente,
			t2.nombre,
			t3.direccion,
			t3.colonia,
			t3.ciudad,
			t3.estado,
			t3.cp,
			t3.telefono
		FROM eventos t1
		LEFT JOIN clientes t2 ON t1.id_cliente=t2.id_cliente
		LEFT JOIN clientes_contacto t3 ON t1.id_cliente=t3.id_cliente
		WHERE id_evento=$eve;";
		$res=$bd->query($sql);
		$res=$res->fetchAll(PDO::FETCH_ASSOC);
		$evento=$res[0];
		$cliente=$evento["nombre"];
		$telCliente=$evento["telefono"];
		$domicilio=$evento["direccion"]." ".$evento["colonia"]." ".$evento["ciudad"]." ".$evento["estado"]." ".$evento["cp"];
		$fechaEve=$evento["fechaevento"];

		//para saber los articulos y paquetes
		$sql="SELECT
			t1.*,
			t2.nombre
		FROM eventos_articulos t1
		LEFT JOIN articulos t2 ON t1.id_articulo=t2.id_articulo
		WHERE t1.id_evento=$eve;";
		$res=$bd->query($sql);
		$articulos=array();
		foreach($res->fetchAll(PDO::FETCH_ASSOC) as $d){
			if($d["id_articulo"]!=""){
				$art=$d["id_item"];
				unset($d["id_item"]);
				$articulos[$art]=$d;
			}else{
				$art=$d["id_item"];
				unset($d["id_item"]);
				$articulos[$art]=$d;
				$paq=$d["id_paquete"];

				//nombre del paquete
				$sql="SELECT nombre FROM paquetes WHERE id_paquete=$paq;";
				$res3=$bd->query($sql);
				$res3=$res3->fetchAll(PDO::FETCH_ASSOC);
				$articulos[$art]["nombre"]="PAQ. ".$res3[0]["nombre"];

				$sql="SELECT
					t1.cantidad,
					t2.nombre
				FROM paquetes_articulos t1
				INNER JOIN articulos t2 ON t1.id_articulo=t2.id_articulo
				WHERE id_paquete=$paq AND t2.perece=0;";
				$res2=$bd->query($sql);

				foreach($res2->fetchAll(PDO::FETCH_ASSOC) as $dd){
					$dd["precio"]="";
					$dd["total"]="";
					$dd["nombre"]=$dd["cantidad"]." ".$dd["nombre"];
					$dd["cantidad"]="";
					$articulos[]=$dd;
				}
			}
		}
		//para saber el anticipo
		$emp_eve=$emp."_".$eve;
		$sql="SELECT SUM(cantidad) as pagado FROM eventos_pagos WHERE id_evento='$emp_eve';";
		$res=$bd->query($sql);
		$res=$res->fetchAll(PDO::FETCH_ASSOC);
		$pagado=$res[0]["pagado"];
	}catch(PDOException $err){
		$error= $err->getMessage();
	}
}

?>
<?php if($error==""){ ?>
<style>
span{
	display:inline-block;
	padding:10px;
}
h1{
	font-size:20px;
}
.spacer{
	display:inline-block;
	height:1px;
}
td{
	background-color:#FFF;
}
th{
	color:#FFF;
	text-align:center;
}
</style>
<table style="width:100%;border-bottom:<?php echo pxtomm(2); ?> solid #000;" cellpadding="0" cellspacing="0" >
	    <tr>
      <td valign="top" style="width:25%; text-align:center; font-size:10px; background-color:#999"><img src="../img/logo.png" width="52%" height="35" style="width:50%;" /></td>
      <td valign="middle" style="width:15%; text-align:center;">ORDEN DE PAGO</td>
      <td valign="middle" style="width:15%; text-align:right;">Folio</td>
      <td valign="middle" style="width:15%; text-align:left;"><?php echo $folio ?></td>
    </tr>
    </table>
    <table border="0.3px" align="center" style="width:100%;">
      <tr align="center">
    	<td style="width:25%;color:#000; font-size:10px;">Solicitado para el evento:</td>
        <td style="width:25%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>
        <td style="width:25%;color:#000;font-size:10px;text-align:left;">Fecha Solicitud:</td>
        <td style="width:25%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>
    </tr>
    <tr align="center">
    	<td style="width:25%;color:#000; font-size:10px;">Solicitado por:</td>
        <td style="width:25%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>        
        <td style="width:25%;color:#000;font-size:10px;text-align:left;">Fecha Requerido:</td>
        <td style="width:25%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>
    </tr>
    </table>
<div style="border: 0;">
  <table border="0.3px" align="center" style="width:100%;">
      <tr align="center">
    	<th style="width:5%;color:#000; font-size:10px;">PARTIDA</th>
        <th style="width:10%;color:#000; font-size:10px;">CANTIDAD</th>
        <th style="width:45%;color:#000;font-size:10px;text-align:center;">DESCRIPCION</th>
        <th style="width:15%;color:#000;font-size:10px;">PRECIO UNITARIO</th>
        <th style="width:15%;color:#000;font-size:10px;">NOTAS</th>
        <th style="width:10%;color:#000;font-size:10px;">TOTAL</th>        
    </tr>
<?php 
	$total=0;
	foreach($articulos as $id=>$d){ 
	$total+=$d["total"];
?>
    <tr>
        <td style="width:5%;text-align:center;font-size:10px;"></td>
        <td style="width:10%;font-size:10px;"><?php echo $d["cantidad"] ?></td>
        <td style="width:45%;text-align:center;font-size:10px;"><?php echo $d["nombre"] ?></td>
        <td style="width:15%;text-align:right;font-size:10px;"><?php echo $d["total"] ?></td>
        <td style="width:15%;text-align:center;font-size:10px;">&nbsp; </td>
        <td style="width:10%;text-align:center;font-size:10px;"><?php echo $total; ?></td>
    </tr>
<?php } ?>	
  </table>
</div>

 <table border="0.3px" align="center" style="width:100%;">
      <tr align="center">
    	<td style="width:15%;color:#000; font-size:10px;">Proveedor:</td>
        <td style="width:20%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>
        <td style="width:15%;color:#000;font-size:10px;text-align:left;">Clave Proveedor:</td>
        <td style="width:20%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>
        <td style="width:20%;color:#000; font-size:10px;">Total cotizado:</td>
        <td style="width:20%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>        
    </tr>
    </table>
    <table border="0.3px" align="center" style="width:100%;">
    <tr align="center">
    	<td style="width:20%;color:#000; font-size:10px;">Observaciones</td>
        <td style="width:40%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>        
        <td style="width:20%;color:#000;font-size:10px;text-align:left;">Cheque:</td>
        <td style="width:20%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>
    </tr>
    </table>
    <table border="0.3px" align="center" style="width:100%;">
    <tr align="center">
    	<td style="width:15%;color:#000; font-size:10px;">elaborado por:</td>
        <td style="width:20%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>        
        <td style="width:15%;color:#000;font-size:10px;text-align:left;">Seleccionado por:</td>
        <td style="width:20%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>
        <td style="width:20%;color:#000;font-size:10px;text-align:left;">Fecha entrega:</td>
        <td style="width:20%;color:#000;font-size:10px;"><?php echo $fechaEve; ?></td>
    </tr>
    </table>
<?php }else{
	echo $error;
}?>
<?php
$html=ob_get_clean();
$path='../docs/';
$filename="generador.pdf";
$orientar="portrait";

$topdf=new HTML2PDF($orientar,array($mmCartaW,$mmCartaH),'es');
$topdf->writeHTML($html);
$topdf->Output();
?>