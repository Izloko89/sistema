// JavaScript Document
$(document).ready(function(e) {
    //busca cliente
	$( ".nombre" ).autocomplete({
      source: "scripts/busca_salones.php",
      minLength: 1,
      select: function( event, ui ) {
		//asignacion individual alos campos
		$("#f_salones .id_salon").val(ui.item.id_salon);
		$("#f_salones .direccion").val(ui.item.Direccion);
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
    $(".volver").click(function(e) {
		ingresar=true;
    	$("#formularios_modulo").hide("slide",{direction:'right'},rapidez,function(){
			$("#botones_modulo").fadeIn(rapidez);
		});
    });
	$(".guardar").click(function(e) {
	  if(requerido()){
		//metodo en que se va a guardar
		nombre = document.getElementById("nombre").value;
		direccion = document.getElementById("direccion").value;
		
		//procesamiento de datos
		$.ajax({
			url:'scripts/s_guardar_salon.php',
			cache:false,
			async:false,
			type:'POST',
			data:{
				'nombre':nombre,
				'direccion':direccion
			},
			success: function(r){
				if(r){
					alerta("info","El Salon se agrego");
					$(".volver").click();
				}else{
					alerta("error","Ocurrio un error al agregar el salon");
				}
			}
		});
	  }//if del requerido*/
    });
});
	function eliminar_art(elemento, id_item){
		$.ajax({
			url:'scripts/eSalon_evento.php',
			cache:false,
			type:'POST',
			data:{
				'id_item':id_item
			},
			success: function(r){
			  if(r){
				document.getElementById("tableEve").deleteRow(6);
				alerta("info","<strong>Salon</strong> Eliminado");
					$(".volver").click();
			  }else{
				alerta("error", r);
			  }
			}
		});
	}