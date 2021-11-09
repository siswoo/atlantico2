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

	$sql1 = "SELECT sec.id as sec_id, sec.titulo as sec_titulo, sec.tabla as sec_tabla, sec.orden as sec_orden, sec.estatus as sec_estatus, pro.id as pro_id, pro.nombre as pro_nombre FROM secciones sec 
		INNER JOIN proyectos pro 
		ON sec.proyecto = pro.id 
		WHERE sec.id != 0 
		".$filtrado." 
		".$sede."
	";
	
	$sql2 = "SELECT sec.id as sec_id, sec.titulo as sec_titulo, sec.tabla as sec_tabla, sec.orden as sec_orden, sec.estatus as sec_estatus, pro.id as pro_id, pro.nombre as pro_nombre FROM secciones sec 
		INNER JOIN proyectos pro 
		ON sec.proyecto = pro.id 
		WHERE sec.id != 0 
		".$filtrado." 
		".$sede."
		ORDER BY sec.id ASC LIMIT ".$limit." OFFSET ".$offset."
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
	                <th class="text-center">Titulo</th>
	                <th class="text-center">Tabla</th>
	                <th class="text-center">Orden</th>
	                <th class="text-center">Estatus</th>
	                <th class="text-center">Proyecto</th>
	                <th class="text-center">Opciones</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {

			if($row2["sec_tabla"]==1){
				$sec_tabla = 'Si';
			}else{
				$sec_tabla = 'No';
			}

			if($row2["sec_estatus"]==1){
				$sec_estatus = 'Activo';
			}else{
				$sec_estatus = 'Inactivo';
			}

			$html .= '
		                <tr id="tr_'.$row2["sec_id"].'">
		                    <td style="text-align:center;">'.$row2["sec_id"].'</td>
		                    <td style="text-align:center;">'.$row2["sec_titulo"].'</td>
		                    <td style="text-align:center;">'.$sec_tabla.'</td>
		                    <td style="text-align:center;">'.$row2["sec_orden"].'</td>
		                    <td style="text-align:center;">'.$sec_estatus.'</td>
		                    <td style="text-align:center;">'.$row2["pro_nombre"].'</td>
		                    <td class="text-center" nowrap="nowrap">
		                    	<button class="btn btn-danger" onclick="eliminar1('.$row2["sec_id"].');">Eliminar</button>
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
	$titulo = $_POST["titulo"];
	$tabla = $_POST["tabla"];
	$proyecto = $_POST["proyecto"];
	$orden = $_POST["orden"];
	$estatus = $_POST["estatus"];

	$sql1 = "INSERT INTO secciones (titulo,tabla,proyecto,orden,estatus,fecha_creacion) VALUES ('$titulo',$tabla,$proyecto,$orden,$estatus,'$fecha_creacion')";
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

	$sql1 = "DELETE FROM secciones WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
		"sql1" 		=> $sql1,
		"msg"		=> "Eliminado exitosamente!",
	];
	echo json_encode($datos);
}

?>