<?php
@session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../resources/PHPMailer/PHPMailer/src/Exception.php';
require '../resources/PHPMailer/PHPMailer/src/PHPMailer.php';
require '../resources/PHPMailer/PHPMailer/src/SMTP.php';
include('conexion.php');
include('conexion2.php');
$condicion = $_POST["condicion"];
$datetime = date('Y-m-d H:i:s');
$empresa = $_SESSION["camaleonapp_empresa"];
$fecha_creacion = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');
$responsable = $_SESSION['camaleonapp_id'];

if($condicion=='login1'){
	$usuario = $_POST['usuario'];
	$clave = md5($_POST["clave"]);
	$estatus = $_POST['estatus'];

	if($estatus=='Pasantia'){
		$sql1 = "SELECT dpa.id_usuarios as id, us.id_empresa as empresa FROM usuarios us 
		INNER JOIN datos_pasantias dpa ON us.id = dpa.id_usuarios 
		WHERE us.correo_empresa = '".$usuario."' and us.clave = '".$clave."' and us.estatus_pasantia = 1 LIMIT 1";
	}else if($estatus=='Modelo'){
		$sql1 = "SELECT us.id as id, us.estatus_modelo as estatus_modelo, us.id_empresa as empresa FROM usuarios us WHERE us.correo_personal = '".$usuario."' and us.clave = '".$clave."' LIMIT 1";
	}else if($estatus=='Nomina'){
		$sql1 = "SELECT dno.id_usuarios as id, us.id_empresa as empresa FROM usuarios us 
		INNER JOIN datos_nominas dno ON us.id = dno.id_usuarios 
		WHERE correo_empresa = '".$usuario."' and clave = '".$clave."' and estatus_nomina = 1 LIMIT 1";
	}else if($estatus=='Satelite'){
		$sql1 = "SELECT * FROM usuarios WHERE correo_personal = '".$usuario."' and clave = '".$clave."' and estatus_satelite = 1 LIMIT 1";
	}else if($estatus=='Empresa'){
		$sql1 = "SELECT * FROM usuarios WHERE correo_personal = '".$usuario."' and clave = '".$clave."' and estatus_empresa = 1 LIMIT 1";
	}

	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	if($contador1>=1){
		if($estatus=='Modelo'){
			while($row1 = mysqli_fetch_array($proceso1)) {
				$usuario_id=$row1['id'];
				$empresa=$row1['empresa'];

				$sql2 = "SELECT us.estatus_modelo as estatus_modelo, dmo.id_usuarios as id, us.id_empresa as empresa FROM usuarios us 
				INNER JOIN datos_modelos dmo ON us.id = dmo.id_usuarios 
				WHERE us.id = ".$usuario_id." LIMIT 1";
				$proceso2 = mysqli_query($conexion,$sql2);
				$contador2 = mysqli_num_rows($proceso2);
				
				if($contador2>=1){
					while($row2 = mysqli_fetch_array($proceso2)) {
						$estatus_modelo=$row2['estatus_modelo'];
						if($estatus_modelo==0){
							$datos = [
								"estatus" => $estatus,
								"respuesta" => "error",
								"msg" => "Su cuenta de Modelo no ha sido Activada!",
								"sql1" => $sql1,
							];
							echo json_encode($datos);
							exit;
						}else if($estatus_modelo==2){
							$datos = [
								"estatus" => $estatus,
								"respuesta" => "error",
								"msg" => "Su cuenta de Modelo ha sido Rechazada!",
								"sql1" => $sql1,
							];
							echo json_encode($datos);
							exit;
						}
					}
				}else if($contador2==0){
					$datos = [
						"sql1" => $sql1,
						"respuesta" => "error",
						"msg" => "Su cuenta de Modelo no ha sido Activada!",
					];
					echo json_encode($datos);
					exit;
				}

				session_start();
				$_SESSION["camaleonapp_id"]=$usuario_id;
				$_SESSION["camaleonapp_estatus"]=$estatus;
				$_SESSION["camaleonapp_empresa"]=$empresa;

				$datos = [
					"estatus" => $estatus,
					"sql1" => $sql1,
					"usuario_id" => $usuario_id,
				];
			}
			echo json_encode($datos);
			exit;
		}
		while($row1 = mysqli_fetch_array($proceso1)) {
			$usuario_id=$row1['id'];
			$empresa=$row1['empresa'];
			session_start();
			$_SESSION["camaleonapp_id"]=$usuario_id;
			$_SESSION["camaleonapp_estatus"]=$estatus;
			$_SESSION["camaleonapp_empresa"]=$empresa;

			$datos = [
				"estatus" => $estatus,
				"sql1" => $sql1,
				"usuario_id" => $usuario_id,
			];
		}
		echo json_encode($datos);
	}else{
		$datos = [
			"sql1" => $sql1,
			"estatus"	=> "sin resultados",
		];
		echo json_encode($datos);
	}
}

if($condicion=='consultar_pasantes1'){
	$id = $_POST['id'];
	$sql1 = "SELECT * FROM usuarios WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	while($row1 = mysqli_fetch_array($proceso1)) {
		$nombre1 = $row1["nombre1"];
		$nombre2 = $row1["nombre2"];
		$apellido1 = $row1["apellido1"];
		$apellido2 = $row1["apellido2"];
		$documento_tipo = $row1["documento_tipo"];
		$documento_numero = $row1["documento_numero"];
		$correo_personal = $row1["correo_personal"];
		$telefono = $row1["telefono"];
		$genero = $row1["genero"];
		$direccion = $row1["direccion"];
	}

	$sql2 = "SELECT * FROM datos_pasantes WHERE id_usuarios = ".$id;
	$proceso2 = mysqli_query($conexion,$sql2);
	while($row2 = mysqli_fetch_array($proceso2)) {
		$sede = $row2["sede"];
		$estatus = $row2["estatus"];
		$turno = $row2["turno"];
	}

	$datos = [
		"sql1" 				=> $sql1,
		"estatus"			=> $estatus,
		"nombre1"			=> $nombre1,
		"nombre2"			=> $nombre2,
		"apellido1"			=> $apellido1,
		"apellido2"			=> $apellido2,
		"documento_tipo"	=> $documento_tipo,
		"documento_numero"	=> $documento_numero,
		"correo_personal"	=> $correo_personal,
		"telefono"			=> $telefono,
		"genero"			=> $genero,
		"direccion"			=> $direccion,
		"sede"				=> $sede,
	];
	echo json_encode($datos);
}

if($condicion=='peticion_pasantes1'){
	$id = $_POST['id'];
	$sql1 = "SELECT * FROM datos_pasantes WHERE id_usuarios = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	while($row1 = mysqli_fetch_array($proceso1)) {
		$sede = $row1["sede"];
		$estatus = $row1["estatus"];
		$turno = $row1["turno"];
	}

	$datos = [
		"sede" 		=> $sede,
		"estatus"	=> $estatus,
		"turno"	 	=> $turno,
	];
	echo json_encode($datos);
}

if($condicion=='editar_pasantes1'){
	$id = $_POST['id'];
	$sql1 = "SELECT * FROM datos_pasantes WHERE id_usuarios = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	while($row1 = mysqli_fetch_array($proceso1)) {
		$estatus = $row1["estatus"];
	}

	if($estatus==1){
		$nombre1 = $_POST['nombre1'];
		$nombre2 = $_POST['nombre2'];
		$apellido1 = $_POST['apellido1'];
		$apellido2 = $_POST['apellido2'];
		$documento_tipo = $_POST['documento_tipo'];
		$documento_numero = $_POST['documento_numero'];
		$correo_personal = $_POST['correo_personal'];
		$telefono = $_POST['telefono'];
		$genero = $_POST['genero'];
		$sede = $_POST['sede'];
		
		$sql2 = "UPDATE usuarios SET nombre1 = '$nombre1', nombre2 = '$nombre2', apellido1 = '$apellido1', apellido2 = '$apellido2', documento_tipo = '$documento_tipo', documento_numero = '$documento_numero', correo_personal = '$correo_personal', telefono = '$telefono', genero = '$genero' WHERE id = ".$id;
		$proceso2 = mysqli_query($conexion,$sql2);

		$sql3 = "UPDATE datos_pasantes SET sede = '$sede' WHERE id_usuarios = ".$id;
		$proceso3 = mysqli_query($conexion,$sql3);

		$sql4 = "SELECT * FROM sedes WHERE id = ".$sede;
		$proceso4 = mysqli_query($conexion,$sql4);
		while($row4 = mysqli_fetch_array($proceso4)) {
			$sede_nombre = $row4["nombre"];
		}

		$sql5 = "SELECT * FROM documento_tipo WHERE id = ".$documento_tipo;
		$proceso5 = mysqli_query($conexion,$sql5);
		while($row5 = mysqli_fetch_array($proceso5)) {
			$documento_tipo_nombre = $row5["nombre"];
		}

		$datos = [
			"estatus" => 1,
			"documento_tipo" => $documento_tipo_nombre,
			"documento_numero" => $documento_numero,
			"nombres" => $nombre1." ".$nombre2,
			"apellidos" => $apellido1." ".$apellido2,
			"genero" => $genero,
			"correo_personal" => $correo_personal,
			"telefono" => $telefono,
			"sede" => $sede_nombre,
		];
		echo json_encode($datos);
	}else{
		$datos = [
			"estatus" => 0,
		];
		echo json_encode($datos);
	}
}

if($condicion=='table1'){
	$pagina = $_POST["pagina"];
	$consultasporpagina = $_POST["consultasporpagina"];
	$filtrado = $_POST["filtrado"];
	$link1 = $_POST["link1"];
	$empresa = $_POST["empresa"];
	$m_estatus = $_POST["m_estatus"];
	$link1 = explode("/",$link1);
	$link1 = $link1[3];

	if($pagina==0 or $pagina==''){
		$pagina = 1;
	}

	if($consultasporpagina==0 or $consultasporpagina==''){
		$consultasporpagina = 10;
	}

	if($filtrado!=''){
		$filtrado = ' and (nombre1 LIKE "%'.$filtrado.'%" or nombre2 LIKE "%'.$filtrado.'%" or apellido1 LIKE "%'.$filtrado.'%" or apellido2 LIKE "%'.$filtrado.'%" or documento_numero LIKE "%'.$filtrado.'%" or us.correo_personal LIKE "%'.$filtrado.'%" or telefono LIKE "%'.$filtrado.'%")';
	}

	if($empresa!=''){
		$empresa = ' and (mo.id_empresa = '.$empresa.') ';
	}

	if($m_estatus!=''){
		$m_estatus = " and (mo.estatus = ".$m_estatus.")";
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT us.nombre1 as nombre1, us.nombre2 as nombre2, us.apellido1 as apellido1, us.apellido2 as apellido2, us.documento_tipo as documento_tipo, us.documento_numero as documento_numero, us.correo_personal as correo_personal, us.telefono as telefono, us.rol as rol, us.estatus_modelo as estatus_modelo, us.estatus_nomina as estatus_nomina, us.estatus_pasantia as estatus_pasantia, us.estatus_pasantes as estatus_pasantes, us.genero as genero, us.direccion as direccion, us.id_empresa as id_empresa, us.id_pais as id_pais, us.fecha_modificacion as fecha_modificacion, us.fecha_creacion as fecha_creacion, em.nombre as empresa_nombre, em.id as empresa_id, doct.nombre as documento_tipo_nombre 
		FROM usuarios us
		INNER JOIN empresas em
		ON us.id_empresa = em.id 
		INNER JOIN paises pa
		ON pa.id = us.id_pais
		INNER JOIN documento_tipo doct
		ON doct.id = us.documento_tipo
		WHERE us.id != 0 
		".$filtrado." 
		".$empresa."
		".$m_estatus." 
	";
	
	$sql2 = "SELECT us.id as id, us.nombre1 as nombre1, us.nombre2 as nombre2, us.apellido1 as apellido1, us.apellido2 as apellido2, us.documento_tipo as documento_tipo, us.documento_numero as documento_numero, us.correo_personal as correo_personal, us.telefono as telefono, us.rol as rol, us.estatus_modelo as estatus_modelo, us.estatus_nomina as estatus_nomina, us.estatus_pasantia as estatus_pasantia, us.estatus_pasantes as estatus_pasantes, us.genero as genero, us.direccion as direccion, us.id_empresa as id_empresa, us.id_pais as id_pais, us.fecha_modificacion as fecha_modificacion, us.fecha_creacion as fecha_creacion, em.nombre as empresa_nombre, em.id as empresa_id, doct.nombre as documento_tipo_nombre 
		FROM usuarios us
		INNER JOIN empresas em
		ON us.id_empresa = em.id 
		INNER JOIN paises pa
		ON pa.id = us.id_pais
		INNER JOIN documento_tipo doct
		ON doct.id = us.documento_tipo
		WHERE us.id != 0 
		".$filtrado." 
		".$empresa."
		".$m_estatus."
		ORDER BY us.id ASC LIMIT ".$limit." OFFSET ".$offset."
	";

	$proceso1 = mysqli_query($conexion,$sql1);
	$proceso2 = mysqli_query($conexion,$sql2);
	$conteo1 = mysqli_num_rows($proceso1);
	$paginas = ceil($conteo1 / $consultasporpagina);

	$html = '';

	$html .= '
		<div class="col-xs-12">
	        <table class="table table-bordered">
	            <thead>
	            <tr>
	                <th class="text-center">T Doc</th>
	                <th class="text-center">N Doc</th>
	                <th class="text-center">Nombre</th>
	                <th class="text-center">Modelo</th>
	                <th class="text-center">Nomina</th>
	                <th class="text-center">Satelite</th>
	                <th class="text-center">Pasantes</th>
	                <th class="text-center">Empresa</th>
	                <th class="text-center">Ingreso</th>
	                <th class="text-center">Opciones</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {

			if($row2["estatus_modelo"]==0){
				$estatus_modelo = "No";
			}else if($row2["estatus_modelo"]==1){
				$estatus_modelo = "Si";
			}

			if($row2["estatus_nomina"]==0){
				$estatus_nomina = "No";
			}else if($row2["estatus_nomina"]==1){
				$estatus_nomina = "Si";
			}

			if($row2["estatus_pasantes"]==0){
				$estatus_pasantes = "No";
			}else if($row2["estatus_pasantes"]==1){
				$estatus_pasantes = "Si";
			}

			$html .= '
		                <tr id="tr_'.$row2["id"].'">
		                    <td style="text-align:center;">'.$row2["documento_tipo_nombre"].'</td>
		                    <td style="text-align:center;">'.$row2["documento_numero"].'</td>
		                    <td>'.$row2["nombre1"]." ".$row2["nombre2"]." ".$row2["apellido1"]." ".$row2["apellido2"].'</td>
		                    <td style="text-align:center;">'.$estatus_modelo.'</td>
		                    <td style="text-align:center;">'.$estatus_nomina.'</td>
		                    <td style="text-align:center;">'.$estatus_pasantes.'</td>
		                    <td  style="text-align:center;">'.$row2["empresa_nombre"].'</td>
		                    <td style="text-align:center;">'.$row2["fecha_creacion"].'</td>
		                    <td nowrap="nowrap">'.$row2["fecha_creacion"].'</td>
		                    <td class="text-center" nowrap="nowrap">
		                    	<button type="button" class="btn btn-primary" style="cursor:pointer;" data-toggle="modal" data-target="#personales1" onclick="editar1('.$row2["id"].');">Editar</button>
		                    	<button type="button" class="btn btn-info" style="cursor:pointer;" data-toggle="modal" data-target="#permisos1" onclick="editar1('.$row2["id"].');">VP</button>
		                    	<button type="button" class="btn btn-success" style="cursor:pointer;" data-toggle="modal" data-target="#permisos2" onclick="editar1('.$row2["id"].');">AP</button>
			';
		    
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

if($condicion=='consulta1'){
	$id = $_POST["usuario_id"];
	$html1 = '';

	$sql1 = "SELECT us.nombre1 as nombre1, us.nombre2 as nombre2, us.apellido1 as apellido1, us.apellido2 as apellido2, us.documento_tipo as documento_tipo, us.documento_numero as documento_numero, us.correo_personal as correo, us.telefono as telefono, us.genero as genero, us.direccion as direccion, us.estatus_modelo as estatus_modelo, us.estatus_nomina as estatus_nomina, us.estatus_pasantes as estatus_pasantes, doct.nombre as documento_tipo_nombre, us.id_empresa as id_empresa FROM usuarios us  
	INNER JOIN documento_tipo doct
	ON doct.id = us.documento_tipo 
	WHERE us.id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	while($row1 = mysqli_fetch_array($proceso1)) {
		$nombre1 = $row1["nombre1"];
		$nombre2 = $row1["nombre2"];
		$apellido1 = $row1["apellido1"];
		$apellido2 = $row1["apellido2"];
		$documento_tipo = $row1["documento_tipo"];
		$documento_numero = $row1["documento_numero"];
		$documento_numero = $row1["documento_numero"];
		$correo = $row1["correo"];
		$telefono = $row1["telefono"];
		$genero = $row1["genero"];
		$direccion = $row1["direccion"];
		$estatus_modelo = $row1["estatus_modelo"];
		$estatus_nomina = $row1["estatus_nomina"];
		$estatus_pasantes = $row1["estatus_pasantes"];
		$documento_tipo_nombre = $row1["documento_tipo_nombre"];
		$usuario_nombre = $nombre1." ".$nombre2." ".$apellido1." ".$apellido2;
		$id_empresa = $row1["id_empresa"];

		$sql2 = "SELECT msubus.id as msubus_id, msubus.estatus as msubus_estatus, msub.id as msub_id, msub.nombre as msub_nombre, msub.id_modulos as msub_id_modulos, msub.id_usuario_rol as msub_id_usuario_rol, msub.estatus as msub_estatus, mo.id as mo_id, mo.nombre as mo_nombre FROM modulos_sub_usuarios msubus 
		INNER JOIN modulos_sub msub 
		ON msub.id = msubus.id_modulos_sub 
		INNER JOIN modulos mo 
		ON mo.id = msub.id_modulos 
		WHERE msubus.estatus = 1 and mo.estatus = 1 and msub.estatus = 1 and msubus.id_usuarios = ".$id;

		$proceso2 = mysqli_query($conexion,$sql2);
		$contador2 = mysqli_num_rows($proceso2);
		if($contador2>=1){
			while($row2 = mysqli_fetch_array($proceso2)) {
				$msub_nombre = $row2["msub_nombre"];
				$msub_id_usuario_rol = $row2["msub_id_usuario_rol"];
				$msub_estatus = $row2["msub_estatus"];
				$mo_nombre = $row2["mo_nombre"];

				$html1 .= '
					<div class="col-12">
						<hr style="font-size:2px; background-color: black;">
					</div>
					<div class="col-4 form-group form-check">
						<label style="font-weight: bold;">SubModulo</label>
					</div>
					<div class="col-4 form-group form-check">
						<label style="font-weight: bold;">Rol</label>
					</div>
					<div class="col-4 form-group form-check">
						<label style="font-weight: bold;">Modulo</label>
					</div>
					<div class="col-4 form-group form-check">
						'.$msub_nombre.'
					</div>
					<div class="col-4 form-group form-check">
						'.$msub_id_usuario_rol.'
					</div>
					<div class="col-4 form-group form-check">
						'.$mo_nombre.'
					</div>
				';
			}
		}
	}

	$datos = [
		"estatus"			=> "ok",
		"sql1" 				=> $sql1,
		"nombre1"			=> $nombre1,
		"nombre2"			=> $nombre2,
		"apellido1"			=> $apellido1,
		"apellido2"			=> $apellido2,
		"usuario_nombre"	=> $usuario_nombre,
		"documento_tipo"	=> $documento_tipo,
		"documento_numero"	=> $documento_numero,
		"correo"			=> $correo,
		"telefono"			=> $telefono,
		"genero"			=> $genero,
		"direccion"			=> $direccion,
		"estatus_modelo"	=> $estatus_modelo,
		"estatus_nomina"	=> $estatus_nomina,
		"estatus_pasantes"	=> $estatus_pasantes,
		"documento_tipo_nombre"	=> $documento_tipo_nombre,
		"html1"	=> $html1,
	];
	echo json_encode($datos);
}

if($condicion=='editar1'){
	$usuario_id = $_POST["usuario_id"];
	$documento_tipo = $_POST["documento_tipo"];
	$documento_numero = $_POST["documento_numero"];
	$nombre1 = $_POST["nombre1"];
	$nombre2 = $_POST["nombre2"];
	$apellido1 = $_POST["apellido1"];
	$apellido2 = $_POST["apellido2"];
	$correo = $_POST["correo"];
	$telefono = $_POST["telefono"];
	$direccion = $_POST["direccion"];

	$sql1 = "UPDATE usuarios SET documento_tipo = $documento_tipo, documento_numero = '$documento_numero', nombre1 = '$nombre1', nombre2 = '$nombre2', apellido1 = '$apellido1', apellido2 = '$apellido2', correo_personal = '$correo', telefono = '$telefono', direccion = '$direccion' WHERE id = ".$usuario_id;
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
		"sql1"		=> $sql1,
		"msg"		=> "Se ha modificado exitosamente!",
	];
	echo json_encode($datos);
}

if($condicion=='permisos2_select'){
	$value = $_POST["value"];
	$select = $_POST["select"];
	$html1 = '';
	$html2 = '';
	$html3 = '';

	if($select=='1' and $value!=""){
		$html1 .= '
			<select class="form-control" id="permisos2_submodulo" name="permisos2_submodulo" onchange="permisos2_select(value,2);" required>
				<option value="">Seleccione</option>
		';
		$sql1 = "SELECT * FROM modulos_sub WHERE estatus = 1 and id_modulos = ".$value;
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1 = mysqli_fetch_array($proceso1)) {
			$submodulo_id = $row1["id"];
			$submodulo_nombre = $row1["nombre"];
			$html1 .= '
				<option value="'.$submodulo_id.'">'.$submodulo_nombre.'</option>
			';
		}
		$html1 .= '
			</select>
		';
	}else if($select=='2' and $value!=""){
		$html2 .= '
			<select class="form-control" id="permisos2_multiple" name="permisos2_multiple" required>
				<option value="">Seleccione</option>
		';
		$sql1 = "SELECT * FROM modulos_multiple WHERE estatus = 1 and id_sub_modulos = ".$value;
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1 = mysqli_fetch_array($proceso1)) {
			$multiple_id = $row1["id"];
			$multiple_nombre = $row1["nombre"];
			$html2 .= '
				<option value="'.$multiple_id.'">'.$multiple_nombre.'</option>
			';
		}
		$html2 .= '
			</select>
		';
	}

	$datos = [
		"estatus"	=> "ok",
		"html1" => $html1,
		"html2" => $html2,
		"html3" => $html3,
	];
	echo json_encode($datos);
}

if($condicion=='agregar_permiso1'){
	$usuario_id = $_POST["usuario_id"];
	$modulo = $_POST["permisos2_modulo"];
	$submodulo = $_POST["permisos2_submodulo"];
	$multiple = $_POST["permisos2_multiple"];

	$sql1 = "SELECT * FROM modulos_sub_usuarios WHERE id_usuarios = ".$usuario_id." and id_modulos_sub = ".$submodulo;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	$sql2 = "SELECT * FROM modulos_multiple_usuarios WHERE id_usuarios = ".$usuario_id." and id_modulos_multiple = ".$multiple;
	$proceso2 = mysqli_query($conexion,$sql2);
	$contador2 = mysqli_num_rows($proceso2);

	if($contador2>=1){
		$datos = [
			"estatus"	=> "error",
			"sql2"		=> $sql2,
			"msg"		=> "Ya tenia el multiple vinculado!",
		];
		echo json_encode($datos);
	}else{
		if($contador1==0){
			$sql3 = "INSERT INTO modulos_sub_usuarios (id_modulos_sub,id_usuarios,estatus,responsable,fecha_creacion) VALUES ($submodulo,$usuario_id,1,$responsable,'$fecha_creacion')";
			$proceso3 = mysqli_query($conexion,$sql3);
		}
		$sql3 = "INSERT INTO modulos_multiple_usuarios (id_usuarios,id_modulos_multiple,responsable,fecha_creacion) VALUES ($usuario_id,$multiple,$responsable,'$fecha_creacion')";
		$proceso3 = mysqli_query($conexion,$sql3);
		$datos = [
			"estatus"	=> "ok",
			"sql2"		=> $sql2,
			"sql3"		=> $sql3,
			"msg"		=> "Agregado Permiso exitosamente!",
		];
		echo json_encode($datos);
	}
}


?>