<?php
include('conexion.php');
require '../resources/Spreadsheet/autoload.php';
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$condicion = $_GET["condicion"];

if($condicion=='exportar1'){

	$columnas[1] = "A";
	$columnas[2] = "B";
	$columnas[3] = "C";
	$columnas[4] = "D";
	$columnas[5] = "E";
	$columnas[6] = "F";
	$columnas[7] = "G";
	$columnas[8] = "H";
	$columnas[9] = "I";
	$columnas[10] = "J";
	$columnas[11] = "K";
	$columnas[12] = "L";
	$columnas[13] = "M";
	$columnas[14] = "N";
	$columnas[15] = "O";
	$columnas[16] = "P";
	$columnas[17] = "Q";
	$columnas[18] = "R";
	$columnas[19] = "S";
	$columnas[20] = "T";
	$columnas[21] = "U";
	$columnas[22] = "V";
	$columnas[23] = "W";
	$columnas[24] = "X";
	$columnas[25] = "Y";
	$columnas[26] = "Z";
	$columnas[27] = "AA";
	$columnas[28] = "AB";
	$columnas[29] = "AC";
	$columnas[30] = "AD";
	$columnas[31] = "AE";
	$columnas[32] = "AF";
	$columnas[33] = "AG";
	$columnas[34] = "AH";
	$columnas[35] = "AI";
	$columnas[36] = "AJ";
	$columnas[37] = "AK";
	$columnas[38] = "AL";
	$columnas[39] = "AM";
	$columnas[40] = "AN";
	$columnas[41] = "AO";
	$columnas[42] = "AP";
	$columnas[43] = "AQ";
	$columnas[44] = "AR";
	$columnas[45] = "AS";
	$columnas[46] = "AT";
	$columnas[47] = "AU";
	$columnas[48] = "AV";
	$columnas[49] = "AX";
	$columnas[50] = "AY";
	$columnas[51] = "AZ";

	$contador1 = 1;
	$contador2 = 1;
	$fila = 1;
	$proyecto = $_GET["proyecto"];
	$firma = '';

	$sql1 = "SELECT pre.texto as pre_texto FROM secciones sec 
	INNER JOIN preguntas pre 
	ON sec.id = pre.secciones 
	WHERE sec.proyecto = $proyecto";
	$proceso1 = mysqli_query($conexion,$sql1);
	while($row1 = mysqli_fetch_array($proceso1)) {
		$pre_texto = $row1['pre_texto'];
		$spreadsheet->getActiveSheet()->getColumnDimension($columnas[$contador1])->setWidth(30);
		$sheet->setCellValue($columnas[$contador1].$contador2, $pre_texto);
		$contador1 = $contador1+1;
	}

	$spreadsheet->getActiveSheet()->getColumnDimension($columnas[$contador1])->setWidth(30);
	$sheet->setCellValue($columnas[$contador1].$contador2, "Responsable");
	$contador1 = $contador1+1;
	$spreadsheet->getActiveSheet()->getColumnDimension($columnas[$contador1])->setWidth(30);
	$sheet->setCellValue($columnas[$contador1].$contador2, "Fecha de Creación");

	$sql2 = "SELECT en.id as en_id, en.seccion as en_seccion, en.respuesta as en_respuesta, en.pregunta as en_pregunta, en.firma as en_firma, en.fecha_creacion as en_fecha_creacion, pre.texto as pre_texto, pre.id as pre_id, pre.campos_tipos as pre_campos_tipos, us.nombre as us_nombre, us.documento_numero as us_documento_numero FROM encuestas en 
	INNER JOIN preguntas pre 
	ON en.pregunta = pre.id 
	INNER JOIN usuarios us 
	ON en.responsable = us.id 
	WHERE en.proyecto = ".$proyecto;
	$proceso2 = mysqli_query($conexion,$sql2);
	while($row2 = mysqli_fetch_array($proceso2)) {
		$seccion = $row2['en_seccion'];
		$respuesta = $row2['en_respuesta'];
		$texto = $row2['pre_texto'];
		$pre_id = $row2['pre_id'];
		$campos_tipos = $row2['pre_campos_tipos'];
		$nombre = $row2['us_nombre'];
		$documento_numero = $row2['us_documento_numero'];
		$firmasql = $row2['en_firma'];
		$fecha_creacion = $row2['en_fecha_creacion'];
		
		if($firma!=$firmasql){
			$fila = $fila+1;
			//echo $nombre." | ".$columnas[$contador2].$fila."<br>";
			if($contador2>=2){
				$sheet->setCellValue($columnas[$contador2].$fila, $nombre);
				$contador2 = $contador2+1;
				$sheet->setCellValue($columnas[$contador2].$fila, $fecha_creacion);
			}
			$firma=$firmasql;
			$contador2 = 1;
		}

		if($campos_tipos==4){
			$sql3 = "SELECT * FROM preguntas_opciones WHERE preguntas = ".$pre_id;
			$proceso3 = mysqli_query($conexion,$sql3);
			while($row3=mysqli_fetch_array($proceso3)){
				$tabla = $row3["tabla"];
				$sql4 = "SELECT * FROM ".$tabla." WHERE id = ".$respuesta;
				$proceso4 = mysqli_query($conexion,$sql4);
				while($row4=mysqli_fetch_array($proceso4)){
					$respuesta = $row4["nombre"];
				}
			}
		}

		$sheet->setCellValue($columnas[$contador2].$fila, $respuesta);
		$contador2 = $contador2+1;


		//$spreadsheet->getActiveSheet()->getCell('A'.$fila)->setValue($documento_numero);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$fila)->getNumberFormat()->setFormatCode('00');
	}

	$fecha_inicio1 = date('Y-m-d');
	$writer = new Xlsx($spreadsheet);
	$writer->save('../exportacion '.$fecha_inicio1.'.xlsx');
	header("Location: ../exportacion ".$fecha_inicio1.".xlsx");
}

?>