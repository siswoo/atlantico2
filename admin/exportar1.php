<?php
@session_start();
if(@$_SESSION['atlantico2_id']==''){?>
	<script type="text/javascript">
		window.location.href = "../index.php";
	</script>
<?php } ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Administrador">
    <title>AtlanticoV2</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="../css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../css/style.css">

    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />
  </head>

  <style type="text/css">
  	.error{
  		color: red;
  		font-size: 14px;
  	}
  </style>

<body>
    <?php
    include("lateral1.php");
    include("../script/conexion.php");
    $ubicacion_url = $_SERVER["PHP_SELF"];
    ?>

    <div id="right-panel" class="right-panel">

        <?php
        include("header.php");
        ?>

        <div class="content">
            <div class="animated fadeIn">
                <form action="../script/exportaciones.php" method="GET" target="_blank">
                    <div class="row">
                        <div class="col-lg-5 col-md-5">
                            <label for="proyecto" style="color:black; font-weight: bold;">Consultas por Proyecto</label>
                            <select class="form-control" id="proyecto" name="proyecto" required>
                                <option value="">Seleccione</option>
                                <?php
                                $sql1 = "SELECT * FROM proyectos";
                                $proceso1 = mysqli_query($conexion,$sql1);
                                while($row1 = mysqli_fetch_array($proceso1)) {
                                    echo '
                                        <option value="'.$row1["id"].'">'.$row1["nombre"].'</option>
                                    ';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 text-center">
                            <input type="hidden" name="condicion" id="condicion" value="exportar1">
                            <button type="submit" class="btn btn-info">Exportar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="clearfix"></div>

        <footer class="site-footer">
            <div class="footer-inner bg-white">
                <div class="row">
                    <div class="col-sm-6">
                        Copyright &copy; 2021 Juan Maldonado
                    </div>
                    <div class="col-sm-6 text-right">
                        Diseñado por <a href="https://sodaec.com">SODAEC</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="../js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="../js/popper.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
    <script src="../js/init/fullcalendar-init.js"></script>

</body>
</html>

<script type="text/javascript"></script>