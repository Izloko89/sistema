<?php include("partes/header.php");
setlocale(LC_ALL,"");
setlocale(LC_TIME,"es_MX");
include("scripts/func_form.php");

//pendientes
//- añadir botón para autorizar el evento sin haberlo pagado
//- cuando se añade un nuevo articulo pasarlo al almacen

?>
<script src="js/gastos.js"></script>
<script src="js/formularios.js"></script>
<style>
/* estilos para formularios */
.flota_der{
	position:absolute;
	bottom:0px;
	right:10px;
}
	.flota_der2{
		position:absolute;
		bottom:0px;
		right:80px;
	}
.alejar_izq{
	margin-left:10px;
}
.clave{
	text-align:right;
}
.campo_form{
	margin:4px 0;
	text-align:center;
}
.text_corto{
	width:80px;
}
.text_mediano{
	width:150px;
}
.text_largo{
	width:400px;
}
.text_full_width{
	width:100%;
}
.text_half_width{
	width:50%;
}
.label_width{
	width:175px;
}
.borrar_fecha{
	cursor:pointer;
	display:none;
}
.table{
	margin:0 auto;
}
.guardar_articulo{
	background: white url('img/check.png') left center no-repeat;
	background-size:contain;
	cursor:pointer;
	width:20px;
	height:20px;
	display:inline-block;
	margin-right:10px;
}
.eliminar_articulo{
	background: white url('img/cruz.png') left center no-repeat;
	background-size:contain;
	cursor:pointer;
	width:20px;
	height:20px;
	display:inline-block;
	margin-right:10px;
}
.crearevento{
	background-color:#070;
	color:#FFF;
	font-weight:bold;
	border:none;
	cursor:pointer;
	padding: 2px 10px;
}
.crearevento:active{
	background-color:#FFF;
	color:#070;
}
#hacer .precio{
	/*display:none;*/
	width:50px;
}
td{
	
}
.divplazos, .divbancos{
	display:inline-block;
}
#cuenta .campo_form{
	text-align:left;
}
#cuenta label{
	display:inline-block;
	width:100px;
	margin-right:5px;
}
#observaciones{
	width:50%;
	height:100px;
}
li{
	list-style:none;
}
</style>
<div id="contenido">
<div id="tabs">
  <ul>
    <li class="hacer"><a href="#hacer">Gastos</a></li>
    <li class="mias"><a href="#mias">Mis gastos</a></li>
  </ul>
  <div id="hacer">
    <form id='eventos' class='formularios'>
	<h3 class='titulo_form'>Gasto</h3>
      <div class="tabla">
      
    <?php /*
	  if(isset($_GET["cve"])){?>
        <input type="hidden" name="id_evento" class="id_evento" value="<?php echo $_GET["cve"]; ?>" />
    <?php }else{ ?>
        <input type="hidden" name="id_evento" class="id_evento" value="" />
    <?php } */?>
    
        <input type="text" name="id_usuario" class="id_usuario" value="<?php echo $_SESSION["id_usuario"]; ?>" style="display:none;" />
        <input type="hidden" name="id_cliente" class="id_cliente" value="" />
        <input type="hidden" name="id_eve" class="id_eve" value="" />
        <?php
			$bd=new PDO($dsnw, $userw, $passw, $optPDO);
			$sql = "select MAX(id) as id from gastos_eventos";
			$res = $bd->query($sql);
			$res = $res->fetchAll(PDO::FETCH_ASSOC);
		?>
        <div class="campo_form celda">
			<label class="">FOLIO</label>
				 <input type="text" name="clave" class="clave label clave_cotizacion requerido mayuscula text_corto" id="clave_cotizacion" data-nueva="" value="<?php echo $res[0]["id"] + 1;?>" />

          </div>
		  <!--
        <div class="campo_form celda fondo_azul" align="center">
        	<label>Salón</label><input class="eventosalon salonr" type="radio" name="quitar" value="salon" />
            <label>Evento</label><input class="eventosalon eventor" type="radio" name="quitar" value="evento" />
            <input type="hidden" class="eventosalon_h" name="eventosalon" />
        </div>
        <div class="campo_form salones celda" style="width:292px;">
			<label>Salones</label>
			<select name="salon" class="salon">
            	<option selected disabled>Elige un salón</option>
            	<?php //salonesOpt();	?>
            </select>
		</div>
        <div class="campo_form celda" style="">
			<label>Tipo de evento</label>
			<select name="id_tipo" class="id_tipo">
            	<option selected disabled value="">Elige un tipo</option>
            	<?php //tipoEventosOpt();	?>
            </select>
		</div>-->
      </div>
      <div class="tabla">
        <div class="celda" style=" width:600px;">
          <div class="campo_form">
          	<label>Solicitado por</label>
            <input class="cliente_evento text_largo" id="empleado" type="text" />
          </div>
          <div class="campo_form">
          	<label>Nombre de Evento</label>
			<input type="hidden" id="id_eve" value=""/>
            <input class="cliente_evento text_largo" id="evento" type="text" onkeyup="eve_autocompletar();"/>
          </div>
          <div class="campo_form">
          	<label>Direccion</label>
            <input class="cliente_evento text_largo" id="direccion" type="text" value=""/>
          </div>
          <div class="campo_form">
          	<label>Nombre</label>
            <input class="cliente_evento text_largo" id="nombre" type="text" value=""/>
          </div>
          <div class="campo_form">
          	<label>Telefono de Contacto</label>
            <input class="cliente_evento text_largo" id="telefono" type="text" value=""/>
          </div>
		  
		  <!--
		  
		  
          <div class="campo_form">
            <label class="">Nombre del evento</label>
			<input type="text" name="nombre" class="nombre text_largo requerido" />
          </div>-->
		</div>
        <div class="celda" style="">
          <div class="campo_form">
            <label class="align_right" style="width:120px;">Fecha Solicitud</label>
        	<abbr title=""><input placeholder="Click para elegir" class="fecha alejar_izq requerido fechaevento" type="text" name="fechaevento"  readonly/></abbr><!--
            --><img class="borrar_fecha" data-class="fechaevento" src="img/cruz.png" width="15" />
          </div>
          <div class="campo_form">
            <label class="align_right" style="width:120px;">Fecha Requerido</label>
        	<abbr title=""><input placeholder="Click para elegir" class="fecha alejar_izq requerido fechamontaje" type="text" name="fechamontaje" readonly/></abbr><!--
            --><img class="borrar_fecha" data-class="fechamontaje" src="img/cruz.png" width="15" />
          </div>
          <div class="campo_form">
            <label class="align_right" style="width:120px;">Fecha Entrega</label>
        	<abbr title=""><input placeholder="Click para elegir" class="fecha alejar_izq requerido fechadesmont" type="text" name="fechadesmont"  readonly/></abbr><!--
            --><img class="borrar_fecha" data-class="fechadesmont" src="img/cruz.png" width="15" />
          </div>
		</div>
      </div>
        <div align="right">
            <input type="button" class="modificar" value="MODIFICAR" data-wrap="#hacer" style="display:none;" />
            <input type="button" class="guardar" value="CREAR" data-wrap="#hacer" data-accion="guardar" data-m="pivote" />
            <input type="button" class="nueva" value="NUEVA"  />
        </div>
	</form>
    <div class='formularios'>
	<h3 class='titulo_form'>Gastos</h3>
    <table id="articulos" class="table">
      <tr>
      	<th class="agregar_articulo"><img src="img/mas.png" height="25" /></th>
        <th width="100">Cant.</th>
        <th width="250">Concepto</th>
        <th width="100">precio unitario</th>
        <th width="100">total</th>
        <th width="150">Acciones</th>
      </tr>       
	  <?php
	  /*
			$bd=new PDO($dsnw, $userw, $passw, $optPDO);
			$sql = "SELECT cantidad, precio, total, nombre
FROM gastos_art
INNER JOIN gastos ON gastos.id_gasto = gastos_art.id_gasto
WHERE id_gEve =1";
			$res = $bd->query($sql);
			foreach($res->fetchAll(PDO::FETCH_ASSOC) as $d)
			{
				echo '<tr><th></th><th>' . $d["cantidad"] . '</th><th>' . $d["nombre"] . '</th><th>' . $d["precio"] . '</th><th>' . $d["total"] . '</th></tr>';
			}*/
		?>
    </table>
    </div>
	
	<div id="cuenta" class="formularios" align="left">
    <h3 class='titulo_form'>Cuenta</h3>
        <div class="campo_form">
            <label class="">Total de gastos</label>
            <input type="text" class="totalgasto numerico" id="totalGas" readonly="readonly" />
        </div>
        <div class="campo_form">
            <label class="">Restante:</label>
            <input type="text" class="restante numerico" id="restGas" readonly="readonly" />
        </div>
        <div align="right">
            <input type="button" class="historial" value="Ver historial de pagos" />
            <input type="button" class="agregarpago" value="Agregar Pago" />
        </div>
        <div id="historial" class="formularios" style="display:none;">
        	<h3 class='titulo_form'>Historial de pagos</h3>
            <div class="mostrar"></div>
        </div>
        <div id="nuevopago" class="formularios" style="display:none;">
        	<h3 class='titulo_form'>Nuevo Pago</h3>
            <input type="hidden" class="id_emp_eve" value="" />
             <div class="campo_form">
                <label class="">Importe:</label>
                <input type="text" class="importe numerico" />
            </div>      
            <div class="campo_form">
                <label class="">Fecha del pago:</label>
                <input type="text" class="fechasql fechapago numerico" />
            </div>
			<div class="campo_form">
            <label class="">Metodo de pago</label>
            <select class="metodo">
            	<option value="Efectivo">Efectivo</option>
                <option value="Cheque">Cheque</option>
                <option value="Transferencia">Transferencia</option>
            <option value="Tarjeta de credito">Tarjeta de credito</option>
            <option value="Tarjeta de débito">Tarjeta de débito</option>
            </select>
            <div class="divplazos" style="display:none;">
                <label class="">Plazos:</label>
                <input type="text" class="plazos numerico" size="4" value="1" />
            </div>
            <div class="divbancos" style="display:none;" id="bancos">
                <label class="">Bancos:</label>
				<?php 
					$bd=new PDO($dsnw,$userw,$passw,$optPDO);
					$sql = "select nombre, id_banco from bancos";
					$res = $bd->query($sql);
				?>
                <select class="bancos"><option value="0">Elige un banco</option>
				<?php 
					foreach($res->fetchAll(PDO::FETCH_ASSOC) as $datos)
					{
						$id = $datos["id_banco"];
						$nombre = $datos["nombre"];
						echo "<option value=$id>$nombre</option>";
					}
				?>
				</select>
            </div>
        </div>
            <div align="right">
                <input type="button" class="anadir" value="Añadir pago" />
            </div>
        </div>
    </div>
	
    <div align="left" class="formularios">
    <h3 class='titulo_form'>Observaciones</h3>
      <form action="scripts/pdf_gastos.php" method="get" target="_blank">
	  <table class="">
		  <tr>
			  <td>
					<label class="">Proveedor:</label>
			  </td>
			  <td>
					<input type="text" name="encargado" id="encargado"/>
			  </td>
			  <td rowspan="5"><textarea name="obs" id="obs" placeholder="Notas" cols="70" rows="5" style="resize:none;"></textarea></td>
		  </tr>
		  <tr>
			  <td>
					<label class="">Clave Proveedor:</label>
			  </td>
			  <td>
					<input type="text" name="unidad" id="unidad"/>
			  </td>
		  </tr>
		  <tr>
			  <td>
					<label class="">Cheque:</label>
			  </td>
			  <td>
					<input type="text" name="cheque" id="cheque"/>
			  </td>
		  </tr>
		  <tr>
			  <td>
					<label class="">Elaborado:</label>
			  </td>
			  <td>
					<input type="text" name="elaborado" id="elaborado"/>
			  </td>
		  </tr>
		  <tr>
			  <td>
					<label class="">Seleccionado por:</label>
			  </td>
			  <td>
					<input type="text" name="selec" id="selec"/>
			  </td>
		  </tr>
	  </table>
        

        <input type="hidden" name="id_evento" class="id_evento" value="" />
        <input type="hidden" name="total_cot" class="total_cot" value="" />
        <input type="hidden" name="id_Folio" class="id_Folio" value="" />	  <!--
        <input type="submit" onclick="this.form.action='scripts/nota_venta_pdf.php'" value="Hoja de bulto" class="flota_der2" />-->
		  <input type="submit" value="Imprimir" class="flota_der" />
      </form>
    </div>
  </div>
  <div id="mias">
  <style>
  	#mias table{
		font-size:0.85em;
	}
	#mias th{
		font-size:1.05em;
		margin:2px;
	}
	#mias td{
		margin:2px;
		padding:5px 2px;
	}
	#mias .filtro{
		width:100%;
	}
	.accion{
		margin:0 5px;
		cursor:pointer;
	}
  </style>
  <table cellpadding="0" cellspacing="2" border="0" width="100%" class="listado" id="tablaEve">
  <tr>
  	<th>Clave<br />Folio</th>
    <th style="width:200px;">Nombre del evento</th>
    <th style="width:200px;">Cliente</th>
    <th>Estatus</th>
    <th>Fecha<br />evento</th>
    <th>Montaje</th>
    <th>Desmontaje</th>
    <th>acciones</th>
  </tr>
  <tr class="barra_accion">
    <td style="width:34px;"><input class="filtro" data-c="bfolio" /></td>
    <td><input class="filtro" data-c="bnombre" /></td>
    <td><input class="filtro" data-c="btipo_evento" /></td>
    <td><input class="filtro" data-c="bcliente" /></td>
    <td style="width:34px;"><input class="filtro" data-c="bestatus" /></td>
    <td><input class="filtro filtrofecha" data-c="bfechaevento" /></td>
    <td><input class="filtro filtrofecha" data-c="bfechamontaje" /></td>
    <td><input class="filtro filtrofecha" data-c="bfechadesmont" /></td>
    <td><a href="#" class="pdf" onclick="return false;" data-nombre="evento" data-orientar="L">generar pdf</a></td>
  </tr>
  	<?php 
	try{
		$bd=new PDO($dsnw,$userw, $passw, $optPDO);
			
		$sql="SELECT DISTINCT
		gastos_eventos.id,
		gastos_eventos.empleado,
		gastos_eventos.fecha1,
		gastos_eventos.fecha2,
		gastos_eventos.fecha3,
		eventos.nombre,
		eventos.id_evento
		FROM gastos_art
		INNER JOIN gastos_eventos ON gastos_eventos.id_evento=gastos_art.id_gEve 
		INNER JOIN eventos ON eventos.id_evento=gastos_art.id_gEve;";
		
		$res=$bd->query($sql);
		
		
		//correlacionar los subarrays al array principal de evento
		
		$cot = $res->fetchAll(PDO::FETCH_ASSOC);
		$cont = 2;
		//escribimos la tabla
		foreach($cot as $folio=>$d){
			echo '<tr class="cot'.$d["id"].'">';
			echo '<td class="bfolio">'.$d["id"]. '</td>';
			echo '<td class="bnombre">'.$d["nombre"].'</td>';
			echo '<td class="bcliente">'.$d["empleado"].'</td>';
			echo '<td class="bestatus">1</td>';
			echo '<td class="bfechaevento">'.varFechaAbrNorm($d["fecha1"]).'</td>';
			echo '<td class="bfechamontaje">'.varFechaAbrNorm($d["fecha2"]).'</td>';
			echo '<td class="bfechadesmont">'.varFechaAbrNorm($d["fecha3"]).'</td>';
			echo '<td><img src="img/check.png" data-cve="'.$d["id"].'" height="20" onclick="autorizarEve('.$folio.','.$d["id"].')" /><img class="accion" src="img/edit.png" data-cve="'.$d["id"].'" onclick="editar(' . $d["id_evento"] . ', ' . $d["id"] . ');" height="20" /><img class="accion eliminar" src="img/cruz.png" data-cve="'.$d["id"].'" height="20" onclick="eliminar_eve(' . $d["id"] . ',' . $cont . ')"/></td>';
			echo '</tr>';
			$cont++;
		}
		$bd=NULL;
	}catch(PDOException $err){
		echo "Error encontrado: ".$err->getMessage();
	}
	?>
  	</table>
  </div>
</div>
</div>
<?php include("partes/footer.php"); ?>