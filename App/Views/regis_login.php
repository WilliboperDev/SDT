<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuario</title>
    <link rel="stylesheet" href="/SDT/Public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="backfond">
    <div class="contenedor">
        <div class="title-verifi">
            <h1 class="title-regis">Crea tu perfil</h1>
            <h2 style="font-size: 14px;">Por favor rellena el formulario para registrarte</h2>
        </div>
        <div class="cuadecore">
            <form class="form-grid" id="miFormulario">
                <div>
                    <label for="corr">Correo Electrónico:</label><br>
                    <input type="email" id="corr" name="correo" placeholder="Ingrese email" required>
                    <span class="error-log" id="errorDiv"></span>
                </div>
                <div>
                    <label for="pass">Contraseña:</label><br>
                    <input type="password" id="pass" name="clave" placeholder="Ingrese clave" minlength="6" required>
                    <span class="error-log" id="errorClave"></span>
                </div>
                <div>
                    <label for="pass2">Repite contraseña:</label><br>
                    <input type="password" id="pass2" name= "clave2" placeholder="Repita clave" minlength="6" required>
                    <span class="error-log" id="errorClave2"></span>
                </div>
                
                <!--IMPRIMIR MENSAJES DE CONFIRMACION O ERROR-->
                <div id="errorDiv2" class="errorDiv2"></div>
                <div id="successDiv" style="display:none; color:green;"></div>
                
                <div>
                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    <a href="/SDT/"><button type="button"><i class="fa-solid fa-house"></i></button></a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Mostrar loader y overlay -->
    <div id="overlay" class="overlay"></div> <!-- Overlay (fondo opaco) -->
    <div id="loader-reg" class="loader-reg"> <!-- Loader -->
        <span id="loader-message"></span>
    </div> 

    <!-- JavaScript Libraries -->
    <script src="/SDT/Public/js/validar.js"></script>
</body>
</html>
