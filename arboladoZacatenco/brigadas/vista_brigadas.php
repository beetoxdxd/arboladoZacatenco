<?php include("../db.php"); 
$secciones = array("BIBLIOTECA", "CENLEX", "DIRECCION", "ENCB", "ESCOM", "ESFM", "ESIA", "ESIME",
                    "ESIQIE", "ESIT", "ESTADIO", "UPDCE", "VERDE");

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

    if(isset($_SESSION['idBrigada'])){
        unset($_SESSION['idBrigada']);
    }
?>">

    <?php include("../includes/navbar.php") ?> 

    <h3 class="center-align"><strong>BRIGADAS POR <i>SECCIÓN</i></strong></h3><br><br>
    
    
    <div class="container">
<?php 
        for($x = 0; $x < 13; $x++){
            if($x%3 == 0){
?>
        <div class="row">
<?php 
            }
?>
            
            <div class="col s4">
                <div class="card hoverable">
                    <div class="card-image">
                        <img src=<?php echo "../img/" . $secciones[$x] . ".jpg"; ?> height="215rem">
                        <span class="card-title grey black-text"><b> <?php 
                        if(strcmp($secciones[$x], "DIRECCION") == 0){ echo "DIRECCIÓN GENERAL"; }
                        elseif(strcmp($secciones[$x], "ESCOM") == 0){ echo "ESCOM/CIC"; }
                        elseif(strcmp($secciones[$x], "ESTADIO") == 0){ echo "<h6>ESTADIO WILFRIDO MASSIEU</h6>"; }
                        elseif(strcmp($secciones[$x], "VERDE") == 0){ echo "VERDE 1"; }
                        else{ echo $secciones[$x]; } ?> </b></span> 
                        <a class="btn-floating btn-large halfway-fab waves-effect waves-light light-green darken-4 modal-trigger" 
                        href="#modal<?php echo $x ?>"><i class="material-icons">visibility</i></a>
                    </div>
                </div>
            </div>
<?php
            if(($x + 1)%3 == 0){
?>
        </div>
<?php
            }
        }
?>
    </div>

<?php
    $ids = [];
    $contID = 0;
    for($x=0; $x < 13; $x++){
        $s = $secciones[$x];
?>
    <div id="modal<?php echo "$x" ?>" class="modal modal-fixed-footer">
        <div class="modal-content">
            <ul class="collection with-header">
                <li class="collection-header"><h3>BRIGADAS DE <strong class="light-green-text text-darken-4"><?php 
                if(strcmp($secciones[$x], "DIRECCION") == 0){ $s = "DIRECCIÓN GENERAL"; echo $s; }
                elseif(strcmp($secciones[$x], "ESCOM") == 0){ $s = "ESCOM/CIC"; echo $s; }
                elseif(strcmp($secciones[$x], "ESTADIO") == 0){ $s = "ESTADIO WILFRIDO MASSIEU"; echo $s; }
                elseif(strcmp($secciones[$x], "VERDE") == 0){ $s = "VERDE 1"; echo $s; }
                else{ echo $secciones[$x]; }
                ?></strong></h3>
                <p>Da clic en el nombre de la brigada para obtener más información de ella.</p>
                </li>
<?php 
        $query = "SELECT id, nombre FROM brigadas WHERE seccion = '$s';";
        $result = mysqli_query($conn, $query);
        $cont = 0;

        while($row = mysqli_fetch_array($result)){ 
            $idBrig = $row['id'];
            $queryBrig = "SELECT COUNT(id) AS numeroReportes FROM reporte WHERE brigada_asignada = '$idBrig' AND estado_reporte = 'En proceso';";
            $resultBrig = mysqli_query($conn, $queryBrig);
            $rowBrig = mysqli_fetch_array($resultBrig);

            $queryCensos = "SELECT COUNT(id) AS numeroCensos FROM censos WHERE brigada = '$idBrig' AND estado = 'Activo';";
            $resultCensos = mysqli_query($conn, $queryCensos);
            $rowCensos = mysqli_fetch_array($resultCensos);
?>
                <li class="collection-item hoverable">
                    <div>
                        <form action="borrar_brigada.php?id=<?php echo $row['id'] ?>" id="borrarBrigada<?php echo $row['id'] ?>" method="POST" enctype="multipart/form-data">
                            <a id="linkBorrar<?php echo $row['id'] ?>" href="#!" class="secondary-content"><i class="material-icons red-text">delete</i></a>
                            <input type="hidden" name="borrar_brigada" value="1">
                        </form>

                        <form action="monitoreoActividades.php?id=<?php echo $row['id'] ?>" id="monitoreoBrigada<?php echo $row['id'] ?>" method="POST" enctype="multipart/form-data">
                            <a id="linkMonitoreo<?php echo $row['id'] ?>" href="#!" class="light-green-text text-darken-2"><span class="new badge light-green darken-4" 
                            data-badge-caption=""><?php echo $rowBrig['numeroReportes'] ?> reportes</span>
                            <span class="new badge light-green darken-4" 
                            data-badge-caption=""><?php echo $rowCensos['numeroCensos'] ?> censos</span><?php echo $row['nombre'] ?></a>
                            
                            <input type="hidden" name="monitoreoActividades" value="1">
                        </form>
                    </div>
                </li>
<?php
            $cont++;
            $ids[] = $row['id'];
            $contID++;
        }
?>
            </ul>
<?php
        if($cont == 0){
?>
            <p class="center-align"><i class="large material-icons">priority_high</i><br><strong>No hay brigadas</strong> registradas en esta sección.</p>  
<?php
        }
?>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Aceptar</a>
        </div>
    </div>
<?php
    }
?>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large light-green darken-4 modal-trigger" href="#nueva_brigada">
            <i class="large material-icons">add</i>
        </a>
    </div>

    <div id="nueva_brigada" class="modal">
        <div class="modal-content center-align">
            <h4><strong>CREA UNA NUEVA BRIGADA</strong></h4>
            <form action="crear_brigada.php" method="POST" id="formBrigada" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix">format_quote</i>
                        <input id="nombreBrigada" name="nombreBrigada" type="text" class="validate" required>
                        <label for="nombreBrigada">Nombre de la brigada</label>
                    </div>
                
                    <div class="input-field col s6">
                        <select id="seccionBrigada" name="seccionBrigada" required>
                            <option value disabled selected>Selecciona una opción</option>
                            <option value="BIBLIOTECA">BIBLIOTECA</option>
                            <option value="CENLEX">CENLEX</option>
                            <option value="DIRECCIÓN GENERAL">DIRECCIÓN GENERAL</option>
                            <option value="ENCB">ENCB</option>
                            <option value="ESCOM/CIC">ESCOM/CIC</option>
                            <option value="ESFM">ESFM</option>
                            <option value="ESIA">ESIA</option>
                            <option value="ESIME">ESIME</option>
                            <option value="ESIQIE">ESIQIE</option>
                            <option value="ESIT">ESIT</option>
                            <option value="ESTADIO WILFRIDO MASSIEU">ESTADIO WILFRIDO MASSIEU</option>
                            <option value="UPDCE">UPDCE</option>
                            <option value="VERDE 1">VERDE 1</option>
                        </select>
                        <label class="light-green-text text-darken-4">Sección a la que pertenece</label>
                    </div>
                </div>
                <button class="btn waves-effect waves-light light-green darken-4" type="submit" name="crear_brigada" value="Submit">Crear
                    <i class="material-icons right">forward</i>
                </button>
            </form>
        </div>
    </div>


<?php include("../includes/footer.php") ?>
<script>
    <?php for($x = 0; $x < $contID; $x++){
?>
    document.getElementById("linkBorrar<?php echo $ids[$x] ?>").onclick = function() {
        document.getElementById("borrarBrigada<?php echo $ids[$x] ?>").submit();
    }

    document.getElementById("linkMonitoreo<?php echo $ids[$x] ?>").onclick = function() {
        document.getElementById("monitoreoBrigada<?php echo $ids[$x] ?>").submit();
    }
<?php
    } 
    ?>
    
</script>

</body>
</html>