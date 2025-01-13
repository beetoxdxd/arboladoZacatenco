<?php include("db.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arbolado Zacatenco</title>
    <link rel="icon" href="img/tree-fill.svg">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/dropdown.css" />
</head>
<body onload="<?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $message_type = $_SESSION['message_type'] === "success" ? "light-green darken-4" : "red accent-4"; // Color basado en el tipo
        echo "M.toast({html: '$message', classes: '$message_type'});";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>">

    <nav class="light-green darken-1">
        <div class="navbar-wrapper">
            <div class="nav-wrapper container">
                <a href="index.php" class="brand-logo"><i class="bi bi-house-door-fill"></i></a>
                <ul class="right">
                    <li> <!-- class ="active"-->
                        <a href="#" class="dropdown-trigger" data-target="reportes"><b>Reportes<i class="material-icons right">arrow_drop_down</i></b></a>
                    </li>
                        <?php if(isset($_SESSION['tipoUsuario'])){
                        ?>
                    <li>
                        <a class="dropdown-trigger" href="#!" data-target="dropdown1"><b>Brigadas</b><i class="material-icons right">arrow_drop_down</i></a>
                    </li>
                        <?php
                        } ?>
                        <?php if(isset($_SESSION['tipoUsuario']) && strcmp($_SESSION['tipoUsuario'], "coordinador") == 0){
                        ?>
                    <li>
                        <a class="dropdown-trigger" href="#" data-target="censos"><b>Censos</b><i class="material-icons right">arrow_drop_down</i></a>
                    </li>
                    <li>
                        <a class="dropdown-trigger" href="#" data-target="coordinador"><b>Coordinador</b><i class="material-icons right">arrow_drop_down</i></a>
                    </li>
                        <?php
                        } ?>
                    <li>
                        <?php if(!isset($_SESSION['tipoUsuario'])){
                        ?>
                        <a href="login/indexa.php" class="btn light-green darken-4 waves-effect waves-light">Iniciar sesión</a>
                        <?php
                        } else { ?>
                        <a href="login/cerrar_sesion.php" class="btn red accent-4 waves-effect waves-light">Cerrar sesión</a>
                        <?php
                        } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dropdown options -->
    <ul id="dropdown1" class="dropdown-content">
        <?php if(isset($_SESSION['tipoUsuario']) && strcmp($_SESSION['tipoUsuario'], "coordinador") == 0){
            ?>
        <li><a href="brigadas/vista_brigadas.php">Brigadas existentes</a></li>
        <li><a href="brigadas/b.html">Voluntarios</a></li>
        <?php
        } else if(isset($_SESSION['tipoUsuario']) && strcmp($_SESSION['tipoUsuario'], "brigadista") == 0) { ?>
        <li><a href="brigadas/monitoreoActividades.php">Monitorea tu brigada</a></li>
        <?php
        } ?>
    </ul>

        <!-- Dropdown options -->
    <ul id="reportes" class="dropdown-content">
        <li><a href="reportes/reporte.php">Levantar reporte</a></li>
        <?php if(isset($_SESSION['tipoUsuario']) && strcmp($_SESSION['tipoUsuario'], "coordinador") == 0){
            ?>
        <li><a href="reportes/seguimiento_reporte.php">Seguimiento de reportes</a></li>
            <?php
        } ?>
    </ul> 

    <ul id="censos" class="dropdown-content">
        <li><a href="censos/vista_censos.php">Censos existentes</a></li>
    </ul>

    <ul id="coordinador" class="dropdown-content">
        <li><a href="coordinador/registroCoordinador.php">Registrar coordinadores</a></li>
    </ul>

    <h3 class="center-align"><strong>AQUÍ VA EL MAPA</strong></h3><br><br>


<?php include("includes/footer.php") ?>

</body>
</html>