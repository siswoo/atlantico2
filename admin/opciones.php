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
                        <label for="consultaporsede" style="color:black; font-weight: bold;">Consultas por Preguntas</label>
                        <select class="form-control" id="consultaporsede" name="consultaporsede">
                            <option value="">Todos</option>
                            <?php
                            $sql9 = "SELECT * FROM preguntas";
                            $proceso9 = mysqli_query($conexion,$sql9);
                            while($row9 = mysqli_fetch_array($proceso9)) {
                                echo '
                                    <option value="'.$row9["id"].'">'.$row9["texto"].'</option>
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
                                <textarea name="nuevo_texto" id="nuevo_texto" class="form-control" autocomplete="off" required></textarea>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label for="nuevo_preguntas" style="font-weight: bold;">Preguntas</label>
                                <select class="form-control" id="nuevo_preguntas" name="nuevo_preguntas" onchange="validarcodigo(value);" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $sql2 = "SELECT * FROM preguntas";
                                    $proceso2 = mysqli_query($conexion,$sql2);
                                    while($row2 = mysqli_fetch_array($proceso2)) {
                                        echo '
                                            <option value="'.$row2["id"].'">'.$row2["id"].' -> "'.$row2["texto"].'"</option>
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
                            <div class="col-12 form-group form-check" id="nuevo_tabla_div" style="display: none;">
                                <label for="nuevo_tabla" style="font-weight: bold;">Tabla</label>
                                <input type="text" class="form-control" name="nuevo_tabla" id="nuevo_tabla" minlength="3">
                            </div>
                            <div class="col-12 form-group form-check" id="nuevo_condicional1_div" style="display: none;">
                                <label for="nuevo_condicional1" style="font-weight: bold;">Condicional1</label>
                                <input type="text" class="form-control" name="nuevo_condicional1" id="nuevo_condicional1">
                            </div>
                            <div class="col-12 form-group form-check" id="nuevo_condicional2_div" style="display: none;">
                                <label for="nuevo_condicional2" style="font-weight: bold;">Condicional2</label>
                                <input type="text" class="form-control" name="nuevo_condicional2" id="nuevo_condicional2">
                            </div>
                            <div class="col-12 form-group form-check" id="nuevo_condicional3_div" style="display: none;">
                                <label for="nuevo_condicional3" style="font-weight: bold;">Condicional3</label>
                                <input type="text" class="form-control" name="nuevo_condicional3" id="nuevo_condicional3">
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
            url: '../script/crud_opciones.php',
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
        var preguntas = $('#nuevo_preguntas').val();
        var orden = $('#nuevo_orden').val();

        var tabla = $('#nuevo_tabla').val();
        var condicional1 = $('#nuevo_condicional1').val();
        var condicional2 = $('#nuevo_condicional2').val();
        var condicional3 = $('#nuevo_condicional3').val();

        $.ajax({
            type: 'POST',
            url: '../script/crud_opciones.php',
            dataType: "JSON",
            data: {
                "texto": texto,
                "preguntas": preguntas,
                "tabla": tabla,
                "orden": orden,
                "condicional1": condicional1,
                "condicional2": condicional2,
                "condicional3": condicional3,
                "condicion": "agregar1",
            },

            success: function(respuesta) {
                console.log(respuesta);

                if(respuesta["estatus"]=="ok"){
                    $('#nuevo_texto').val("");
                    $('#nuevo_preguntas').val("");
                    //$('#nuevo_orden').val("");
                    $('#nuevo_tabla').val("");
                    $('#nuevo_condicional1').val("");
                    $('#nuevo_condicional2').val("");
                    $('#nuevo_condicional3').val("");
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
            url: '../script/crud_opciones.php',
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

    function validarcodigo(value){
        $.ajax({
            type: 'POST',
            url: '../script/crud_opciones.php',
            dataType: "JSON",
            data: {
                "value": value,
                "condicion": "validarcodigo1",
            },

            success: function(respuesta) {
                console.log(respuesta);
                if(respuesta["campos_tipos"]==4){
                    $('#nuevo_tabla_div').show('slow');
                    $('#nuevo_condicional1_div').show('slow');
                    $('#nuevo_condicional2_div').show('slow');
                    $('#nuevo_condicional3_div').show('slow');
                }else{
                    $('#nuevo_tabla_div').hide('slow');
                    $('#nuevo_condicional1_div').hide('slow');
                    $('#nuevo_condicional2_div').hide('slow');
                    $('#nuevo_condicional3_div').hide('slow');
                }
            },

            error: function(respuesta) {
                console.log(respuesta["responseText"]);
            }
        });
    }
    
</script>