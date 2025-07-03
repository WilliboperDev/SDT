<?php 
// bloqueo para evitar entrar directamente desde el navegador
require_once __DIR__ . '/../Config/cabeceras.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slider de Productos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/SDT/Public/css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="title-regis">Sección de productos</h1>
        <p>Sube los logos de los productos de tu emprendimiento.</p>
        <div class="add-product-container">
            <button class="add-product-btn" id="addProductBtn">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        
        <div class="slider-container">
            <div class="slider-din" id="sliderDin">
                <!-- Las slides se agregarán dinámicamente -->
            </div>
            
            <button class="prev-btn-pro" id="prevBtn">&#10094;</button>
            <button class="next-btn-pro" id="nextBtn">&#10095;</button>
            
            <div class="slider-nav" id="sliderNav">
                <!-- Los puntos de navegación se agregarán dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Modal para añadir producto -->
    <div class="modalp" id="addProductModal">
        <div class="modal-content-pro">
            <h2>Añadir Nuevo Producto</h2>
            <form id="productForm">
                <div class="form-group">
                    <label for="productName">Nombre del Producto *</label>
                    <input type="text" id="productName" name="productName" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ_\s]{3,30}" 
                    title="El nombre de usuario debe tener entre 3 y 30 caracteres." maxlength="30">
                    <span class="error-message" id="errorNom"></span>
                </div>
                <div class="form-group">
                    <label for="productDescription">Descripción *</label>
                    <textarea id="productDescription" name="productDescription" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ]{3,70}"
                    title="La descripción debe tener entre 3 y 70 caracteres." maxlength="70"></textarea>
                    <div id="char-counter"></div> <!-- Contador de caracteres -->
                    <span class="error-message" id="errorDes"></span>
                </div>

                <div class="form-group">
                    <label for="productImage">URL de la Imagen *</label>
                    <div class="input-group">
                        <!-- Input oculto para seleccionar el archivo -->
                        <input type="file" id="hiddenProductImage" name="hiddenProductImage" accept="image/*" style="display: none;">
                        <!-- Botón para abrir el selector de archivos -->
                        <button type="button" class="change-avatar-btn" aria-label="Cambiar imagen">
                            <i class="fa-solid fa-folder-open"></i>
                        </button>
                        <!-- Input de texto para mostrar la URL -->
                        <input type="text" id="productImage" readonly required placeholder="Selecciona una imagen">
                    </div>
                </div>
                
                <div class="modal-pro">
                    <button type="submit" class="modal-btn save">Guardar</button>
                    <button type="button" class="modal-btn cancel" id="cancelBtn">Cancelar</button>
                </div>
            </form>
            <div id="error-message" class="error-message"></div>
        </div>
    </div>

    <!-- Modal de advertencia para eliminar producto -->
    <div id="deleteModal" class="modal-t" style="display:none;">
        <div class="modal-content-t">
            <p>¿Estás seguro de que quieres eliminar este producto?</p>
            <div class="modal-actions-t">
                <button id="confirmDeleteBtn" class="modal-t-btn save">Sí, eliminar</button>
                <button id="cancelDeleteBtn" class="modal-t-btn cancel">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Mostrar loader y overlay -->
    <div id="overlay" class="overlay"></div> <!-- Overlay (fondo opaco) -->
    <div id="loader" class="loader">
        <span id="loader-message"></span>
    </div> <!-- Loader -->
</body>
</html>




