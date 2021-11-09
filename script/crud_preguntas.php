<?php
@session_start();
include('conexion.php');
$condicion = $_POST["condicion"];
$datetime = date('Y-m-d H:i:s');
$fecha_creacion = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');

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
		$filtrado = ' and (sec.titulo LIKE "%'.$filtrado.'%")';
	}

	if($sede!=''){
		$sede = ' and sec.proyecto = '.$sede;
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT pre.id as pre_id, pre.texto as pre_texto, pre.ordenador as pre_ordenador, pre.tablet as pre_tablet, pre.telefono as pre_telefono,pre.orden as pre_orden, pre.estatus as pre_estatus, pre.fecha_creacion as pre_fecha_creacion, sec.id as sec_id, sec.titulo as sec_titulo, cam.nombre as cam_nombre FROM preguntas pre 
		INNER JOIN secciones sec 
		ON pre.secciones = sec.id 
		INNER JOIN campos_tipos cam 
		ON pre.campos_tipos = cam.id 
		WHERE pre.id != 0 
		".$filtrado." 
		".$sede."
	";
	
	$sql2 = "SELECT pre.id as pre_id, pre.texto as pre_texto, pre.ordenador as pre_ordenador, pre.tablet as pre_tablet, pre.telefono as pre_telefono,pre.orden as pre_orden, pre.estatus as pre_estatus, pre.fecha_creacion as pre_fecha_creacion, sec.id as sec_id, sec.titulo as sec_titulo, cam.nombre as cam_nombre FROM preguntas pre 
		INNER JOIN secciones sec 
		ON pre.secciones = sec.id 
		INNER JOIN campos_tipos cam 
		ON pre.campos_tipos = cam.id 
		WHERE pre.id != 0 
		".$filtrado." 
		".$sede."
		ORDER BY pre.id ASC LIMIT ".$limit." OFFSET ".$offset."
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
	                <th class="text-center">ID</th>
	                <th class="text-center">Texto</th>
	                <th class="text-center">Seccion</th>
	                <th class="text-center">Orden</th>
	                <th class="text-center">Campo Tipo</th>
	                <th class="text-center">Estatus</th>
	                <th class="text-center">Ordenador</th>
	                <th class="text-center">Tablet</th>
	                <th class="text-center">Telefono</th>
	                <th class="text-center">Opciones</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {

			if($row2["pre_estatus"]==1){
				$pre_estatus = 'Activo';
			}else{
				$pre_estatus = 'Inactivo';
			}

			$html .= '
		                <tr id="tr_'.$row2["pre_id"].'">
		                    <td style="text-align:center;">'.$row2["pre_id"].'</td>
		                    <td style="text-align:center;">'.$row2["pre_texto"].'</td>
		                    <td style="text-align:center;">"'.$row2["sec_titulo"].'" -> ['.$row2["sec_id"].']</td>
		                    <td style="text-align:center;">'.$row2["pre_orden"].'</td>
		                    <td style="text-align:center;">'.$row2["cam_nombre"].'</td>
		                    <td style="text-align:center;">'.$pre_estatus.'</td>
		                    <td style="text-align:center;">'.$row2["pre_ordenador"].'</td>
		                    <td style="text-align:center;">'.$row2["pre_tablet"].'</td>
		                    <td style="text-align:center;">'.$row2["pre_telefono"].'</td>
		                    <td style="text-align:center;">'.$pre_estatus.'</td>
		                    <td style="text-align:center;">'.$row2["pre_fecha_creacion"].'</td>
		                    <td class="text-center" nowrap="nowrap">
		                    	<button class="btn btn-primary" data-toggle="modal" data-target="#modal_editar" onclick="editar1('.$row2["pre_id"].');">Editar</button>
		                    	<button class="btn btn-danger" onclick="eliminar1('.$row2["pre_id"].');">Eliminar</button>
		                    </td>
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
	$texto = $_POST["texto"];
	$campos_tipos = $_POST["campos_tipos"];
	$seccion = $_POST["seccion"];
	$orden = $_POST["orden"];
	$estatus = $_POST["estatus"];
	$ordenador = $_POST["ordenador"];
	$tablet = $_POST["tablet"];
	$telefono = $_POST["telefono"];

	$sql1 = "INSERT INTO preguntas (texto,campos_tipos,secciones,orden,ordenador,tablet,telefono,estatus,fecha_creacion) VALUES ('$texto',$campos_tipos,$seccion,$orden,'$ordenador','$tablet','$telefono',$estatus,'$fecha_creacion')";
	$proceso1 = mysqli_query($conexion,$sql1);
	$datos = [
		"estatus"	=> "ok",
		"sql1" 		=> $sql1,
		"msg"		=> "Seccion creada exitosamente!",
	];
	echo json_encode($datos);
}

if($condicion=="eliminar1"){
	$id = $_POST["id"];

	$sql1 = "DELETE FROM preguntas WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
		"sql1" 		=> $sql1,
		"msg"		=> "Eliminado exitosamente!",
	];
	echo json_encode($datos);
}

if($condicion=="consultar1"){
	$id = $_POST['id'];
	$sql1 = "SELECT * FROM preguntas WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	
	if($contador1>=1){
		while($row1=mysqli_fetch_array($proceso1)){
			$texto = $row1["texto"];
			$campos_tipos = $row1["campos_tipos"];
			$secciones = $row1["secciones"];
			$ordenador = $row1["ordenador"];
			$tablet = $row1["tablet"];
			$telefono = $row1["telefono"];
			$orden = $row1["orden"];
			$estatus = $row1["estatus"];
		}

		$datos = [
			"estatus"		=> "ok",
			"sql1" 			=> $sql1,
			"texto" 		=> $texto,
			"campos_tipos" 	=> $campos_tipos,
			"secciones" 	=> $secciones,
			"ordenador" 	=> $ordenador,
			"tablet" 		=> $tablet,
			"telefono" 		=> $telefono,
			"orden" 		=> $orden,
			"estatus2" 		=> $estatus,
		];
		echo json_encode($datos);

	}else{
		$datos = [
			"estatus"	=> "error",
			"sql1" 		=> $sql1,
			"msg"		=> "Registro no conseguido!",
		];
		echo json_encode($datos);
	}
}

if($condicion=="editar1"){
	$id = $_POST["id"];
	$texto = $_POST["texto"];
	$campos_tipos = $_POST["campos_tipos"];
	$seccion = $_POST["seccion"];
	$orden = $_POST["orden"];
	$estatus = $_POST["estatus"];
	$ordenador = $_POST["ordenador"];
	$tablet = $_POST["tablet"];
	$telefono = $_POST["telefono"];

	$sql1 = "UPDATE preguntas SET texto = '$texto', campos_tipos = '$campos_tipos', secciones = '$seccion', orden = '$orden', estatus = '$estatus', ordenador = '$ordenador', tablet = '$tablet', telefono = '$telefono' WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
		"sql1" 		=> $sql1,
		"msg"		=> "Editado exitosamente!",
	];
	echo json_encode($datos);
}

?>