<?php
session_start();
session_destroy();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/validaciones.css">
    <title>Camaleon Sistem</title>
  </head>
  <style type="text/css">
  	#submit{
  		background-color: #A67D4C!important; 
  		border-color: #A67D4C;
  	}

  	#submit:hover{
  		background-color: #735735 !important; 
  		border-color: #735735 !important;
  		color: white !important;
  	}

  	body{
  		background-image: url("img/logos/FONDO APP.png");
  	}

  	.btn-info{
  		background-color: #A9814F !important;
  		border-color: #A9814F !important;
  	}

  	.btn-primary{
  		background-color: #A9814F !important;
  		border-color: #A9814F !important;
  	}
  </style>
<body>

	<div class="col-12 text-center" style="margin-top: 4rem;">
		<img src="img/logos/logo_index1.png" style="width: 200px;">
	</div>

    <div class="seccion1" style="margin-top: 3rem;">
	    <div class="row">
		    <div class="container">
			    <form action="#" method="POST" id="formulario1" style="margin-left: 30%; margin-right: 30%;">
				    <div class="col-12" class="text-center">
				    	<p class="text-center titulo1">Datos de Ingreso</p>
				    </div>
				    <div class="form-group form-check">
					    <label for="usuario">Correo</label>
					    <input type="text" class="form-control" name="usuario" id="usuario" minlength="4" required>
				    </div>
				    <div class="form-group form-check">
					    <label for="clave">Clave</label>
					    <input type="password" class="form-control" name="clave" id="clave" minlength="4" required>
				    </div>
				    <div class="form-group form-check">
				    	<label for="estatus">Módulo</label>
					    <select class="form-control" id="estatus" name="estatus" required>
					    	<option value="Modelo">Modelo</option>
					    	<option value="Nomina">Nómina</option>
					    	<option value="Pasantia">Pasantia</option>
					    	<option value="Satelite">Satelite</option>
					    </select>
				    </div>
					<div class="row">
						<div class="col-12 form-group form-check text-center">
							<button type="submit" id="submit" class="btn btn-success mr-3">Ingresar</button>
							<button type="button" data-toggle="modal" data-target="#exampleModal1" class="btn btn-primary ml-3">Recuperar Contraseña</button>
						</div>
					</div>
			    </form>

		    </div>
	    </div>
    </div>

    <!-- Modal Recuperar Registro -->
	<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form action="#" method="POST" id="form_modal_recuperar1">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel" style="color:black;">Nuevo Registro</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
					    <div class="row">
						    <div class="col-12 form-group form-check">
							    <label for="recovery_correo" style="color:black;">Correo electrónico</label>
							    <input type="email" id="recovery_correo" name="recovery_correo" class="form-control" required>
						    </div>
						    <div class="col-12 form-group form-check">
							    <label for="recovery_estatus" style="color:black;">Modulo</label>
							    <select class="form-control" name="recovery_estatus" id="recovery_estatus" required>
							    	<option value="Modelo">Modelo</option>
							    	<option value="Nomina">Nómina</option>
							    	<option value="Empresa">Empresa</option>
							    	<option value="Pasantia">Pasantia</option>
							    	<option value="Satelite">Satelite</option>
							    	<option value="Aliados">Aliados</option>
							    </select>
						    </div>
						    <div class="col-12 text-center" style="font-size: 14px;color:blue;font-weight: bold;">
						    	La nueva clave le será enviada a su correo electrónico
						    </div>
						</div>
					</div>
					<div class="modal-footer">
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				        <button type="submit" class="btn btn-success" id="submit_guardar1">Recuperar</button>
			      	</div>
		      	</form>
	    	</div>
	  	</div>
	</div>
<!-- FIN Modal Recuperar Registro -->

<form action="welcome/index.php" id="formulario2" method="POST">
	<input type="hidden" value="" id="usuario_id" name="usuario_id">
	<input type="hidden" value="" id="usuario_estatus" name="usuario_estatus">
</form>

<form action="pasantias/index.php" id="formulario3" method="POST">
	<input type="hidden" value="" id="usuario_id2" name="usuario_id2">
	<input type="hidden" value="" id="usuario_estatus2" name="usuario_estatus2">
</form>

</body>
</html>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script>

	$(document).ready(function() {
		/*******************PRUEBAS*********************/
		//$('#usuario').val("juanmaldonado.co@gmail.com");
		$('#usuario').val("programador@camaleonmg.com");
		$('#clave').val("2421187");
		$('#estatus').val("Nomina");
		/***********************************************/
	});

	$('#myModal').on('shown.bs.modal', function () {
	  	$('#myInput').trigger('focus')
	});

	$("#formulario1").on("submit", function(e){
		e.preventDefault();
		var f = $(this);
		var usuario = $('#usuario').val();
		var clave = $('#clave').val();
		var estatus = $('#estatus').val();
		$.ajax({
	        url: 'script/crud_usuarios.php',
	        type: 'POST',
	        dataType: "JSON",
	        data: {
				"usuario": usuario,
				"clave": clave,
				"estatus": estatus,
				"condicion": "login1",
			},

	        beforeSend: function (){},

	        success: function(respuesta){
	        	console.log(respuesta);

	        	if(estatus=='Modelo'){
	        		if(respuesta["respuesta"]=='error'){
	        			Swal.fire({
									title: 'Error',
									text: respuesta["msg"],
									icon: 'error',
									position: 'center',
									showConfirmButton: true,
								});
								return false;
	        		}
	        	}

	        	if(respuesta["estatus"]=="sin resultados"){
	        		Swal.fire({
								title: 'Error',
								text: "Datos Incorrectos!",
								icon: 'error',
								position: 'center',
								showConfirmButton: false,
								timer: 2000
							});
	        	}else if(respuesta["estatus"]!="sin resultados" && respuesta["estatus"]=="Pasantia"){
	        		$('#usuario_id2').val(respuesta["usuario_id"]);
	        		$('#usuario_estatus2').val(respuesta["estatus"]);
	        		$('#formulario3').submit();
	        	}else{
	        		$('#usuario_id').val(respuesta["usuario_id"]);
	        		$('#usuario_estatus').val(respuesta["estatus"]);
	        		$('#formulario2').submit();
	        	}
	        },

	        error: function(respuesta){
	           	console.log(respuesta['responseText']);
	        }
	    });
	});

	$("#form_modal_recuperar1").on("submit", function(e){
		e.preventDefault();
		var f = $(this);
		var recovery_correo = $('#recovery_correo').val();
		var recovery_estatus = $('#recovery_estatus').val();
		$.ajax({
	        url: '../script/crud_usuarios.php',
	        type: 'POST',
	        data: {
				"recovery_correo": recovery_correo,
				"recovery_estatus": recovery_estatus,
				"condicion": "recuperar_contraseña1",
			},

	        beforeSend: function (){},

	        success: function(response){
	        	console.log(response);
	        	Swal.fire({
					title: 'Correcto!',
					text: "Correo enviado satisfactoriamente",
					icon: 'success',
					position: 'center',
					showConfirmButton: false,
					timer: 2000
				});

				$("#exampleModal1").modal('hide');
				$('#exampleModal1').removeClass('modal-open');
				$('.modal-backdrop').remove();
	        },

	        error: function(response){
	           	console.log(response['responseText']);
	        }
	    });
	});

</script>