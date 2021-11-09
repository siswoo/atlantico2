<?php
session_start();
include('conexion.php');
require('../resources/fpdf/fpdf.php');
$id = $_GET["id"];

$sql1 = "SELECT en.firma as en_firma, pro.id as pro_id, pro.nombre as pro_nombre FROM encuestas en 
INNER JOIN proyectos pro 
ON en.proyecto = pro.id 
WHERE en.id = ".$id;
$proceso1 = mysqli_query($conexion,$sql1);
while($row1=mysqli_fetch_array($proceso1)){
	$firma_global = $row1["en_firma"];
	$proyecto_id = $row1["pro_id"];
	$proyecto_nombre = $row1["pro_nombre"];
}

$sql2 = "SELECT us.nombre as us_nombre, us.documento_numero as us_documento_numero, mu.nombre as mu_nombre, en.responsable as en_responsable, en.fecha_creacion as en_fecha_creacion, pro.nombre as pro_nombre, en.seccion as en_seccion, en.pregunta as en_pregunta, en.respuesta as en_respuesta FROM encuestas en 
INNER JOIN usuarios us 
ON en.responsable = us.id 
INNER JOIN documento_tipo doct 
ON us.documento_tipo = doct.id 
INNER JOIN firmas fir 
ON en.firma = fir.id 
INNER JOIN proyectos pro 
ON us.proyectos = pro.id 
INNER JOIN municipios mu 
ON us.municipio = mu.id 
WHERE en.firma = ".$firma_global;

class PDF extends FPDF{
	function Header(){}
	function Footer(){}
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->Ln(5);
$pdf->SetFont('Times','',12);
$pdf->Image('../img/logopdf1.png',25,5,50,40);
$pdf->Image('../img/logopdf2.jpeg',145,15,50,20);

$pdf->Ln(30);
$pdf->SetFont('Times','B',14);
$pdf->Cell(190,10,utf8_decode('Encuesta #'.$id),0,0,'C');

$pdf->Ln(10);
$pdf->SetFont('Times','B',12);
//$pdf->Cell(190,5,utf8_decode($proyecto_nombre),0,1,'C');
$pdf->Cell(35,5,"",0,0,'C');
$pdf->MultiCell(120,5,utf8_decode($proyecto_nombre),0,"C");

$proceso2 = mysqli_query($conexion,$sql2);
$contador2 = mysqli_num_rows($proceso2);
if($contador2>=1){
	$seccion_titulo_bucle = '';
	while($row2 = mysqli_fetch_array($proceso2)) {
		$nombre = $row2["us_nombre"];
		$identificacion = $row2["us_documento_numero"];
		$municipio = $row2["mu_nombre"];
		$departamento = "Atlántico";
		$responsable = $row2["en_responsable"];
		$fecha_creacion = $row2["en_fecha_creacion"];
		$seccion_id = $row2["en_seccion"];
		$pregunta_id = $row2["en_pregunta"];
		$respuesta = $row2["en_respuesta"];

		$sql3 = "SELECT * FROM secciones WHERE id = ".$seccion_id." and estatus = 1 ORDER BY orden ASC";
		$proceso3 = mysqli_query($conexion,$sql3);
		while($row3 = mysqli_fetch_array($proceso3)) {
			$seccion_titulo = $row3["titulo"];
			$seccion_tabla = $row3["tabla"];
		}

		if($seccion_titulo!='' and $seccion_titulo_bucle!=$seccion_titulo){
			$seccion_titulo_bucle = $seccion_titulo;
			$pdf->Ln(10);
			$pdf->SetFont('Times','B',10);
			$pdf->MultiCell(190,5,utf8_decode($seccion_titulo),0,"C");
		}

		$sql4 = "SELECT * FROM preguntas WHERE id = ".$pregunta_id." and estatus = 1";
		$proceso4 = mysqli_query($conexion,$sql4);
		while($row4 = mysqli_fetch_array($proceso4)) {
			$preguntas_id = $row4["id"];
			$preguntas_texto = $row4["texto"];

			/*
			if($seccion_tabla==1){
				$pdf->Ln(5);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(70,10,utf8_decode($preguntas_texto),0,1,'');
				$pdf->Cell(190,10,utf8_decode($respuesta),1,1,'');
			}else{
				$pdf->Ln(5);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(140,10,utf8_decode($respuesta."".$pdf->Cell(50,10,utf8_decode($preguntas_texto),1,0,'L')
				),1,1,"C");
			}
			*/
			$pdf->Ln(5);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(70,10,utf8_decode($preguntas_texto),0,1,'');
			$pdf->Cell(190,10,utf8_decode($respuesta),1,1,'');


		}
	}

	$pdf->AddPage();
	$pdf->Ln(10);
	$pdf->SetFont('Times','B',14);
	$pdf->Cell(190,5,utf8_decode('Firma Digital'),0,1,'C');
	$pdf->SetFont('Times','',12);
	$pdf->Image('../resources/firmas/'.$proyecto_id.'/'.$firma_global.'.png',10,30,190);
}

$pdf->Output();
?>