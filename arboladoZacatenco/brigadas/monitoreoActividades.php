<?php include("../db.php"); 

$id = NULL;

if(!isset($_SESSION['tipoUsuario'])){
    header("Location: ../index.php");
} else if(strcmp($_SESSION['tipoUsuario'], "brigadista") == 0) {
    $correo = $_SESSION['correo'];
    $query = "SELECT brigada FROM brigadistas WHERE correo = '$correo';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    $id = $row['brigada'];
}

if(isset($_POST['monitoreoActividades'])){
    $id = $_GET['id'];
}

if(isset($_SESSION['idBrigada'])){
    $id = $_SESSION['idBrigada'];
}



if(strcmp($id, NULL) == 0){
    header("Location: vista_brigadas.php");
}

$query = "SELECT nombre FROM brigadas WHERE id = '$id';";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);

$query = "SELECT fecha FROM reporte WHERE brigada_asignada = '$id' AND estado_reporte = 'En proceso'; ";
$result = mysqli_query($conn, $query);
$diferenciaDias = 0;

if($rowToast = mysqli_fetch_array($result)){
    $fechaReporte = strtotime($rowToast['fecha']);
    $fechaActual = time(); // Fecha y hora actual en formato timestamp
    $diferenciaDias = floor(($fechaActual - $fechaReporte) / (60 * 60 * 24)); // Diferencia en días
}

?>

<?php include("../includes/header.php") ?>

<body onload="
<?php 
    if(isset($_SESSION['correcto'])){ 
        echo $_SESSION['correcto'];
        unset($_SESSION['correcto']);
    }

    if(isset($_SESSION['censo'])){ 
        echo $_SESSION['censo'];
        unset($_SESSION['censo']);
    }

    if ($diferenciaDias > 5) {
        echo "M.toast({html: 'Hay reportes con más de 5 días de antigüedad.', displayLength: 4000});";
    }

    if (isset($_SESSION['message'])) {
        if ($_SESSION['message'] == 'correo_existente') {
            echo "M.toast({html: 'El correo ya ha sido registrado previamente', displayLength: 8000});";
        } elseif ($_SESSION['message'] == 'error_registrador') {
            echo "M.toast({html: 'El correo del registrador no pertenece a un coordinador superior o no está registrado en la base de datos', displayLength: 8000});";
        } elseif ($_SESSION['message'] == 'registro_exitoso') {
            echo "M.toast({html: 'El registro ha sido exitoso, se ha enviado un correo de confimación al correo registrado', displayLength: 8000});";
        }
        
        unset($_SESSION['message']);
    }
?>">
    <?php include("../includes/navbar.php") ?> 

    <h3 class="center-align"><strong>MONITOREO DE ACTIVIDADES DE LA BRIGADA <span class="light-green-text text-darken-4">"<?php
    if($id != NULL) echo $row['nombre'];
    ?>"</span></strong></h3>
    <p class="container center-align">Puedes dar clic en la descripción de cada reporte para desplegar su información completa.</p>
    

    <div class="row">
        <div class="col s8" style="margin-right: 2rem; margin-left: 2rem;">
            <table id="tabla_monitoreo_reportes" class="responsive-table centered highlight display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo de reporte</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Fecha</th>
                        <th>Acciones</th> <!-- Botones -->
                    </tr>
                </thead>
                <tbody>
<?php 
    $query = "SELECT id, tipoReporte, latitud, longitud, descripcion, imagen, fecha FROM reporte WHERE brigada_asignada = '$id' AND estado_reporte = 'En proceso'; ";
    $result = mysqli_query($conn, $query);

    $cont = 0;
    $ids = [];
    while($row = mysqli_fetch_array($result)){ 
        $fechaReporte = strtotime($row['fecha']);
        $fechaActual = time(); // Fecha y hora actual en formato timestamp
        $diferenciaDias = floor(($fechaActual - $fechaReporte) / (60 * 60 * 24)); // Diferencia en días
?> 
                        <tr>
                            <td><?php echo $row['id'] ?></td>
                            <td><?php echo $row['tipoReporte'] ?></td>
                            <td><div class="truncate" style="max-width: 10rem;">
                                <a href="#mostrarInfo<?php echo $row['id'] ?>" class="light-green-text text-darken-2 
                                modal-trigger"><?php echo $row['descripcion'] . 'id=' . $row['id'] ?></a>
                            </div></td>
                            <td><img class="materialboxed" height="30em" src="data:image/jpg;base64, <?php echo base64_encode($row['imagen']); ?>"/></td>
                            <td>
                                <div class="center-align">
<?php 
                                echo $row['fecha'] . "<br>";
        if ($diferenciaDias > 5) {
            echo '<a class="btn btn-small pulse red accent-4">URGENTE</a>';
        }
?>
                                </div>
                            </td>
                            <td>
                                <a class="waves-effect waves-light btn-small modal-trigger light-green darken-4" 
                                href="#registrarIncidente?id=<?php echo $row['id']; ?>">Registrar incidente</a><br><br>
                                <a class="waves-effect waves-light btn-small modal-trigger light-green darken-4" 
                                href="#finalizarReporte?id=<?php echo $row['id']; ?>">Finalizar reporte</a>
                            </td>
                        </tr>
<?php 
        $ids[] = $row['id'];
        $cont++;
    } 
?>
                </tbody>
            </table>
        </div>

        <div class="col s3" style="height: 30rem;">
            <div style="overflow-y: auto;">
                <h4 class="center-align"><i class="material-icons">face</i>   <strong class="light-green-text text-darken-4">Participantes</strong></h4>
                <ul class="collapsible popout">
<?php 
    $query = "SELECT nombre, apellidos, telefono, correo FROM brigadistas WHERE brigada = '$id'; ";
    $result = mysqli_query($conn, $query);  
    $contPeople = 0;
    while($row = mysqli_fetch_array($result)){ 
?>
                    <li>
                        <div class="collapsible-header"><i class="material-icons">arrow_drop_down</i><?php 
                        echo $row['nombre'] . " " . $row['apellidos']; ?></div>
                        <div class="collapsible-body"><span><strong>Teléfono:</strong> <?php echo $row['telefono']; 
                        ?><br><strong>Correo:</strong> <?php echo $row['correo']; ?></span></div>
                    </li>
<?php
        $contPeople++;
    }
?>
                </ul>

<?php
    if($contPeople == 0){
?>
                <p class="center-align">
                    <i class="large material-icons">mood_bad</i><br>
                    <strong>No hay participantes</strong> asignados a esta brigada.
                </p>  
<?php 
    } 
?>
            </div>
            <div style="overflow-y: auto;">
                <h4 class="center-align"><i class="material-icons">list</i>   <strong class="light-green-text text-darken-4">Censos asignados</strong></h4>
                <ul class="collection">
<?php 
    $queryCensos = "SELECT id, nombre, fecha_inicio, fecha_fin FROM censos WHERE brigada = '$id' AND estado = 'Activo';";
    $resultCensos = mysqli_query($conn, $queryCensos);
    $contCensos = 0; $coor = []; $idCensos = [];

    while($rowCensos = mysqli_fetch_array($resultCensos)){ 
?>
                    <li class="collection-item hoverable">
                        <div class="valign-wrapper">
                            <p class="truncate">
                                <strong><?php echo $rowCensos['nombre'] ?></strong><br>
                                <?php echo "<strong>Inicio:</strong> " . $rowCensos['fecha_inicio'] . "<br><strong>Fin:</strong> " . $rowCensos['fecha_fin'] ?>
                            </p>
                            <a href="#nuevo_arbol?idCenso=<?php echo $rowCensos['id'] ?>" class="secondary-content col s2 right-align modal-trigger"><i class="small material-icons light-green-text text-darken-4">launch</i></a>
                        </div>
                    </li>
<?php
        $idCensos[] = $rowCensos['id'];
        $contCensos++;
    }
?>
                </ul>
<?php
    if($contCensos == 0){
?>
                <p class="center-align">
                    <i class="large material-icons">check</i><br>
                    <strong>No hay censos</strong> asignados a esta brigada.
                </p>  
<?php 
    } 
?>
            </div>
        </div>

    </div>
    
<?php 
    for($x = 0; $x < $cont; $x++){
?>
    <div id="mostrarInfo<?php echo $ids[$x] ?>" class="modal modal-fixed-footer"> <!-- Modals para visualizar toda la información del reporte -->
        <div class="modal-content center-align">
            <h4><strong>Información del <span class="light-green-text text-darken-4">reporte <?php echo $ids[$x] ?></span></strong></h4>
<?php 
        $queryAux = "SELECT*FROM reporte WHERE id = '$ids[$x]'";
        $resultAux = mysqli_query($conn, $queryAux);
        $rowAux = mysqli_fetch_array($resultAux);    
?>
            <p>
                Este reporte de tipo <strong><?php echo $rowAux['tipoReporte'] ?></strong> está ubicado en 
                <strong>[<?php echo $rowAux['latitud'] . ", " . $rowAux['longitud'] ?>]</strong>, además, 
                fue registrado en la fecha <strong><?php echo $rowAux['fecha'] ?></strong>. Su descripción 
                es como sigue: <em><?php echo $rowAux['descripcion'] ?></em>
            </p>
            <div class="center-align"><img class="materialboxed" width="600rem" 
            src="data:image/jpg;base64, <?php echo base64_encode($rowAux['imagen']); ?>"
            style="display: block; margin: auto; max-width: 100%; height: auto;"/></div>
            <p><a href="https://www.google.com/maps?q=<?php echo $rowAux['latitud'] ?>,<?php echo $rowAux['longitud']
            ?>&t=k" class="light-green-text text-darken-2" target="_blank" rel="noopener noreferrer">Visualiza la ubicación en el mapa.</a></p>
        </div>

        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Aceptar</a>
        </div>
    </div>

    <div id="registrarIncidente?id=<?php echo $ids[$x] ?>" class="modal opcionIndicente"> <!-- Modals para registrar incidente -->
        <div class="modal-content center-align">
            <h4><strong>Registrar incidente producido en el <span class="light-green-text text-darken-4">reporte <?php echo $ids[$x] ?></span></strong></h4>
        
            <form action="subirReporteIncidente.php?id=<?php echo $ids[$x] ?>" method="post">
                <div class="row">
                    <div class="input-field col s6 offset-s1">
                        <select id="opcionIncidente<?php echo $ids[$x] ?>" name="opcionIncidente<?php echo $ids[$x] ?>" required>
                            <option value="" disabled selected>Elige una opción</option>
                            <option value="Condiciones ambientales adversas">Condiciones ambientales adversas</option>
                            <option value="Accidentes y lesiones">Accidentes y lesiones</option>
                            <option value="Problemas con el equipo">Problemas con el equipo</option>
                            <option value="Conflictos con personas externas">Conflictos con personas externas</option>
                            <option value="Seguridad y emergencias">Seguridad y emergencias</option>
                            <option value="Condiciones de trabajo no optimas">Condiciones de trabajo no optimas</option>
                        </select>
                        <label>Selecciona una opción</label>
                    </div>

                    <div class="input-field col s2">
                        <input type="text" class="datepicker" id="fechaIncidente<?php echo $ids[$x] ?>" name="fechaIncidente<?php echo $ids[$x] ?>" required>
                        <label for="fechaIncidente<?php echo $ids[$x] ?>">Fecha</label>
                    </div>

                    <div class="input-field col s2">
                        <input type="text" class="timepicker" id="horaIncidente<?php echo $ids[$x] ?>" name="horaIncidente<?php echo $ids[$x] ?>" required>
                        <label for="horaIncidente<?php echo $ids[$x] ?>">Hora</label>
                    </div>

                    <div class="input-field offset-s1 col s10">
                        <input type="text" id="descripcionIncidente<?php echo $ids[$x] ?>" name="descripcionIncidente<?php echo $ids[$x] ?>" required>
                        <label for="descripcionIncidente<?php echo $ids[$x] ?>">Descripción</label>
                    </div>
                    
                    <div class="input-field offset-s1 col s10">
                        <input type="text" id="accionTomada<?php echo $ids[$x] ?>" name="accionTomada<?php echo $ids[$x] ?>" required>
                        <label for="accionTomada<?php echo $ids[$x] ?>">Acción tomada</label>
                    </div>
                </div>
                <input type="hidden" id="idBrigada" name="idBrigada" value="<?php echo $id; ?>">
                <div class="center-align">
                    <button class="btn waves-effect waves-light light-green darken-4" name="registrarIncidente" type="submit" value="Submit">Registrar
                        <i class="material-icons right">forward</i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="finalizarReporte?id=<?php echo $ids[$x] ?>" class="modal"> <!-- Modals para registrar incidente -->
        <div class="modal-content center-align">
            <h4><strong>¿Seguro que quieres finalizar el <span class="light-green-text text-darken-4">reporte <?php echo $ids[$x] ?></span>?</strong></h4>
        
            <form action="finalizarReporte.php?id=<?php echo $ids[$x] ?>" method="post">
                <input type="hidden" id="idBrigada" name="idBrigada" value="<?php echo $id; ?>">
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

    for($x = 0; $x < $contCensos; $x++){
?>
    <!-- Esto es para registrar un arbol del censo -->
    <div id="nuevo_arbol?idCenso=<?php echo $idCensos[$x] ?>" class="modal">
        <div class="modal-content center-align">
            <h4><strong>REGISTRA UN NUEVO ÁRBOL</strong></h4>
            <form action="../censos/registrar_arbol.php?idCenso=<?php echo $idCensos[$x] ?>" method="POST" id="censof" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field col s10 offset-s1">
                        <select name="especieSelect" id="especieSelect" required>
                            <option value="" disabled selected>Selecciona una especie</option>
                        </select>
                        <label>Especie de árbol:</label>
                    </div>

                    <div class="input-field col s5 offset-s1">
                        <input id="altura" name="altura" type="text" pattern="^\d+(\.\d{1,2})?$" class="validate" required>
                        <label for="altura">Altura (en metros)</label>
                    </div>
                    <div class="input-field col s5">
                        <input id="diametro" name="diametro" type="text" pattern="^\d+(\.\d{1,2})?$" class="validate" required>
                        <label for="diametro">Diametro del tronco (en metros)</label>
                    </div>
                    <div class="input-field col s3 offset-s1">
                        <input id="latitud" name="latitud" type="text" pattern="^-?\d+(\.\d+)?$" class="validate" required>
                        <label for="latitud">Latitud</label>
                    </div>
                    <div class="input-field col s3">
                        <input id="longitud" name="longitud" type="text" pattern="^-?\d+(\.\d+)?$" class="validate" required>
                        <label for="longitud">Longitud</label>
                    </div>

                    <div class="input-field col s5">
                        <button type="button" id="getLocation" class="btn waves-effect waves-light light-green darken-4">
                            Obtener ubicación
                            <i class="material-icons right">location_on</i>
                        </button>
                    </div>

                    <div class="input-field col s10 offset-s1">
                        <textarea id="condicion<?php echo $idCensos[$x] ?>" name="condicion<?php echo $idCensos[$x] ?>" class="materialize-textarea" data-length="200" required></textarea>
                        <label for="condicion<?php echo $idCensos[$x] ?>">Condiciones generales</label>
                    </div>
                <input type="hidden" id="idBrigada" name="idBrigada" value="<?php echo $id; ?>">
                    
                </div>
                <button class="btn waves-effect waves-light light-green darken-4" type="submit" name="subir_arbol" value="Submit">Registrar
                    <i class="material-icons right">forward</i>
                </button>
            </form>
        </div>
    </div>
<?php
    }
?>

<?php if(isset($_SESSION['tipoUsuario']) && strcmp($_SESSION['tipoUsuario'], "coordinador") == 0){
        ?>
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large light-green darken-4 modal-trigger" href="#nuevo_brigadista">
            <i class="large material-icons">add</i>
        </a>
    </div>

    <div id="nuevo_brigadista" class="modal">
        <div class="modal-content center-align valign-wrapper">
            <h4><strong>ASIGNA UN NUEVO BRIGADISTA</strong></h4>
            <form action="../coordinador/subirRegistroBrig.php" method="POST" id="formBrigadista" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix">person</i>
                        <input id="nombreBrig" name="nombreBrig" type="text" class="validate" required>
                        <label for="nombreBrig">Nombre(s)</label>
                    </div>

                    <div class="input-field col s6">
                        <input id="apellidoBrig" name="apellidoBrig" type="text" class="validate" required>
                        <label for="apellidoBrig">Apellidos</label>
                    </div>

                    <div class="input-field col s5">
                        <i class="material-icons prefix">phone</i>
                        <input id="telefonoBrig" name="telefonoBrig" type="tel" pattern="[0-9]{10}" class="validate" required>
                        <label for="telefonoBrig">Teléfono</label>
                    </div>    

                    <div class="input-field col s7">
                        <i class="material-icons prefix">email</i>
                        <input id="correoBrig" name="correoBrig" type="email" class="validate" required>
                        <label for="correoBrig">Correo</label>
                    </div>
                    
                </div>
                <input type="hidden" id="idBrigada" name="idBrigada" value="<?php echo $id; ?>">
                <button class="btn waves-effect waves-light light-green darken-4" type="submit" name="crear_brigadista" value="Submit">Asignar
                    <i class="material-icons right">forward</i>
                </button>
            </form>
        </div>
    </div>
    <?php
    } ?>

<?php include("../includes/footer.php") ?>

<script>
    $(document).ready(function() {
        $('<?php for($x = 0; $x < $contCensos; $x++){
            echo "textarea#condicion" . $ids[$x];
            if($x + 1 != $contCensos) echo ", ";
        } ?>').characterCounter();
    });

    // Prevenir que se introduzcan más caracteres que el límite y controlar el envío del formulario
    $('<?php for($x = 0; $x < $contCensos; $x++){
        echo "textarea#condicion" . $ids[$x];
        if($x + 1 != $contCensos) echo ", ";
    } ?>').on('input', function() {
        const maxLength = $(this).attr('data-length'); // Obtener límite
        const value = $(this).val(); // Obtener valor actual
        if (value.length > maxLength) {
            $(this).val(value.substring(0, maxLength)); // Recortar al máximo permitido
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
<?php for ($x = 0; $x < $cont; $x++) {
        $query = "SELECT fecha FROM reporte WHERE id = '$ids[$x]'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        $fecha = $row['fecha']; 
?>
        var elem = document.getElementById('fechaIncidente<?php echo $ids[$x]; ?>');
        var parts = "<?php echo $fecha; ?>".split('-');
        var min = new Date(parts[0], parts[1] - 1, parts[2]); 
        var today = new Date();

        if (elem) {
            M.Datepicker.init(elem, {
                minDate: min,
                maxDate: today,
                autoClose: true,
                format: "yyyy-mm-dd",
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
        }
    <?php } ?>
});


    document.addEventListener("DOMContentLoaded", () => {
        <?php
            $json = file_get_contents("../censos/especies.JSON");
            echo "const especies = $json;";
        ?>
        const selectEspecie = document.getElementById("especieSelect");

        especies.forEach(especie => {
            const option = document.createElement("option");
            option.value = especie.nombre_cientifico;
            option.textContent = `${especie.nombre_cientifico} (${especie.nombre_comun})`;
            selectEspecie.appendChild(option);
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const getLocationBtn = document.getElementById('getLocation');
        const latitudInput = document.getElementById('latitud');
        const longitudInput = document.getElementById('longitud');

        // Función para obtener la geolocalización
        const obtenerUbicacion = () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const latitud = position.coords.latitude.toFixed(6);
                        const longitud = position.coords.longitude.toFixed(6);

                        // Coloca los valores en los inputs
                        latitudInput.value = latitud;
                        longitudInput.value = longitud;

                        // Actualiza los labels de Materialize
                        M.updateTextFields();
                    },
                    (error) => {
                        let mensaje = '';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                mensaje = 'El usuario negó el acceso a la ubicación.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                mensaje = 'La ubicación no está disponible.';
                                break;
                            case error.TIMEOUT:
                                mensaje = 'La solicitud para obtener la ubicación expiró.';
                                break;
                            default:
                                mensaje = 'Error desconocido al obtener la ubicación.';
                        }
                        M.toast({ html: mensaje, classes: 'red' });
                    }
                );
            } else {
                M.toast({ html: 'La geolocalización no es soportada por este navegador.', classes: 'red' });
            }
        };

        getLocationBtn.addEventListener('click', obtenerUbicacion);
    });
</script>

</html>