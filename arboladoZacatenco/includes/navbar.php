<nav class="light-green darken-1">
    <div class="navbar-wrapper">
        <div class="nav-wrapper container">
            <a href="../index.php" class="brand-logo"><i class="bi bi-house-door-fill"></i></a>
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
                    <a href="../login/indexa.php" class="btn light-green darken-4 waves-effect waves-light">Iniciar sesión</a>
                    <?php
                    } else { ?>
                    <a href="../login/cerrar_sesion.php" class="btn red accent-4 waves-effect waves-light">Cerrar sesión</a>
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
    <li><a href="../brigadas/vista_brigadas.php">Brigadas existentes</a></li>
    <li><a href="../brigadas/b.html">Voluntarios</a></li>
    <?php
    } else if(isset($_SESSION['tipoUsuario']) && strcmp($_SESSION['tipoUsuario'], "brigadista") == 0) { ?>
    <li><a href="../brigadas/monitoreoActividades.php">Monitorea tu brigada</a></li>
    <?php
    } ?>
</ul>

    <!-- Dropdown options -->
<ul id="reportes" class="dropdown-content">
    <li><a href="../reportes/reporte.php">Levantar reporte</a></li>
    <?php if(isset($_SESSION['tipoUsuario']) && strcmp($_SESSION['tipoUsuario'], "coordinador") == 0){
        ?>
    <li><a href="../reportes/seguimiento_reporte.php">Seguimiento de reportes</a></li>
        <?php
    } ?>
</ul> 

<ul id="censos" class="dropdown-content">
    <li><a href="../censos/vista_censos.php">Censos existentes</a></li>
</ul>

<ul id="coordinador" class="dropdown-content">
    <li><a href="../coordinador/registroCoordinador.php">Registrar coordinadores</a></li>
</ul>