<?php session_start();
setlocale(LC_ALL,"");
setlocale(LC_ALL,"es_MX");
include_once("datos.php");
require_once('../clases/html2pdf.class.php');
include_once("func_form.php");
$emp= $_GET["id_Folio"];
$cheque = $_GET["cheque"];
$elaborado = $_GET["elaborado"];
$selec = $_GET["selec"];
$obs = $_GET["obs"];
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
	$cot=$_GET["total_cot"];
	try{
		$bd=new PDO($dsnw,$userw,$passw,$optPDO);
			$sql = "SELECT cantidad, precio, total, nombre
					FROM gastos_art
					INNER JOIN gastos ON gastos.id_gasto = gastos_art.id_gasto
					WHERE id_gEve =$eve";
			$art = $bd->query($sql);
			$art=$art->fetchAll(PDO::FETCH_ASSOC);
			$sql = "select * from gastos_eventos where id = $eve";
			$evento = $bd->query($sql);
			
			
		// para saber los datos del cliente
		$sql="SELECT
			t1.id_evento,
			t1.empleado,
			t1.fecha1,
			t1.fecha2,
			t1.fecha3,
			t2.nombre
		FROM gastos_eventos t1
		LEFT JOIN eventos t2 ON t1.id_evento=t2.id_evento
		WHERE t1.id_evento=$eve;";
		$res=$bd->query($sql);
		$res=$res->fetchAll(PDO::FETCH_ASSOC);
		$event=$res[0];
		$nombre=$event["empleado"];
		$nombreeve=$event["nombre"];
		$fecha1=$event["fecha1"];
		$fecha2=$event["fecha2"];
		$fecha3=$event["fecha3"];

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
<table style="width:100%; border:0.5px solid #000;" cellpadding="0" cellspacing="0" >
	    <tr>
      <td valign="middle" style="width:25%; text-align:center; font-size:14px; background-color:#000000; color:#FFFFFF;">bariconcept</td>
      <td valign="middle" style="width:35%; text-align:center;border:0.3px">ORDEN DE PAGO</td>
      <td valign="middle" style="width:15%; text-align:right;border:0.3px">Folio</td>
      <td valign="middle" style="width:25%; text-align:left; color:#C00;border:0.3px">N&ordm; <?php echo $emp ?></td>
    </tr>
    </table >
    <table cellpadding="0" cellspacing="0" style=" font-size:10px;width:100%; border:0.5px solid #000;">
      <tr align="center">
    	<td style="width:25%;color:#000; font-size:10px;border:0.3px">Solicitado para el evento:</td>
        <td style="width:25%;color:#000;font-size:10px;border:0.3px"><?php echo $nombreeve ?></td>
        <td style="width:25%;color:#000;font-size:10px;text-align:left;border:0.3px">Fecha Solicitud:</td>
        <td style="width:25%;color:#000;font-size:10px;border:0.3px"><?php echo $fecha1 ?></td>
    </tr>
    <tr align="center">
    	<td style="width:25%;color:#000; font-size:10px;border:0.3px">Solicitado por:</td>
        <td style="width:25%;color:#000;font-size:10px;border:0.3px"><?php echo $nombre ?></td>        
        <td style="width:25%;color:#000;font-size:10px;text-align:left;border:0.3px">Fecha Requerido:</td>
        <td style="width:25%;color:#000;font-size:10px;border:0.3px"><?php echo $fecha2 ?></td>
    </tr>
    </table>
    <br/>
  <table cellpadding="0" cellspacing="0" style=" font-size:10px;width:100%; border:0.5px solid #000;">
      <tr align="center">
    	<th style="width:10%;color:#000;border:0.3px">PARTIDA</th>
        <th style="width:10%;color:#000;border:0.3px ">CANTIDAD</th>
        <th style="width:40%;color:#000;border:0.3px">DESCRIPCION</th>
        <th style="width:15%;color:#000;border:0.3px">PRECIO UNITARIO</th>
        <th style="width:15%;color:#000;border:0.3px">NOTAS</th>
        <th style="width:10%;color:#000;border:0.3px">TOTAL</th>        
    </tr>
	
	<?php 
		if(sizeof($res)>=0){
			for($i=0 ; $i<sizeof($res) ; $i++)
			{
				$articulos=$art[$i];
				
				$cantidad = $articulos['cantidad'];
				$nombre = $articulos['nombre'];
				$precio = $articulos['precio'];
				$total = $articulos['total'];
				
				$imprimir = <<<EOT
							<tr align="center">
								<td style="width:10%;color:#000; border:0.3px ">1</td>
								<td style="width:10%;color:#000; border:0.3px ">$cantidad</td>
								<td style="width:40%;color:#000; border:0.3px ">$nombre</td>
								<td style="width:15%;color:#000; border:0.3px ">$precio</td>
								<td style="width:15%;color:#000; border:0.3px "></td>
								<td style="width:10%;color:#000; border:0.3px ">$total</td>        
							</tr>

EOT;
							
				echo $imprimir;
			}
		}
	?>
	
  </table>
  <br/>
 <table cellpadding="0" cellspacing="0" style=" font-size:10px;width:100%; border:0.5px solid #000;">
      <tr align="center">
    	<td style="width:20%;color:#000; font-size:10px;">Proveedor:</td>
        <td style="width:20%;color:#000;font-size:10px;"></td>
        <td style="width:15%;color:#000;font-size:10px;text-align:left;">Clave Proveedor:</td>
        <td style="width:10%;color:#000;font-size:10px;"></td>
        <td style="width:20%;color:#000; font-size:10px;">Total cotizado:</td>
        <td style="width:15%;color:#000;font-size:10px;"><?php echo $cot ?></td>        
    </tr>
    </table>
  <table cellpadding="0" cellspacing="0" style=" font-size:10px;width:100%; border:0.5px solid #000;">
    <tr align="center">
    	<td style="width:20%;color:#000; font-size:10px;">Observaciones</td>
        <td style="width:40%;color:#000;font-size:10px;"><?php echo $obs;?></td>        
        <td style="width:20%;color:#000;font-size:10px;text-align:left;">Cheque:</td>
        <td style="width:20%;color:#000;font-size:10px;"><?php echo $cheque;?></td>
    </tr>
    </table>
    <table cellpadding="0" cellspacing="0" style=" font-size:10px;width:100%; border:0.5px solid #000;">
    <tr align="center">
    	<td style="width:15%;color:#000; font-size:10px;">elaborado por:</td>
        <td style="width:15%;color:#000;font-size:10px;"><?php echo $elaborado;?></td>        
        <td style="width:15%;color:#000;font-size:10px;text-align:left;">Seleccionado por:</td>
        <td style="width:20%;color:#000;font-size:10px;"><?php echo $selec;?></td>
        <td style="width:15%;color:#000;font-size:10px;text-align:left;">Fecha entrega:</td>
        <td style="width:20%;color:#000;font-size:10px;"><?php echo $fecha3 ?></td>
    </tr>
    </table>
    	  <?php
$html=ob_get_clean();
$path='../docs/';
$filename="generador.pdf";
$orientar="portrait";

$topdf=new HTML2PDF($orientar,array($mmCartaW,$mmCartaH),'es');
$topdf->writeHTML($html);
$topdf->Output();
?>
   	   
<?php }else{
	echo $error;
}?>