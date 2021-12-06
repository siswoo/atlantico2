<?php
@session_start();
if(@$_SESSION['atlantico2_id']==''){?>
	<script type="text/javascript">
		window.location.href = "index.php";
	</script>
<?php } ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="resources/signature/docs/css/signature-pad.css">
    <title>AtlanticoV2</title>
  </head>

  <style type="text/css">
  	.error{
  		color: red;
  		font-size: 14px;
  	}
  </style>

<body>

<?php
$ubicacion='Inicio';
include("script/conexion.php");
include("header.php");

$sql1 = "SELECT * FROM usuarios WHERE id = ".$_SESSION['atlantico2_id'];
$proceso1 = mysqli_query($conexion,$sql1);
while($row1 = mysqli_fetch_array($proceso1)){
	$proyecto = $row1["proyectos"];
	$municipio = $row1["municipio"];
}
?>

<div class="container">
	<form id="formulario1" method="POST" action="#">
		<input type="hidden" id="usuario_id" name="usuario_id" value="<?php echo $_SESSION['atlantico2_id']; ?>">
		<input type="hidden" id="condicion" name="condicion" value="registro1">
		<div class="row">
			<?php
			$sql2 = "SELECT * FROM proyectos WHERE id =".$proyecto;
			$proceso2 = mysqli_query($conexion,$sql2);
			while($row2 = mysqli_fetch_array($proceso2)){
				$proyecto_nombre = $row2["nombre"];
				echo '
					<div class="col-12 text-center mt-3 mb-3" style="text-transform: uppercase; font-size: 28px; font-weight: bold;">'.$proyecto_nombre.'</div>
				';
			}

			$sql3 = "SELECT * FROM secciones WHERE proyecto =".$proyecto." and estatus = 1 ORDER BY orden ASC";
			$proceso3 = mysqli_query($conexion,$sql3);
			while($row3 = mysqli_fetch_array($proceso3)){
				$secciones_id = $row3["id"];
				$secciones_titulo = $row3["titulo"];
				$secciones_tabla = $row3["tabla"];
				$secciones_orden = $row3["orden"];

				if($secciones_titulo!=''){
					echo '
						<div class="col-12 text-center mt-3 mb-3" style="text-transform: uppercase; font-size: 25px; font-weight: bold;">'.$secciones_titulo.'</div>
					';
				}

				if($secciones_tabla==1){

					echo '
					<div class="col-12">
						<table class="table" border="1">
	          	<thead>
	          		<tr>
	            		<th class="text-center" style="font-size: 18px;">Preguntas</th>
	            		<th class="text-center" style="font-size: 18px;">Respuesta</th>
	            	</tr>
	            </thead>
	            <tbody>
					';

					$sql4 = "SELECT * FROM preguntas WHERE secciones = ".$secciones_id." and estatus = 1 ORDER BY orden ASC";
					$proceso4 = mysqli_query($conexion,$sql4);
					while($row4 = mysqli_fetch_array($proceso4)){
						$preguntas_id = $row4["id"];
						$preguntas_texto = $row4["texto"];
						$preguntas_campos_tipos = $row4["campos_tipos"];
						$preguntas_ordenador = $row4["ordenador"];
						$preguntas_tablet = $row4["tablet"];
						$preguntas_telefono = $row4["telefono"];

						echo '
								<tr>
									<td class="text-center">'.$preguntas_texto.'</td>
									<td class="text-center">
										<select class="form-control" name="pregunta_'.$preguntas_id.'" id="pregunta_'.$preguntas_id.'" required>
											<option value="">Seleccione</option>
						';
						
						$sql5 = "SELECT * FROM preguntas_opciones WHERE preguntas = ".$preguntas_id." ORDER BY orden ASC";
						$proceso5 = mysqli_query($conexion,$sql5);
						while($row5 = mysqli_fetch_array($proceso5)){
							$preguntaop_texto = $row5["texto"];
							echo '
										<option value="'.$preguntaop_texto.'">'.$preguntaop_texto.'</option>
							';
						}

						echo '
								</td>
							</tr>
						';
					}

					echo '
							</tbody>
						</table>
					</div>
					';
				}else{

					$sql4 = "SELECT * FROM preguntas WHERE secciones = ".$secciones_id." and estatus = 1 ORDER BY orden ASC";
					$proceso4 = mysqli_query($conexion,$sql4);
					while($row4 = mysqli_fetch_array($proceso4)){
						$preguntas_id = $row4["id"];
						$preguntas_texto = $row4["texto"];
						$preguntas_campos_tipos = $row4["campos_tipos"];
						$preguntas_ordenador = $row4["ordenador"];
						$preguntas_tablet = $row4["tablet"];
						$preguntas_telefono = $row4["telefono"];

						if($preguntas_campos_tipos==1){
							echo '
								<div class="'.$preguntas_ordenador.' '.$preguntas_tablet.' '.$preguntas_telefono.' form-group">
									<label for="pregunta_'.$preguntas_id.'" style="font-weight: bold;">'.$preguntas_texto.'</label>
									<input type="text" class="form-control" name="pregunta_'.$preguntas_id.'" id="pregunta_'.$preguntas_id.'" required>
								</div>
							';
						}else if($preguntas_campos_tipos==2){
							$sql5 = "SELECT * FROM preguntas_opciones WHERE preguntas = ".$preguntas_id." ORDER BY orden ASC";
							$proceso5 = mysqli_query($conexion,$sql5);
							$contador5 = mysqli_num_rows($proceso5);
							if($contador5>=1){
								while($row5 = mysqli_fetch_array($proceso5)){
									$preguntaop_texto = $row5["texto"];
									if($preguntaop_texto=='[departamento_pertenece]'){
										$sql6 = "SELECT * FROM municipios WHERE id = ".$municipio;
										$proceso6 = mysqli_query($conexion,$sql6);
										while($row6=mysqli_fetch_array($proceso6)){
											$preguntaop_texto = $row6["nombre"];
										}
									}
								}
							}else{
								$preguntaop_texto = "";
							}

							echo '
								<div class="'.$preguntas_ordenador.' '.$preguntas_tablet.' '.$preguntas_telefono.' form-group">
									<label for="pregunta_'.$preguntas_id.'" style="font-weight: bold;">'.$preguntas_texto.'</label>
									<input type="text" class="form-control" name="pregunta_'.$preguntas_id.'" id="pregunta_'.$preguntas_id.'" value="'.$preguntaop_texto.'" readonly="readonly">
								</div>
							';
						}else if($preguntas_campos_tipos==3){
							$sql5 = "SELECT * FROM preguntas_opciones WHERE preguntas = ".$preguntas_id." ORDER BY orden ASC";
							$proceso5 = mysqli_query($conexion,$sql5);
							echo '
								<div class="'.$preguntas_ordenador.' '.$preguntas_tablet.' '.$preguntas_telefono.' form-group">
									<label for="pregunta_'.$preguntas_id.'" style="font-weight: bold;">'.$preguntas_texto.'</label>
									<select class="form-control" name="pregunta_'.$preguntas_id.'" id="pregunta_'.$preguntas_id.'" required>
										<option value="">Seleccione</option>
							';
										while($row5 = mysqli_fetch_array($proceso5)){
											$preguntaop_id = $row5["id"];
											$preguntaop_texto = $row5["texto"];
											echo '
												<option value="'.$preguntaop_texto.'">'.$preguntaop_texto.'</option>
											';
										}
							echo '
									</select>
								</div>
							';
						}else if($preguntas_campos_tipos==4){
							$sql5 = "SELECT * FROM preguntas_opciones WHERE preguntas = ".$preguntas_id." ORDER BY orden ASC";
							$proceso5 = mysqli_query($conexion,$sql5);

							echo '
								<div class="'.$preguntas_ordenador.' '.$preguntas_tablet.' '.$preguntas_telefono.' form-group">
									<label for="pregunta_'.$preguntas_id.'" style="font-weight: bold;">'.$preguntas_texto.'</label>
									<select class="form-control" name="pregunta_'.$preguntas_id.'" id="pregunta_'.$preguntas_id.'" required>
										<option value="">Seleccione</option>
							';

										while($row5 = mysqli_fetch_array($proceso5)){
											$preguntaop_tabla = $row5["tabla"];
											$preguntaop_condicional1 = $row5["condicional1"];
											$preguntaop_condicional2 = $row5["condicional2"];
											$preguntaop_condicional3 = $row5["condicional3"];
										}

										$sql6 = "SELECT * FROM $preguntaop_tabla WHERE id != 0 $preguntaop_condicional1 $preguntaop_condicional2 $preguntaop_condicional3";
										$proceso6 = mysqli_query($conexion,$sql6);
										while($row6=mysqli_fetch_array($proceso6)){
											$variable_id = $row6["id"];
											$variable_nombre = $row6["nombre"];
											echo '
												<option value="'.$variable_nombre.'">'.$variable_nombre.'</option>
											';
										}

							echo '
									</select>
								</div>
							';
						}
					}
				}
			}
			?>
			<div class="col-lg-6 col-xl-6 col-md-6 col-12 col-sm-12 text-center mt-3 mb-3">
				<button type="button" class="btn btn-primary" onclick="firma1();">Generar Firma Digital</button>
			</div>
			<div class="col-lg-6 col-xl-6 col-md-6 col-12 col-sm-12 text-center mt-3 mb-3">
				<input type="file" name="firma" id="firma" class="form-control" required>
			</div>
			<div class="col-12 text-center mt-3">
				<button type="submit" class="btn btn-success" id="enviar" name="enviar" style="font-weight: bold; font-size: 20px;">ENVIAR</button>
			</div>
		</div>
	</form>
</div>

<?php
include("footer.php");
?>

</body>
</html>

<script src="js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="js/popper.js"></script>
<script src="js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="resources/signature/docs/js/signature_pad.umd.js"></script>

<script>
	
	$('#carouselExampleIndicators2').carousel({
  		interval: 2000
	});

	$("#formulario1").on("submit", function(e){
    e.preventDefault();
    $.ajax({
        url: 'script/crud_encuestas.php',
        type: 'POST',
        dataType: "JSON",
        data: new FormData(this),
        processData: false,
        contentType: false,

        beforeSend: function(){
        	$('#enviar').attr("disabled","true");
        },

        success: function(respuesta){
        	console.log(respuesta);
        	if(respuesta["estatus"]=="ok"){
						Swal.fire({
							title: 'Correcto!',
							text: respuesta["msg"],
							icon: 'success',
            });
          }else if(respuesta["estatus"]=="error"){
            Swal.fire({
              title: 'Error',
             	text: respuesta["msg"],
             	icon: 'error',
           	})
          }
          setTimeout("location.href='index2.php'", 5000);
        	/*$('#enviar').removeAttr("disabled");*/
        },
        
        error: function (respuesta){
        	/*$('#enviar').removeAttr("disabled");*/
        	console.log(respuesta['responseText']);
	      }
    });        

});

	function firma1(){
    window.open("script/generar_firma1.php", "Diseño Web", "width=800, height=800");
  }

</script>