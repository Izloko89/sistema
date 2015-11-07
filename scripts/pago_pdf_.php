<?php
	setlocale(LC_ALL,"");
setlocale(LC_ALL,"es_MX");
include_once("datos.php");
require_once('../clases/html2pdf.class.php');
include_once("func_form.php");
$id = 0;
if(isset($_GET["idPagoPdf"])){
	$id=$_GET["idPagoPdf"];
}
$idEve = 0;
if(isset($_GET["idEve"])){
	$idEve=$_GET["idEve"];
}
$cosas = "";
//funciones para convertir px->mm
function mmtopx($d){
	$fc=96/25;
	$n=$d*$fc;
	return $n."px";
}
function pxtomm($d){
	$fc=96/25;
	$n=$d/$fc;
	return $n."mm";
}
function checkmark(){
	$url="http://".$_SERVER["HTTP_HOST"]."/img/checkmark.png";
	$s='<img src="'.$url.'" style="height:10px;" />';
	return $s;
}

try{
	$sql="SELECT logo FROM empresas WHERE id_empresa=1;";
	$bd=new PDO($dsnw,$userw,$passw,$optPDO);
	$res=$bd->query($sql);
	$res=$res->fetchAll(PDO::FETCH_ASSOC);
	$logo='<img src="../'.$res[0]["logo"].'" width="189" />';
}catch(PDOException $err){
	echo "Error: ".$err->getMessage();
}

try{
	//id_evento id_cliente plazo fecha cantidad
	$sql="SELECT eventos_pagos.id_pago, clientes.nombre as cliente, eventos.nombre as evento, eventos_pagos.plazo, eventos_pagos.fecha, eventos_pagos.cantidad, eventos_pagos.modo_pago, bancos.nombre as banco FROM eventos_pagos
	INNER JOIN eventos ON eventos_pagos.id_evento = '$idEve'
	INNER JOIN clientes ON eventos_pagos.id_cliente = clientes.id_cliente
	INNER JOIN bancos ON eventos_pagos.id_banco = bancos.id_banco
	WHERE eventos_pagos.id_pago=$id;";
	$res=$bd->query($sql);
	$cosas=$res->fetchAll(PDO::FETCH_ASSOC);
	if(count($cosas) < 1)
	{
		$sql1="SELECT eventos_pagos.id_pago, clientes.nombre as cliente, eventos.nombre as evento, eventos_pagos.plazo, eventos_pagos.fecha, eventos_pagos.cantidad, eventos_pagos.modo_pago FROM eventos_pagos
		INNER JOIN eventos ON eventos_pagos.id_evento = '$idEve'
		INNER JOIN clientes ON eventos_pagos.id_cliente = clientes.id_cliente
		WHERE eventos_pagos.id_pago=$id;";
		$res=$bd->query($sql1);
		$cosas=$res->fetchAll(PDO::FETCH_ASSOC);
	}
}catch(PDOException $err){
	echo "Error: ".$err->getMessage();
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
$heightCarta=660;
$widthCarta=960;
$celdas=12;
$widthCell=$widthCarta/$celdas;
$mmCartaH=pxtomm($heightCarta);
$mmCartaW=pxtomm($widthCarta);

ob_start();
?>
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
</style>
<table style="width:100%" cellpadding="0" cellspacing="0" >
    <tr>
		 <td style="width:10%"><?php echo $logo; ?></td>
         <td style="width:25%; text-align:center;"><h1>Recibo de pago</h1></td>
		<td valign="top" style="width:20%; text-align:left;">
		<div style="width:40%; background-color:#E1E1E1; font-weight:bold; text-align:center; padding-top:5px; padding-bottom:5px; font-size:12px;">Recibo N&ordm;</div>
		<div style="width:45%; font-size:12px; color:#C00; text-align:center;"><?php echo $id;?></div>
		</td>
    </tr>
</table>
<table style= cellpadding="0" cellspacing="0" >
	<tr>
		<td><div style="width:35%; background-color:#E1E1E1; font-weight:bold; text-align:center; padding-top:5px; padding-bottom:5px; font-size:12px;">Recibi de:</div></td>
		<td><div style="width:45%; font-size:12px; text-align:left;border-bottom:1px solid #000;"><?php echo $cosas[0]["cliente"];?></div></td>
	</tr>
	<tr>
		<td><div style="width:30%; background-color:#E1E1E1; font-weight:bold; text-align:center; padding-top:5px; padding-bottom:5px; font-size:12px;">Monto:</div></td>
		<td><div style="width:45%; font-size:12px; text-align:center;border-bottom:1px solid #000;"><?php echo $cosas[0]["cantidad"];?></div></td>
	</tr>
	<tr>
		<td><div style="width:50%; background-color:#E1E1E1; font-weight:bold; text-align:center; padding-top:5px; padding-bottom:5px; font-size:12px;">Cantidad Escrita:</div></td>
		<td><div style="width:45%; font-size:12px; text-align:center; border-bottom:1px solid #000;"></div></td>
	</tr>
	<tr>
		<td><div style="width:30%; background-color:#E1E1E1; font-weight:bold; text-align:center; padding-top:5px; padding-bottom:5px; font-size:12px;">Concepto:</div></td>
		<td><div style="width:45%; font-size:12px; text-align:center; border-bottom:1px solid #000;"><?php echo $cosas[0]["evento"];?></div></td>
	</tr>
	<tr>
		<td><div style="width:30%; background-color:#E1E1E1; font-weight:bold; text-align:center; padding-top:5px; padding-bottom:5px; font-size:12px;">Fecha:</div></td>
		<td><div style="width:45%; font-size:12px; text-align:center; border-bottom:1px solid #000;"><?php echo $cosas[0]["fecha"];?></div></td>
	</tr>
	<tr>
		<td><div style="width:30%; background-color:#E1E1E1; font-weight:bold; text-align:center; padding-top:5px; padding-bottom:5px; font-size:12px;">Modo de Pago:</div></td>
		<td><div style="width:45%; font-size:12px; text-align:center; border-bottom:1px solid #000;"><?php echo $cosas[0]["modo_pago"];?></div></td>
	</tr>
	<?php if(isset($cosas[0]["banco"])){?>
	<tr>
		<td><div style="width:30%; background-color:#E1E1E1; font-weight:bold; text-align:center; padding-top:5px; padding-bottom:5px; font-size:12px;">Banco:</div></td>
		<td><div style="width:45%; font-size:12px; text-align:center; border-bottom:1px solid #000;"><?php echo $cosas[0]["banco"];?></div></td>
	</tr>
	<?php }?>
</table>
<table style="width:100%; font-size:14px;" cellpadding="0" cellspacing="0" >

</table>
<?php
$html=ob_get_clean();
$path='../docs/';
$filename="generador.pdf";
//$filename=$_POST["nombre"].".pdf";

//configurar la pagina
//$orientar=$_POST["orientar"];
$orientar="portrait";

$topdf=new HTML2PDF($orientar,array($mmCartaW,$mmCartaH),'es');
$topdf->writeHTML($html);
$topdf->Output();
//$path.$filename,'F'

//echo "http://".$_SERVER['HTTP_HOST']."/docs/".$filename;

?>