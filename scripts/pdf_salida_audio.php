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
		date_format(t1.fecha, '%d/%m/%Y') As fecha,
		t1.fechaevento,
		date_format(t1.fechamontaje,'%H:%i %p') As fechamontaje,
		date_format(t1.fechadesmont,'%H:%i %p') As fechadesmont,
		t1.id_cliente,
		t1.salon,
		t2.nombre,
		t3.direccion,
		t3.colonia,
		t3.ciudad,
		t3.estado,
		t3.cp,
		t3.telefono,
		t3.email
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
	$salon=$evento["salon"];
	$email=$evento["email"];
	$fechaMont=$evento["fechamontaje"];
	$fechaDesm=$evento["fechadesmont"];
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
					<p style="width:100%; text-align:center; margin:5px auto; font-size:10px;">Oficina en México DF. Tels: (55) 5532 7964,
						5532 8240, 3330 8240</p>
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
<table style="width:100%;" cellpadding="0" cellspacing="0" >
    <tr>
		 <td valign="top" style="width:25%; text-align:center;"><img src="../img/logo.jpg" width="50%" height="75"  /></td>
         <td valign="bottom" style="width:50%; text-align:left;">CONGRESOS CONVENCIONES, & EVENTOS, S.A. de C.V.					
         <br/>RELACION DE MATERIAL AUDIOVISUAL PARA EVENTO</td>
         <td valign="top" style="width:25%; text-align:center;"><img src="../img/logo2.jpg" width="50%" height="75" /></td>
    </tr>
</table>
<table border="0.3px"cellpadding="0" cellspacing="0" style=" font-size:12px;width:100%">
	<tr>    	
        <td style="width:25%;">NOMBRE DEL EVENTO:</td>
        <td style="width:25%;">'.$nombreEve.'</td>
  </tr>
    <tr>
    	<td style="width:25%;">SEDE:</td>
        <td style="width:25%;">'.$salon.'</td>
        <td style="width:25%;">COORDINADOR:</td>
        <td style="width:25%;"></td>
    </tr>
    <tr>
        <td style="width:25%;">FECHA DE EVENTO:</td>
        <td style="width:25%;">'.$fechaEve.'</td>
    </tr>
    <tr>
        <td style="width:25%;">NUMERO DE FILE:</td>
        <td style="width:25%;">'.folio(4,$id).'</td>
    </tr>
    <tr>
        <td style="width:25%;">FECHA DE SALIDA:</td>
        <td style="width:25%;">'.$fechaMont.'</td>
    </tr>
    <tr>
        <td style="width:25%;">FECHA DE REGRESO:</td>
        <td style="width:25%;">'.$fechaMont.'</td>
    </tr>    
</table>
<br>
<table border="1" cellspacing="-0.5" cellpadding="1" style="width:100%;font-size:10px;margin-top:5px;">
<tr>
       <td style="width:50%;text-align:center;"><strong>SALIDA DE EQUIPO DE AUDIO - VIDEO</strong> </td>
    </tr>
    </table>
	<table border="0.3px" cellspacing="0" cellpadding="0" style="width:100%;font-size:10px">
	<tr align="center">
    	<th style="width:5%;">CANTIDAD</th>
        <th style="width:25%;">DESCRIPCION</th>
        <th style="width:20%;">MARCA</th>
        <th style="width:20%;">MODELO</th>
        <th style="width:30%;">OBSERVACIONES</th>
    </tr> 
	 <tr align="center">
    	<td style="width:5%;">'. $d["cantidad"].'</td>
        <td style="width:25%;">'. $d["nombre"].'</td>
        <td style="width:20%;"></td>
        <td style="width:20%;"></td>
        <td style="width:30%;"></td>
    </tr>
    </table>
    <div style="width:100%; padding:0 20px; font-size:12px;">
    <strong>NOTA:</strong> El coordinador del evento ó  responsable del mismo se hará cargo de revisar lo que sale de la oficina,
    para serciorarse que el equipo se encuentre en buen estado y de igual forma sera devuelto   Este formato se tiene que entregar 
    a la  persona responsable del departamento  de audio y video, si el equipo se daña durante el evento por cuestiones ajenas al
    responsable tendrán que dar aviso a la oficina para que se levante un reporte de daño o extravio de lo contrario se le sancionara
    con un percentaje para la reparacion o compra del mismo.  Si el equipo se daña o se extravía por descuido del coordinador  será
    responsable del 100%  de los gastos que genere la reparación o la compra.
    </div>
        
<table border="0" cellpadding="0" cellspacing="0" style="font-size:12px; width:100%;">
<tr>
<td style="width:50%; text-align:center;font-size:10px;">DEVOLUCION DE EQUIPO </td>
</tr>
	<tr>
	  <td style="width:33.3%; text-align:center; font-size:10px;">&nbsp; </td>
	  <td style="width:33.3%; text-align:center; font-size:10px;">&nbsp; </td>
      <td style="width:33.3%; text-align:center; font-size:10px;">&nbsp; </td>
	</tr>
	tr>
	  <td rowspan="2" style="text-align:center;font-size:10px;">&nbsp; </td>
	  <td style="width:33.3%; text-align:center;font-size:10px;">FIRMA </td>
      <td style="width:33.3%; text-align:center; font-size:10px;">NOMBRE</td>
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