<?php include("../db.php");
function pointInPolygon($point, $polygon) {
    $x = $point[0];
    $y = $point[1];
    $inside = false;

    $n = count($polygon);
    for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
        $xi = $polygon[$i][0];
        $yi = $polygon[$i][1];
        $xj = $polygon[$j][0];
        $yj = $polygon[$j][1];

        $intersect = (($yi > $y) != ($yj > $y)) &&
                     ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
        if ($intersect) {
            $inside = !$inside;
        }
    }

    return $inside;
}

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
      
    <h3 class="center-align"><strong>SEGUIMIENTO DE REPORTES</strong></h3>
    <p class="container center-align">Puedes dar clic en la descripción de cada reporte para desplegar su información completa.</p>
    <div class="row responsive-table">
        <table id="tabla_reportes" class="centered highlight display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo de reporte</th>
                    <th>Latitud</th>
                    <th>Longitud</th>
                    <th>Descripción</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Imagen</th>
                    <th>Fecha</th>
                    <th>Estado del reporte</th>
                    <th>Brigada asignada</th>
                    <th>Bitácora</th>
                </tr>
            </thead>
            <tbody>
<?php 
    $query = "SELECT id, tipoReporte, latitud, longitud, descripcion, nombre, 
        (aes_decrypt(correo, 'AES')) correo, imagen, fecha, estado_reporte, brigada_asignada 
        FROM reporte; ";
    $result = mysqli_query($conn, $query);
    $cont = 0;
    $ids = []; $estados = [];

    while($row = mysqli_fetch_array($result)){ 
?>
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['tipoReporte'] ?></td>
                    <td><div class="truncate" style="max-width: 5rem;">
                        <?php echo $row['latitud']; ?>
                    </div></td>
                    <td><div class="truncate" style="max-width: 5rem;">
                        <?php echo $row['longitud']; ?>
                    </div></td>
                    <td><div class="truncate" style="max-width: 10rem;">
                        <a href="#mostrarInfo<?php echo $row['id'] ?>" class="light-green-text text-darken-2 
                        modal-trigger"><?php echo $row['descripcion'] . 'id=' . $row['id'] ?></a>
                    </div></td>
                    <td><?php echo $row['nombre'] ?></td>
                    <td><?php echo $row['correo'] ?></td>
                    <td><img class="materialboxed" height="30em" src="data:image/jpg;base64, <?php echo base64_encode($row['imagen']); ?>"/></td>
                    <td><?php echo $row['fecha'] ?></td>
                    <td><strong><?php echo $row['estado_reporte'] ?></strong><br><a 
                        class="waves-effect waves-light btn-small modal-trigger light-green darken-4 
                        <?php if(strcmp($row['brigada_asignada'], NULL) == 0){
                                echo "disabled";
                        } ?>" href="#ME?id=<?php echo $row['id']; ?>">Modificar</a></td>
                    <td><strong><?php 
                        $b = 0;
                        if(strcmp($row['brigada_asignada'], NULL) == 0){
                            echo "N/A";
                        } else { 
                            echo "Brigada " . $row['brigada_asignada'];
                            $b = 1;
                        } ?></strong><br><a class="waves-effect waves-light btn-small modal-trigger light-green darken-4 
                        <?php if($b == 0 || strcmp($row['estado_reporte'], 'Resuelto') == 0){
                                echo "disabled";
                        } ?>" href="#cambiarBrigada<?php echo $row['id']; ?>">Cambiar</a></td>
                    <td><a href="#verCambios<?php echo $row['id']; ?>" class="waves-effect waves-light modal-trigger">
                        <i class="material-icons light-green-text text-darken-4">visibility</i></a></td>
                </tr>
<?php 
        $ids[] = $row['id'];
        $estados[] = $row['estado_reporte'];
        $cont++; 
    } 
?>
            </tbody>
        </table>
    </div> 

<?php 
    $rep = $cont;
    for($x = 0; $x < $cont; $x++){
?>
    <div id="ME?id=<?php echo $ids[$x]; ?>" class="modal">
        <div class="modal-content center-align">
            <h4><strong>Modificar el estado del <span class="light-green-text text-darken-4">reporte <?php echo $ids[$x]; ?></span></strong></h4>
            <p>Una vez que selecciones el nuevo estado del reporte, debes introducir el <strong>motivo</strong>
             por el cual se está realizando el cambio.</p>
            <form action="modificar.php?id=<?php echo $ids[$x]; ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field">
                        <div class="col s6 right-align">
                            <label>
                                <input name="estado" type="radio" value="En proceso" <?php echo (strcmp($estados[$x], "En proceso") == 0) ? 'checked' : ''; ?> />
                                <span class="black-text">En proceso</span>
                            </label>
                        </div>
                        <div class="col s6 left-align">
                            <label>
                                <input name="estado" type="radio" value="Resuelto" <?php echo (strcmp($estados[$x], "Resuelto") == 0) ? 'checked' : ''; ?> />
                                <span class="black-text">Resuelto</span>
                            </label>
                        </div>
                    </div>
                    <br><br>
                    <div class="input-field col s10 offset-s1">
                        <i class="material-icons prefix">chat_bubble</i>
                        <textarea id="notas<?php echo $ids[$x] ?>" name="notas<?php echo $ids[$x] ?>" class="materialize-textarea" required data-length="200"></textarea>
                        <label for="notas<?php echo $ids[$x] ?>">Notas de actualización de estado</label>
                    </div>

                    <button class="btn waves-effect waves-light light-green darken-4" type="submit" name="editar_reporte" value="Submit">Modificar
                        <i class="material-icons right">forward</i>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
<?php
        $sec = ""; $brig = "";
        if(strcmp($rowAux['brigada_asignada'], NULL) != 0){
            $queryInfo = "SELECT tipoReporte, latitud, longitud, descripcion, imagen, fecha, estado_reporte, 
            brigadas.nombre as nombre_brigada, secciones.nombre as seccion FROM reporte INNER JOIN brigadas 
            ON reporte.brigada_asignada = brigadas.id INNER JOIN secciones ON 
            brigadas.seccion = secciones.nombre WHERE reporte.id = '$ids[$x]'; ";
            $resultInfo = mysqli_query($conn, $queryInfo);
            $rowInfo = mysqli_fetch_array($resultInfo);
?>
            <p>
                Actualmente, este reporte está en el estado de <strong><?php echo $rowInfo['estado_reporte'] ?>
                </strong>, y está asignado a la brigada <strong><?php echo $rowInfo['nombre_brigada'] ?>
                </strong>, perteneciente a la sección <strong><?php echo $rowInfo['seccion'] ?></strong>.
            </p>
<?php 
            $sec = $rowInfo['seccion'];
            $brig = $rowInfo['nombre_brigada'];
        } 
?>

            <p><a href="https://www.google.com/maps?q=<?php echo $rowAux['latitud'] ?>,<?php echo $rowAux['longitud']
            ?>&t=k" class="light-green-text text-darken-2" target="_blank" rel="noopener noreferrer">Visualiza la ubicación en el mapa.</a></p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Aceptar</a>
        </div>
    </div>

    <div id="cambiarBrigada<?php echo $ids[$x] ?>" class="modal asignarBrigada">
        <div class="modal-content center-align">
            <h4><strong>Asignar una nueva brigada al <span class="light-green-text text-darken-4">reporte <?php echo $ids[$x] ?></span></strong></h4>
            <p>Este reporte está asignado a la brigada <strong><?php echo $brig ?></strong>, 
            perteneciente a la sección <strong><?php echo $sec ?></strong>.</p>
            <form action="asignar_reporteBrigada.php?reporteId=<?php echo $ids[$x] ?>" method="POST" id="formReasignar" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field col s12">
                        <select id="seccionBrigada" name="seccionBrigada" required>
                            <option value disabled selected>Selecciona una opción</option>
<?php
        $queryBrigadas = "SELECT*FROM brigadas WHERE seccion IN (
            SELECT brigadas.seccion as seccion FROM reporte INNER JOIN brigadas ON 
            reporte.brigada_asignada = brigadas.id WHERE reporte.id = '$ids[$x]');";
        $resultBrigadas = mysqli_query($conn, $queryBrigadas);
        while($rowBrigadas = mysqli_fetch_array($resultBrigadas)){
?>
                            <option value="<?php echo $rowBrigadas['id'] ?>"><?php echo $rowBrigadas['nombre'] ?></option>
<?php 
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

    <div id="verCambios<?php echo $ids[$x] ?>" class="modal modal-fixed-footer"> <!-- Modals para visualizar los cambios -->
        <div class="modal-content center-align">
            <h4><strong>Historial de cambios del <span class="light-green-text text-darken-4">reporte <?php echo $ids[$x] ?></span></strong></h4>
            <ul class="collection collection-modal">
<?php 
        $queryHistorial = "SELECT fecha_cambio, notas FROM estado_reporte INNER JOIN reporte ON 
        estado_reporte.reporte_id = reporte.id WHERE estado_reporte.reporte_id = '$ids[$x]';";
        $resultHistorial = mysqli_query($conn, $queryHistorial);
        while($rowHistorial = mysqli_fetch_array($resultHistorial)){
?>
                <li class="collection-item"><div><strong>Fecha: </strong>
                    <?php echo $rowHistorial['fecha_cambio']; ?><br><strong>Notas registradas:</strong><em>
                        <?php echo $rowHistorial['notas']; ?></em></div>
                </li>
<?php 
        } 
?>
            </ul>

            <h4><strong>Incidentes ocurridos en el <span class="light-green-text text-darken-4">reporte <?php echo $ids[$x] ?></span></strong></h4>
            <ul class="collapsible collection-modal">
            <?php 
        $queryHistorial = "SELECT opcion, fecha, hora, descripcion, accionTomada FROM reporteIncidente 
        WHERE reporte = '$ids[$x]';";
        $resultHistorial = mysqli_query($conn, $queryHistorial);
        while($rowHistorial = mysqli_fetch_array($resultHistorial)){
?>
                <li>
                    <div class="collapsible-header"><i class="material-icons">arrow_drop_down</i><strong class="light-green-text text-darken-4"><?php 
                    echo $rowHistorial['opcion'] ?></strong><?php echo ". Registrado el " . $rowHistorial['fecha'] . " a las " . 
                    $rowHistorial['hora'] ?></div>
                    <div class="collapsible-body"><span><strong>Descripción:</strong> <?php echo $rowHistorial['descripcion']; 
                    ?><br><strong>Acción tomada:</strong> <?php echo $rowHistorial['accionTomada']; ?></span></div>
                </li>
<?php 
        } 
?>
            </ul>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Aceptar</a>
        </div>
    </div>
<?php
    }     
?>

    <div class="container">
        <div class="row collection-wrapper">
            <ul class="collection with-header">
                <li class="collection-header light-green darken-4 white-text center-align"><h5>REPORTES SIN BRIGADA ASIGNADA</h5></li>
<?php 
    $queryReporte = "SELECT id, tipoReporte, latitud, longitud, descripcion, fecha FROM reporte WHERE brigada_asignada IS NULL;";
    $resultReporte = mysqli_query($conn, $queryReporte);
    $cont = 0; $coor = []; $id = [];

    while($rowReporte = mysqli_fetch_array($resultReporte)){ 
?>
                <li class="collection-item hoverable">
                    <div class="valign-wrapper">
                        <p class="truncate">
                            <strong><?php echo $rowReporte['tipoReporte'] ?></strong>
                            <?php echo " [" . $rowReporte['latitud'] . ", " . $rowReporte['longitud'] . "]" ?>
                            <?php echo "<br><strong>Fecha:</strong> " . $rowReporte['fecha'] . "<br><strong>Descripción:</strong> " . $rowReporte['descripcion'] ?>
                        </p>
                        <a href="#asignarBrigada<?php echo $cont ?>" class="secondary-content col s2 right-align modal-trigger"><i class="medium material-icons light-green-text text-darken-4">assignment_late</i></a>
                    </div>
                </li>
<?php
        $xy = [floatval($rowReporte['latitud']), floatval($rowReporte['longitud'])];
        $coor[] = $xy;
        $id[] = $rowReporte['id'];
        $cont++;
    }
?>
            </ul>
<?php
    if($cont == 0){
?>
            <p class="center-align">
                <i class="large material-icons">check</i><br>
                <strong>No hay reportes</strong> sin alguna brigada asignada.
            </p>  
<?php 
    } 
?>
        </div>
    </div>

<?php 
    for($x = 0; $x < $cont; $x++){ 
?>
    <div id="asignarBrigada<?php echo $x ?>" class="modal asignarBrigada">
        <div class="modal-content center-align">
<?php
        $querySeccion = "SELECT*FROM secciones;";
        $resultSeccion = mysqli_query($conn, $querySeccion);
        $seccion = "";
            
        while($rowSeccion = mysqli_fetch_array($resultSeccion)){ 
            $location = $coor[$x];
            $pol = json_decode($rowSeccion['poligono'], true);
            if(pointInPolygon($location, $pol)){
                $seccion = $rowSeccion['nombre'];
                break;
            }
        }
?>
            <h4><strong>Este reporte pertenece a la sección <span class="light-green-text text-darken-4"><?php echo $seccion ?></span></strong></h4>
<?php
        $queryBrigadas = "SELECT*FROM brigadas WHERE seccion = '$seccion';";
        $resultBrigadas = mysqli_query($conn, $queryBrigadas);
        $rowBrigadas = mysqli_fetch_array($resultBrigadas);

        if($rowBrigadas){
?>
            <form action="asignar_reporteBrigada.php?reporteId=<?php echo $id[$x] ?>" method="POST" id="formAsignar" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field col s12">
                        <select id="seccionBrigada" name="seccionBrigada" required>
                            <option value disabled selected>Selecciona una opción</option>
<?php
            while($rowBrigadas){ 
?>
                            <option value="<?php echo $rowBrigadas['id'] ?>"><?php echo $rowBrigadas['nombre'] ?></option>
<?php 
                    $rowBrigadas = mysqli_fetch_array($resultBrigadas);
                }
?>
                        </select>
                        <label class="light-green-text text-darken-4">Selecciona la brigada a la que quieres asignar el reporte</label>
                        <p>Puedes crear más brigadas <a href="../brigadas/vista_brigadas.php" class="light-green-text text-darken-2">aquí</a>.</p>
                    </div>
                    <button class="btn waves-effect waves-light light-green darken-4" type="submit" name="asignarB" value="Submit">Asignar
                        <i class="material-icons right">forward</i>
                    </button>
                </div>
            </form>
<?php
        } else { 
?>
            <p>Parece que aún no hay brigadas creadas en esta sección. <a href="../brigadas/vista_brigadas.php" class="light-green-text text-darken-2">Crea una brigada aquí</a></p>
<?php
        }
?>
        </div>
    </div>
<?php 
    } 
?>

<?php include("../includes/footer.php") ?>

<script>
    $(document).ready(function() {
        $('<?php for($x = 0; $x < $rep; $x++){
            echo "textarea#notas" . $ids[$x];
            if($x + 1 != $rep) echo ", ";
        } ?>').characterCounter();
    });

    // Prevenir que se introduzcan más caracteres que el límite y controlar el envío del formulario
    $('<?php for($x = 0; $x < $rep; $x++){
        echo "textarea#notas" . $ids[$x];
        if($x + 1 != $rep) echo ", ";
    } ?>').on('input', function() {
        const maxLength = $(this).attr('data-length'); // Obtener límite
        const value = $(this).val(); // Obtener valor actual
        if (value.length > maxLength) {
            $(this).val(value.substring(0, maxLength)); // Recortar al máximo permitido
        }
    });
</script>
</body>

</html>