<?php
if($_SERVER['SERVER_ADDR']=="65.99.205.96"){
	//para DSN PDO en eventos.enthalpy
	$dsnw="mysql:host=localhost; dbname=desarrollo_original; charset=utf8;";
	$userw="desarrollo_write";
	$passw="Writer1";
	$optPDO=array(PDO::ATTR_EMULATE_PREPARES=>false, PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);
}elseif($_SERVER['SERVER_ADDR']=="65.99.205.96"){
	//para DSN PDO en eventos.enthalpy
	$dsnw="mysql:host=localhost; dbname=leadonco_eventos; charset=utf8;";
	$userw="leadonco_writer";
	$passw="writer1";
	$optPDO=array(PDO::ATTR_EMULATE_PREPARES=>false, PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);
}else{
	//para DSN PDO en localhost
	$dsnw="mysql:host=localhost; dbname=eventos; charset=utf8";
	$userw="americanetw";
	$passw="writer1";
	$optPDO=array(PDO::ATTR_EMULATE_PREPARES=>false, PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);
}
//datos de servidor
@define("HOST",$_SERVER['HTTP_HOST']);
@define("LIGA","HTTP://".$_SERVER['HTTP_HOST']."/");
?>