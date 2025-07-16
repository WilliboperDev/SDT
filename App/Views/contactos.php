<?php 
// bloqueo para evitar entrar directamente desde el navegador
require_once dirname(__DIR__) . '/Config/def_ruta.php';
require_once ROOT_PATH . '/Config/cabeceras.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguidores y Seguidos</title>
    <link rel="stylesheet" href= "<?= htmlspecialchars($appUrl, ENT_QUOTES) ?>/Public/css/style.css">
</head>
<body>
    <div style="width: 90%; height: 80%;">
        <h1 class="title-regis">Sección de contactos</h1>
        <p>Aquí puedes saber más de tus contactos y solicitar ayuda cuando lo desees.</p>
        <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-btn active" id="btn-seguidores">Seguidores</button>
                <button class="tab-btn" id="btn-seguidos">Seguidos</button>
            </div>
            
            <!-- Contenido de Seguidores -->
            <div id="seguidores" class="tab-content active">
                <ul class="user-list">
                    <!-- Seguidores cargados dinámicamente desde el servidor -->
                </ul>
            </div>
            
            <!-- Contenido de Seguidos -->
            <div id="seguidos" class="tab-content">
                <ul class="user-list">
                    <!-- Seguidos cargados dinámicamente desde el servidor -->
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Modal para detalles del usuario con scroll -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" id="modal-close">&times;</button>
            
            <div class="modal-actions">
                <div class="modal-header">
                    <img id="modalAvatar" src="" alt="Avatar" class="modal-avatar">
                    <div class="modal-user-info">
                        <h2 id="modalName"></h2>
                        <p id="modalUsername"></p>
                    </div>
                </div>
            </div>

            <div class="modal-scrollable">
                <div class="info-grid">
                    <div class="info-item dobl-colum">
                        <div class="info-label">
                            <span class="icon">📝</span> Acerca de
                        </div>
                        <div class="bio-text" id="modalBio"></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="modal-bio" id="modalema"></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Teléfono</div>
                        <div class="modal-bio" id="modaltel"></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Estado</div>
                        <div class="modal-bio" id="modalest"></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Municipio/Parroquia</div>
                        <div class="modal-bio" id="modalmup"></div>
                    </div>
                    <div class="info-item dobl-colum">
                        <div class="info-label">Dirección</div>
                        <div class="modal-bio" id="modaldir"></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Horario comercial</div>
                        <div class="modal-bio" id="modalhor"></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Sitio web</div>
                        <div class="modal-bio" id="modalweb"></div>
                    </div>
                </div>

                <!-- Slider de productos dentro del modal -->
                <div class="product-slider-container">
                    <h3>Productos relacionados</h3>
                    <div class="slider">
                        <button class="slider-btn prev-btn" id="btn-prev">&#10094;</button>
                        <div class="slider-wrapper">
                            <div class="slider-items">
                                <!-- Productos cargados dinámicamente desde el servidor -->
                            </div>
                        </div>
                        <button class="slider-btn next-btn" id="btn-next">&#10095;</button>
                    </div>
                </div>
            </div>

            <div class="modal-stats">
                <div class="stat-item">
                    <div class="stat-number" id="modalFollowers"></div>
                    <div class="stat-label">Seguidores</div>
                </div>  
                <div class="stat-item">
                    <div class="stat-number" id="modalFollowing"></div>
                    <div class="stat-label">Seguidos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="modalPosts"></div>
                    <div class="stat-label">Publicaciones</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>