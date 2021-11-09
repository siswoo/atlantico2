<?php
@session_start();
include('conexion.php');
$condicion = $_POST["condicion"];
$datetime = date('Y-m-d H:i:s');
$fecha_creacion = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');

if($condicion=='login1'){
	$usuario = $_POST['usuario'];
	$clave = md5($_POST["clave"]);
	$sql1 = "SELECT * FROM usuarios WHERE usuario = '$usuario' and clave = '$clave'";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	
	if($contador1>=1){
		while($row1 = mysqli_fetch_array($proceso1)) {
			$id = $row1["id"];
			$permisos = $row1["permisos"];
		}

		$_SESSION['atlantico2_id'] = $id;

		$datos = [
			"estatus"	=> "ok",
			"sql1" 		=> $sql1,
			"permisos"	=> $permisos,
		];
		echo json_encode($datos);
	}else{
		$datos = [
			"estatus"	=> "error",
			"sql1" 		=> $sql1,
			"msg"		=> "Credenciales incorrectas",
		];
		echo json_encode($datos);
	}
}

if($condicion=='table1'){
	$pagina = $_POST["pagina"];
	$consultasporpagina = $_POST["consultasporpagina"];
	$filtrado = $_POST["filtrado"];
	$sede = $_POST["sede"];
	$link1 = $_POST["link1"];
	$link1 = explode("/",$link1);
	$link1 = $link1[3];

	if($pagina==0 or $pagina==''){
		$pagina = 1;
	}

	if($consultasporpagina==0 or $consultasporpagina==''){
		$consultasporpagina = 10;
	}

	if($filtrado!=''){
		$filtrado = ' and (us.nombre LIKE "%'.$filtrado.'%" or us.documento_numero LIKE "%'.$filtrado.'%" or us.telefono LIKE "%'.$filtrado.'%" or us.correo LIKE "%'.$filtrado.'%")';
	}

	if($sede!=''){
		$sede = ' and us.proyectos = '.$sede;
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT us.nombre as us_nombre, us.documento_numero as us_documento_numero, us.telefono as us_telefono, us.correo as us_correo, us.estatus as us_estatus, doct.nombre as doct_nombre, mu.nombre as mu_nombre FROM usuarios us 
		INNER JOIN proyectos pro 
		ON us.proyectos = pro.id 
		INNER JOIN municipios mu 
		ON us.municipio = mu.id 
		INNER JOIN documento_tipo doct 
		ON us.documento_tipo = doct.id 
		WHERE us.id != 0 
		".$filtrado." 
		".$sede."
	";
	
	$sql2 = "SELECT us.id as us_id, us.nombre as us_nombre, us.documento_numero as us_documento_numero, us.telefono as us_telefono, us.correo as us_correo, us.estatus as us_estatus, doct.nombre as doct_nombre, mu.nombre as mu_nombre FROM usuarios us 
		INNER JOIN proyectos pro 
		ON us.proyectos = pro.id 
		INNER JOIN municipios mu 
		ON us.municipio = mu.id 
		INNER JOIN documento_tipo doct 
		ON us.documento_tipo = doct.id 
		WHERE us.id != 0 
		".$filtrado." 
		".$sede."
		ORDER BY us.id ASC LIMIT ".$limit." OFFSET ".$offset."
	";

	$proceso1 = mysqli_query($conexion,$sql1);
	$proceso2 = mysqli_query($conexion,$sql2);
	$conteo1 = mysqli_num_rows($proceso1);
	$paginas = ceil($conteo1 / $consultasporpagina);

	$html = '';

	$html .= '
		<div class="col-12">
	        <table class="table table-bordered">
	            <thead>
	            <tr>
	                <th class="text-center">Nombre</th>
	                <th class="text-center">Doc tipo</th>
	                <th class="text-center">Doc Numero</th>
	                <th class="text-center">Telefono</th>
	                <th class="text-center">Correo</th>
	                <th class="text-center">Municipio</th>
	                <th class="text-center">Estatus</th>
	                <th class="text-center">Opciones</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {

			if($row2["us_estatus"]==1){
				$us_estatus = "Activo";
			}else{
				$us_estatus = "Inactivo";
			}

			$html .= '
		                <tr id="tr_'.$row2["us_id"].'">
		                    <td style="text-align:center;">'.$row2["us_nombre"].'</td>
		                    <td style="text-align:center;">'.$row2["doct_nombre"].'</td>
		                    <td style="text-align:center;">'.$row2["us_documento_numero"].'</td>
		                    <td style="text-align:center;">'.$row2["us_telefono"].'</td>
		                    <td style="text-align:center;">'.$row2["us_correo"].'</td>
		                    <td style="text-align:center;">'.$row2["mu_nombre"].'</td>
		                    <td style="text-align:center;">'.$us_estatus.'</td>
		                    <td class="text-center" nowrap="nowrap">
		                    	<button type="button" class="btn btn-primary" style="cursor:pointer;" data-toggle="modal" data-target="#modal_editar" onclick="editar1('.$row2["us_id"].');">Editar</button>
			';

			if($row2["us_estatus"]==1){
				$html .= '
								<button type="button" class="btn btn-danger" style="cursor:pointer;" onclick="desactivar1('.$row2["us_id"].');">Desactivar</button>
				';
			}else{
				$html .= '
								<button type="button" class="btn btn-success" style="cursor:pointer;" onclick="activar1('.$row2["us_id"].');">Activar</button>
				';
			}
		    
		    $html .= '		</td>
		    			</tr>
		    ';
		}
	}else{
		$html .= '<tr><td colspan="10" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
	}

	$html .= '
	            </tbody>
	        </table>
	        <nav>
	            <div class="row">
	                <div class="col-xs-12 col-sm-4 text-center">
	                    <p>Mostrando '.$consultasporpagina.' de '.$conteo1.' Datos disponibles</p>
	                </div>
	                <div class="col-xs-12 col-sm-4 text-center">
	                    <p>Página '.$pagina.' de '.$paginas.' </p>
	                </div> 
	                <div class="col-xs-12 col-sm-4">
			            <nav aria-label="Page navigation" style="float:right; padding-right:2rem;">
							<ul class="pagination">
	';
	
	if ($pagina > 1) {
		$html .= '
								<li class="page-item">
									<a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
										<span aria-hidden="true">Anterior</span>
									</a>
								</li>
		';
	}

	$diferenciapagina = 3;
	
	/*********MENOS********/
	if($pagina==2){
		$html .= '
		                		<li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
		';
	}else if($pagina==3){
		$html .= '
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
			                            '.($pagina-2).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
	';
	}else if($pagina>=4){
		$html .= '
		                		<li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-3).');" href="#"">
			                            '.($pagina-3).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
			                            '.($pagina-2).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
		';
	} 

	/*********MAS********/
	$opcionmas = $pagina+3;
	if($paginas==0){
		$opcionmas = $paginas;
	}else if($paginas>=1 and $paginas<=4){
		$opcionmas = $paginas;
	}
	
	for ($x=$pagina;$x<=$opcionmas;$x++) {
		$html .= '
			                    <li class="page-item 
		';

		if ($x == $pagina){ 
			$html .= '"active"';
		}

		$html .= '">';

		$html .= '
			                        <a class="page-link" onclick="paginacion1('.($x).');" href="#"">'.$x.'</a>
			                    </li>
		';
	}

	if ($pagina < $paginas) {
		$html .= '
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina+1).');" href="#"">
			                            <span aria-hidden="true">Siguiente</span>
			                        </a>
			                    </li>
		';
	}

	$html .= '

						</ul>
					</nav>
				</div>
	        </nav>
	    </div>
	';

	$datos = [
		"estatus"	=> "ok",
		"html"	=> $html,
		"sql2"	=> $sql2,
	];
	echo json_encode($datos);
}

if($condicion=="agregar1"){
	$nombre = $_POST["nombre"];
	$documento_tipo = $_POST["documento_tipo"];
	$documento_numero = $_POST["documento_numero"];
	$telefono = $_POST["telefono"];
	$correo = $_POST["correo"];
	$municipio = $_POST["municipio"];
	$estatus = $_POST["estatus"];
	$proyectos = $_POST["proyectos"];

	$sql1 = "SELECT * FROM usuarios WHERE documento_numero = '$documento_numero' or correo = '$correo'";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	if($contador1>=1){
		$datos = [
			"estatus"	=> "error",
			"sql1" 		=> $sql1,
			"msg"		=> "Usuario ya existente!",
		];
		echo json_encode($datos);
	}else{
		$usuario = $correo;
		$clave = md5($documento_numero);
		$sql2 = "INSERT INTO usuarios (nombre,documento_tipo,documento_numero,telefono,correo,municipio,estatus,proyectos,usuario,clave) VALUES ('$nombre','$documento_tipo','$documento_numero','$telefono','$correo','$municipio','$estatus','$proyectos','$usuario','$clave')";
		$proceso2 = mysqli_query($conexion,$sql2);
		$datos = [
			"estatus"	=> "ok",
			"sql1" 		=> $sql1,
			"sql2" 		=> $sql2,
			"msg"		=> "Usuario agregado exitosamente!",
		];
		echo json_encode($datos);
	}
}

if($condicion=='consultar1'){
	$usuario_id = $_POST['usuario_id'];
	$sql1 = "SELECT * FROM usuarios WHERE id = ".$usuario_id;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	
	if($contador1>=1){
		while($row1 = mysqli_fetch_array($proceso1)) {
			$nombre = $row1["nombre"];
			$documento_tipo = $row1["documento_tipo"];
			$documento_numero = $row1["documento_numero"];
			$telefono = $row1["telefono"];
			$correo = $row1["correo"];
			$municipio = $row1["municipio"];
			$estatus = $row1["estatus"];
			$proyectos = $row1["proyectos"];
		}

		$datos = [
			"estatus"			=> "ok",
			"sql1" 				=> $sql1,
			"nombre" 			=> $nombre,
			"documento_tipo" 	=> $documento_tipo,
			"documento_numero" 	=> $documento_numero,
			"telefono" 			=> $telefono,
			"correo" 			=> $correo,
			"municipio" 		=> $municipio,
			"estatus2" 			=> $estatus,
			"proyectos" 		=> $proyectos,
		];
		echo json_encode($datos);

	}else{
		$datos = [
			"estatus"	=> "error",
			"sql1" 		=> $sql1,
			"msg"		=> "Usuario no existente!",
		];
		echo json_encode($datos);
	}
}

if($condicion=='editar1'){
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$documento_tipo = $_POST['documento_tipo'];
	$documento_numero = $_POST['documento_numero'];
	$telefono = $_POST['telefono'];
	$correo = $_POST['correo'];
	$municipio = $_POST['municipio'];
	$estatus = $_POST['estatus'];
	$proyectos = $_POST['proyectos'];

	$sql1 = "SELECT * FROM usuarios WHERE id != $id and (documento_numero = '$documento_numero' or correo = '$correo')";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	if($contador1>=1){
		$datos = [
			"estatus"	=> "error",
			"sql1" 		=> $sql1,
			"msg"		=> "Usuario ya existente!",
		];
		echo json_encode($datos);
	}else{
		$usuario = $correo;
		$clave = md5($documento_numero);
		$sql2 = "UPDATE usuarios SET nombre = '$nombre', documento_tipo = '$documento_tipo', documento_numero = '$documento_numero', telefono = '$telefono', correo = '$correo', municipio = '$municipio', estatus = '$estatus', proyectos = '$proyectos', usuario = '$usuario', clave = '$clave' WHERE id = ".$id;
		$proceso2 = mysqli_query($conexion,$sql2);

		$datos = [
			"estatus"	=> "ok",
			"sql1" 		=> $sql1,
			"sql2" 		=> $sql2,
			"msg"		=> "Usuario modificado exitosamente!",
		];
		echo json_encode($datos);
	}
}

if($condicion=='activar1'){
	$id = $_POST["usuario_id"];
	$sql1 = "UPDATE usuarios SET estatus = 1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	
	$datos = [
		"estatus"	=> "error",
		"sql1" 		=> $sql1,
		"msg"		=> "Estatus Cambiado!",
	];
	echo json_encode($datos);
}

if($condicion=='inactivar1'){
	$id = $_POST["usuario_id"];
	$sql1 = "UPDATE usuarios SET estatus = 2 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	
	$datos = [
		"estatus"	=> "error",
		"sql1" 		=> $sql1,
		"msg"		=> "Estatus Cambiado!",
	];
	echo json_encode($datos);
}

?>