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
		$filtrado = ' and (us.nombre LIKE "%'.$filtrado.'%" or us.documento_numero LIKE "%'.$filtrado.'%")';
	}

	if($sede!=''){
		$sede = ' and us.proyectos = '.$sede;
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT en.id as en_id, en.fecha_creacion as en_fecha_creacion, us.nombre as us_nombre, us.documento_numero as us_documento_numero, us.telefono as us_telefono, doct.nombre doct_nombre FROM encuestas en 
		INNER JOIN usuarios us 
		ON en.responsable = us.id 
		INNER JOIN documento_tipo doct  
		ON us.documento_tipo = doct.id 
		INNER JOIN firmas fir 
		ON en.firma = fir.id 
		WHERE en.id != 0 
		".$filtrado." 
		".$sede." 
		GROUP BY en.firma
	";

	$sql2 = "SELECT en.id as en_id, en.fecha_creacion as en_fecha_creacion, us.nombre as us_nombre, us.documento_numero as us_documento_numero, us.telefono as us_telefono, doct.nombre doct_nombre FROM encuestas en 
		INNER JOIN usuarios us 
		ON en.responsable = us.id 
		INNER JOIN documento_tipo doct  
		ON us.documento_tipo = doct.id 
		INNER JOIN firmas fir 
		ON en.firma = fir.id 
		WHERE en.id != 0 
		".$filtrado." 
		".$sede." 
		GROUP BY en.firma 
		ORDER BY en.id ASC LIMIT ".$limit." OFFSET ".$offset."
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
	                <th class="text-center">Doc Tipo</th>
	                <th class="text-center">Doc Numero</th>
	                <th class="text-center">Telefono</th>
	                <th class="text-center">Fecha Creacion</th>
	                <th class="text-center">Opciones</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {

			$html .= '
		                <tr id="tr_'.$row2["en_id"].'">
		                    <td style="text-align:center;">'.$row2["us_nombre"].'</td>
		                    <td style="text-align:center;">'.$row2["doct_nombre"].'</td>
		                    <td style="text-align:center;">'.$row2["us_documento_numero"].'</td>
		                    <td style="text-align:center;">'.$row2["us_telefono"].'</td>
		                    <td style="text-align:center;">'.$row2["en_fecha_creacion"].'</td>
		                    <td class="text-center" nowrap="nowrap">
		                    	<a href="../script/pdf1.php?id='.$row2["en_id"].'" target="_blank">
		                    		<button class="btn btn-primary">Generar PDF</button>
		                    	</a>
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
	$preguntas = $_POST["preguntas"];
	$orden = $_POST["orden"];

	$tabla = $_POST["tabla"];
	$condicional1 = $_POST["condicional1"];
	$condicional2 = $_POST["condicional2"];
	$condicional3 = $_POST["condicional3"];

	$sql1 = "INSERT INTO preguntas_opciones (texto,preguntas,tabla,condicional1,condicional2,condicional3,orden,fecha_creacion) VALUES ('$texto',$preguntas,'$tabla','$condicional1','$condicional2','$condicional3',$orden,'$fecha_creacion')";
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

	$sql1 = "DELETE FROM preguntas_opciones WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
		"sql1" 		=> $sql1,
		"msg"		=> "Eliminado exitosamente!",
	];
	echo json_encode($datos);
}

if($condicion=='validarcodigo1'){
	$pregunta_id = $_POST["value"];
	$sql1 = "SELECT pre.id as pre_id, cam.id as cam_id FROM preguntas pre
	INNER JOIN campos_tipos cam 
	ON pre.campos_tipos = cam.id 
	WHERE pre.id = ".$pregunta_id;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	if($contador1>=1){
		while($row1 = mysqli_fetch_array($proceso1)){
			$campos_típos = $row1["cam_id"];
			$datos = [
				"estatus"			=> "ok",
				"sql1" 				=> $sql1,
				"campos_tipos"		=> $campos_típos,
			];
			echo json_encode($datos);
		}
	}
}

?>