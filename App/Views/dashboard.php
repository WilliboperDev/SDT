<?php
require_once __DIR__ . '/../Config/policia.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Principal</title>
  <link rel="stylesheet" href="/SDT/Public/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">    
</head>
<body>
  <section>
    <div class="layout-container">
      <!-- Menú lateral -->
      <nav class="side-menu">
        <div class="menu-header">
          <div class="email-container">
            <h2 class="user-email" id="sec_user" data-valoruser= "<?php echo htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8');?>">
              <span class="edit-profile-option">
                <a href="/perfil" class="edit-link">
                  <i class="fas fa-pencil-alt"></i> Modificar perfil
                </a>
              </span>
          </div>
        </div>
        <ul class="menu-items">
          <li>
            <button class="menu-btn active">
              <i class="fas fa-home"></i>
              <span>Inicio</span>
            </button>
          </li>
          <li>
            <button class="menu-btn">
              <i class="fas fa-users"></i>
              <span>Contactos</span>
            </button>
          </li>
          <li>
            <button class="menu-btn">
            <i class="fa-solid fa-cart-shopping"></i>
              <span>Productos</span>
            </button>
          </li>
          <li>
            <button class="menu-btn">
              <i class="fas fa-cog"></i>
              <span>Configuración</span>
            </button>
          </li>
          <li>
            <button class="menu-btn" id='logoutBtn'>
            <i class="fa-solid fa-unlock-keyhole"></i>
              <span>Cerrar Sesión</span>
            </button>
          </li>
        </ul>
      </nav>
      <!-- Contenido principal -->
      <main class="main-content backfond">
        <div>
          <h1 class="title-regis">Sección de Bienvenida</h1>
          <p>Conoce a profesionales de excelencia que ofrecen sus servicios a la comunidad.</p>

          <select id="categoriaSelect">
            <option value="">Todas las categorías</option>
          </select>

          <div class="contact-slider-container">
            <button class="slider-arrow prev">‹</button>
            <div class="contacts-slider">
              <!-- Contactos se generarán dinámicamente -->
            </div>
            <button class="slider-arrow next">›</button>
          </div>
        </div>
      </main>
    </div>
  </section>

  <!-- Modal de confirmación de cierre de sesión -->
  <div id="logoutModal" class="modal-cierre" style="display:none;">
    <div class="modal-content-cierre">
      <p>¿Seguro que deseas cerrar la sesión?</p>
      <button id="confirmLogout" class="modal-cierre-btn save">Sí, cerrar sesión</button>
      <button id="cancelLogout" class="modal-cierre-btn cancel">Cancelar</button>
    </div>
  </div>

  <!-- Mostrar loader y overlay -->
  <div id="overlay" class="overlay"></div> <!-- Overlay (fondo opaco) -->
  <div id="loader" class="loader">
      <span id="loader-message"></span>
  </div> <!-- Loader -->  
  
  <!-- Cargar scripts -->
  <script src="/SDT/Public/js/validar.js"></script>
</body>
</html>