// JavaScript Document
$(document).ready(function(e) {
	//alerta("info","Vista de gastos NO DISPONIBLE todavía");
	$("#tabs").tabs({
		heightstyle:"content"
	});// Controla las pestañas que cambian la pagina
	
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
		eve=$("#id_eve").get(0).value;
		//alert(eve);
		monto=$(".importe").val();
		//alert(monto);
		resto=$("#restGas").val();
		fecha=$(".fechapago").val();
		//alert(fecha);
		cliente=$(".id_cliente").val();
		//alert($(".id_cliente").val());
		metodo=$(".metodo").val();
		//alert(metodo);
		//var banco=document.getElementById("bancos");
		banco = $(".bancos").val();
		idbanco = 0;
		
		if(monto <= resto)
		{
			$.ajax({
				url:'scripts/s_pagar_gastos.php',
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
		}else{
			if(resto>0){
				alerta("info", "El monto a pagar es mayor a la deuda.");
			}else{
				alerta("info", "El monto total ya ha sido pagado.");
			}
		}
		checarTotalGas(eve);
		historial(eve);
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
	

    //busca cliente
	$( ".nombre" ).autocomplete({
      source: "scripts/busca_usuarios.php",
      minLength: 1,
      select: function( event, ui ) {
		//asignacion individual alos campos
		//alert(ui);
		$("#f_tipo_evento .id_tipo").val(ui.item.id_gasto);
		$(".modificar").show();
		$(".guardar_individual").hide();
	  }
    });
	$(".nombre").keyup(function(e) {
        if(e.keyCode==8){
			if($(this).val()==""){
				$(".modificar").hide();
				$(".guardar_individual").show();
			}
		}
    });
	$(".guardar").click(function (){
		empleado = document.getElementById("empleado").value;
		evento = document.getElementById("id_eve").value;
		direccion = document.getElementById("direccion").value;
		nombre = document.getElementById("nombre").value;
		telefono = document.getElementById("telefono").value;
		fecha1 = $(".fechaevento").val();
		fecha2 = $(".fechamontaje").val();
		fecha3 = $(".fechadesmont").val();
		$.ajax({
			url:'scripts/guardar_gasto.php',
			cache:false,
			async:false,
			type:'POST',
			data:{ 
				'empleado':empleado,
				'evento':evento,
				'direccion':direccion,
				'nombre':nombre,
				'telefono':telefono,
				'fecha1':fecha1,
				'fecha2':fecha2,
				'fecha3':fecha3
			},
			success: function(r){
				if(r){
					alerta("info","Gasto agregado");
			$(".nueva").hide();
			$(".modificar").show();
				}else{
					alerta("error","Ocurrio un error al editar");
				}//
			}
		});
	});
		$( ".clave_cotizacion" ).keyup(function(){//-------------------------------
		_this=$(this);
		if(typeof timer=="undefined"){
			timer=setTimeout(function(){
				buscarClaveGet()
			},300);
		}else{
			clearTimeout(timer);
			timer=setTimeout(function(){
				buscarClaveGet();
			},300);
		}
    }); //termina buscador de cotizacion
	$(".modificar").click(function(e) {
	  if(requerido()){
				term = document.getElementById("id_tipo").value;
				name = document.getElementById("nombre").value;
		//procesamiento de datos
		$.ajax({
			url:'scripts/s_modificar_gastos.php',
			cache:false,
			async:false,
			type:'POST',
			data:{ 
				'term':term,
				'name':name
			},
			success: function(r){
				if(r){
					alerta("info","Articulo editado");
						$(".volver").click();
				}else{
					alerta("error","Ocurrio un error al editar");
				}//
			}
		});
	  }//if del requerido
    });
	$(".agregar_articulo").click(function(){
		id_evento=$(".id_evento").get(0).value;
		id=$(".lista_articulos").length+1;
		$("#articulos").append('<tr id="'+id+'" class="lista_articulos"><td style="background-color:#FFF;"><input type="hidden" class="id_item" value="" /><input type="hidden" class="id_evento" value="" /><input type="hidden" class="id_articulo" /><input type="hidden" class="id_paquete" /></td><td><input class="cantidad" type="text" size="7" onkeyup="cambiar_cant('+id+')" /></td><td><input class="articulo_nombre text_full_width" onkeyup="art_autocompletar('+id+');" /></td><td>$<input type="text" class="precio" onkeyup="darprecio(this)" /></td><td>$<span class="total"></span></td><td><span class="guardar_articulo" onclick="guardar_art('+id+')"></span><span class="eliminar_articulo" onclick="eliminar_art('+id+')"></span></td></tr>');
		$.each($(".lista_articulos"),function(i,v){
			$(this).find(".id_evento").val(id_evento);
		});
		$(".cantidad").numeric();
	});
	$(".guardar").click(function(e) {
	  if(requerido()){
		term = document.getElementById("nombre").value;
		//datos de los formularios
		//procesamiento de datos
		$.ajax({
			url:'scripts/s_guardar_gastos.php',
			cache:false,
			async:false,
			type:'POST',
			data:{
				'term':term
			},
			success: function(r){
				if(r){
					alerta("info","Registro añadido satisfactoriamente");
					ingresar=true;
					$("#formularios_modulo").hide("slide",{direction:'right'},rapidez,function(){
						$("#botones_modulo").fadeIn(rapidez);
					});
				}else{
					alerta("error","ocurrio un error al agregar el registro");
				}
			}
		});
	  }//if del requerido
    });
    $(".volver").click(function(e) {
		ingresar=true;
    	$("#formularios_modulo").hide("slide",{direction:'right'},rapidez,function(){
			$("#botones_modulo").fadeIn(rapidez);
		});
    });
});
function buscarClaveGet(){
	$(".totalevento").val('');
	$(".restante").val('');
	$(".eventosalon").prop("checked",false);
	dato=$(".clave_cotizacion").val();
	cotizacion = dato;
	input=$(".clave_cotizacion");
	input.addClass("ui-autocomplete-loading-left");
	$.ajax({
	  url:"scripts/busca_gasto.php",
	  cache:false,
	  data:{
		term:dato
	  },
	  success: function(r){
		form="cotizaciones";
		if(r.bool){
			document.getElementById("empleado").value = r.empleado;
			document.getElementById("evento").value = r.nombre;
			document.getElementById("direccion").value = r.direccion;
			document.getElementById("nombre").value = r.np;
			document.getElementById("telefono").value = r.telefono;
			$(".id_Folio").val($("#clave_cotizacion").val());
			$(".fechaevento").val(r.fecha1);
			$(".fechamontaje").val(r.fecha2);
			$(".fechadesmont").val(r.fecha3);
			$("#id_eve").val(r.id_evento);
			$(".id_evento").val(r.id_evento);
			historial(r.id_evento);
			$(".id_cliente").val(r.id_cliente);
			get_items_cot(cotizacion);
			checarTotal('cotizaciones',cotizacion);
			$(".guardar").hide();
			$(".modificar").show();
			checarTotalGas(r.id_evento);
		}else{
			$.each($("#hacer form"),function(i,v){
				$(this).get(i).reset();
			});
			alerta("info","Cotización no existe o ya es un evento");
			//le da el nombre al boton
			$(".guardar").show();
			$(".modificar").hide();
		}
		input.removeClass("ui-autocomplete-loading-left");
	  }
	});
}
function historial(eve){
	$.ajax({
		url:'scripts/s_historial_gastos_pagos.php',
		cache:false,
		type:'POST',
		data:{
			'eve':eve
		},
		success: function(r){
			$("#historial .mostrar").html(r);
		}
	});
	//funcion para ver el historial de pagos del evento
}

function checarTotal(tabla,id){
	var total;
	$.ajax({
		url:'scripts/s_check_total_gastos.php',
		cache:false,
		async:false,
		type:'POST',
		data:{
			'tabla':tabla,
			'id':id
		},
		success: function(r){
			if(r){
				var total = "<tr><td colspan=4></td><td ><span>" + r.total + "</span></td></tr>";
				//$("#articulos").append(total);
			}else{
				//alerta("error",r.info);
			}
		}
	});
}
function checarTotalGas(id){
	var total;
	$.ajax({
		url:'scripts/s_check_total_gastos.php',
		cache:false,
		async:false,
		type:'POST',
		data:{
			'tabla':"gastos_art",
			'id':id
		},
		success: function(r){
			var res = JSON.parse(r);
			$("#totalGas").val(res.total);
			$(".totalcot").val(res.total);
			total = res.total;
		}
	});
	
	$.ajax({
		url:'scripts/s_check_total_gastos_pagos.php',
		cache:false,
		async:false,
		type:'POST',
		data:{
			'tabla':"gastos_art",
			'id':id
		},
		success: function(r){
			var res = JSON.parse(r);
			$(".restante").val(total - res.pagado);
		}
	});
}
function get_items_cot(id){
	$(".lista_articulos").remove();
	$.ajax({
		url:'scripts/get_items_gastos.php',
		cache:false,
		async:false,
		data:{
			'id_cotizacion':id
		},
		success: function(r){
			$("#articulos").append(r);
		}
	});
}
function requerido(){
	selector=".requerido";
	continuar=true;
	$.each($(selector).parent().find(".requerido"),function(i,v){
		if($(this).val()==""){
			$(this).addClass("falta_llenar");
			continuar=false;
		}
	});
	return continuar;
}
function darprecio(e){
	precio=$(e).val();
	$(e).parent().parent().removeClass("verde_ok");
	cant=$(e).parent().parent().find(".cantidad").val();
	$(e).siblings(".precio").html(precio);
	total=(precio*1)*(cant*1);
	$(e).parent().parent().find(".total").html(total);
}
function art_autocompletar(id){
	padre=$("#"+id);
	cantidad=padre.find(".cantidad").val()*1;
	id_articulo=padre.find(".id_articulo");
	id_paquete=padre.find(".id_paquete");
	precio=padre.find(".precio").parent();
	total=padre.find(".total");
	$( "#"+id+" .articulo_nombre").autocomplete({
	  source: "scripts/busca_gastos.php",
	  minLength: 1,
	  select: function( event, ui ) {
		  total.parent().parent().removeClass("verde_ok");
		  id_articulo.val(ui.item.id_articulo);
		  id_paquete.val(ui.item.id_paquete);
		  precio.html(ui.item.precio);
		  totalca=cantidad*ui.item.precio;
		  total.html(totalca);
	  }
	});
}
function eve_autocompletar(){
	$( "#evento").autocomplete({
	  source: "scripts/busca_eventos.php",
	  minLength: 1,
	  select: function( event, ui ) {
		  $("#id_eve").val(ui.item.id_evento);
	  }
	});
}
	function eliminar_gasto(elemento, id_item){
		$.ajax({
			url:'scripts/eGasto.php',
			cache:false,
			type:'POST',
			data:{
				'id_item':id_item
			},
			success: function(r){
			  if(r){
				document.getElementById("tableEve").deleteRow(elemento);
					alerta("info","<strong>Tipo de Evento</strong> Eliminado");
					ingresar=true;
					$("#formularios_modulo").hide("slide",{direction:'right'},rapidez,function(){
						$("#botones_modulo").fadeIn(rapidez);
					});
			  }else{
				alerta("error", r);
			  }
			}
		});
	}
	function editar(e, id)
	{
		$(".clave_cotizacion").val(id);
		$(".id_eve").val(e);
		buscarClaveGet();
	}
function guardar_art(elemento){
	row=$("#"+elemento);
	padre=$("#"+elemento).parent();
	
	id_cotizacion=$(".id_cotizacion").first().val();
	eve = $(".id_eve").val();
	
	if(id_cotizacion!=""){
		id_item=$("#"+elemento+" .id_item").val();
		id_articulo=$("#"+elemento+" .id_articulo").val();
		id_paquete=$("#"+elemento+" .id_paquete").val();
		cantidad=$("#"+elemento+" .cantidad").val();
		precio=$("#"+elemento+" .precio").val();
		total=$("#"+elemento+" .total").html();
		$.ajax({
			url:'scripts/guarda_articulo_gasto.php',
			cache:false,
			type:'POST',
			data:{
				'id_item':id_item,
				'id_paquete':id_paquete,
				'id_articulo':id_articulo,
				'id_gasto':id_cotizacion,
				'cantidad':cantidad,
				'precio':precio,
				'total':total,
				'id_eve':eve
			},
			success: function(r){
				if(r.continuar){
					$("#"+elemento+" .id_item").val(r.id_item);
					padre.find(".id_cotizacion").val(id_cotizacion);
					alerta("info",r.info);
					row.addClass("verde_ok");
					setTimeout(function(){checarTotal('cotizaciones',id_cotizacion);},500);
				  }else{
					alerta("error",r.info);
				  }
			}
		});
	}else{
		alert("Debes guardar la cotización primero");
	}
}