<?php include("../db.php");

if(!isset($_SESSION['tipoUsuario'])){
    header("Location: ../index.php");
} elseif(isset($_SESSION['tipoUsuario']) && strcmp($_SESSION['tipoUsuario'], "brigadista") == 0){
    header("Location: ../index.php");
}
?>

<?php include("../includes/header.php") ?>

<body onload="<?php 
    if(isset($_SESSION['message'])){ 
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
?>">

<?php include("../includes/navbar.php") ?>  
      
    <h3 class="center-align"><strong>CENSOS REGISTRADOS</strong></h3>
    <!--
    <p class="container center-align">Puedes dar clic en la descripción de cada reporte para desplegar su información completa.</p>
    -->
    <div class="container">
        <div class="row responsive-table">
            <table id="tabla_censos" class="centered highlight display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Fecha de inicio</th>
                        <th>Fecha de finalización</th>
                        <th>Brigada asignada</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
<?php 
    $query = "SELECT * FROM censos; ";
    $result = mysqli_query($conn, $query);
    $cont = 0;
    $ids = []; $brig = [];

    while($row = mysqli_fetch_array($result)){ 
?>
                    <tr>
                        <td><?php echo $row['id'] ?></td>
                        <td><?php echo $row['nombre'] ?></td>
                        <td><?php echo $row['fecha_inicio'] ?></td>
                        <td><?php echo $row['fecha_fin'] ?><br><a 
                            class="waves-effect waves-light btn-small modal-trigger light-green darken-4 
                            <?php if(strcmp($row['estado'], 'Finalizado') == 0){
                                    echo "disabled";
                            } ?>" href="#fechaFin?id=<?php echo $row['id']; ?>">Modificar</a>
                        </td>
                        <td><strong>Brigada <?php echo $row['brigada']; ?></strong><br><a 
                            class="waves-effect waves-light btn-small modal-trigger light-green darken-4 
                            <?php if(strcmp($row['estado'], 'Finalizado') == 0){
                                    echo "disabled";
                            } if(strcmp($row['brigada'], NULL) == 0) echo "pulse"; ?>" href="#cambiarBrigada<?php echo $row['id']; ?>">Cambiar</a></td>
                        <td><a href="#finalizarCenso?id=<?php echo $row['id']; ?>" class="waves-effect waves-light modal-trigger">
                            <i class="material-icons small light-green-text text-darken-4 ">archive</i></a></td>
                    </tr>
<?php 
        $ids[] = $row['id'];
        $brig[] = $row['brigada'];
        $cont++; 
    } 
?>
                </tbody>
            </table>
        </div>
    </div>

<?php 
    for($x = 0; $x < $cont; $x++){
?>
    <div id="fechaFin?id=<?php echo $ids[$x]; ?>" class="modal">
        <div class="modal-content center-align">
            <h4><strong>Modificar la fecha de finalización del <span class="light-green-text text-darken-4">censo <?php echo $ids[$x]; ?></span></strong></h4>
            <p>En este apartado puedes modificar la fecha de finalización del censo. Puedes realizar
                esta acción siempre y cuando el censo esté activo; una vez que el censo haya sido archivado,
                no podrás modificar la fecha de finalización.
            </p>
            <form action="modificarFechaFin.php?id=<?php echo $ids[$x]; ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field col s8 offset-s2">
                        <input type="text" class="datepicker" id="fechaFin" name="fechaFin" required>
                        <label for="fechaFin">Fecha de finalización</label>
                    </div>
                </div>
                <button class="btn waves-effect waves-light light-green darken-4" type="submit" name="editar_fecha" value="Submit">Modificar
                    <i class="material-icons right">forward</i>
                </button>
            </form>
        </div>
    </div>

    <div id="cambiarBrigada<?php echo $ids[$x] ?>" class="modal asignarBrigada">
        <div class="modal-content center-align">
            <?php 
            $query = "SELECT nombre, seccion FROM brigadas WHERE id = '$brig[$x]';";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            ?>
            <h4><strong>Asignar una nueva brigada al <span class="light-green-text text-darken-4">censo <?php echo $ids[$x] ?></span></strong></h4>
            <?php if($row){ ?>
            <p>Este censo está asignado a la brigada <strong><?php echo $row['nombre'] ?></strong>, 
            perteneciente a la sección <strong><?php echo $row['seccion'] ?></strong>.</p>
            <?php } ?>
            <form action="asignar_censoBrigada.php?censoId=<?php echo $ids[$x] ?>" method="POST" id="formReasignar" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field col s12">
                        <select id="seccionBrigada" name="seccionBrigada" required>
                            <option value disabled selected>Selecciona una opción</option>
<?php 
                            $querySelect = "SELECT nombre FROM secciones;";
                            $resultSelect = mysqli_query($conn, $querySelect);
                            while($rowSelect = mysqli_fetch_array($resultSelect)){
                                $nombreSeccion = $rowSelect['nombre'];
                                $queryAnidado = "SELECT id, nombre, seccion FROM brigadas WHERE seccion = '$nombreSeccion';";
                                $resultAnidado = mysqli_query($conn, $queryAnidado);
                                $rowAnidado = mysqli_fetch_array($resultAnidado);

                                if($rowAnidado){ 
?>
                            <optgroup label="<?php echo $rowAnidado['seccion']; ?>">
<?php
                                    while($rowAnidado){
?>
                                <option value="<?php echo $rowAnidado['id']; ?>"><?php echo $rowAnidado['nombre']; ?></option>

<?php
                                        $rowAnidado = mysqli_fetch_array($resultAnidado);
                                    }
?>
                            </optgroup>

<?php
                                }
                            }
?>
                        </select>
                        <label class="light-green-text text-darken-4">Selecciona la nueva brigada a la que quieres asignar el reporte</label>
                        <p>Puedes crear más brigadas <a href="../brigadas/vista_brigadas.php" class="light-green-text text-darken-2">aquí</a>.</p>
                    </div>
                    <button class="btn waves-effect waves-light light-green darken-4" type="submit" name="asignarB" value="Submit">Asignar
                        <i class="material-icons right">forward</i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="finalizarCenso?id=<?php echo $ids[$x] ?>" class="modal"> <!-- Modals para registrar incidente -->
        <div class="modal-content center-align">
            <h4><strong>¿Seguro que quieres finalizar el <span class="light-green-text text-darken-4">censo <?php echo $ids[$x] ?></span>?</strong></h4>
            <p>Esta acción no se puede deshacer.</p>
        
            <form action="finalizarCenso.php?id=<?php echo $ids[$x] ?>" method="post">
                <div class="center-align">
                    <button class="btn waves-effect waves-light light-green darken-4" name="finalizarReporte" type="submit" value="Submit">Finalizar
                        <i class="material-icons right">forward</i>
                    </button>
                </div>
            </form>
        </div>
    </div>

<?php
    }     
?>

    
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large light-green darken-4 modal-trigger" href="#nuevo_censo">
            <i class="large material-icons">add</i>
        </a>
    </div>

    <div id="nuevo_censo" class="modal opcionIndicente">
        <div class="modal-content center-align">
            <h4><strong>CREA UN NUEVO CENSO</strong></h4>
            <form action="crear_censo.php" method="POST" id="censof" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">format_quote</i>
                        <input id="nombreCenso" name="nombreCenso" type="text" class="validate" required>
                        <label for="nombreCenso">Nombre del censo</label>
                    </div>

                    <div class="input-field col s6">
                        <i class="material-icons prefix">date_range</i>
                        <input type="text" class="datepicker" id="fechaInicio" name="fechaInicio" required>
                        <label for="fechaInicio">Fecha de inicio</label>
                    </div>

                    <div class="input-field col s6">
                        <input type="text" class="datepicker" id="fechaFin" name="fechaFin" required>
                        <label for="fechaFin">Fecha de finalización</label>
                    </div>

                    <div class="input-field col s10 offset-s1">
                        <select id="brigadaCenso" name="brigadaCenso">
                            <option value="" disabled selected>Escoge una opción</option>
<?php 
                            $querySelect = "SELECT nombre FROM secciones;";
                            $resultSelect = mysqli_query($conn, $querySelect);
                            while($rowSelect = mysqli_fetch_array($resultSelect)){
                                $nombreSeccion = $rowSelect['nombre'];
                                $queryAnidado = "SELECT id, nombre, seccion FROM brigadas WHERE seccion = '$nombreSeccion';";
                                $resultAnidado = mysqli_query($conn, $queryAnidado);
                                $rowAnidado = mysqli_fetch_array($resultAnidado);

                                if($rowAnidado){ 
?>
                            <optgroup label="<?php echo $rowAnidado['seccion']; ?>">
<?php
                                    while($rowAnidado){
?>
                                <option value="<?php echo $rowAnidado['id']; ?>"><?php echo $rowAnidado['nombre']; ?></option>

<?php
                                        $rowAnidado = mysqli_fetch_array($resultAnidado);
                                    }
?>
                            </optgroup>

<?php
                                }
                            }
?>
                        </select>
                        <label>Selecciona una brigada</label>
                        <p>Puedes crear más brigadas <a href="../brigadas/vista_brigadas.php" class="light-green-text text-darken-2">aquí</a>.</p>
                    </div>
                    
                </div>
                <button class="btn waves-effect waves-light light-green darken-4" type="submit" name="crear_censo" value="Submit">Crear
                    <i class="material-icons right">forward</i>
                </button>
            </form>
        </div>
    </div>


<?php include("../includes/footer.php") ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.datepicker');
        var today = new Date();

        var instances = M.Datepicker.init(elems, {
            autoClose: true,
            format: "yyyy-mm-dd",
            minDate: today,
            i18n: {
                cancel: 'Cancelar',
                clear: 'Limpiar',
                done: 'Aceptar',
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S']
            }
        });
    });

    

</script>
</body>
</html>