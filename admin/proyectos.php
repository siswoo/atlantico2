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

                <div class="row">
                    <div class="col-12 text-right">
                        <button class="btn btn-success" data-toggle="modal" data-target="#modal_nuevo">Nuevo Registro</button>
                    </div>
                </div>

                <div class="row">
                    <input type="hidden" name="datatables" id="datatables" data-pagina="1" data-consultasporpagina="10" data-filtrado="" data-sede="">
                    <div class="col-3 form-group form-check">
                        <label for="consultasporpagina" style="color:black; font-weight: bold;">Consultas por Página</label>
                        <select class="form-control" id="consultasporpagina" name="consultasporpagina">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-4 form-group form-check">
                        <label for="buscarfiltro" style="color:black; font-weight: bold;">Buscar</label>
                        <input type="text" class="form-control" id="buscarfiltro" name="buscarfiltro">
                    </div>
                    <!--
                    <div class="col-3 form-group form-check">
                        <label for="consultaporsede" style="color:black; font-weight: bold;">Consultas por Proyecto</label>
                        <select class="form-control" id="consultaporsede" name="consultaporsede">
                            <option value="">Todos</option>
                            <?php
                            $sql9 = "SELECT * FROM proyectos";
                            $proceso9 = mysqli_query($conexion,$sql9);
                            while($row9 = mysqli_fetch_array($proceso9)) {
                                echo '
                                    <option value="'.$row9["id"].'">'.$row9["nombre"].'</option>
                                ';          
                            }
                            ?>
                        </select>
                    </div>
                    -->
                    <input type="hidden" name="consultaporsede" id="consultaporsede" value="">
                    <div class="col-2">
                        <br>
                        <button type="button" class="btn btn-info mt-2" onclick="filtrar1();">Filtrar</button>
                    </div>
                    <div class="col-12" style="background-color: white; border-radius: 1rem; padding: 20px 1px 1px 1px;" id="resultado_table1">Aqui!</div>
                </div>

            </div>
        </div>

        <div class="clearfix"></div>
    </div>

    <!-- Modal Nuevo -->
    <div class="modal fade" id="modal_nuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="#" method="POST" id="nuevo_form" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Registro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 form-group form-check">
                                <label for="nuevo_nombre" style="font-weight: bold;">Nombre Completo</label>
                                <textarea name="nuevo_nombre" id="nuevo_nombre" class="form-control" autocomplete="off" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="submit_nuevo">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!---------------------------------------->
</body>
</html>

<script src="../js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="../js/popper.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
<script src="../js/init/fullcalendar-init.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script type="text/javascript">

    $(document).ready(function() {
        filtrar1();
        setInterval('filtrar1()',1000);
    } );

    function filtrar1(){
        var input_consultasporpagina = $('#consultasporpagina').val();
        var input_buscarfiltro = $('#buscarfiltro').val();
        var input_consultaporsede = $('#consultaporsede').val();
        
        $('#datatables').attr({'data-consultasporpagina':input_consultasporpagina})
        $('#datatables').attr({'data-filtrado':input_buscarfiltro})
        $('#datatables').attr({'data-sede':input_consultaporsede})

        var pagina = $('#datatables').attr('data-pagina');
        var consultasporpagina = $('#datatables').attr('data-consultasporpagina');
        var sede = $('#datatables').attr('data-sede');
        var filtrado = $('#datatables').attr('data-filtrado');
        var ubicacion_url = '<?php echo $ubicacion_url; ?>';

        $.ajax({
            type: 'POST',
            url: '../script/crud_proyectos.php',
            dataType: "JSON",
            data: {
                "pagina": pagina,
                "consultasporpagina": consultasporpagina,
                "sede": sede,
                "filtrado": filtrado,
                "link1": ubicacion_url,
                "condicion": "table1",
            },

            success: function(respuesta) {
                //console.log(respuesta);
                if(respuesta["estatus"]=="ok"){
                    $('#resultado_table1').html(respuesta["html"]);
                }
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

    function paginacion1(value){
        $('#datatables').attr({'data-pagina':value})
        filtrar1();
    }

    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    });

    $("#nuevo_form").on("submit", function(e){
        e.preventDefault();
        var nuevo_nombre = $('#nuevo_nombre').val();

        $.ajax({
            type: 'POST',
            url: '../script/crud_proyectos.php',
            dataType: "JSON",
            data: {
                "nombre": nuevo_nombre,
                "condicion": "agregar1",
            },

            success: function(respuesta) {
                console.log(respuesta);

                if(respuesta["estatus"]=="ok"){
                    $('#nuevo_nombre').val("");
                    Swal.fire({
                        title: 'Correcto!',
                        text: respuesta["msg"],
                        icon: 'success',
                    })
                }else if(respuesta["estatus"]=="error"){
                    Swal.fire({
                        title: 'Error',
                        text: respuesta["msg"],
                        icon: 'error',
                    })
                }

            },

            error: function(respuesta) {
                console.log(respuesta["responseText"]);
            }
        });
    });

    function eliminar1(id){
        $.ajax({
            type: 'POST',
            url: '../script/crud_proyectos.php',
            dataType: "JSON",
            data: {
                "id": id,
                "condicion": "eliminar1",
            },

            success: function(respuesta) {
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

            },

            error: function(respuesta) {
                console.log(respuesta["responseText"]);
            }
        });
    }
    
</script>