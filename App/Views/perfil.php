<?php 
// bloqueo para evitar entrar directamente desde el navegador
require_once __DIR__ . '/../Config/cabeceras.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="/SDT/Public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body> 
  <form id="edit-form" class="edit-form">
    <button type="button" id="actu-btn" class="actu-btn"><i class="fa-solid fa-pen"></i></button>
    <div class="edit-avatar">
      <!-- Imagen del avatar -->
      <!--<img class="pre-avatar" src="https://randomuser.me/api/portraits/men/32.jpg" alt="avatar" id="avatar-preview">-->
      <img class="pre-avatar" src="Public/img/usuario.png" alt="avatar" id="avatar-preview">
      <input type="file" id="edit-avatar" name="avatar" accept="image/*" style="display: none;">
      <button type="button" id="change-avatar" class="change-avatar-btn" aria-label="Cambiar imagen"><i class="fa-solid fa-camera"></i></button>
    </div>
    <div id="error-message" class="error-message"></div>

    <!-- Modo visualización -->
    <div class="centered-form" id="perfilContainer-view">
      <div class="perfil-dato"><b>Nombre:</b> <span id="vie-edit-name"></span></div>
      <div class="perfil-dato"><b>Categoría:</b> <span id="vie-edit-position"></span></div>
      <div class="perfil-dato"><b>Acerca de:</b> <span style="text-align: left;" id="vie-edit-descrip"></span></div>
      <div class="perfil-dato"><b>Teléfono:</b> <span id="vie-edit-phone"></span></div>
      <div class="perfil-dato"><b>Estado:</b> <span id="vie-edit-est"></span></div>
      <div class="perfil-dato"><b>Municipio:</b> <span id="vie-edit-mun"></span></div>
      <div class="perfil-dato"><b>Parroquia:</b> <span id="vie-edit-parr"></span></div>
      <div class="perfil-dato"><b>Dirección:</b> <span id="vie-edit-direc"></span></div>
      <div class="perfil-dato"><b>Horario:</b> <span id="vie-edit-horario"></span></div>
      <div class="perfil-dato"><b>Sitio Web:</b> <span id="vie-edit-website"></span></div>
    </div>

    <div class="centered-form" id="perfilContainer-edit" style="display:none;">
      <label for="edit-name">Nombre:</label>
      <input type="text" id="edit-name" name="nombre" placeholder="Tu nombre" minlength="5" maxlength="30" pattern="[a-zA-Z\s]+" title="Solo letras y espacios">
      
      <label for="edit-position">Categoria:</label>
      <select id="edit-position" name="Categoria" style="width: 94%;">
        <option value=""disabled selected>Selecciona tu profesión</option>
      </select>
      <input type="text" id="edit-otra" name="otra_profesion" placeholder="Especifique su profesión" 
      pattern="[a-zA-ZñÑ\s]+" title="Solo letras y espacios" class="hidden">
      
      <label for="edit-descrip">Acerca de:</label>
      <textarea name="descrip" id="edit-descrip" placeholder="Breve descripcion a que se dedica" minlength = "5" maxlength="200"></textarea>
      <div id="char-counter"></div> <!-- Contador de caracteres -->

      <label for="edit-phone">Teléfono:</label>
      <div>
        <select id="edit-phone" name="ext" style="width: 43%;">
          <option value="" disabled selected>Selecciona código área</option>
        </select>
        <input type="tel" id="text-phone" name="phone" class="input-phone" placeholder="Teléfono" 
        maxlength="7" pattern="[0-9]{7}" title="Debe tener 7 dígitos.">
      </div>
      
      <label for="edit-est">Estado:</label>
      <select id="edit-est" name="estado" style="width: 94%;">
        <option value="" disabled selected>Selecciona el estado</option>
      </select>

      <label for="edit-mun">Municipio:</label>
      <select id="edit-mun" name="municipio" style="width: 94%;">
        <option value="" disabled selected>Selecciona el municipio</option>
      </select>

      <label for="edit-parr">Parroquia:</label>
      <select id="edit-parr" name="parroquia" style="width: 94%;">
        <option value="" disabled selected>Selecciona la parroquia</option>
      </select>

      <label for="edit-direc">Dirección:</label>
      <textarea name="direccion" id="edit-direc" placeholder="¿Dónde ofreces tus servicios?" minlength = "5" maxlength="200"></textarea>
      <div id="char-counter2"></div> <!-- Contador de caracteres -->

      <label>Horario comercial:</label>
      <div>
        <select id="edit-horario-apertura" name="horario_apertura" placeholder="Horario de apertura" style="width: 46%;">
          <option value="" disabled selected>Selecciona el horario de apertura</option>
        </select>
        <select id="edit-horario-cierre" name="horario_cierre" placeholder="Horario de cierre" style="width: 47%;">
          <option value="" disabled selected>Selecciona el horario de cierre</option>
        </select>
      </div>

      <label for="edit-website">Sitio web:</label>
      <div style="display: flex; align-items: center; gap: 10px; width: 94%;">
          <input type="checkbox" id="no-website" name="no-website">
          <label id="label-web" for="no-website">No tengo sitio web</label>
          <input type="url" id="edit-website" name="web" pattern="https?://.*" 
          title="El sitio web debe comenzar con http:// o https://"placeholder="https://misitio.com" style="flex: 1;">
      </div>
    </div>
    <button type="submit" class="save-btn"><i class="fas fa-paper-plane"></i></button>
    <!--<button type="button" class="cancel-btn"><i class="fa-solid fa-house"></i></button>-->
  </form>

  <!-- Mostrar loader y overlay -->
  <div id="overlay" class="overlay"></div> <!-- Overlay (fondo opaco) -->
  <div id="loader" class="loader">
    <span id="loader-message"></span>
  </div> <!-- Loader -->

  <div id="error-message" class="error-message"></div> <!-- Mensaje de error -->
  <div id="success-message" class="success-message" style="display:none;"></div> <!-- Mensaje de éxito -->
  
  <input type="hidden" id="accion" name="accion" value=""> <!-- Campo oculto para la acción -->

  <!-- Para el modal -->
  <div id="successModal" class="modal" style="display: none;">
    <div class="modal-content">
        <h2>Perfil Actualizado</h2>
        <p>Los cambios se han guardado correctamente.</p>
        <button class="close-btn" type="button"><i class="fa-solid fa-house"></i></button>
    </div>
  </div>
</body>
</html>