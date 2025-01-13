<?php include("../db.php");
$correo = isset($_SESSION['correo']) ? $_SESSION['correo'] : ''; // Recuperar el correo desde la sesión
?>

<?php include("../includes/header.php") ?>
<body onload="<?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $message_type = $_SESSION['message_type'] === "success" ? "light-green darken-4" : "red accent-4"; // Color basado en el tipo
        echo "M.toast({html: '$message', classes: '$message_type'});";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>">

    <?php include("../includes/navbar.php") ?> 

    <div class="">
        <h2 class="center-align"><strong>¿Formas parte del equipo?</strong></h2>
        <div class="row">
            <!-- Mapa -->
            <div class="col s7" id="mapa-container" style="height: 68vh; padding-left: 2rem; margin-right: 5rem;">
                <div id="mapaLogin" style="width: 100%; height: 100%;"></div>
            </div>

            <!-- Formulario de Login -->
            <div class="col s4">
                <div class="card">
                    <div class="card-content">
                        <h5 class="center-align"><b>Inicia sesión</b></h5>
                        <form action="login.php" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="material-icons prefix">account_circle</i>
                                    <input id="email" name="correo" type="email" class="validate" required value="<?php echo htmlspecialchars($correo); ?>">
                                    <label for="email">Correo electrónico</label>
                                    <span class="helper-text" data-error="Correo no válido" data-success="Válido">Ejemplo: usuario@correo.com</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="material-icons prefix">lock</i>
                                    <input id="password" name="contrasena" type="password" class="validate" required>
                                    <label for="password">Contraseña</label>
                                    <i class="material-icons prefix" id="togglePassword" style="cursor: pointer; position: absolute; right: 10px;">visibility_off</i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="center-align col s12">
                                    <button class="btn waves-effect waves-light light-green darken-4" type="submit" name="iniciar_sesion">Iniciar Sesión
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include("../includes/footer.php") ?>
<script>
        // Inicializa el mapa Leaflet
        var mapa = L.map('mapaLogin', {
            center: [19.502166, -99.139326], // Coordenadas de Zacatenco
            zoom: 15,
            zoomControl: false, // Desactiva los controles de zoom
            dragging: false, // Desactiva el arrastre
            scrollWheelZoom: false // Desactiva el zoom con la rueda del mouse
        });

        // Añade un tile layer
        var layer = new L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        });

        // Adding layer to the map
        mapa.addLayer(layer);

        document.addEventListener("DOMContentLoaded", function() {
            const passwordInput = document.getElementById("password");
            const togglePasswordIcon = document.getElementById("togglePassword");

            togglePasswordIcon.addEventListener("click", function() {
                // Alternar el tipo de entrada entre "password" y "text"
                const isPassword = passwordInput.type === "password";
                passwordInput.type = isPassword ? "text" : "password";

                // Cambiar el ícono
                togglePasswordIcon.textContent = isPassword ? "visibility" : "visibility_off";
            });
        });
    </script>
</body>
</html>
