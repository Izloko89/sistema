<?php session_start(); 
include("../scripts/funciones.php");
include("../scripts/func_form.php");
include("../scripts/datos.php");
$emp=$_SESSION["id_empresa"];

try{
	$bd=new PDO($dsnw,$userw,$passw,$optPDO);
	$sql="SELECT
		*
	FROM proveedores
	WHERE proveedores.id_empresa=$emp;";
	$res=$bd->query($sql);
	$articulos=array();
	foreach($res->fetchAll(PDO::FETCH_ASSOC) as $d){
		$proveedores[$d["id_proveedor"]]=$d;
	}
}catch(PDOException $err){
	echo "Error: ".$err->getMessage();
}

?>
<style>
.dbc{
	cursor:pointer;
	color:#900;
}
.table{
	margin:0 auto;
}
</style>
<script src="js/formularios.js"></script>


<script>


$("#clave").on("change keyup paste", function(){
	//alert("entrando en el evento");
   //realizaProceso($('#clave').val());return false;
})

$(document).ready(function(e) {
	//para ver el formulario de pago
	$(".agregarpago").click(function(e) {
        $("#nuevopago").slideToggle(200);
    });
	//para ver historial de pago
	$(".historial").click(function(e) {
        $("#historial").slideToggle(200);
    });
	//para añadir pago
	$(".anadir").click(function(e) {
		eve=$(".id_evento").get(0).value;
		monto=$(".importe").val();
		fecha=$(".fechapago").val();
		cliente=$(".id_cliente").val();
		metodo=$(".metodo").val();
		//var banco=document.getElementById("bancos");
		banco = $(".bancos").val();
		idbanco = 0;
		$.ajax({
			url:'scripts/s_pagar.php',
			cache:false,
			type:'POST',
			data:{
				'eve':eve,
				'monto':monto,
				'fecha':fecha,
				'cliente':cliente,
				'metodo':metodo,
				'banco':idbanco
			},
			success: function(r){
				if(r.continuar){
					alerta("info","Pago añadido exitosamente");
					checarTotalEve(eve);
					historial(evento);
					$("#nuevopago input[type=text]").val('');
				}else{
					alerta("error",r.info);
				}
			}
		});
	});
	$(".metodo").change(function(e) {
		$(".divplazos").hide();
		$(".divbancos").hide();
        if($(this).find("option:selected").val()=="A crédito"){
			$(".divplazos").show();
		}else if($(this).find("option:selected").val()=="Transferencia" || $(this).find("option:selected").val()=="Cheque" || $(this).find("option:selected").val()=="Tarjeta de credito" || $(this).find("option:selected").val()=="Tarjeta de débito"){
			$(".divbancos").show();
		}
    });
});
function realizaProceso(valorCaja2){
	valorCaja2 = document.getElementById("clave").value;

        var parametros = {

              

                "aidi" : valorCaja2

        };

		
		
        $.ajax({

                data:  parametros,

                url:   'select_proveedores.php',

                type:  'post',

                beforeSend: function () {

                        $("#destino").html("Procesando, espere por favor...");

                },

                success:  function (response) {

                        $("#destino").html(response);

                }

        });

}

</script>


<script>
$(document).ready(function(e) {
    $(".nombre").focusout(function(e) {
		$(".razon").val($(this).val());
    });
	$( ".proveedor_clave" ).keyup(function(e){
		_this=$(this);
		if(e.keyCode!=8 && _this.val()!=""){
			if(typeof timer=="undefined"){
				timer=setTimeout(function(){
					ClaveProveedor();
				},300);
			}else{
				clearTimeout(timer);
				timer=setTimeout(function(){
					ClaveProveedor();
				},300);
			}
		}else{
			resetform();
		}
    }); //termina buscador de cotizacion
	$(".dbc").dblclick(function(e) {
        accion=$(this).attr("data-action");
		val=$(this).text();
		switch(accion){
			case 'clave':
				$(".clave").val(val);
				scrollTop();
				ClaveProveedor();
			break;
		}
    });
	$( ".nombre" ).autocomplete({
      source: "scripts/busca_proveedores1.php",
      minLength: 1,
      select: function( event, ui ) {
		//da el nombre del formulario para buscarlo en el DOM
		form="cotizaciones";
		
		//asignacion individual alos campos
		$(".clave").val(ui.item.id_cliente);
		$( ".proveedor_clave" ).keyup();
	  }
    });
});

</script>
<form id="f_proveedores" class="formularios">
  <h3 class="titulo_form">PROVEEDOR</h3>
  	<input type="hidden" name="id_proveedor" class="id_proveedor" />
    <div class="campo_form">
    <label class="label_width">CLAVE</label>
    <input type="text" name="clave" class="clave proveedor_clave text_corto requerido mayuscula" value="<?php nCveProv(); ?>">
    </div>
    <div class="campo_form">
    <label class="label_width">Nombre</label>
    <input type="text" name="nombre" class="nombre text_largo nombre_buscar">
    </div>
    <div class="campo_form">
    <label class="label_width">Límite de crédito</label>
    <input type="text" name="limitecredito" class="limitecredito text_mediano">
    </div>
    <input class="boton_dentro" type="reset" value="Limpiar" />
</form>


<table>
<tr>
<td>
<form id="f_proveedores_contacto" class="formularios">
  <h3 class="titulo_form">DATOS DE CONTACTO</h3>
  <input type="hidden" name="id" class="id" />
  <input type="hidden" name="id_empresa" value="<?php echo $_SESSION["id_empresa"]; ?>" />
    <div class="campo_form">
        <label class="label_width">CLAVE</label>
        <input type="text" name="clave" class="requerido mayuscula clave">
    </div>
	

	
    <div class="campo_form">
        <label class="label_width">Dirección</label>
        <input type="text" name="direccion" class="direccion">
    </div>
    <div class="campo_form">
        <label class="label_width">Colonia</label>
        <input type="text" name="colonia" class="colonia">
    </div>
    <div class="campo_form">
        <label class="label_width">Ciudad</label>
        <input type="text" name="ciudad" class="ciudad">
    </div>
    <div class="campo_form">
        <label class="label_width">Estado</label>
        <input type="text" name="estado" class="estado">
    </div>
    <div class="campo_form">
        <label class="label_width">Código Postal</label>
        <input type="text" name="cp" class="cp">
    </div>
    <div class="campo_form">
        <label class="label_width">Telefono</label>
        <input type="text" name="telefono" class="telefono">
    </div>
    <div class="campo_form">
        <label class="label_width">Celular</label>
        <input type="text" name="celular" class="celular">
    </div>
    <div class="campo_form">
        <label class="label_width">E-mail</label>
        <input type="text" name="email" class="email">
    </div>
</form>
</td>
<td >
<form id="f_proveedores_fiscal" class="formularios">
  <h3 class="titulo_form">INFORMACIóN FISCAL</h3>
  <input type="hidden" name="id" class="id" />
  <input type="hidden" name="id_empresa" value="<?php echo $_SESSION["id_empresa"]; ?>" />
    <div class="campo_form">
        <label class="label_width">RFC</label>
        <input type="text" name="rfc" class="requerido mayuscula rfc">
    </div>
    <div class="campo_form" style="display:none;">
        <label class="label_width">Razón social</label>
        <input type="text" name="razon" class="razon">
    </div>
    <div class="campo_form">
        <label class="label_width">Nombre Comercial</label>
        <input type="text" name="nombrecomercial" class="requerido nombrecomercial">
    </div>
    <div class="campo_form">
        <label class="label_width">Direccion Fiscal</label>
        <input type="text" name="direccion" class="requerido direccion">
    </div>
    <div class="campo_form">
        <label class="label_width">Colonia</label>
        <input type="text" name="colonia" class="requerido colonia">
    </div>
    <div class="campo_form">
        <label class="label_width">Ciudad</label>
        <input type="text" name="ciudad" class="requerido ciudad">
    </div>
    <div class="campo_form">
        <label class="label_width">Estado</label>
        <input type="text" name="estado" class="requerido estado">
    </div>
    <div class="campo_form">
        <label class="label_width">Código Postal</label>
        <input type="text" name="cp" class="requerido cp">
    </div>
    </form>
	</td>
	</tr>
	</table>
    <div align="right">
        <input type="button" class="guardar" value="GUARDAR" data-wrap="#" data-accion="nuevo" data-m="pivote" />
        <input type="button" class="modificar" value="MODIFICAR" style="display:none;" />
    	<input type="button" class="volver" value="VOLVER">
    </div>
<div class="formularios">
	<h3 class="titulo_form">Estado de Cuenta</h3>
	<table>
		<tr class="campo_form">
			<td>
				<label class="">Total:</label>
			</td>
			<td>
				<input type="text" class="totalevento numerico" readonly="readonly" />
			</td>
		</tr>
		<tr class="campo_form">
			<td>
				<label class="">Restante:</label>
			</td>
			<td>
				<input type="text" class="restante numerico" readonly="readonly" />
		</tr>
	</table>
        <div align="right">
            <input type="button" class="agregarpago" value="Agregar Pago" />
        </div>
        <div id="historial" class="formularios" >
        	<h3 class='titulo_form'>Historial de pagos</h3>
	<div id="histpago">
	</div>
        </div>
        <div id="nuevopago" class="formularios" style="display:none;">
        	<h3 class='titulo_form'>Nuevo Pago</h3>
            <input type="hidden" class="id_emp_eve" value="" />
			<table>
			<tr>
				 <td class="campo_form">
					<label class="">Importe:</label>
			</td>
			<td>
					<input type="text" class="importe numerico" />
				</td>      
			</tr>
			<tr>
				 <td class="campo_form">
					<label class="">Fecha del pago:</label>
			</td>
			<td>
					<input type="text" class="fechasql fechapago numerico" />
				</td>      
			</tr>
			<tr>
				 <td class="campo_form">
            <label class="">Metodo de pago</label>
			</td>
			<td>
            <select class="metodo">
            	<option value="De contado">De contado</option>
                <option value="A crédito">A crédito</option>
                <option value="Cheque">Cheque</option>
                <option value="Transferencia">Transferencia</option>
            <option value="Tarjeta de credito">Tarjeta de credito</option>
            <option value="Tarjeta de débito">Tarjeta de débito</option>
            </select>
				</td>      
			</tr>
				<tr class="divplazos" style="display:none;">
					<td>
						<label class="">Plazos:</label>
					</td>
					<td>
						<input type="text" class="plazos numerico" size="4" value="1" />
					</td>
				</tr>
			<table class="divbancos" style="display:none;" id="bancos">
				<tr>
					<td>
                <label class="">Bancos:</label>
				<?php 
					$bd=new PDO($dsnw,$userw,$passw,$optPDO);
					$sql = "select nombre, id_banco from bancos";
					$res = $bd->query($sql);
				?>
					</td>
					<td>
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
					</td>
				</tr>

			</table>    
            <div align="right">
                <input type="button" class="anadir" value="Añadir pago" />
            </div>
        </div>        

        </div>
<div id="destino" align="center"></div>
	
	
	</div>
</div>

<div class="formularios">
<h3 class="titulo_form">Listado de clientes registrados</h3>
	<table style="width:100%;">
    	<tr>
        	<th>CLAVE<br /><font style="font-size:0.4em; color:#999;">Doble Clic<br />para modificar</font></th>
            <th>NOMBRE</th>
        </tr>
        
    <?php if(count($proveedores)>0){foreach($proveedores as $art=>$d){
		echo '<tr>';
		echo '<td class="dbc" data-action="clave">'.$d["clave"].'</td>';
		echo '<td>'.$d["nombre"].'</td>';
		echo '</tr>';
	}//foreach
	}//if end ?>
    </table>
</div>
<script>
function ClaveProveedor(){
	$(".id_proveedor").val('');
	dato=$(".proveedor_clave").val();
	input=$(".proveedor_clave");
	input.addClass("ui-autocomplete-loading");
	$.ajax({
	  url:"scripts/busca_proveedores.php",
	  cache:false,
	  async:false,
	  data:{
		term:dato
	  },
	  success: function(r){
		clave=$(".proveedores_clave").val();
		resetform();
		$(".proveedor_clave").val(clave);
		$.each(r[0],function(i,v){
			$("."+i).text(v);
			$("."+i).val(v);
		});
		datosContacto(r[0].id_cliente,"proveedores");
		datosFiscal(r[0].id_cliente,"proveedores");
		//asigna el id de cotización
		input.removeClass("ui-autocomplete-loading");
		document.getElementById("histpago").innerHTML = r.tabla;
	  }
	});
	realizaProceso($('#clave').val());return false;
}
</script>