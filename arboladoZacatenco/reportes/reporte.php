<?php include("../db.php"); ?>

<?php include("../includes/header.php") ?>

<body onload="<?php 
    if(isset($_SESSION['message'])){ 
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }

    if(isset($_SESSION['message_correo'])){ 
        echo ",";
        echo $_SESSION['message_correo'];
        unset($_SESSION['message_correo']);
    }

    ?>">

    <?php include("../includes/navbar.php") ?>  
    
    <h3 class="center-align"><strong>LEVANTAR REPORTE DE ARBOLADO</strong></h3>
    
    <div class="row">
        <div id="map" class="col s12" style="height:30em;"></div>
    </div>
    
    <div class ="row">
        <form action="subir_reporte.php" method="POST" id="report" enctype="multipart/form-data">
            <div class = "container">
                <div class="input-field col s6">
                    <input id="latitud" name="latitud" type="text" class="validate" value="x" readonly>
                    <label for="latitud">Latitud</label>
                </div>
                <div class ="input-field col s6">
                    <input id="longitud" name="longitud" type="text" class="validate" value="x" readonly>
                    <label for="longitud">Longitud</label>
                </div>
            </div>
            <div class = "col s6">
                <div class ="input-field col s10 offset-s2">
                    <i class="bi bi-card-list prefix"></i>
                    <select class="icons" name="tipo" id="tipo" required>
                        <option value="" disabled selected>Escoge una opción</option>
                        <option value="Ramas caídas" data-icon="../img/tree-branch.png">Ramas caídas</option>
                        <option value="Árbol enfermo/plagas" data-icon="../img/bug-fill.svg">Árbol enfermo/plagas</option>
                        <option value="Poda necesaria" data-icon="../img/tree-tools.png">Poda necesaria</option>
                        <option value="Árbol caído" data-icon="../img/tree-fallen.png">Árbol caído</option>
                        <option value="Falta de mantenimiento" data-icon="../img/tree-maintenance.png">Falta de mantenimiento</option>
                        <option value="Otros riesgos potenciales" data-icon="../img/tree-risks.png">Otros riesgos potenciales</option>
                    </select>
                    <label>Tipo de reporte</label>
                </div>
                <div class="input-field col s10 offset-s2">
                    <i class="bi bi-chat-square-fill prefix"></i>
                    <textarea id="descripcion" name="descripcion" class="materialize-textarea" required></textarea>
                    <label for="descripcion">Descripción del problema</label>
                </div>
                <div class="file-field input-field col s10 offset-s2">
                    <div class="btn light-green darken-4">
                        <i class="bi bi-image"></i>
                        <input type="file" id="imagen" name="imagen" required accept="image/png, image/jpeg, image/jpg">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Sube una fotografía del árbol">
                    </div>
                </div>
            </div>

            <div class="col s4 center-align card panel light-green lighten-4 light-green-text text-darken-3 offset-s1">
                <h4><strong>Datos de contacto</strong></h4>
                <div class="input-field col s10 offset-s1">
                    <i class="bi bi-person-circle prefix"></i>
                    <input placeholder="Introduce tu nombre completo" id="nombre" name="nombre" type="text" class="validate">
                    <label for="nombre">Nombre</label>
                </div>
                <div class="input-field col s10 offset-s1">
                    <i class="bi bi-envelope prefix"></i>
                    <input id="correo" name="correo" type="email" class="validate">
                    <label for="correo">Correo electrónico</label>
                </div>
            </div>

            <div class="col s12 center-align">
                <button class="btn waves-effect waves-light light-green darken-4" type="submit" name="subir_reporte" value="Submit">Enviar reporte
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </form>
    </div>
<?php include("../includes/footer.php") ?>

</body>
</html>