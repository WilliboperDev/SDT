
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recupera contraseña</title>
    <link rel="stylesheet" href="/SDT/Public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="backfond">
    <div class="contenedor">
        <div class="cuadecore">
            <form class="form-grid" id="formuRecu">
                <h1 id="rest"class="title-regis">Restablecer clave</h1>
                <div id="user"class="input-box"> <label for="corr">Correo:</label>
                    <input type="email" name="usuario" id="corr" placeholder="Ingrese el email" oninput="validarcorr()" required>
                    <span class="error" id="errorDiv"></span>
                </div>

                <!--IMPRIMIR MENSAJES DE CONFIRMACION O ERROR-->
                <div id="errorDiv2" class="errorDiv2"></div>
                <div id="successDiv" style="display:none;"></div>

                <div class="input-rec">
                    <button id="clatemp"style="font-size: 15px" type="submit" name="btn_clav">Solicitar clave temporal <i class="fas fa-paper-plane"></i></button>
                    <p><a href="/SDT/">
                        <button type="button"
                            style="background-color: #000000; font-size: 15px;"
                            onmouseover="this.style.backgroundColor='#363636'"
                            onmouseout="this.style.backgroundColor='#000000'"
                        ><i class="fa-solid fa-house"></i></button>
                    </a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Mostrar loader y overlay -->
    <div id="overlay" class="overlay"></div> <!-- Overlay (fondo opaco) -->
    <div id="loader" class="loader"></div> <!-- Loader -->

    <script src="/SDT/Public/js/validar.js"></script>
    
</body>
</html>




