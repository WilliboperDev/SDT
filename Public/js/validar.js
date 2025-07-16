// SCRIPT DE VALIDACION //

///////////////////////////////////////////////////////////////////////////
//////////////////////// LOGIN ///////////////////////////////////////////

// Definir la URL base del proyecto
const PROJECT_FOLDER = window.location.pathname.split('/')[1] || '';
const BASE_URL = `/${PROJECT_FOLDER}/`;

// Inyecta la ruta dinámica para el estilo de fondo
document.documentElement.style.setProperty(
  '--dynamic-bg', 
  `url('${window.location.pathname.split('/')[1] ? '/' + window.location.pathname.split('/')[1] + '/' : '/'}Public/img/soft-white.jpg')`
);

document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('formuInd');
    const errorDiv = document.getElementById('errorDiv');
    const errorDiv2 = document.getElementById('errorDiv2');
    const successDiv = document.getElementById('successDiv');
    const ClaveInput = document.getElementById('pass');

    if (formulario) {
        formulario.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene el envío del formulario

            const correo = formulario.usuario.value.trim();
            const clave = formulario.password.value.trim();
            errorDiv2.style.display = 'none';

            // Validar si el correo es válido
            if (!isValidEmail(correo)) {
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'El Correo es inválido.';
                formulario.usuario.style.border = '2px solid red';
                return;
            } else {
                errorDiv.style.display = 'none';
                formulario.usuario.style.border = '';
            }

            // Validar si la clave tiene menos de 6 caracteres
            if (clave.length < 6) {
                errorClave.style.display = 'block';
                errorClave.textContent = 'Debe tener mas de 6 caracteres.';
                formulario.password.style.border = '2px solid red';
                return;
            } else {
                errorClave.style.display = 'none';
                formulario.password.style.border = '';
            }

            /******************** PARA EJECUTAR EL LOADER ************************/
            // Deshabilitar el formulario y el botón de envío
            const inputs = formulario.querySelectorAll('input, button'); // Selecciona todos los inputs y el botón
            
            inputs.forEach(input => input.disabled = true); // Deshabilitar todos los campos y el botón
            /*errorDiv2.style.display = 'block';  
            errorDiv2.textContent = 'Procesando, espere un momento...';*/

            // Mostrar el overlay y el loader
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('overlay').style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
            document.getElementById('loader-vali').style.display = 'block';

            /****************************************************************/

            setTimeout(function() { // Simular el procesamiento de datos

                /* Enviar datos al servidor y recibir una respuesta sin recargar la pagina */
                const data = { // Crear un objeto con los datos del formulario
                    correo: correo,
                    clave: clave
                };
                // Enviar los datos al servidor para registrar el usuario
                fetch(`${BASE_URL}App/Models/login.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data) //convertir un objeto JavaScript (data) en una cadena JSON
                })
                .then(response => response.json()) //convertir la respuesta a un objeto JSON
                .then(data => {
                    if (data.success) { 
                        // Ocultar el overlay y el loader después de procesar
                        document.getElementById('overlay').style.display = 'none';
                        document.getElementById('loader-vali').style.display = 'none';
                        // Habilitar el formulario y el botón de envío
                        inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón
                        
                        formulario.reset(); // Limpiar el formulario
                        // Redirijo al dashboard
                        window.location.href = `${BASE_URL}dashboard`;
                        
                    } else {
                        // Ocultar el overlay y el loader después de procesar
                        document.getElementById('overlay').style.display = 'none';
                        document.getElementById('loader-vali').style.display = 'none';
                        // Habilitar el formulario y el botón de envío
                        inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón

                        errorDiv2.style.display = 'block';
                        //errorDiv2.innerHTML = '<br>' + data.error; // renderizo para que antes haga un salto de linea
                        errorDiv2.textContent = data.error;
                        successDiv.style.display = 'none';
                        ClaveInput.value = '';
                    }
                })
                .catch(error => {
                    // Ocultar el overlay y el loader después de procesar
                    document.getElementById('overlay').style.display = 'none';
                    document.getElementById('loader-vali').style.display = 'none';
                    // Habilitar el formulario y el botón de envío
                    inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón

                    errorDiv2.style.display = 'block';
                    //errorDiv2.textContent = 'Ocurrió un error al procesar el registro.'; //modo produccion
                    //errorDiv2.textContent = 'Error al procesar el registro: ' + error; //modo desarrollo
                    errorDiv2.innerHTML = 'Error al procesar el registro <br>' + error; //modo desarrollo
                    ClaveInput.value = '';
                    successDiv.style.display = 'none';
                });
            }, 1000); // Simula un tiempo de procesamiento de 3 segundos
        });    
    }
});

// Mostrar/ocultar la contraseña
document.addEventListener('DOMContentLoaded', function () {
    const buttonOcult = document.getElementById('togglePassword');
    if (buttonOcult) { // validar que se haga click en el boton
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('pass');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
            passwordInput.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }
});

///////////////////////////////////////////////////////////////////////////
//////////////////////// RECUPERA CLAVE ///////////////////////////////////

document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('formuRecu');
    const errorDiv = document.getElementById('errorDiv');
    const errorDiv2 = document.getElementById('errorDiv2');
    const successDiv = document.getElementById('successDiv');
    const emailInput = document.getElementById('corr');

    if (formulario) {
        formulario.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene el envío del formulario

            const correo = formulario.usuario.value.trim();
            errorDiv2.style.display = 'none';

            // Validar si el correo es válido
            if (!isValidEmail(correo)) {
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'El Correo es inválido.';
                formulario.usuario.style.border = '2px solid red';
                return;
            } else {
                errorDiv.style.display = 'none';
                formulario.usuario.style.border = '';
            }

            /******************** PARA EJECUTAR EL LOADER ************************/
            // Deshabilitar el formulario y el botón de envío
            const inputs = formulario.querySelectorAll('input, button'); // Selecciona todos los inputs y el botón
            
            inputs.forEach(input => input.disabled = true); // Deshabilitar todos los campos y el botón
            errorDiv2.style.display = 'block';  
            errorDiv2.textContent = 'Procesando, espere un momento...';

            // Mostrar el overlay y el loader
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('loader').style.display = 'block';
            /****************************************************************/

            setTimeout(function() { // Simular el procesamiento de datos
                // Enviar los datos al servidor para registrar el usuario
                fetch(`${BASE_URL}App/Models/mensaje.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(correo) //convertir un objeto JavaScript (data) en una cadena JSON
                })
                .then(response => response.json()) //convertir la respuesta a un objeto JSON
                .then(data => {
                    if (data.success) { 
                        //ocultamos los campos parra mostrar solo mensaje
                        document.getElementById('rest').style.display = 'none';
                        document.getElementById('user').style.display = 'none';
                        document.getElementById('clatemp').style.display = 'none';

                        successDiv.style.display = 'block';
                        successDiv.classList.add('title-cent');
                        successDiv.innerHTML = data.confirmado;

                        // Ocultar el overlay y el loader después de procesar
                        document.getElementById('overlay').style.display = 'none';
                        document.getElementById('loader').style.display = 'none';
                        // Habilitar el formulario y el botón de envío
                        inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón
                        
                        formulario.reset(); // Limpiar el formulario
                        errorDiv2.style.display = 'none';
 
                        
                    } else {
                        // Ocultar el overlay y el loader después de procesar
                        document.getElementById('overlay').style.display = 'none';
                        document.getElementById('loader').style.display = 'none';
                        // Habilitar el formulario y el botón de envío
                        inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón

                        errorDiv2.style.display = 'block';
                        //errorDiv2.innerHTML = '<br>' + data.error; // renderizo para que antes haga un salto de linea
                        errorDiv2.textContent = data.error;
                        successDiv.style.display = 'none';
                        emailInput.value = '';
                    }
                })
                .catch(error => {
                    // Ocultar el overlay y el loader después de procesar
                    document.getElementById('overlay').style.display = 'none';
                    document.getElementById('loader').style.display = 'none';
                    // Habilitar el formulario y el botón de envío
                    inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón

                    errorDiv2.style.display = 'block';
                    //errorDiv2.textContent = 'Ocurrió un error al procesar el registro.'; //modo produccion
                    //errorDiv2.textContent = 'Error al procesar el registro: ' + error; //modo desarrollo
                    errorDiv2.innerHTML = 'Error al procesar el registro <br>' + error; //modo desarrollo
                    emailInput.value = '';
                    successDiv.style.display = 'none';
                });
            }, 3000); // Simula un tiempo de procesamiento de 3 segundos
        });
    }
});

///////////////////////////////////////////////////////////////////////////
//////////////////////// REGISTRAR LOGIN //////////////////////////////////

/* El script se ejecuta después de que el DOM esté completamente cargado, 
es decir, después de que se hayan cargado todos los elementos HTML. */
document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('miFormulario');
    const errorDiv = document.getElementById('errorDiv');
    const errorDiv2 = document.getElementById('errorDiv2');
    const successDiv = document.getElementById('successDiv');
    const repetirClaveInput = document.getElementById('pass2');

    if (formulario) {
        formulario.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene el envío del formulario

            const correo = formulario.correo.value.trim();
            const clave = formulario.clave.value.trim();
            const clave2 = formulario.clave2.value.trim();
            errorDiv2.style.display = 'none';

            // Validar si el correo es válido
            if (!isValidEmail(correo)) {
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'El Correo es inválido.';
                formulario.correo.style.border = '2px solid red';
                return;
            } else {
                errorDiv.style.display = 'none';
                formulario.correo.style.border = '';
            }

            // Validar si la clave tiene menos de 6 caracteres
            if (clave.length < 6) {
                errorClave.style.display = 'block';
                errorClave.textContent = 'Debe tener mas de 6 caracteres.';
                formulario.clave.style.border = '2px solid red';
                return;
            } else {
                errorClave.style.display = 'none';
                formulario.clave.style.border = '';
            }

            // Validar si las claves coinciden
            if (clave !== clave2) {
                errorClave2.style.display = 'block';
                errorClave2.textContent = 'Las claves no coinciden.';
                formulario.clave.style.border = '2px solid red';
                formulario.clave2.style.border = '2px solid red';
                return;
            } else {
                errorClave2.style.display = 'none';
                formulario.clave.style.border = '';
                formulario.clave2.style.border = '';
            }

            /******************** PARA EJECUTAR EL LOADER ************************/
            // Deshabilitar el formulario y el botón de envío
            const inputs = formulario.querySelectorAll('input, button'); // Selecciona todos los inputs y el botón
            
            inputs.forEach(input => input.disabled = true); // Deshabilitar todos los campos y el botón

            // Mostrar el overlay y el loader
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('loader-reg').style.display = 'block';
            /****************************************************************/

            setTimeout(function() { // Simular el procesamiento de datos

                /* Enviar datos al servidor y recibir una respuesta sin recargar la pagina */
                const data = { // Crear un objeto con los datos del formulario
                    correo: correo,
                    clave: clave,
                    clave2: clave2
                };
                // Enviar los datos al servidor para registrar el usuario
                fetch(`${BASE_URL}App/Models/valida_registro.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data) //convertir un objeto JavaScript (data) en una cadena JSON
                })
                .then(response => response.json()) //convertir la respuesta a un objeto JSON
                .then(data => {
                    if (data.success) { 
                        /*successDiv.style.display = 'block';
                        successDiv.textContent = 'Registro exitoso. ' + data.user;*/
                        const id = data.user;

                        // Ocultar el overlay y el loader después de procesar
                        document.getElementById('overlay').style.display = 'none';
                        document.getElementById('loader-reg').style.display = 'none';
                        // Habilitar el formulario y el botón de envío
                        inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón
                        
                        formulario.reset(); // Limpiar el formulario

                        // Redirijo pasando ID con una URL amigable
                        window.location.href = `${BASE_URL}verificar/`+ id; 
                        
                    } else {
                        // Ocultar el overlay y el loader después de procesar
                        document.getElementById('overlay').style.display = 'none';
                        document.getElementById('loader-reg').style.display = 'none';
                        // Habilitar el formulario y el botón de envío
                        inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón

                        errorDiv2.style.display = 'block';
                        //errorDiv2.innerHTML = '<br>' + data.error; // renderizo para que antes haga un salto de linea
                        errorDiv2.textContent = data.error;
                        successDiv.style.display = 'none';
                        repetirClaveInput.value = '';
                    }
                })
                .catch(error => {
                    // Ocultar el overlay y el loader después de procesar
                    document.getElementById('overlay').style.display = 'none';
                    document.getElementById('loader-reg').style.display = 'none';
                    // Habilitar el formulario y el botón de envío
                    inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón

                    errorDiv2.style.display = 'block';
                    errorDiv2.textContent = 'Ocurrió un error al procesar el registro.'; //modo produccion
                    //errorDiv2.textContent = 'Error al procesar el registro: ' + error; //modo desarrollo
                    //errorDiv2.innerHTML = 'Error al procesar el registro <br>' + data.error; //modo desarrollo
                    repetirClaveInput.value = '';
                    successDiv.style.display = 'none';
                });
            }, 3000); // Simula un tiempo de procesamiento de 3 segundos
        });
    }
});

function validaruser() {
    const emailInput = document.getElementById('user');
    const errorEmail = document.getElementById('errorEmail');
    const formulario = document.getElementById('miFormulario');
    const emailRegex = /^[^\s@]+@[^\s@]+\.com$/;  //expresiones regulares

    // Elimina espacios al inicio y final
    emailInput.value = emailInput.value.trim();

    //Muestra en linea al escribir, si el correo no es valido
    emailInput.addEventListener('input', () => {
        if (!emailRegex.test(emailInput.value)) {
            emailInput.style.border = '2px solid red';
            errorEmail.textContent = 'El email no es válido.';
        } else {
            emailInput.style.border = '';
            errorEmail.textContent = '';
        }
    });
    formulario.addEventListener('submit', function(event) {
            if (!emailRegex.test(emailInput.value)) {
                event.preventDefault(); // Previene el envío del formulario
            }
    });
}  

function validarClave() {
    const clave = document.getElementById('pass');
    clave.value = clave.value.trim();

    if (clave.value.length >= 6) {
        errorClave.textContent = '';
        clave.style.border = '';
    }
}

/* Funcion que se activa si el correo esta errado al enviar el formulario, 
   cuando el usuario escribe correctamente el correo, el borde y el msj rojo desaparece */
function validarcorr() {
    const emailInput = document.getElementById('corr');
    const errorEmail = document.getElementById('errorDiv');

    // Elimina espacios al inicio y final
    emailInput.value = emailInput.value.trim();

    if (isValidEmail(emailInput.value)) {
        emailInput.style.border = '';
        errorEmail.style.display = 'none';
    }
}

function isValidEmail(email) {
    // Expresión regular para validar emails
    return /^[^\s@]+@[^\s@]+\.com$/.test(email);
}

document.addEventListener('DOMContentLoaded', function() {
    const inputCorreo = document.getElementById('corr');
    if (inputCorreo) {
        inputCorreo.addEventListener('input', validarcorr);
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const inputPass = document.getElementById('pass');
    if (inputPass) {
        inputPass.addEventListener('input', validarClave);
    }
});

////////////////////////////////////////////////////////////////////////////
////////////////////// CODIGO DE VERIFICACION //////////////////////////////

// Ejecutar la función al cargar el DOM
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('cod-verif-page')) {
        if (typeof iniciarContador === 'function') {
            iniciarContador();
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const inputCod = document.getElementById('cod');
    if (inputCod) {
        // Validar solo números en el input del código
        inputCod.addEventListener('input', function(event) {
            // Quitar espacios al inicio y final
            event.target.value = event.target.value.trim();
            // Obtener el valor actual del campo de entrada
            let inputValue = event.target.value;

            // Expresión regular para permitir solo números
            let soloNumeros = /^[0-9]*$/;

            // Verificar si el valor coincide con la expresión regular
            if (!soloNumeros.test(inputValue)) {
                // Si no coincide, eliminar el último carácter ingresado
                event.target.value = inputValue.slice(0, -1);
            }
            
            // Quitar el error si el codigo tiene 5 caracteres
            if (inputCod.value.length == 5) {
                errorDiv2.textContent = '';
            } 
        });     
    }
});

function loader_redi(element) {
    /******************** PARA EJECUTAR EL LOADER ************************/
    // Deshabilitar el formulario y el botón de envío
    const formulario = document.getElementById(element);
    const inputs = formulario.querySelectorAll('input, button'); // Selecciona todos los inputs y el botón
    
    inputs.forEach(input => input.disabled = true); // Deshabilitar todos los campos y el botón

    // Mostrar el overlay y el loader
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('loader').style.display = 'block';
    /****************************************************************/

    setTimeout(function() { // Simular el procesamiento de datos
        // Ocultar el overlay y el loader después de procesar
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('loader').style.display = 'none';
        // Habilitar el formulario y el botón de envío
        inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón
    },3000);
}

/* CONTADOR PARA EL CODIGO DE VERIFICACION */
/*******************************************/
// Tiempo en segundos para el contador
let tiempoLimite = 300; // 5 minutos
let contadorActivo = true; // Estado del contador

document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('for-cod');
    if (formulario) {
        formulario.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene el envío del formulario

            const codver = formulario.cod.value.trim();
            const codtok = formulario.codeval.value.trim();
            const errorDiv2 = document.getElementById('errorDiv2');
            const successDiv = document.getElementById('successDiv');
            const codever = document.getElementById('cod');
            const contadorElement = document.getElementById('contador');

            // Validar si el código tiene 5 caracteres
            if (codver.length !== 5) {
                successDiv.style.display = 'none';
                errorDiv2.style.display = 'block';
                errorDiv2.textContent = 'El codigo debe tener 5 caracteres.';
                return;
            } else {
                errorDiv2.style.display = 'none';
            }
        
            fetch(`${BASE_URL}App/Models/valida_token.php`,{
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ codv: codver, codt: codtok }) // Enviar el código como JSON
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    //console.log(data); // Mostrar datos
                    errorDiv2.style.display = 'none'; 
                    successDiv.style.display = 'block';
                    successDiv.textContent = data.message;
                    codever.value = ''; 
                    contadorActivo = false; // Detener el contador
                    contadorElement.textContent = '';
                    //loader_redi('for-cod');

                    /******************** PARA EJECUTAR EL LOADER ************************/
                    // Deshabilitar el formulario y el botón de envío
                    const formulario = document.getElementById('for-cod');
                    const inputs = formulario.querySelectorAll('input, button'); // Selecciona todos los inputs y el botón

                    inputs.forEach(input => input.disabled = true); // Deshabilitar todos los campos y el botón

                    // Mostrar el overlay y el loader
                    document.getElementById('overlay').style.display = 'block';
                    document.getElementById('loader').style.display = 'block';
                    /****************************************************************/

                    setTimeout(function() { // Simular el procesamiento de datos
                        // Ocultar el overlay y el loader después de procesar
                        document.getElementById('overlay').style.display = 'none';
                        document.getElementById('loader').style.display = 'none';
                        // Habilitar el formulario y el botón de envío
                        inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón

                        // Mostrar el modal
                        const modal = document.getElementById('successModal');
                        modal.style.display = 'block';

                        // Ocultar el formulario
                        document.querySelector('.contenedor').style.display = 'none';

                        // Cerrar el modal al hacer clic en el botón de cerrar
                        const closeBtn = document.querySelector('.close-btn');
                        closeBtn.addEventListener('click', function () {
                            modal.style.display = 'none';
                            // Redirijo al index
                            window.location.href = `${BASE_URL}`;
                        });
                    },2000);

                } else {
                    errorDiv2.style.display = 'block';  
                    errorDiv2.textContent = data.error;
                    successDiv.style.display = 'none';
                    codever.value = ''; 
                    console.error(data.error); // Mostrar error
                    if (data.error == 'Token no verificado') {
                        loader_redi('for-cod');
                        window.location.href = `${BASE_URL}`;
                    }
                }
            })
            .catch(error => {
                //console.error('Error:', error);
                errorDiv2.style.display = 'block';
                errorDiv2.textContent = error; //modo produccion
                successDiv.style.display = 'none';
            });
        });
    }
});

// Función para validar tiempo de espera al ingresar el codigo
function iniciarContador() {
    const contadorElement = document.getElementById('contador');
    const codetok = document.getElementById('codeval');

    //Capturar id del token
    const data = {
        codigo: codetok.value
    };
    fetch(`${BASE_URL}App/Models/iniciar_contador.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Iniciar intervalo para actualizar el contador visualmente
            let intervalo = setInterval(() => {
                if (tiempoLimite > 0 && contadorActivo) {
                    const minutos = Math.floor(tiempoLimite / 60);
                    const segs = tiempoLimite % 60;
                    contadorElement.textContent = `Tiempo restante: ${minutos.toString().padStart(2, '0')}:${segs.toString().padStart(2, '0')}`;
                    tiempoLimite--;
                } else {
                    clearInterval(intervalo);
                    if (contadorActivo) {
                        contadorElement.textContent = "Tiempo agotado, redireccionando....";
                        //mensajeElement.textContent = "El código ha expirado. Ya no es válido.";
                        contadorActivo = false; // Detener el contador
                        fetch(`${BASE_URL}App/Models/borra_token.php`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({codigo: codetok.value})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) { 
                                loader_redi('for-cod');
                                window.location.href = `${BASE_URL}`; 
                            } 
                        });        
                    }
                }
            }, 1000); // Actualizar cada segundo

        } else {
            const veri = data.error;
            if (veri == 'con fecha') {
                verificarContador(codetok.value); 
            } else {
                errorDiv2.style.display = 'block';  
                errorDiv2.textContent = data.error;
                successDiv.style.display = 'none';
            }
        }
    })
    .catch(error => {
        errorDiv2.style.display = 'block';
        errorDiv2.textContent = 'Ocurrió un error al iniciar el contador.'; //modo produccion
        successDiv.style.display = 'none';
    });
    
}
function verificarContador(code) {
    const data = {
        codigo: code,
        tempo: tiempoLimite
    };
    const contadorElement = document.getElementById('contador');

    fetch(`${BASE_URL}App/Models/verificar_contador.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Mostrar mensaje o redirigir
        window.location.href = `${BASE_URL}`;
      } else {
        if (data.tiempoTranscurrido) {
            // Retomar el contador con el tiempo transcurrido
            let intervalo = setInterval(() => {
                tempo = tiempoLimite - data.tiempoTranscurrido; 
                const minutos = Math.floor(tempo / 60);
                const segs = tempo % 60;
                if (tempo > 0 && contadorActivo) {
                    //contadorElement.textContent = `Tiempo restante: ${tempo} segundos`;
                    contadorElement.textContent = `Tiempo restante: ${minutos.toString().padStart(2, '0')}:${segs.toString().padStart(2, '0')}`;
                    tiempoLimite--;
                } else {
                    clearInterval(intervalo);
                    if (contadorActivo) {
                        contadorElement.textContent = "Tiempo agotado, redireccionando....";
                        contadorActivo = false; // Detener el contador
                        fetch(`${BASE_URL}App/Models/borra_token.php`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({codigo: code})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                loader_redi('for-cod');
                                window.location.href = `${BASE_URL}`;
                            }
                        })  
                    }
                }
            }, 1000); // Actualizar cada segundo
            contadorElement.textContent = '';
        } else {
            errorDiv2.style.display = 'block';  
            errorDiv2.textContent = data.error;
            successDiv.style.display = 'none';
        }
    }
    })
    .catch(error => {
        errorDiv2.style.display = 'block';
        errorDiv2.textContent = 'Ocurrió un error al iniciar el contador.'; //modo produccion
        successDiv.style.display = 'none';
    });
}

////////////////////////////////////////////////////////////////////////////
////////////////////////////////// DASHBOARD ///////////////////////////////

// Guardar la sesión en sessionStorage
const secUser = document.getElementById('sec_user');
if (secUser) {
    const userSession = secUser.getAttribute('data-valoruser');
    sessionStorage.setItem('userSession', userSession); // Guardar en sessionStorage 
}

//console.log(sessionStorage.getItem('userSession'));
// Recuperar la sesión desde sessionStorage
const storedUserSession = sessionStorage.getItem('userSession');

function mostrarModalLogout() {
    document.getElementById('logoutModal').style.display = 'flex';
}

function ocultarModalLogout() {
    document.getElementById('logoutModal').style.display = 'none';
}

// Cerrar la sesion
document.addEventListener('DOMContentLoaded', function() {
    const confirLog = document.getElementById('confirmLogout');
    if (confirLog) {
        confirLog.addEventListener('click', function() {
            sessionStorage.clear(); // Limpiar sessionStorage
            ocultarModalLogout();
            
            /******************** PARA EJECUTAR EL LOADER ************************/
            // Mostrar el overlay y el loader
            document.getElementById('loader-message').textContent = 'Cerrando sesión, Vuelva pronto...';
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('loader').style.display = 'block';
            /****************************************************************/

            setTimeout(function() { // Simular el procesamiento de datos
                // Ocultar el overlay y el loader después de procesar
                document.getElementById('overlay').style.display = 'none';
                document.getElementById('loader').style.display = 'none';
            
                window.location.href = `${BASE_URL}App/Models/logout.php`;
            },3000);
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const cancelLog = document.getElementById('cancelLogout');
    if (cancelLog) {
        cancelLog.addEventListener('click', function() {
            ocultarModalLogout();
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.contacts-slider');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
  
    // Inicializar el slider
    if (slider) {
        generateContactCards();
        
        // Navegación del slider
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                slider.scrollBy({ left: -300, behavior: 'smooth' });
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                slider.scrollBy({ left: 300, behavior: 'smooth' });
            });
        }
    }
});

let recor = []; // Variable global para almacenar los contactos

function generateContactCards() {
    const slider = document.querySelector('.contacts-slider');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    const select = document.getElementById('categoriaSelect');

    fetch(`${BASE_URL}App/Models/valida_contacto.php`, {    
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        },
        body: JSON.stringify({ action: 'per_slider' }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error en la respuesta del servidor: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            recor = data.data.conscont;
            const categoriasUnicas = [...new Set(recor.map(item => item.categoria))];

            // Llenar el combo de busqueda con categorías
            select.innerHTML = '<option value="">Todas las categorías</option>';
            categoriasUnicas.forEach(cat => {
                if (cat && cat.trim() !== '') {
                    const option = document.createElement('option');
                    option.value = cat;
                    option.textContent = cat;
                    select.appendChild(option);
                }
            });
            // Mostrar todos los contactos al inicio
            mostrarContactos(recor);

            // Evento para filtrar por categoría
            select.onchange = function() {
                const categoriaSeleccionada = select.value;
                const filtrados = categoriaSeleccionada
                    ? recor.filter(item => item.categoria === categoriaSeleccionada)
                    : recor;
                mostrarContactos(filtrados);
            };

        } else {
            if (data.error === 'no existe') {
                slider.innerHTML = '<p class="text-conta"><em>Para ver los contactos, Completa tu perfil.<span style="font-size: 1em;">👋</span></em></p>';
            } else {
                slider.innerHTML = '<p class="text-conta">No hay contactos que mostrar</p>';
            }
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
            select.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
        slider.innerHTML = '<p>Error al cargar los contactos</p>';
    });
}

// Función para mostrar los contactos en el slider
function mostrarContactos(contactos) {

    const slider = document.querySelector('.contacts-slider');
    slider.innerHTML = contactos.map(item => {
        return `
            <div class="contact-card">
                <img src="${item.avatar}" alt="${item.nombre}" class="contact-avatar">
                <h3 class="contact-name">${item.nombre}</h3>
                <p class="contact-position">${item.categoria}</p>
                <p class="contact-info">📧 ${item.email}</p>
                <p class="contact-info">${item.telefono ? `📞 ${item.telefono}` : ''}</p><br>
                <button id="seguir-btn" class="follow-btn" data-state="follow" data-user="${item.email}">
                    <span class="follow-text">Seguir</span>
                    <span class="following-text" hidden>Siguiendo</span>
                </button>
                <div class="rating-stars">
                    <div class="stars-container">
                        ${generarEstrellas(item.total_seguidores)}
                    </div>
                    <span class="rating-value">4.5</span>
                </div>
            </div>
        `;
    }).join('');
}

// Función auxiliar para generar las estrellas (puedes adaptar la lógica)
function generarEstrellas(numseg) {
    const maxStars = 5;
    let coloest = 0;
    if (numseg === 0) {
        coloest = 0;
    } else if (numseg === 5) {
        coloest = 2;
    } else if (numseg > 5 && numseg <= 10) {
        coloest = 3;
    } else if (numseg > 10) {
        coloest = 5;
    } else {
        coloest = 1;
    }
    let starsHTML = '';
    for (let i = 0; i < maxStars; i++) {
        starsHTML += `<i class="fas fa-star" style="color: ${i < coloest ? 'gold' : '#ddd'};"></i>`;
    }
    return starsHTML;
}

document.addEventListener('DOMContentLoaded', function() {
    const menuButtons = document.querySelectorAll('.menu-btn');
    const mainContent = document.querySelector('.main-content'); // Seleccionar el elemento <main>
    
    menuButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            menuButtons.forEach(btn => btn.classList.remove('active'));
            
            // Agregar clase active al botón clickeado
            this.classList.add('active');
            
            // Aquí puedes agregar lógica para cargar contenido dinámico
            const buttonText = this.querySelector('span').textContent;

            // Redirigir si el botón es "Inicio"
            if (buttonText === 'Inicio') {

                //mainContent.style.display = 'block'; // Mostrar el contenido principal
                window.location.href = `${BASE_URL}dashboard`;
            
            } else if (buttonText === 'Contactos') {

                // Limpiar el contenido actual del main-content
                mainContent.innerHTML = '';

                fetch (`${BASE_URL}App/Views/contactos.php`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // para el navegador
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar el archivo PHP: ${response.status}`);
                    }
                    return response.text(); // Obtener el contenido del archivo como texto
                })
                .then(html => {
                    mainContent.innerHTML = html; // Insertar el contenido en el mainContent
                    iniciarPantallaContactos() 
                })
                .catch(error => {
                    console.error('Error al cargar el archivo PHP:', error);
                    mainContent.innerHTML = '<p>Error al cargar el contenido.</p>'; // Mostrar un mensaje de error
                });

            } else if (buttonText === 'Productos') {

                // Limpiar el contenido actual del main-content
                mainContent.innerHTML = '';

                // Enviar la solicitud para establecer el acceso a productos
                fetch(`${BASE_URL}App/Views/productos.php`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // para el navegador
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar el archivo PHP: ${response.status}`);
                    }
                    return response.text(); // Obtener el contenido del archivo como texto
                })
                .then(html => {
                    mainContent.innerHTML = html; // Insertar el contenido en el mainContent
                    iniciarPantallaProductos();
                })
                .catch(error => {
                    console.error('Error al cargar el archivo PHP:', error);
                    mainContent.innerHTML = '<p>Error al cargar el contenido.</p>'; // Mostrar un mensaje de error
                });
                
            } else if (buttonText === 'Configuración') {

                // Limpiar el contenido actual del main-content
                mainContent.innerHTML = '';

                fetch (`${BASE_URL}App/Views/actualiza_clave.php`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // para el navegador
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar el archivo PHP: ${response.status}`);
                    }
                    return response.text(); // Obtener el contenido del archivo como texto
                })
                .then(html => {
                    mainContent.innerHTML = html; // Insertar el contenido en el mainContent
                    iniciarPantallaActualizarClave();
                })
                .catch(error => {
                    console.error('Error al cargar el archivo PHP:', error);
                    mainContent.innerHTML = '<p>Error al cargar el contenido.</p>'; // Mostrar un mensaje de error
                });
                
            } else if (buttonText === 'Cerrar Sesión') {
                mostrarModalLogout();
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const editLink = document.querySelector('.edit-link');
    const mainContent = document.querySelector('.main-content'); // Seleccionar el elemento <main>
    const userEmail = storedUserSession;
    // Actualizar el elemento h2
    const emailElement = document.querySelector('.user-email');
    if(emailElement) {
        emailElement.firstChild.textContent = userEmail;
    }

    if (editLink) {
        editLink.addEventListener('click', function(e) {
            e.preventDefault(); // Evitar la redirección predeterminada

            // Limpiar el contenido actual del main-content
            mainContent.innerHTML = '';

            // Realizar una solicitud al archivo PHP externo
            fetch(`${BASE_URL}App/Views/perfil.php`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // para el navegador
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error al cargar el archivo PHP: ${response.status}`);
                }
                return response.text(); // Obtener el contenido del archivo como texto
            })
            .then(html => {
                mainContent.innerHTML = html; // Insertar el contenido en el mainContent

                inicializarVariables();
                // Llamar a la función para cargar el perfil dinámicamente
                cargarPerfilDinamico(); 
                // Llamar a las funciones de inicialización de validar.js
                inicializarPantallaPerfil();

                // Manejar el botón de cancelar
                const cancelButton = document.querySelector('.cancel-btn');
                if (cancelButton) {
                    cancelButton.addEventListener('click', function() {
                        // Redirijo al dashboard
                        window.location.href = `${BASE_URL}dashboard`;
                    });
                }
            })
            .catch(error => {
                console.error('Error al cargar el archivo PHP:', error);
                mainContent.innerHTML = '<p>Error al cargar el contenido.</p>'; // Mostrar un mensaje de error
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(event) {
        if (event.target.closest('#seguir-btn')) { // Detecta clics en el botón o sus hijos
        //if (event.target && event.target.id === 'seguir-btn') {
        const seguirBtn = event.target.closest('#seguir-btn');
        const seguidoId = seguirBtn.dataset.user;
        const currentState = seguirBtn.getAttribute('data-state');
        const newState = currentState === 'follow' ? 'following' : 'follow';
        
        fetch(`${BASE_URL}App/Models/valida_contacto.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
            seguidoId: seguidoId, 
            //correo: userSec, 
            correo: storedUserSession, //usuario
            action: 'pos_seguir',
            estado: newState
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                seguirBtn.setAttribute('data-state', newState);

                // Espera 1 segundo antes de recargar los contactos
                setTimeout(() => {
                    generateContactCards();
                }, 1000);

            } else {
                console.error(data.error);
            }
        });
        }
    });
});

///////////////////////////////////////////////////////////////////////////
//////////////////////// PERFIL ///////////////////////////////////////////

// Objeto para almacenar las variables
const perfilVari = {};
// Inicializar las variables al cargar la página
function inicializarVariables() {
    // Inicializar las variables y almacenarlas en el objeto
    perfilVari.nombre = document.querySelector('#edit-name');
    perfilVari.categoria = document.querySelector('#edit-position');
    perfilVari.descripcion = document.querySelector('#edit-descrip');
    perfilVari.otraCategoria = document.querySelector('#edit-otra');
    perfilVari.telefono = document.querySelector('#text-phone');
    perfilVari.extTelefono = document.querySelector('#edit-phone');
    perfilVari.estado = document.querySelector('#edit-est');
    perfilVari.municipio = document.querySelector('#edit-mun');
    perfilVari.parroquia = document.querySelector('#edit-parr');
    perfilVari.direccion = document.querySelector('#edit-direc');
    perfilVari.horarioApertura = document.querySelector('#edit-horario-apertura');
    perfilVari.horarioCierre = document.querySelector('#edit-horario-cierre');
    perfilVari.sitioWeb = document.querySelector('#edit-website');
    perfilVari.labelWeb = document.querySelector('#label-web');
    perfilVari.noWebsiteCheckbox = document.querySelector('#no-website');
    perfilVari.editButton = document.querySelector('#actu-btn');
    perfilVari.avatarInput = document.querySelector('#edit-avatar');
    perfilVari.avatarPreview = document.querySelector('#avatar-preview');
    perfilVari.changeAvatarBtn = document.querySelector('#change-avatar');
}

// funcion para abrir el modal de avatar
function AbrirModalAvatar() {
    const errorDiv2 = document.querySelector('#error-message'); // Mensaje de error
    // Abrir el selector de archivos al hacer clic en el botón
    perfilVari.changeAvatarBtn.addEventListener('click', function () { 
        perfilVari.avatarInput.click();
    });
    let selectedFile = null; // Variable para almacenar el archivo seleccionado
    // Validar y actualizar la vista previa de la imagen seleccionada
    perfilVari.avatarInput.addEventListener('change', function () {
        const file = perfilVari.avatarInput.files[0];
        if (file) {
            // Validar el tipo de archivo
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validImageTypes.includes(file.type)) {
                errorDiv2.textContent = 'Por favor selecciona un archivo de imagen válido (JPEG, PNG, GIF).';
                errorDiv2.style.display = 'block';
                perfilVari.avatarInput.value = ''; // Limpiar el campo de archivo
                selectedFile = null; // Limpiar la variable de archivo seleccionado
                return;
            }
            // Validar el tamaño del archivo (máximo 2 MB)
            const maxSizeInBytes = 2 * 1024 * 1024; // 2 MB
            if (file.size > maxSizeInBytes) {
                errorDiv2.textContent = 'El archivo es demasiado grande. El tamaño máximo permitido es de 2 MB.';
                errorDiv2.style.display = 'block';
                perfilVari.avatarInput.value = ''; // Limpiar el campo de archivo
                selectedFile = null; // Limpiar la variable de archivo seleccionado
                return;
            }
  
            // Si pasa las validaciones, guardar el archivo en la variable
            selectedFile = file;
  
            // actualizar la vista previa
            errorDiv2.style.display = 'none'; // Ocultar el mensaje de error
            const reader = new FileReader();
            reader.onload = function (e) {
                perfilVari.avatarPreview.src = e.target.result; // Actualizar la imagen de vista previa
            };
            reader.readAsDataURL(file); 
        }
    });
}

// Cargar las categorias al cargar la página
function cargarCategorias(categoriaUsuarioId, esEdicion) {
    fetch(`${BASE_URL}App/Models/carga_perfil.php`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({accion: 'categoria'})
    }) 
    .then(response => response.json())
    .then(data => {
        // Limpiar el select de categorias
        perfilVari.categoria.innerHTML = '<option value="" disabled selected>Selecciona tu profesión</option>';
        // Verificar si hay categorias disponibles
        if (data.length === 0) {
            perfilVari.categoria.innerHTML = '<option value="">No hay categorias disponibles</option>';
            return;
        }
        // Agrupar categorías por sector
        let currentSector = '';
        data.forEach(categoria => {
            // Si el sector cambia, crear un nuevo grupo
            if (categoria.sector !== currentSector) {
                if (currentSector !== '') {
                    perfilVari.categoria.appendChild(document.createElement('optgroup')); // Cerrar el grupo anterior
                }
                const optgroup = document.createElement('optgroup');
                optgroup.label = categoria.sector; // Asignar el sector como etiqueta
                perfilVari.categoria.appendChild(optgroup);
                currentSector = categoria.sector;
            }
            // Crear la opción dentro del grupo
            const option = document.createElement('option');
            option.value = categoria.codigo;
            option.textContent = categoria.nombre;
            perfilVari.categoria.appendChild(option); // Agregar la opción al grupo actual
        });

        // Seleccionar el estado del usuario
        if (categoriaUsuarioId) {
            if (esEdicion) {
                perfilVari.categoria.value = categoriaUsuarioId;
            } else {
                perfilVari.categoria.textContent = categoriaUsuarioId;
            }
        }    
    })
    .catch(error => console.error('Error al cargar las categorias:', error));
}

// Cargar codigos de area al cargar la página
function cargarCodigos(extensionUsuarioId) {
    fetch(`${BASE_URL}App/Models/carga_perfil.php`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({accion: 'codarea'})
    })
    .then(response => response.json())
    .then(data => {
        // Limpiar el select de codigos
        perfilVari.extTelefono.innerHTML = '<option value="" disabled selected>Selecciona código área</option>';
        // Verificar si hay codigos disponibles
        if (data.length === 0) {
            perfilVari.extTelefono.innerHTML = '<option value="">No hay códigos disponibles</option>';
            return;
        }
        data.forEach(codigo => {
            const option = document.createElement('option');
            option.value = codigo.id;
            option.textContent = codigo.codigo;
            perfilVari.extTelefono.appendChild(option);
        });
        // Seleccionar el código del usuario
        if (extensionUsuarioId) {
            perfilVari.extTelefono.value = extensionUsuarioId; // Seleccionar el código por su ID
            if (!perfilVari.extTelefono.value) {
                console.warn('El código del usuario no coincide con ninguna opción del combo.');
            }
        }
    })
    .catch(error => console.error('Error al cargar los codigos:', error));
}

// Cargar los estados al cargar la página
function cargarEstadosYSeleccionar(estadoUsuarioId) {
    fetch(`${BASE_URL}App/Models/carga_perfil.php`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({accion: 'estados'})
    })
    .then(response => response.json())
    .then(data => {
        // Limpiar el select de estados
        perfilVari.estado.innerHTML = '<option value="" disabled selected>Selecciona el estado</option>';
        // Verificar si hay estados disponibles
        if (data.length === 0) {
            perfilVari.estado.innerHTML = '<option value="">No hay estados disponibles</option>';
            return;
        }
        data.forEach(estado => {
            const option = document.createElement('option');
            option.value = estado.id_estado;
            option.textContent = estado.estado;
            perfilVari.estado.appendChild(option);
        });

        // Seleccionar el estado del usuario
        if (estadoUsuarioId) {
            perfilVari.estado.value = estadoUsuarioId; // Seleccionar el estado por su ID
            if (!perfilVari.estado.value) {
                console.warn('El estado del usuario no coincide con ninguna opción del combo.');
            }
        }
    })
    .catch(error => console.error('Error al cargar los estados:', error));
}

// Cargar los municipios al seleccionar un estado
function cargarMunicipiosYSeleccionar(estadoId, municipioId) {
    fetch(`${BASE_URL}App/Models/carga_perfil.php`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ estado_id: estadoId, accion: 'municipios'})
    })
    .then(response => response.json())
    .then(data => {
        // Limpiar el select de municipios
        perfilVari.municipio.innerHTML = '<option value="" disabled selected>Selecciona el municipio</option>';
        // Verificar si hay municipios disponibles
        if (data.length === 0) {
            perfilVari.municipio.innerHTML = '<option value="">No hay municipios disponibles</option>';
            return;
        }
        data.forEach(municipio => {
            const option = document.createElement('option');
            option.value = municipio.id_municipio;
            option.textContent = municipio.municipio;
            perfilVari.municipio.appendChild(option);
        });

        // Seleccionar el municipio del usuario
        if (municipioId) {
            perfilVari.municipio.value = municipioId; // Seleccionar el municipio por su ID
            if (!perfilVari.municipio.value) {
                console.warn('El municipio del usuario no coincide con ninguna opción del combo.');
            }
        }
    })
    .catch(error => console.error('Error al cargar los municipios:', error));
}

// Cargar las parroquias al seleccionar un municipio
function cargarParroquiasYSeleccionar(municipioId, parroquiaId) {
    fetch(`${BASE_URL}App/Models/carga_perfil.php`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ municipio_id: municipioId, accion: 'parroquias'})
    })
    .then(response => response.json())
    .then(data => {
        // Limpiar el select de parroquias
        perfilVari.parroquia.innerHTML = '<option value="" disabled selected>Selecciona la parroquia</option>';
        // Verificar si hay parroquias disponibles
        if (data.length === 0) {
            perfilVari.parroquia.innerHTML = '<option value="">No hay parroquias disponibles</option>';
            return;
        }
        data.forEach(parroquia => {
            const option = document.createElement('option');
            option.value = parroquia.id_parroquia;
            option.textContent = parroquia.parroquia;
            perfilVari.parroquia.appendChild(option);
        });
        // Seleccionar la parroquia del usuario
        if (parroquiaId) {
            perfilVari.parroquia.value = parroquiaId; // Seleccionar la parroquia por su ID
            if (!perfilVari.parroquia.value) {
                console.warn('La parroquia del usuario no coincide con ninguna opción del combo.');
            }
        }
    })
    .catch(error => console.error('Error al cargar las parroquias:', error));
}

// Cargar horarios al cargar la página  
function cargarHorarios(horarioAperturaUsuarioId, horarioCierreUsuarioId) {
    fetch(`${BASE_URL}App/Models/carga_perfil.php`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ accion: 'horarios'})
    })
    .then(response => response.json())
    .then(data => {
        // Limpiar los selects de horarios
        perfilVari.horarioApertura.innerHTML = '<option value="" disabled selected>Selecciona el horario de apertura</option>';
        perfilVari.horarioCierre.innerHTML = '<option value="" disabled selected>Selecciona el horario de cierre</option>';
        
        // Verificar si hay datos de apertura
        if (data.apertura && data.apertura.length > 0) {
            data.apertura.forEach(horario => {
                const option = document.createElement('option');
                option.value = horario.id; // Usar el ID como valor
                option.textContent = horario.hora; // Mostrar la hora como texto
                perfilVari.horarioApertura.appendChild(option);
            });
        } else {
            perfilVari.horarioApertura.innerHTML = '<option value="">No hay horarios de apertura disponibles</option>';
        }
        // Seleccionar el horario apertura del usuario
        if (horarioAperturaUsuarioId) {
            perfilVari.horarioApertura.value = horarioAperturaUsuarioId; // Seleccionar el horario por su ID
            if (!perfilVari.horarioApertura.value) {
                console.warn('El horario de apertura del usuario no coincide con ninguna opción del combo.');
            }
        }

        // Verificar si hay datos de cierre
        if (data.cierre && data.cierre.length > 0) {
            data.cierre.forEach(horario => {
                const option = document.createElement('option');
                option.value = horario.id; // Usar el ID como valor
                option.textContent = horario.hora; // Mostrar la hora como texto
                perfilVari.horarioCierre.appendChild(option);
            });
        } else {
            perfilVari.horarioCierre.innerHTML = '<option value="">No hay horarios de cierre disponibles</option>';
        }
        // Seleccionar el horario de cierre del usuario
        if (horarioCierreUsuarioId) {
            perfilVari.horarioCierre.value = horarioCierreUsuarioId; // Seleccionar el horario por su ID
            if (!perfilVari.horarioCierre.value) {
                console.warn('El horario de cierre del usuario no coincide con ninguna opción del combo.');
            }
        }
    })
    .catch(error => console.error('Error al cargar los horarios:', error));
}

function cargarPerfilDinamico(esEdicion) {
    const formData = new FormData();
    formData.append('ind', 'consultaPerfil');
    // Hacer la solicitud al servidor para obtener los datos del perfil
    fetch(`${BASE_URL}App/Models/valida_perfil.php`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error en la respuesta del servidor: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Verificar si hay datos disponibles
        if (data.message !== 'No existe') {
            // Asignar los valores a los campos del formulario
            if (data.data.avatar) {
                perfilVari.avatarPreview.src = data.data.avatar;
            } 
            if (esEdicion) {
                perfilVari.nombre.value = data.data.nombre || ''; 

                if (data.data.tipocate === 'OTRO') {  
                    cargarCategorias(data.data.tipocate, true);
                    perfilVari.otraCategoria.classList.remove('hidden'); // Mostrar el campo de otra categoria
                    perfilVari.otraCategoria.value = data.data.categoria || '';
                } else {
                    cargarCategorias(data.data.categoria, true);
                }

                perfilVari.descripcion.value = data.data.descripcion || '';
                cargarCodigos(data.data.extension);
                perfilVari.telefono.value = data.data.numero || '';
                perfilVari.direccion.textContent = data.data.direccion || '';
                cargarHorarios(data.data.hora_apertura, data.data.hora_cierre);
                cargarEstadosYSeleccionar(data.data.estado);
                cargarMunicipiosYSeleccionar(data.data.estado, data.data.municipio);
                cargarParroquiasYSeleccionar(data.data.municipio, data.data.parroquia);
                
                perfilVari.sitioWeb.value = data.data.web || '';
                if (data.data.web === '') {
                    perfilVari.noWebsiteCheckbox.checked = true;
                    perfilVari.sitioWeb.disabled = true;
                } else {
                    perfilVari.noWebsiteCheckbox.checked = false;
                    perfilVari.sitioWeb.disabled = false;  
                }
                
            } else { // cuando es vista previa del perfil
                // Quitar border y deshabilitar campos
                document.querySelector('.change-avatar-btn').style.display = 'none'; // Ocultar el botón de cambiar avatar
                document.querySelector('.save-btn').style.display = 'none'; // Ocultar el botón de guardar
                
                const campos = document.querySelectorAll('input, select, textarea');
                campos.forEach(campo => {
                    if (campo.id !== 'edit-avatar' ) {
                        campo.classList.add('input-combo'); // Agregar clase especial para cambiar el estilo
                        campo.style.border = 'none'; // Restaurar el estilo
                        campo.disabled = true; // Deshabilitar el campo
                    }
                });
                document.getElementById('vie-edit-name').textContent = data.data.nombre || '';
                if (data.data.tipocate === 'OTRO') {  
                    document.getElementById('vie-edit-position').textContent = data.data.categoria || ''; 
                } else {
                    document.getElementById('vie-edit-position').textContent = data.data.descate || ''; 
                }
                document.getElementById('vie-edit-descrip').textContent = data.data.descripcion || '';
                document.getElementById('vie-edit-phone').textContent = data.data.telefono || '';
                document.getElementById('vie-edit-direc').textContent = data.data.direccion || '';
                document.getElementById('vie-edit-website').textContent = data.data.web || '';
                document.getElementById('vie-edit-est').textContent = data.data.descest || '';
                document.getElementById('vie-edit-mun').textContent = data.data.descmuni || '';
                document.getElementById('vie-edit-parr').textContent = data.data.descparro || '';
                document.getElementById('vie-edit-horario').textContent = data.data.hora_aper + ' - ' + data.data.hora_cie || '';
            }
        } else { // Cuando es edicion ( usuario nuevo )
            document.getElementById('perfilContainer-edit').style.display= 'block';
            document.getElementById('perfilContainer-view').style.display= 'none';
            // Cargar los selects y ocultar el botón de actualizar
            cargarCategorias();
            cargarCodigos();
            cargarHorarios();
            cargarEstadosYSeleccionar();
            cargarMunicipiosYSeleccionar();
            cargarParroquiasYSeleccionar();
            if (perfilVari.editButton) {
                perfilVari.editButton.style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error al cargar el perfil:', error);
    });
}

function inicializarPantallaPerfil() {
    
    // Validar el formulario al enviar
    const formulario = document.getElementById('edit-form');
    const errorDiv2 = document.querySelector('#error-message'); // Mensaje de error
    const saveButton = document.querySelector('.save-btn');
    const saveHidden = document.querySelector('#accion');
    
    AbrirModalAvatar();

    // Manejar el evento de envío del formulario
    if (formulario) {
        formulario.addEventListener('submit', function (event) {
            event.preventDefault(); // Previene el envío del formulario
            
            let camposVacios = false; // Bandera para verificar si hay campos vacíos
            // Seleccionar todos los inputs, selects y textareas del formulario
            const campos = formulario.querySelectorAll('input, select, textarea');
            campos.forEach(campo => {
                // Excluir campos deshabilitados, de solo lectura, el campo de imagen
                if (campo.disabled || campo.id === 'edit-avatar' ) {
                    campo.style.border = ''; // Restaurar el estilo
                    return; // Saltar la validación de este campo
                }
                // Excluir el campo de otra profesión si no está visible
                if (campo.id === 'edit-otra') {
                    campo.style.border = ''; // Restaurar el estilo
                    return; // Saltar la validación de este campo
                }
                
                // Excluir el campo de sitio web si el checkbox "No tengo sitio web" está marcado
                if (campo.id === 'edit-website' && perfilVari.noWebsiteCheckbox.checked) {
                    campo.style.border = ''; // Restaurar el estilo
                    return; // Saltar la validación de este campo
                }
                if (campo.value.trim() === '') {
                    // Si el campo está vacío, marcarlo en rojo
                    campo.style.border = '2px solid red';
                    camposVacios = true; // Indicar que hay al menos un campo vacío
                } else {
                    // Si el campo no está vacío, restaurar el estilo
                    campo.style.border = '';
                }
            });
            // Mostrar un mensaje si hay campos vacíos
            if (camposVacios) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'Por favor, completa todos los campos obligatorios.';
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos vacíos
            }
            // Sanitizar y validar
            if (!validarTexto(perfilVari.nombre.value) || perfilVari.nombre.value.length < 5 || perfilVari.nombre.value.length > 30) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'El nombre es corto o excede 30 caracteres, solo letras y espacios.).';
                perfilVari.nombre.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarTexto(perfilVari.categoria.value)) { 
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'Selecciona una categoria válida.';
                perfilVari.categoria.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (perfilVari.categoria.value === 'OTRO' && !validarTexto(perfilVari.otraCategoria.value)) { 
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'Especifique su profesión.';
                perfilVari.otraCategoria.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarDescripcion(perfilVari.descripcion.value) || perfilVari.descripcion.value.length < 5 || perfilVari.descripcion.value.length > 200) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'La descripción es corta o excede 200 caracteres.';
                perfilVari.descripcion.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarCombox(perfilVari.extTelefono.value)) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'Seleccione un código de área válido.';
                perfilVari.extTelefono.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarTelefono(perfilVari.telefono.value)) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'El teléfono solo puede contener 7 dígitos.';
                perfilVari.telefono.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarCombox(perfilVari.estado.value)) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'Seleccione un estado válido.';
                perfilVari.estado.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarCombox(perfilVari.municipio.value)) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'Seleccione un municipio válido.';
                perfilVari.municipio.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarCombox(perfilVari.parroquia.value)) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'Seleccione una parroquia válida.';
                perfilVari.parroquia.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarDescripcion(perfilVari.direccion.value) || perfilVari.direccion.value.length < 5 || perfilVari.direccion.value.length > 200) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'La dirección es corta o excede 200 caracteres.';
                perfilVari.direccion.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarCombox(perfilVari.horarioApertura.value)) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'Seleccione un horario de apertura válido.';
                perfilVari.horarioApertura.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarCombox(perfilVari.horarioCierre.value)) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'Seleccione un horario de cierre válido.';
                perfilVari.horarioCierre.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }
            if (!validarSitioWeb(perfilVari.sitioWeb.value) && !perfilVari.noWebsiteCheckbox.checked) {
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = 'Sitio web no válido, o marca "No tengo sitio web".';
                perfilVari.sitioWeb.style.border = '2px solid red'; // Marcar el campo en rojo
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos inválidos
            }

            // Crear un objeto FormData
            const formData = new FormData(formulario);
            // Agregar parámetro de consulta al FormData
            formData.append('ind', 'userPerfil');
            formData.append('accion', saveHidden.value);

            // Mover el scroll al principio de la página
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });

            /******************** PARA EJECUTAR EL LOADER ************************/
            // Deshabilitar el formulario y el botón de envío
            const inputs = formulario.querySelectorAll('input, button'); // Selecciona todos los inputs y el botón
            inputs.forEach(input => input.disabled = true); // Deshabilitar todos los campos y el botón
            errorDiv2.style.display = 'none';  

            // Mostrar el overlay y el loader
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('loader').style.display = 'block';
            document.body.style.overflow = 'hidden'; //Evitar el scroll
            /****************************************************************/

            setTimeout(function() { // Simular el procesamiento de datos
            fetch(`${BASE_URL}App/Models/valida_perfil.php`, {
                method: 'POST',
                body: formData, // Enviar el FormData con los datos del formulario
            })
            .then(response => {
                // Primero verifica si la respuesta es OK
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json(); // Lee como texto primero
            })
            .then(data => {
                // Ocultar el overlay y el loader después de procesar
                document.getElementById('overlay').style.display = 'none';
                document.getElementById('loader').style.display = 'none';
                // Habilitar el formulario y el botón de envío
                inputs.forEach(input => input.disabled = false); // Habilitar todos los campos y el botón
                
                if (data.success) {
                    if (data.message === 'No hay cambios') {
                        // Redirijo al dashboard directamente
                        window.location.href = `${BASE_URL}dashboard`;
                    } else {
                        // Mostrar el modal
                        const modal = document.getElementById('successModal');
                        modal.style.display = 'block';

                        // Cerrar el modal al hacer clic en el botón de cerrar
                        const closeBtn = document.querySelector('.close-btn');
                        closeBtn.addEventListener('click', function () {
                            modal.style.display = 'none';
                            // Redirijo al dashboard
                            window.location.href = `${BASE_URL}dashboard`;
                        });

                        // Cerrar el modal al hacer clic fuera del contenido
                        window.addEventListener('click', function (event) {
                            if (event.target === modal) {
                                modal.style.display = 'none';
                                // Redirijo al dashboard
                                window.location.href = `${BASE_URL}dashboard`;
                            }
                        });
                    }
                } else {
                    errorDiv2.style.display = 'block';
                    errorDiv2.textContent = data.error;
                    errorDiv2.classList.add('error_form');
                }
            })
            .catch(error => {
                // Manejar errores de red u otros problemas
                document.getElementById('overlay').style.display = 'none';
                document.getElementById('loader').style.display = 'none';
                inputs.forEach(input => input.disabled = false);

                errorDiv2.style.display = 'block';
                errorDiv2.textContent = 'Error al enviar el formulario. Inténtalo nuevamente.';
                errorDiv2.classList.add('error_form');
                console.error('Error:', error);
            });
            }, 3000); // Simula un tiempo de procesamiento de 3 segundos
        });
    }

    // Función para validar input del nombre
    const inputName = document.getElementById('edit-name');
    // Escuchar el evento 'input' en el campo
    inputName.addEventListener('input', function () {
        // Reemplazar cualquier carácter que no sea una letra o espacio
        this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
    });
    
    // Función para validar el input del teléfono
    const inputPhone = document.getElementById('text-phone');
    // Escuchar el evento 'input' en el campo
    inputPhone.addEventListener('input', function () {
        // Reemplazar cualquier carácter que no sea un número
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Función para validar input de otra profesión
    const inputOtraProf = document.getElementById('edit-otra');
    // Escuchar el evento 'input' en el campo
    inputOtraProf.addEventListener('input', function () {
        // Reemplazar cualquier carácter que no sea una letra o espacio
        this.value = this.value.replace(/[^a-zA-ZñÑ\s]/g, '');
    });
    
    // Manejar el cambio del select de profesión
    const otroProfInput = document.querySelector('#edit-otra');
    const selectProf = document.querySelector('#edit-position');
    selectProf.addEventListener('change', function() {
        
        // Limpiar el campo de texto al cambiar la opción
        otroProfInput.value = ''; // Limpiar el campo de texto al cambiar la opción
        if (this.value === 'OTRO') {
            otroProfInput.classList.remove('hidden'); // Mostrar el campo
        } else {
            otroProfInput.classList.add('hidden'); // Ocultar el campo
        }
    });
  
    // Limitar el campo de descripción y mostrar contador
    const textarea = document.getElementById('edit-descrip');
    textarea.addEventListener("input", () => {
        const charCounter = document.getElementById('char-counter');
        charCounter.classList.add('char-counter');
    
        if (textarea && charCounter) {
            const maxLength = 200; // Obtener el valor máximo permitido
            const currentLength = textarea.value.length; // Obtener la longitud actual del texto
            charCounter.textContent = `${currentLength}/${maxLength} caracteres`;
        } else {
            console.error('No se encontraron los elementos textarea o char-counter.');
        }
    });

    // Manejar el cambio del select de estado y cargar municipios
    perfilVari.estado.addEventListener('change', function() {
        const estadoId = perfilVari.estado.value; // Obtener el ID del estado seleccionado
        // Verificar que se haya seleccionado un estado válido
        if (!estadoId) {
            perfilVari.municipio.innerHTML = '<option value="" disabled selected>Selecciona el municipio</option>';
            return;
        }
        fetch(`${BASE_URL}App/Models/carga_perfil.php`, {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json'
            },
            body: JSON.stringify({ accion: 'municipios', estado_id: estadoId })
        })
        .then(response => response.json())
        .then(data => {
            // Limpiar el select de municipios
            perfilVari.municipio.innerHTML = '<option value="" disabled selected>Selecciona el municipio</option>';
            perfilVari.parroquia.innerHTML = '<option value="" disabled selected>Selecciona la parroquia</option>';
            // Verificar si hay municipios disponibles
            if (data.length === 0) {
                perfilVari.municipio.innerHTML = '<option value="">No hay municipios disponibles</option>';
                perfilVari.parroquia.innerHTML = '<option value="">No hay parroquias disponibles</option>';
                return;
            }
            data.forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.id_municipio;
                option.textContent = municipio.municipio;
                perfilVari.municipio.appendChild(option);
            });
        })
        .catch(error => console.error('Error al cargar los municipios:', error));
    });
  
    // Manejar el cambio del select de municipio y cargar parroquias
    perfilVari.municipio.addEventListener('change', function() {
        const municipioId = perfilVari.municipio.value; // Obtener el ID del municipio seleccionado
        // Verificar que se haya seleccionado un municipio válido
        if (!municipioId) {
            perfilVari.parroquia.innerHTML = '<option value="" disabled selected>Selecciona la parroquia</option>';
            return;
        }
        fetch(`${BASE_URL}App/Models/carga_perfil.php`, {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json'
            },
            body: JSON.stringify({ accion: 'parroquias', municipio_id: municipioId })
        })
        .then(response => response.json())
        .then(data => {
            // Limpiar el select de parroquias
            perfilVari.parroquia.innerHTML = '<option value="" disabled selected>Selecciona la parroquia</option>';
            // Verificar si hay parroquias disponibles
            if (data.length === 0) {
                perfilVari.parroquia.innerHTML = '<option value="">No hay parroquias disponibles</option>';
                return;
            }
            data.forEach(parroquia => {
                const option = document.createElement('option');
                option.value = parroquia.id_parroquia;
                option.textContent = parroquia.parroquia;
                perfilVari.parroquia.appendChild(option);
            });
        })
        .catch(error => console.error('Error al cargar las parroquias:', error));
    });
  
    // Manejar el cambio del checkbox "No tengo sitio web"
    perfilVari.noWebsiteCheckbox.addEventListener('change', function () {
        if (this.checked) {
            perfilVari.sitioWeb.value = ''; // Limpiar el campo de entrada
            perfilVari.sitioWeb.disabled = true; // Deshabilitar el campo
            perfilVari.sitioWeb.required = false; // Hacer que no sea obligatorio
            perfilVari.sitioWeb.style.border = ''; // Restaurar el estilo
        } else {
            perfilVari.sitioWeb.disabled = false; // Habilitar el campo
            perfilVari.sitioWeb.required = true; // Hacerlo obligatorio nuevamente
        }
    });

    // Limitar el campo de dirección y mostrar contador
    const textarea2 = document.getElementById('edit-direc');
    textarea2.addEventListener("input", () => {
        const charCounter2 = document.getElementById('char-counter2');
        charCounter2.classList.add('char-counter');

        if (textarea2 && charCounter2) {
            const maxLength = 200; // Obtener el valor máximo permitido
            const currentLength = textarea2.value.length; // Obtener la longitud actual del texto
            charCounter2.textContent = `${currentLength}/${maxLength} caracteres`;
        } else {
            console.error('No se encontraron los elementos textarea o char-counter.');
        }
    });

    // Activar los inputs con el boton de editar
    perfilVari.editButton.addEventListener('click', function () {
        // Oculta visualizacion y muestra edicion de selectores
        document.getElementById('perfilContainer-edit').style.display= 'block';
        document.getElementById('perfilContainer-view').style.display= 'none';
        const inputs = formulario.querySelectorAll('input, select, textarea'); // Selecciona todos los inputs y selects
        inputs.forEach(input => {
            if (input.id !== 'edit-avatar') { // Excluir el campo de imagen
                input.disabled = false; // Habilitar todos los campos
                input.style.border = '1px solid #ccc'; // Cambiar el borde a gris claro
                input.classList.remove('input-combo');
            }
            if (input.id === 'edit-website') {
                if (perfilVari.noWebsiteCheckbox.checked) {
                    perfilVari.sitioWeb.disabled = true; // Deshabilitar el campo
                    perfilVari.sitioWeb.required = false; // Hacer que no sea obligatorio
                } else {
                    perfilVari.sitioWeb.disabled = false; // Habilitar el campo
                    perfilVari.sitioWeb.required = true; // Hacerlo obligatorio nuevamente
                }
                perfilVari.sitioWeb.classList.remove('hidden'); // Restaurar el estilo
                perfilVari.labelWeb.style.display ='block'; // Mostrar el label
            }
        });
        cargarPerfilDinamico(true); // Cargar los datos del perfil para edición
        perfilVari.changeAvatarBtn.style.display = 'block'; // mostrar el botón de cambiar avatar
        saveButton.style.display = 'block'; // Mostrar el botón de guardar
        perfilVari.editButton.style.display = 'none'; // Ocultar el botón de editar
        saveHidden.value = 'update'; // Cambiar el valor del botón oculto para actualizacion
    });

    // funciones de sanitizacion de los campos
    function validarTexto(texto) {
        if (!texto) return '';
        const sanitized = texto.trim(); // Eliminar espacios
        // Expresión regular para validar el formato del nombre
        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ_\s]+$/; // Solo letras y espacios
        return regex.test(sanitized) ? sanitized : ''; // Retornar vacío si no es válido
    }
    function validarCombox(campo) {
        if (!campo) return '';
        const sanitized = campo.trim(); // Eliminar espacios
        // Expresión regular para validar el formato de la categoría
        const regex = /^\d+$/; // Solo dígitos
        return regex.test(sanitized) ? sanitized : ''; // Retornar vacío si no es válido
    }
    function validarTelefono(telefono) {
        if (!telefono) return '';
        const sanitized = telefono.trim(); // Eliminar espacios
        // Expresión regular para validar el formato del teléfono
        const regex = /^[0-9]{7}$/; // Solo 7 dígitos
        return regex.test(sanitized) ? sanitized : ''; // Retornar vacío si no es válido
    }
    function validarDescripcion(descripcion) {
        if (!descripcion) return '';
        const sanitized = descripcion.trim(); // Eliminar espacios
        return sanitized;
    }
    function validarSitioWeb(sitioWeb) {
        if (!sitioWeb) return '';
        const sanitized = sitioWeb.trim(); // Eliminar espacios
        // Expresión regular para validar el formato del sitio web
        const regex = /^(https?:\/\/)?([a-z0-9-]+\.)+[a-z]{2,}(:\d+)?(\/.*)?$/i; 
        return regex.test(sanitized) ? sanitized : ''; // Retornar vacío si no es válido
    }
}

//////////////////////////////////////////////////////////////////////////////
//////////////////////// CONTACTOS ///////////////////////////////////////////

function iniciarPantallaContactos() {
    document.getElementById('btn-seguidores').addEventListener('click', function(event) {
        openTab(event, 'seguidores');
    });
    document.getElementById('btn-seguidos').addEventListener('click', function(event) {
        openTab(event, 'seguidos');
    });
    // Configurar el evento de clic en el botón de cerrar del modal
    document.getElementById('modal-close').addEventListener('click', function() {
        closeModal();
    });
    // Botones del slider del modal
    document.getElementById('btn-prev').addEventListener('click', function() {
        moverSlide(-1);
    });
    document.getElementById('btn-next').addEventListener('click', function() {
        moverSlide(1);
    });

    // Función para cambiar entre pestañas
    function openTab(evt, tabName) {
        const tabContents = document.getElementsByClassName("tab-content");
        for (let i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.remove("active");
        }
        
        const tabButtons = document.getElementsByClassName("tab-btn");
        for (let i = 0; i < tabButtons.length; i++) {
            tabButtons[i].classList.remove("active");
        }
        
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    // Función para agregar eventos a los botones "Seguir"
    function configurarBotonesSeguir() {
        const followButtons = document.querySelectorAll('.follow-btn');
        
        followButtons.forEach(button => {
            // Iterar sobre cada botón y agregar la clase 'following'
            if (button.textContent === 'Siguiendo') {
                button.classList.add('following');
            }
            button.addEventListener('click', function(e) {
                e.stopPropagation(); 
                // Si es Siguiendo
                if (this.classList.contains('following')) {
                    this.classList.remove('following');
                    this.textContent = 'Seguir';
                // Si es Seguir
                } else {
                    this.classList.add('following');
                    this.textContent = 'Siguiendo';
                }

                // Validar la relacion de los usuarios
                const seguidoId = button.dataset.user;
                const currentState = button.getAttribute('data-state');
                const newState = currentState === 'follow' ? 'following' : 'follow';

                fetch(`${BASE_URL}App/Models/valida_contacto.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        seguidoId: seguidoId,  
                        action: 'pos_seguir',
                        estado: newState
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.setAttribute('data-state', newState);
                    } else {
                        console.error(data.error);
                    }
                });
            });
        });
    }

    // Cargar los productos en el slide dentro del modal
    async function cargarProductos(email, container) {
        try {
            const response = await fetch(`${BASE_URL}App/Models/valida_producto.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, action: 'productos' })
            });
            if (!response.ok) {
                throw new Error('Error al cargar los productos');
            }
            const productos = await response.json();
            container.innerHTML = ''; // Limpiar el contenido previo

            // Elementos del DOM
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');

            // Agregar productos al slider
            if (productos.success && Array.isArray(productos.productos)) {
                productos.productos.forEach(producto => {
                    const dive = document.createElement('div');
                    dive.className = 'slider-item';

                    // Crear imagen
                    const img = document.createElement('img');
                    img.src = producto.imageUrl;
                    img.alt = producto.name;

                    // Crear párrafo
                    const p = document.createElement('p');
                    p.textContent = producto.name;

                    // Construir el slide
                    dive.appendChild(img);
                    dive.appendChild(p);
                    container.appendChild(dive);
                });
                prevBtn.style.display = 'block';
                nextBtn.style.display = 'block';
            } else {
                container.innerHTML = '<p>No hay productos que mostrar</p>';
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
                return;
            }
        } catch (error) {
            console.error('Error al cargar los productos:', error);
            container.innerHTML = '<p>Error al cargar los productos</p>';
        }
    }
        
    // Funciones para el modal con scroll
    async function openModal(name, username, avatar, bio, email, telef, estado, munipar, followers, following, posts, direc, horario, web) {
        document.getElementById('modalName').textContent = name;
        document.getElementById('modalUsername').textContent = username;
        document.getElementById('modalAvatar').src = avatar;
        document.getElementById('modalBio').textContent = bio;
        document.getElementById('modalema').textContent = email;
        document.getElementById('modaltel').textContent = telef;
        document.getElementById('modalest').textContent = estado;
        document.getElementById('modalmup').textContent = munipar;
        document.getElementById('modaldir').textContent = direc;
        document.getElementById('modalhor').textContent = horario;
        document.getElementById('modalweb').textContent = web;
        document.getElementById('modalFollowers').textContent = followers;
        document.getElementById('modalFollowing').textContent = following;
        document.getElementById('modalPosts').textContent = posts;
        
        // Mostrar el modal
        document.getElementById('userModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Cargar productos para el usuario actual
        const productosContainer = document.querySelector('.slider-items');
        //productosContainer.innerHTML = '<p>Cargando productos...</p>'; // Mostrar mensaje de carga
        await cargarProductos(email, productosContainer);

        // Función para iniciar el desplazamiento automático
        setInterval(() => {
            moverSlide(1); // Mover al siguiente slide cada 3 segundos
        }, 3000);
    }
    
    function closeModal() {
        document.getElementById('userModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Cerrar modal al hacer clic fuera del contenido
    window.onclick = function(event) {
        const modal = document.getElementById('userModal');
        if (event.target === modal) {
            closeModal();
        }
    }
    
    // Función para mover el slider de productos dentro del modal
    let currentSlide = 0; // Índice del slide actual
    function moverSlide(direccion) {
        const sliderWrapper = document.querySelector('.slider-wrapper');
        const slides = document.querySelectorAll('.slider-item');
        const totalSlides = slides.length;
        if (!sliderWrapper || totalSlides === 0) return;

        // Actualizar el índice del slide actual
        currentSlide += direccion;

        // Asegurarse de que el índice esté dentro de los límites
        if (currentSlide < 0) {
            currentSlide = totalSlides - 1; // Ir al último slide
        } else if (currentSlide >= totalSlides) {
            currentSlide = 0; // Volver al primer slide
        }

        // Mover el slider
        const offset = -currentSlide * 100; // Calcular el desplazamiento
        sliderWrapper.style.transform = `translateX(${offset}%)`;
    }

    // Función para cargar seguidores dinámicamente
    async function cargarSeguidores() {
        try {
            const response = await fetch(`${BASE_URL}App/Models/valida_contacto.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'seguidores' })
            });
            if (!response.ok) {
                throw new Error('Error al cargar los seguidores');
            }

            const contactos = await response.json();
            if (!contactos.success) {
                console.error(contactos.error);
                return;
            }

            const seguidores = contactos.data.conscont;
            const listaSeguidores = document.querySelector('#seguidores .user-list');
            listaSeguidores.innerHTML = '';

            for (const seguidor of seguidores) {
                const li = document.createElement('li');
                li.className = 'user-item';
                li.addEventListener('click', function() {
                    openModal(
                        seguidor.nombre,
                        seguidor.categoria,
                        seguidor.avatar,
                        seguidor.descripcion,
                        seguidor.email,
                        seguidor.telefono,
                        seguidor.descest,
                        seguidor.descmupar,
                        seguidor.total_seguidores,
                        seguidor.total_seguidos,
                        seguidor.publicaciones || '0',
                        seguidor.direccion,
                        seguidor.horario,
                        seguidor.web
                    );
                });

                li.innerHTML = `
                    <img src="${seguidor.avatar}" alt="Avatar" class="user-avatar">
                    <div class="user-info">
                        <p class="user-name">${seguidor.nombre}</p>
                        <p class="user-username">${seguidor.email}</p>
                    </div>
                    <button class="follows-you-btn" disable>Te sigue</button>
                `;

                listaSeguidores.appendChild(li);
            }
        } catch (error) {
            console.error('Error al cargar los seguidores:', error);
        }
    }

    // Función para cargar seguidos dinámicamente
    async function cargarSeguidos() {
        try {
            const response = await fetch(`${BASE_URL}App/Models/valida_contacto.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'seguidos' })
            });
            if (!response.ok) {
                throw new Error('Error al cargar los seguidos');
            }

            const contactos = await response.json();
            if (!contactos.success) {
                console.error(contactos.error);
                return;
            }

            const seguidos = contactos.data.conscont;
            const listaSeguidos = document.querySelector('#seguidos .user-list');
            listaSeguidos.innerHTML = '';

            for (const seguidor of seguidos) {
                const li = document.createElement('li');
                li.className = 'user-item';
                li.addEventListener('click', function() {
                    openModal(
                        seguidor.nombre,
                        seguidor.categoria,
                        seguidor.avatar,
                        seguidor.descripcion,
                        seguidor.email,
                        seguidor.telefono,
                        seguidor.descest,
                        seguidor.descmupar,
                        seguidor.total_seguidores,
                        seguidor.total_seguidos,
                        seguidor.publicaciones || '0',
                        seguidor.direccion,
                        seguidor.horario,
                        seguidor.web
                    );
                });

                li.innerHTML = `
                    <img src="${seguidor.avatar}" alt="Avatar" class="user-avatar">
                    <div class="user-info">
                        <p class="user-name">${seguidor.nombre}</p>
                        <p class="user-username">${seguidor.email}</p>
                    </div>
                    <button class="follow-btn" data-state="following" data-user="${seguidor.email}">Siguiendo</button>
                `;

                listaSeguidos.appendChild(li);
            }
            // Configurar eventos para los nuevos botones "Seguir"
            configurarBotonesSeguir();
        } catch (error) {
            console.error('Error al cargar los seguidos:', error);
        }
    } 
    // Cargar seguidores y seguidos al iniciar
    cargarSeguidores();
    cargarSeguidos();
}

///////////////////////////////////////////////////////////////////////////////
//////////////////////// PRODUCTOS ////////////////////////////////////////////

function iniciarPantallaProductos() {
    // Función para cargar los productos desde el servidor
    async function CargarProductos(email) {
        try {
            const response = await fetch(`${BASE_URL}App/Models/valida_producto.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, action: 'productos' })
            });
            if (!response.ok) {
                throw new Error('Error al cargar los productos');
            }
            const products = await response.json();
            return products; // Retornar los productos
        } catch (error) {
            console.error('Error al cargar los productos:', error);
            return []; // Retornar un array vacío en caso de error
        }
    }
        
    // Variables del slider
    let currentSlide = 0;
    let slides = [];
    let autoPlayInterval;
    let autoPlayDelay = 3000; // 3 segundos entre slides
    let isAutoPlayPaused = false;
    let products = []; // Array para almacenar los productos
    
    // Elementos del DOM
    const slider = document.getElementById('sliderDin');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const sliderNav = document.getElementById('sliderNav');
    const addProductBtn = document.getElementById('addProductBtn');
    const addProductModal = document.getElementById('addProductModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const productForm = document.getElementById('productForm');
    const errorDiv2 = document.querySelector('#error-message'); // Mensaje de error
    const errorSpan = document.getElementById('errorNom'); // Mensaje de error para el nombre
    const errorSpan2 = document.getElementById('errorDes'); // Mensaje de error para la descripción
    
    // Inicializar el slider
    async function initSlider() {
        // Actualizar la variable global products con los productos cargados
        const email = storedUserSession; 
        const response = await CargarProductos(email);

        if (response && response.success && Array.isArray(response.productos)) {
            products = response.productos;
        }

        // Limpiar slider existente
        slider.innerHTML = '';
        sliderNav.innerHTML = '';
        if (products.length === 0) {
            const emptyMessage = document.createElement('div');
            emptyMessage.className = 'empty-message';
            emptyMessage.textContent = 'No hay productos para mostrar';
            slider.appendChild(emptyMessage);

            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
            return;
        } else {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'block';
        }

        // Crear slides
        products.forEach((product, index) => {
            // Crear contenedor del slide
            const slide = document.createElement('div');
            slide.className = 'slide';

            // Crear imagen
            const img = document.createElement('img');
            img.src = product.imageUrl;
            img.alt = product.name;

            // Crear botón de eliminar
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete-btn';
            deleteBtn.dataset.id = product.id;
            //deleteBtn.textContent = '×';

            // Crear el ícono de papelera
            const trashIcon = document.createElement('i');
            trashIcon.className = 'fas fa-trash'; // Clases de Font Awesome

            // Agregar el ícono al botón
            deleteBtn.appendChild(trashIcon);

            // Crear contenedor de información
            const slideInfo = document.createElement('div');
            slideInfo.className = 'slide-info';

            const slideInfo2 = document.createElement('div');
            slideInfo2.style.fontSize = '13px';

            const title = document.createElement('p');
            title.textContent = product.name;

            const description = document.createElement('p');
            description.textContent = product.description;

            // Construir el slide
            slideInfo.appendChild(slideInfo2);
            slideInfo2.appendChild(title);
            slideInfo2.appendChild(description);
            slide.appendChild(img);
            slide.appendChild(deleteBtn);
            slide.appendChild(slideInfo);
            slider.appendChild(slide);

            // Crear punto de navegación
            const dot = document.createElement('div');
            dot.className = 'slider-dot';
            dot.dataset.index = index;
            sliderNav.appendChild(dot);
        });

        // Actualizar referencias
        slides = document.querySelectorAll('.slide');
        slider.style.width = `${slides.length * 100}%`; // ancho total del slider
        slides.forEach(slide => {
            slide.style.width = `${100 / slides.length}%`; // Ancho individual de cada slide
        });
        const dots = document.querySelectorAll('.slider-dot');

        // Mostrar slide inicial
        showSlide(currentSlide);

        // Iniciar autoplay
        startAutoPlay();

        // Event listeners para botones de navegación
        prevBtn.addEventListener('click', () => {
            pauseAutoPlay();
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
            resumeAutoPlayAfterDelay();
        });

        nextBtn.addEventListener('click', () => {
            pauseAutoPlay();
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
            resumeAutoPlayAfterDelay();
        });

        // Event listeners para puntos de navegación
        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                pauseAutoPlay();
                currentSlide = parseInt(dot.dataset.index);
                showSlide(currentSlide);
                resumeAutoPlayAfterDelay();
            });
        });

        // Event listeners para botones de eliminar
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const productId = parseInt(this.dataset.id);
                deleteProduct(productId);
            });
        });

        // Pausar autoplay al hacer hover
        slider.addEventListener('mouseenter', pauseAutoPlay);
        slider.addEventListener('mouseleave', resumeAutoPlay);
    }
        
    // Mostrar slide específico
    function showSlide(index) {
        slider.style.transform = `translateX(-${index * 100}%)`;
        
        // Actualizar punto activo
        const dots = document.querySelectorAll('.slider-dot');
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }
        
    // Iniciar autoplay
    function startAutoPlay() {
        clearInterval(autoPlayInterval); // Limpiar intervalo existente
        autoPlayInterval = setInterval(() => {
            if (!isAutoPlayPaused && products.length > 0) {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }
        }, autoPlayDelay);
    }
    
    // Pausar autoplay
    function pauseAutoPlay() {
        isAutoPlayPaused = true;
    }
    
    // Reanudar autoplay
    function resumeAutoPlay() {
        isAutoPlayPaused = false;
    }
    
    // Reanudar autoplay después de un retraso
    function resumeAutoPlayAfterDelay(delay = 5000) {
        setTimeout(resumeAutoPlay, delay);
    }
        
    // Eliminar producto
    let productIdToDelete = null;

    function deleteProduct(id) {
        productIdToDelete = id;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    // Confirmar eliminación
    document.getElementById('confirmDeleteBtn').onclick = function() {
        const id = productIdToDelete;
        const product = products.find(product => Number(product.id) === Number(id));
        if (product) {
            pauseAutoPlay(); // Detener el autoplay antes de eliminar
           
            products = products.filter(p => p.id !== product.id); // Eliminar el producto del array
            
            if (currentSlide >= products.length) {
                currentSlide = Math.max(0, products.length - 1);
            }

            // Eliminar el producto del servidor
            fetch(`${BASE_URL}App/Models/valida_producto.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'elimina_producto', id: product.id })
            })
            .then(response => {
                if (!response.ok) throw new Error('Error al eliminar el producto');
                return response.json();
            })
            .then(data => { 
                initSlider(); // Reinicia el slider con los nuevos datos
            })
            .catch(error => { console.error('Error al eliminar el producto del servidor:', error); });
            
            // Reanudar autoplay si hay productos
            if (products.length > 0) {
                resumeAutoPlayAfterDelay(); 
            }
        }
        document.getElementById('deleteModal').style.display = 'none';
        productIdToDelete = null;
    };

    // Cancelar eliminación
    document.getElementById('cancelDeleteBtn').onclick = function() {
        document.getElementById('deleteModal').style.display = 'none';
        productIdToDelete = null;
    };
        
    // Añadir nuevo producto
    async function addProduct() {
        // Recoge los datos del formulario y los convierte en objeto
        const formData = new FormData(productForm);
        formData.append('action', 'inserta_producto');

        try {    
            const response = await fetch(`${BASE_URL}App/Models/valida_producto.php`, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.error) throw new Error(data.error);
            
            products.push(data); // Actualizar el slider en tiempo real
            initSlider(); // Reinicia el slider con los nuevos datos

            // Pausar temporalmente el autoplay para que el usuario vea el nuevo producto
            pauseAutoPlay();
            resumeAutoPlayAfterDelay();

            // Si era el primer producto, ir a ese slide
            if (products.length === 1) {
                currentSlide = 0;
            }
            return true;

        } catch(error) {
            errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
            errorDiv2.textContent = 'Error al añadir el producto';
            errorDiv2.classList.add('error_form');
            console.error('Error al añadir el producto:', error);
            return false;
        }
    }
        
    // Mostrar modal para añadir producto
    function showAddProductModal() {
        addProductModal.style.display = 'flex';
        document.getElementById('productName').focus();
        document.body.style.overflow = 'hidden';
    }
 
    // Ocultar modal
    function hideAddProductModal() {
        addProductModal.style.display = 'none';
        productForm.reset();
        productForm.querySelectorAll('input, textarea').forEach(input => {
            input.style.border = ''; // Limpiar el borde de los inputs
        });
        errorDiv2.style.display = 'none'; // Limpiar el mensaje de error
        errorSpan.style.display = 'none'; // Limpiar el mensaje de error
        errorSpan2.style.display = 'none'; // Limpiar el mensaje de error
    }   
        
    // Función para validar input del nombre
    const inputName = document.getElementById('productName');
    // Escuchar el evento 'input' en el campo
    inputName.addEventListener('input', function () {
        // Reemplazar cualquier carácter que no sea una letra o espacio
        this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/g, '');
    });
        
    // Funcion para validar el input de descripción
    const inputDescription = document.getElementById('productDescription');
    // Escuchar el evento 'input' en el campo
    inputDescription.addEventListener('input', function () {
        // Reemplazar cualquier carácter que no sea una letra o espacio
        this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/g, '');

        // Limitar el campo de descripción y mostrar contador
        const charCounter = document.getElementById('char-counter');
        charCounter.classList.add('char-counter');

        if (inputDescription && charCounter) {
            const maxLength = 70; // Obtener el valor máximo permitido
            const currentLength = inputDescription.value.length; // Obtener la longitud actual del texto
            charCounter.textContent = `${currentLength}/${maxLength} caracteres`;
        } else {
            console.error('No se encontraron los elementos textarea o char-counter.');
        }
    });
        
    // funciones de sanitizacion de los campos
    function validarTexto(texto) {
        if (!texto) return '';
        const sanitized = texto.trim(); // Eliminar espacios
        // Expresión regular para validar el formato del nombre
        const regex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ_\s]+$/; // Solo letras y espacios
        return regex.test(sanitized) ? sanitized : ''; // Retornar vacío si no es válido
    }

    ///// Manejo del input de archivo /////
    // Referencias a los elementos
    const hiddenFileInput = document.getElementById('hiddenProductImage');
    const textFileInput = document.getElementById('productImage');
    const changeAvatarBtn = document.querySelector('.change-avatar-btn');

    // Crear un div para mostrar el mensaje de error
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error'; // Añadir la clase de error
    changeAvatarBtn.parentNode.appendChild(errorDiv); // Añadir el div de error al DOM

    // Abrir el selector de archivos al hacer clic en el botón
    changeAvatarBtn.addEventListener('click', () => {
        hiddenFileInput.click(); // Simula un clic en el input oculto
    });
    
    // Actualizar el input de texto con el nombre del archivo seleccionado
    hiddenFileInput.addEventListener('change', () => {
        const file = hiddenFileInput.files[0];
        // Si no se selecciona un archivo, limpiar el input de texto
        if (!file) {
            textFileInput.value = '';
            errorDiv.style.display = 'none';
            return;
        }
        // Validar el tamaño del archivo (máximo 2 MB)
        if (file.size > 2 * 1024 * 1024) { // Tamaño máximo: 2 MB
            mostrarError('El archivo es demasiado grande. Selecciona un archivo menor a 2 MB.');
            limpiarInputs();
            return;
        } 
        // Validar el tipo de archivo
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validImageTypes.includes(file.type)) {
            mostrarError('Por favor selecciona un archivo de imagen válido (JPEG, PNG, GIF).');
            limpiarInputs();
            return;
        }
        // Si el archivo es válido, actualizar el input de texto y ocultar el mensaje de error
        textFileInput.value = file.name;
        errorDiv.style.display = 'none';
    });

    // Función para mostrar un mensaje de error
    function mostrarError(mensaje) {
        errorDiv.textContent = mensaje;
        errorDiv.style.display = 'block';
    }

    // Función para limpiar los inputs
    function limpiarInputs() {
        hiddenFileInput.value = '';
        textFileInput.value = '';
    }

    // Event listeners para el modal
    addProductBtn.addEventListener('click', showAddProductModal);
    cancelBtn.addEventListener('click', hideAddProductModal);
    
    if (productForm) {
        productForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const name = document.getElementById('productName').value;
            const description = document.getElementById('productDescription').value;

            let camposVacios = false; // Bandera para verificar si hay campos vacíos
            // Seleccionar todos los inputs, selects y textareas del formulario
            const campos = productForm.querySelectorAll('input, textarea');
            campos.forEach(campo => {
                if (campo.value.trim() === '') {
                    campo.style.border = '2px solid red'; // Resaltar el campo vacío
                    camposVacios = true; // Marcar que hay campos vacíos
                } else {
                    campo.style.border = ''; // Limpiar el borde si no está vacío
                }
            });
                // Mostrar un mensaje si hay campos vacíos
            if (camposVacios) {
                //alert('Por favor, completa todos los campos obligatorios.');
                errorDiv2.style.display = 'block'; // Mostrar el mensaje de error
                errorDiv2.textContent = '(*) Campos obligatorios.';
                errorDiv2.classList.add('error_form');
                return; // Detener la ejecución si hay campos vacíos
            }

            // Sanitizar y validar
            const nameProd = validarTexto(name);
            const descriptionProd = validarTexto(description);

            if (!nameProd || nameProd.length < 3 || nameProd.length > 30) {
                errorSpan.style.display = 'block'; // Mostrar el mensaje de error
                errorSpan.textContent = 'El nombre debe tener entre 3 y 30 caracteres.';
                return; // Detener la ejecución si el nombre no es válido
            } else {
                errorSpan.style.display = 'none'; // Ocultar el mensaje de error
            }

            if (!descriptionProd || descriptionProd.length < 3 || description.length > 70) {
                errorSpan2.style.display = 'block'; // Mostrar el mensaje de error
                errorSpan2.textContent = 'La descripción debe tener entre 3 y 70 caracteres.';
                return; // Detener la ejecución si la descripción no es válida
            } else {
                errorSpan2.style.display = 'none'; // Ocultar el mensaje de error
            }
            
            // Si falla, el modal queda abierto y muestra el error
            const success = await addProduct();
            if (success) { 
                hideAddProductModal();
                /******************** PARA EJECUTAR EL LOADER ************************/
                document.getElementById('loader-message').textContent = 'Actualizando...';
                loader_redi('productForm'); // Elemento ID del formulario
            }
        });
    }

    // Cerrar modal al hacer clic fuera del contenido
    addProductModal.addEventListener('click', function(e) {
        if (e.target === addProductModal) {
            hideAddProductModal();
        }
    });
    
    // Inicializar el slider al cargar la página
    initSlider();
}

/////////////////////////////////////////////////////////////////////////////////////
//////////////////////// ACTUALIZA CLAVE ////////////////////////////////////////////

function iniciarPantallaActualizarClave() {
    const formuAct = document.getElementById('formuAct');
    const errorDiv2 = document.getElementById('errorDiv2');

    if (formuAct) {
        formuAct.addEventListener('submit', function(event) {
            event.preventDefault();
            // Sanitizar y validar
            const clant = formuAct.conan.value.trim();
            const clact = formuAct.conac.value.trim();
            const clactr = formuAct.conacr.value.trim();

            // Validar todos los campos
            if (clant === '' || clact === '' || clactr === '') {
                errorDiv2.style.display = 'block';
                errorDiv2.textContent = 'Por favor, complete todos los campos.';
                formuAct.conan.style.border = '2px solid red';
                formuAct.conac.style.border = '2px solid red';
                formuAct.conacr.style.border = '2px solid red';
                return;
            } else {
                errorDiv2.style.display = 'none';
                formuAct.conan.style.border = '';
                formuAct.conac.style.border = '';
                formuAct.conacr.style.border = '';
            }
            // Validar que las contraseñas coincidan    
            if (clact !== clactr) {
                errorDiv2.style.display = 'block';
                errorDiv2.textContent = 'Las contraseñas no coinciden.';
                formuAct.conacr.style.border = '2px solid red';
                return;
            } else {
                errorDiv2.style.display = 'none';
                formuAct.conacr.style.border = '';
            }
            // Validar que la contraseña anterior no sea igual a la nueva
            if (clant === clact) {
                errorDiv2.style.display = 'block';
                errorDiv2.textContent = 'La nueva contraseña no puede ser igual a la anterior.';
                formuAct.conac.style.border = '2px solid red';
                return;
            } else {
                errorDiv2.style.display = 'none';
                formuAct.conac.style.border = '';
            }

            /******************** PARA EJECUTAR EL LOADER ************************/
            // Mostrar el overlay y el loader
            document.getElementById('loader-message').textContent = 'Guardando nueva clave...';
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('loader').style.display = 'block';
            /****************************************************************/

            setTimeout(function() { // Simular el procesamiento de datos
                // Ocultar el overlay y el loader después de procesar

                document.getElementById('overlay').style.display = 'none';
                document.getElementById('loader').style.display = 'none';
            
                window.location.href = `${BASE_URL}dashboard`;
            },3000);

            // Enviar el formulario
            const formData = new FormData(formuAct);
            fetch(`${BASE_URL}App/Models/valida_cambio_clave.php`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    formuAct.reset(); // Limpiar el formulario
                } else {
                    errorDiv2.style.display = 'block';
                    errorDiv2.textContent = data.error;
                }
            })
            .catch(error => {
                errorDiv2.style.display = 'block';
                errorDiv2.textContent = 'Error en la conexión al servidor.';
                console.error('Error:', error);
            });
            
        });
    }
}

    




