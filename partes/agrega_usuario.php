<?php session_start();
header("Content-type: application/json");
$empresaid=$_SESSION["id_empresa"];
$term=$_GET["term"];
include("datos.php");

$usuario = $_POST['usuario'];
$nombre = $_POST['nombre'];
$password = $_POST['password'];
$cotizacion = 0;
$evento = 0;
$almacen = 0;
$compras = 0;
$bancos = 0;
$modulos = 0;


$cotizacion = $_POST['coti'];
$evento = $_POST['even'];
$almacen = $_POST['alma'];
$compras = $_POST['compr'];
$bancos = $_POST['banc'];
$modulos = $_POST['modu'];
$gastos = $_POST['gastos'];

$clave = $_POST['clave'];
$direccion = $_POST['direccion'];
$colonia = $_POST['colonia'];
$ciudad = $_POST['ciudad'];
$estado = $_POST['estado'];
$cp = $_POST['cp'];
$telefono = $_POST['telefono'];
$celular = $_POST['celular'];
$email = $_POST['email'];

	$bd=new PDO($dsnw, $userw, $passw, $optPDO);


try{	
	$bd->query("insert into usuarios (usuario,password,nombre,categoria) values ('$usuario','$password','$nombre','administrador')");
	
	$res = $bd->query("SELECT MAX( id_usuario ) as id FROM usuarios");
	$adidi = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$id = $adidi[0]["id"];

	$sql = "insert into usuario_permisos (id_usuario,cotizacion,evento,almacen,compras,bancos,modulos,gastos) values
	($id,$cotizacion,$evento,$almacen,$compras,$bancos,$modulos, $gastos)";
	$bd->query($sql);
	
	$sql = "insert into usuario_permisos (id_usuario, clave, direccion, colonia, ciudad, estado, cp, telefono, celular, email) values
	($id, $clave, $direccion, $colonia, $ciudad, $estado, $cp, $telefono, $celular, $email)";
	echo $sql
	$bd->query($sql);
	echo true;
	
}catch(PDOException $err){
	echo $err->getMessage();
	echo false;
}
?>