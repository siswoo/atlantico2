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
                                <label for="nuevo_texto" style="font-weight: bold;">Texto</label>
                                <input type="text" name="nuevo_texto" id="nuevo_texto" class="form-control">
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="nuevo_campos_tipos" style="font-weight: bold;">Campos Tipos</label>
                                <select class="form-control" id="nuevo_campos_tipos" name="nuevo_campos_tipos" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $sql1 = "SELECT * FROM campos_tipos";
                                    $proceso1 = mysqli_query($conexion,$sql1);
                                    while($row1 = mysqli_fetch_array($proceso1)) {
                                        echo '
                                            <option value="'.$row1["id"].'">'.$row1["nombre"].'</option>
                                        ';          
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="nuevo_seccion" style="font-weight: bold;">Secciones</label>
                                <select class="form-control" id="nuevo_seccion" name="nuevo_seccion" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $sql2 = "SELECT * FROM secciones";
                                    $proceso2 = mysqli_query($conexion,$sql2);
                                    while($row2 = mysqli_fetch_array($proceso2)) {
                                        if($row2["tabla"]==1){
                                            $tabla = "Tiene Tabla";
                                        }else{
                                            $tabla = "Sin Tabla";
                                        }
                                        echo '
                                            <option value="'.$row2["id"].'">'.$row2["id"].' -> '.$row2["titulo"].' ['.$tabla.']</option>
                                        ';          
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="nuevo_ordenador" style="font-weight: bold;">Ordenador</label>
                                <select class="form-control" id="nuevo_ordenador" name="nuevo_ordenador" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    for($i=1;$i<=12;$i++){
                                        echo '
                                            <option value="col-lg-'.$i.' col-xl-'.$i.'">Col-'.$i.'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="nuevo_tablet" style="font-weight: bold;">Tablet</label>
                                <select class="form-control" id="nuevo_tablet" name="nuevo_tablet" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    for($i=1;$i<=12;$i++){
                                        echo '
                                            <option value="col-md-'.$i.'">Col-'.$i.'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="nuevo_telefono" style="font-weight: bold;">Telefono</label>
                                <select class="form-control" id="nuevo_telefono" name="nuevo_telefono" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    for($i=1;$i<=12;$i++){
                                        echo '
                                            <option value="col-'.$i.' col-sm-'.$i.'">Col-'.$i.'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="nuevo_orden" style="font-weight: bold;">Orden</label>
                                <select class="form-control" id="nuevo_orden" name="nuevo_orden" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    for($i=1;$i<=20;$i++){
                                        echo '<option value="'.$i.'">'.$i.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="nuevo_estatus" style="font-weight: bold;">Estatus</label>
                                <select class="form-control" id="nuevo_estatus" name="nuevo_estatus" required>
                                    <option value="">Seleccione</option>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
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

    <!-- Modal Editar -->
    <div class="modal fade" id="modal_editar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="#" method="POST" id="editar_form" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Registro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 form-group form-check">
                                <label for="editar_texto" style="font-weight: bold;">Texto</label>
                                <input type="text" name="editar_texto" id="editar_texto" class="form-control">
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="editar_campos_tipos" style="font-weight: bold;">Campos Tipos</label>
                                <select class="form-control" id="editar_campos_tipos" name="editar_campos_tipos" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $sql1 = "SELECT * FROM campos_tipos";
                                    $proceso1 = mysqli_query($conexion,$sql1);
                                    while($row1 = mysqli_fetch_array($proceso1)) {
                                        echo '
                                            <option value="'.$row1["id"].'">'.$row1["nombre"].'</option>
                                        ';          
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="editar_seccion" style="font-weight: bold;">Secciones</label>
                                <select class="form-control" id="editar_seccion" name="editar_seccion" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $sql2 = "SELECT * FROM secciones";
                                    $proceso2 = mysqli_query($conexion,$sql2);
                                    while($row2 = mysqli_fetch_array($proceso2)) {
                                        if($row2["tabla"]==1){
                                            $tabla = "Tiene Tabla";
                                        }else{
                                            $tabla = "Sin Tabla";
                                        }
                                        echo '
                                            <option value="'.$row2["id"].'">'.$row2["id"].' -> '.$row2["titulo"].' ['.$tabla.']</option>
                                        ';          
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="editar_ordenador" style="font-weight: bold;">Ordenador</label>
                                <select class="form-control" id="editar_ordenador" name="editar_ordenador" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    for($i=1;$i<=12;$i++){
                                        echo '
                                            <option value="col-lg-'.$i.' col-xl-'.$i.'">Col-'.$i.'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="editar_tablet" style="font-weight: bold;">Tablet</label>
                                <select class="form-control" id="editar_tablet" name="editar_tablet" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    for($i=1;$i<=12;$i++){
                                        echo '
                                            <option value="col-md-'.$i.'">Col-'.$i.'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="editar_telefono" style="font-weight: bold;">Telefono</label>
                                <select class="form-control" id="editar_telefono" name="editar_telefono" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    for($i=1;$i<=12;$i++){
                                        echo '
                                            <option value="col-'.$i.' col-sm-'.$i.'">Col-'.$i.'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="editar_orden" style="font-weight: bold;">Orden</label>
                                <select class="form-control" id="editar_orden" name="editar_orden" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    for($i=1;$i<=20;$i++){
                                        echo '<option value="'.$i.'">'.$i.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="editar_estatus" style="font-weight: bold;">Estatus</label>
                                <select class="form-control" id="editar_estatus" name="editar_estatus" required>
                                    <option value="">Seleccione</option>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="submit_editar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!---------------------------------------->

    <!--HIDDENS-->
    <input type="hidden" id="preguntas_id" name="preguntas_id" id="">
    <!----------->
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
            url: '../script/crud_preguntas.php',
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
        var texto = $('#nuevo_texto').val();
        var campos_tipos = $('#nuevo_campos_tipos').val();
        var seccion = $('#nuevo_seccion').val();
        var orden = $('#nuevo_orden').val();
        var estatus = $('#nuevo_estatus').val();
        var ordenador = $('#nuevo_ordenador').val();
        var tablet = $('#nuevo_tablet').val();
        var telefono = $('#nuevo_telefono').val();

        $.ajax({
            type: 'POST',
            url: '../script/crud_preguntas.php',
            dataType: "JSON",
            data: {
                "texto": texto,
                "campos_tipos": campos_tipos,
                "seccion": seccion,
                "orden": orden,
                "estatus": estatus,
                "ordenador": ordenador,
                "tablet": tablet,
                "telefono": telefono,
                "condicion": "agregar1",
            },

            success: function(respuesta) {
                console.log(respuesta);

                if(respuesta["estatus"]=="ok"){
                    $('#nuevo_texto').val("");
                    $('#nuevo_campos_tipos').val("");
                    $('#nuevo_seccion').val("");
                    //$('#nuevo_orden').val("");
                    $('#nuevo_estatus').val("");
                    $('#nuevo_ordenador').val("");
                    $('#nuevo_tablet').val("");
                    $('#nuevo_telefono').val("");
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

    function editar1(id){
        $('#preguntas_id').val(id);
        $.ajax({
            type: 'POST',
            url: '../script/crud_preguntas.php',
            dataType: "JSON",
            data: {
                "id": id,
                "condicion": "consultar1",
            },

            success: function(respuesta) {
                console.log(respuesta);

                if(respuesta["estatus"]=="ok"){
                   $('#editar_texto').val(respuesta["texto"]);
                   $('#editar_campos_tipos').val(respuesta["campos_tipos"]);
                   $('#editar_seccion').val(respuesta["secciones"]);
                   $('#editar_ordenador').val(respuesta["ordenador"]);
                   $('#editar_tablet').val(respuesta["tablet"]);
                   $('#editar_telefono').val(respuesta["telefono"]);
                   $('#editar_orden').val(respuesta["orden"]);
                   $('#editar_estatus').val(respuesta["estatus2"]);
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

    function eliminar1(id){
        $.ajax({
            type: 'POST',
            url: '../script/crud_preguntas.php',
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

    $("#editar_form").on("submit", function(e){
        e.preventDefault();
        var id = $('#preguntas_id').val();
        var texto = $('#editar_texto').val();
        var campos_tipos = $('#editar_campos_tipos').val();
        var seccion = $('#editar_seccion').val();
        var orden = $('#editar_orden').val();
        var estatus = $('#editar_estatus').val();
        var ordenador = $('#editar_ordenador').val();
        var tablet = $('#editar_tablet').val();
        var telefono = $('#editar_telefono').val();

        $.ajax({
            type: 'POST',
            url: '../script/crud_preguntas.php',
            dataType: "JSON",
            data: {
                "id": id,
                "texto": texto,
                "campos_tipos": campos_tipos,
                "seccion": seccion,
                "orden": orden,
                "estatus": estatus,
                "ordenador": ordenador,
                "tablet": tablet,
                "telefono": telefono,
                "condicion": "editar1",
            },

            success: function(respuesta) {
                console.log(respuesta);

                if(respuesta["estatus"]=="ok"){
                    $('#nuevo_texto').val("");
                    $('#nuevo_campos_tipos').val("");
                    $('#nuevo_seccion').val("");
                    $('#nuevo_orden').val("");
                    $('#nuevo_estatus').val("");
                    $('#nuevo_ordenador').val("");
                    $('#nuevo_tablet').val("");
                    $('#nuevo_telefono').val("");
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
    
</script>