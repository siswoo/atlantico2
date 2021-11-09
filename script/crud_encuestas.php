<?php
include('conexion.php');
$condicion = $_POST["condicion"];
$datetime = date('Y-m-d H:i:s');
$fecha_creacion = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');
$condicion = $_POST["condicion"];

if($condicion=='registro1'){
	$usuario_id = $_POST["usuario_id"];
	$sql1 = "SELECT * FROM usuarios WHERE id = ".$usuario_id;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	if($contador1>=1){
		while($row1=mysqli_fetch_array($proceso1)){
			$proyecto = $row1["proyectos"];
		}

		$sql2 = "SELECT sec.id as sec_id, pre.id as pre_id, pre.texto as pre_texto FROM secciones sec 
		INNER JOIN preguntas pre 
		ON sec.id = pre.secciones 
		WHERE proyecto = ".$proyecto." and sec.estatus = 1 and pre.estatus = 1";
		$proceso2 = mysqli_query($conexion,$sql2);
		$contador2 = mysqli_num_rows($proceso2);

		if($contador2==0){
			$datos = [
				"estatus"	=> "error",
				"sql1" 		=> $sql1,
				"sql2" 		=> $sql2,
				"msg"		=> "No tienes Preguntas habilitadas!",
			];
			echo json_encode($datos);
		}else{

			$imagen_nombre = $_FILES["firma"]["name"];
		    $imagen_temporal = $_FILES["firma"]["tmp_name"];
		    $imagen_tipo = explode("/",$_FILES["firma"]["type"]);
		    $imagen_tipo = $imagen_tipo[1];

		    if($imagen_tipo!='png' and $imagen_tipo!='PNG' and $imagen_tipo!='jpg' and $imagen_tipo!='jpeg' and $imagen_tipo!='JPG' and $imagen_tipo!='JPEG'){
		    	$datos = [
					"estatus"	=> "error",
					"msg"		=> "El tipo de imagen no es aceptable",
				];
				echo json_encode($datos);
				exit;
		    }

		    $location = "../resources/firmas/".$proyecto."/";
		    if(!file_exists($location)){
				mkdir($location, 0777);
			}

			$sql4 = "INSERT INTO firmas (responsable,fecha_creacion) VALUES ($usuario_id,'$fecha_creacion')";
			$proceso4 = mysqli_query($conexion,$sql4);
			$firma_id=mysqli_insert_id($conexion);

			$firma_macro = $location.$firma_id.'.png';
			move_uploaded_file ($_FILES['firma']['tmp_name'],$firma_macro);

			while($row2=mysqli_fetch_array($proceso2)){
				$seccion_id = $row2["sec_id"];
				$pregunta_id = $row2["pre_id"];
				$pregunta_texto = $row2["pre_texto"];
				$pregunta_variable = $_POST["pregunta_".$pregunta_id];

				$sql3 = "INSERT INTO encuestas (proyecto,seccion,pregunta,respuesta,firma,responsable,fecha_creacion) VALUES ($proyecto,$seccion_id,$pregunta_id,'$pregunta_variable',$firma_id,$usuario_id,'$fecha_creacion')";
				$proceso3 = mysqli_query($conexion,$sql3);

			}

			$datos = [
				"estatus"	=> "ok",
				"sql1" 		=> $sql1,
				"sql2" 		=> $sql2,
				"sql3" 		=> $sql3,
				"msg"		=> "Creado exitosamente!",
			];
			echo json_encode($datos);
		}

	}else{
		$datos = [
			"estatus"	=> "error",
			"sql1" 		=> $sql1,
			"msg"		=> "Tu usuario esta inactivo!",
		];
		echo json_encode($datos);
	}
}
?>