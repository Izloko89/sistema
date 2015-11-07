<?php session_start();
setlocale(LC_ALL,"");
setlocale(LC_ALL,"es_MX");
include_once("datos.php");
require_once('../clases/html2pdf.class.php');
include_once("func_form.php");
$emp=$_SESSION["id_empresa"];
$id = 0;
if(isset($_GET["id"])){
	$id=$_GET["id"];
}

//funciones para convertir px->mm
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

try{
	$bd=new PDO($dsnw,$userw,$passw,$optPDO);
	// para saber los datos del cliente
	$sql="SELECT 
		t1.id_cotizacion,
		t1.nombre As nombreEvento,
		t1.fecha,
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
	FROM cotizaciones t1
	LEFT JOIN clientes t2 ON t1.id_cliente=t2.id_cliente
	LEFT JOIN clientes_contacto t3 ON t1.id_cliente=t3.id_cliente
	WHERE id_cotizacion=$id;";
	$res=$bd->query($sql);
	$res1=$res->fetchAll(PDO::FETCH_ASSOC);
	$evento=$res1[0];
	$cliente=$evento["nombre"];
	$telCliente=$evento["telefono"];
	$nombreEve=$evento["nombreEvento"];
	$domicilio=$evento["direccion"]." ".$evento["colonia"]." ".$evento["ciudad"]." ".$evento["estado"]." ".$evento["cp"];
	$fecha=$evento["fecha"];
	$fechaEve=$evento["fechaevento"];
	//print_r($fecha);
	
}catch(PDOException $err){
	echo $err->getMessage();
}
$bd=NULL;

//para saber los articulos y paquetes
try{
	$bd=new PDO($dsnw,$userw,$passw,$optPDO);
	$sql="SELECT
		t1.*,
		t2.nombre
	FROM cotizaciones_articulos t1
	LEFT JOIN articulos t2 ON t1.id_articulo=t2.id_articulo
	WHERE t1.id_cotizacion=$id;";
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
}catch(PDOException $err){
	echo $err->getMessage();
}

//var_dump($articulos);

$html='
<page backbottom="15px">
	
	<page_footer> 
		<table border="0" cellpadding="0" cellspacing="0" style="font-size:13px; width:100%; margin-top:30px; padding:0 20px;">
			<tr>
				<td style="width:100%;vertical-align:top; text-align:center;">
					<p style="width:100%; text-align:center; margin:5px auto; font-size:10px;">Oficina en Eulogio Parra # 2714 Col. Providencia. Guadalajara, Jalisco, México. Tel: 52 (33) 3642 0913,
						3642 0904, 3832 5933 </p>
				</td>
			</tr>
		</table>
	</page_footer> 
	
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
<table style="width:100%;border-bottom:' .pxtomm(2).'solid #000;" cellpadding="0" cellspacing="0" >    
	<tr>
		<td valign="top" style="width:20%; text-align:left;">
		<div style="width:80%; background-color:#E1E1E1; font-weight:bold; text-align:center; padding-top:5px; padding-bottom:5px; font-size:12px;">COTIZACION N&ordm;</div>
		<div style="width:90%; font-size:12px; color:#C00; text-align:center;"> '. folio(4,$id).'</div>
		</td>
		<td valign="top" style="width:60%; text-align:center; font-size:10px;"><img src="../img/logo.png" style="width:50%;" /></td>
		<td valign="top" style="width:20%; text-align:left;">
		</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" style=" font-size:12px;width:100%; margin-top:10px; padding:0 20px;">
	<tr>
    	<td style="width:20%;">Fecha: <div style="margin-left:5px;width:50%; border-bottom:1px solid #000;">'.varFechaAbr($fecha).'</div></td>
        <td style="width:50%;">Cliente: <div style="margin-left:5px;width:80%; border-bottom:1px solid #000;">'. $cliente.'</div></td>
        <td style="width:30%;">Tel:<div style="margin-left:5px;width:80%; border-bottom:1px solid #000;">'. $telCliente.'</div></td>
    </tr>
</table>

<table cellpadding="0" cellspacing="0" style=" font-size:12px;width:100%; margin-top:10px; padding:0 20px;">
	<tr>
		<td style="width:20%;">Evento: <div style="margin-left:5px;width:50%; border-bottom:1px solid #000;">'. $nombreEve.'</div></td>
    	<td style="width:40%;">Fecha del Evento: <div style="margin-left:5px;width:50%; border-bottom:1px solid #000;">'. varFechaAbr($fechaEve).'</div></td>
        <td style="width:40%;">Lugar:<div style="margin-left:5px;width:80%; border-bottom:1px solid #000;">'. $domicilio.'</div></td>
    </tr>
</table>

<br>
<table border="0.3" cellspacing="-0.5" cellpadding="1" style="width:100%;font-size:10px;margin-top:5px; padding:5 30px">
	<tr align="center">
    	<th style="width:15%;">CANT.</th>
        <th style="width:55%;">CONCEPTO</th>
        <th style="width:15%;">P.U.</th>
        <th style="width:15%;">IMPORTE</th>
    </tr>';
	$total=0;
	foreach($articulos as $id=>$d){ 
	$total+=$d["total"];
		$html.='
    <tr>
        <td style="width:15%;text-align:center;">'.$d["cantidad"].'</td>
        <td style="width:55%;">'. $d["nombre"].'</td>
        <td style="width:15%;text-align:center;">'. number_format($d["precio"],2).'</td>
        <td style="width:15%;text-align:right;">'. number_format($d["total"],2).'</td>
    </tr>';
	} 
$html.='
	<tr>
        <td style="width:15%;text-align:center;"> </td>
        <td style="width:55%;"> </td>
		<td style="width:15%;text-align:right;"><strong>>Total:</strong></td>
		<td style="width:15%;text-align:right;"><strong>'. number_format($total,2).'</strong></td>
    </tr>
</table>
<br/>
<div style="width:100%; padding:0 15px; text-align:center;"><strong>POLITICAS DE CONTRATACION:</strong></div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	La forma de trabajar es de un 50 % de anticipo a la firma del contrato y es necesario que el evento esté liquidado al 100 % al menos 5 días hábiles antes del evento, mediante pago directo con cheque en nuestras oficinas, transferencia, pago con tarjeta de crédito o depósito en cheque a nuestra cuenta.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	En caso de que el depósito se realice en efectivo en nuestra cuenta bancaria, se cobrará al cliente el 3 % extra del total por concepto de IDE (Impuesto a depósitos en efectivo).</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	En los pagos con tarjeta de crédito o débito se carga automáticamente el 16 % de IVA.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	Los precios incluyen la instalación y desinstalación del equipo en horarios hábiles.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	Los precios son mas IVA.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	El presupuesto está  cotizado para la ciudad de Guadalajara, si el evento se realizará fuera de
	la zona urbana, se cotizará el flete y /o viáticos correspondientes al lugar de entrega.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	La  renta será  siempre  por  un día  y el  montaje se programará de acuerdo a la logística en conjunto del cliente y la empresa.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	En caso de cancelación no se devolverá el anticipo o pago realizado por garantía de la fecha, pero se otorgará  un crédito a favor para futuros eventos.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	Una  vez  firmado el  contrato y  entregados  los  muebles  en el  evento, no  se realizará ningún cambio ni reembolso por los muebles que no se utilicen.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	Se  solicita  un depósito del 5% para  daños o faltantes  de  equipo  que  de no ser necesario se reembolsará al finalizar el evento. Cualquier daño o pérdida  del  equipo  se  cobrará al cliente de acuerdo a la lista de precios de reposición actualizada.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	El  cliente debe asignar una persona responsable para que reciba y  entregue el mobiliario y equipo,  responsabilizándose de lo recibido. Si la persona responsable no se encuentra en el evento y hay algún daño el cliente deberá de cubrirlo al 100%.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	Se solicita un plano de montaje para el evento y que el área del evento esté disponible y vacia al   iniciar  el  montaje ,  ya  que  el  personal de Bariconcept no será responsable de mover mobiliario ajeno al de la empresa.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	Por la seguridad del equipo  no se podrá  montar mobiliario en exteriores, en época de lluvias, si no están protegidos por una carpa, para evitar daños al equipo.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	Por su seguridad recomendamos contratar una guardia de desmontaje nocturno cuano del equipo o los accesorios de decoración queden en riesgo de pérdida o daño porque el lugar del evento sea público o  rentado y no quede debidamente asegurado; en este caso la guardia se cotizará en base al equipo rentado.
</div>
<br/>
<div style="width:100%; padding:0 20px; text-align:center; color:#F00;" >
	<p><strong>IMPORTANTE:</strong></p>
</div>
<div style="width:100%; padding:5 20px; font-size:12px; text-align:justify; color:#F00;">Con la finalidad de evitar contratiempos considere necesarios, pero  debera tener en cuenta que 5 días
	antes del evento ya no se aceptarán modificaciones. Lo anterior para evitar contratiempos en la logistica de su evento.
</div>
<div style="clear:both;"></div>
<div style="clear:both;"></div>
<div style="padding:0 1px; text-align:center;"><strong>POLITICAS DE MANTELERIA:</strong></div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	Los manteles se rentan y se entregan planchados e impecables para su uso.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	En  su  evento estará una persona especialmente para entregar la mantelería a su casa banquetera y a alguna persona que usted designe de su confianza para revisarla al recibirla y para revisarla nuevamente al entregarla en devolución.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	El costo de renta que usted paga incluye la lavada, planchada y desmanchado de un uso normal de alimentos, mas sin embargo si el mantel presenta quemadas, manchas indelebles de cera, tinta, vino tinto, líquidos colorantes que manchen o dañen permanentemente el mantel,
	este se cobrará a costo de reposición.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	Algunas manchas pueden salir con un tratamiento especial de costo de $ 50.00 que usted podrá decidir si se paga para tratar de desmancharlo o no.</div>
<div style="width:100%; padding:5 20px; font-size:12px;text-align:justify;">
	Sin más por el momento, me despido y quedo a sus órdenes para cualquier duda o comentario al respecto.</div>
<table border="0" cellpadding="0" cellspacing="0" style="font-size:13px; width:100%; margin-top:30px; padding:0 20px;">
	<tr>
		<td style="width:100%;vertical-align:top; text-align:center;">
			ATENTAMENTE<br />______________________<br />Gerente
		</td>
	</tr>
</table>
</page>';

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