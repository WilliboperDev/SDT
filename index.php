<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de directorio telefónico</title>
    <link rel="icon" type="image/png" href="Public/img/favicon.png">
    <link rel="stylesheet" href= "Public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="backfond">
    <div class="contenedor" style="display: none;" id="contenedor">
        <div class="cuadecore">
            <form class="form-grid" id="formuInd">
                <h1 class="title-regis">Iniciar Sesión</h1>
                <div class="input-box">
                    <input type="email" name="usuario" id="corr" placeholder="Ingrese el email" required>
                    <span class="error-log" id="errorDiv"></span>
                </div>
                <div>
                    <input class="input-pass" type="password" name="password" id="pass" placeholder="Ingrese la contraseña" minlength="6" required>
                    <!--<input class="input-check" type="checkbox" id="togglePassword">-->
                    <button style="width: 28px;width: 28px;padding: 5px;font-size: 15px;" type="button" id="togglePassword"><i class="fa fa-eye"></i></button>
                    <span class="error-log" id="errorClave"></span>
                </div>

                <!--IMPRIMIR MENSAJES DE CONFIRMACION O ERROR-->
                <div id="errorDiv2" class="errorDiv2"></div>
                <div id="successDiv" style="display:none; color:green;"></div>
                
                <div class="input-box">
                    <button type="submit" id="submitBtn">Acceder</button>
                    <p><a href="recupera">Olvidé mi contraseña</a></p>

                    <p class="tit-regis">¿Aún no tienes una cuenta?</p>
                    <a href="regis_login">
                        <button type="button"
                            style="background-color: #247228;"
                            onmouseover="this.style.backgroundColor='#328d36'"
                            onmouseout="this.style.backgroundColor='#247228'"
                        >Registrarme</button>
                    </a>
                </div>  
            </form>
        </div>
    </div>
    
    <!-- Mostrar loader y overlay -->
    <div id="overlay" class="overlay"></div> <!-- Overlay (fondo opaco) -->
    <!-- Loader de inicio -->
    <div id="loader-index" class="loader-index">
        <span id="loader-message">Sistema de Directorio telefónico<br>&copy; 2025 Williboper Dev.</span>
    </div> 
    <!-- Loader de validacion -->
    <div id="loader-vali" class="loader-vali"></div>

    <!-- Script especial para el loader de bienvenida -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar el loader y el overlay al cargar la página
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('overlay').style.backgroundColor = 'initial';
            document.getElementById('loader-index').style.display = 'block';
            document.getElementById('loader-message').style.color = '#000000';

            // Ocultar el loader después de 1.3 segundos (simulación de carga)
            setTimeout(function() {
                document.getElementById('overlay').style.display = 'none';
                document.getElementById('loader-index').style.display = 'none';
                document.getElementById('contenedor').style.display = 'block';
            }, 1300);
        });
    </script>
    <script src="Public/js/validar.js"></script>
</body>
</html>
