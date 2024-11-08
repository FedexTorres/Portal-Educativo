// Función para mostrar el error en un alert-danger
function mostrarError(campo, mensajeElemento, mensaje) {
    campo.classList.add('is-invalid');
    mensajeElemento.innerHTML = mensaje;
    mensajeElemento.classList.remove('d-none');
}

// Función que valida y envía el mensaje mediante AJAX

async function validarFormuMensaje(e) {
    e.preventDefault(); // Prevenir la redirección
    const mensajeInput = document.getElementById('mensaje');
    const destinatarioInput = document.getElementById('destinatario');
    const alerta = document.getElementById('alerta');
    const exito = document.getElementById('exito'); // Obtener el div de éxito
    const mensaje = mensajeInput.value.trim();

    // Usamos jQuery para obtener el valor de "data-destinatario-id" del elemento con id "destinatario".
    // Este valor representa el ID del destinatario, asignado dinámicamente en el HTML.
    const destinatarioId = $("#destinatario").data("destinatario-id");

    // Limpiar validaciones anteriores
    mensajeInput.classList.remove('is-invalid');
    destinatarioInput.classList.remove('is-invalid');
    alerta.innerHTML = "";
    alerta.classList.add('d-none'); // Ocultar mensaje de error
    exito.innerHTML = ""; // Limpiar el mensaje de éxito
    exito.classList.add('d-none'); // Asegurarse de que el mensaje de éxito esté oculto

    // Validaciones de campos
    if (!destinatarioId) {
        mostrarError(destinatarioInput, alerta, "El destinatario es requerido.");
        return;
    }
    if (!mensaje) {
        mostrarError(mensajeInput, alerta, "El mensaje no puede estar vacío.");
        return;
    }

    try {
        const response = await fetch('Modulos/enviarMensaje.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                destinatario_id: destinatarioId,
                mensaje: mensaje
            })
        });

        const result = await response.json();

        // Mostrar mensaje de éxito
        if (result.status === 'success') {
            exito.classList.remove('d-none'); // Mostrar el div de éxito
            exito.innerHTML = result.message; // Mostrar mensaje de éxito
            cargarMensajesEnviados(); // Llamar a la función para cargar mensajes enviados
            cargarMensajesRecibidos(); // Llamar a la función para cargar mensajes recibidos hasta implementar las sesiones.
            document.getElementById('form-enviar-mensaje').reset();
            // Limpiar el mensaje de éxito después de 3 segundos
            setTimeout(() => {
                exito.classList.add('d-none'); // Ocultar el div de éxito
                exito.innerHTML = ''; // Limpiar el contenido
            }, 2000);
        } else {
            mostrarError(mensajeInput, alerta, result.message);
        }
    } catch (error) {
        console.error('Error en la solicitud:', error);
        mostrarError(mensajeInput, alerta, 'Hubo un problema con el envío del mensaje.');
    }
}

// Función para buscar destinatarios mediante AJAX y JQUERY
$(document).ready(function () { // Espera a que el DOM esté completamente cargado antes de ejecutar este código
    $("#destinatario").autocomplete({ // Activa el autocompletado en el input con ID 'destinatario'
        source: async function (request, response) { // Define la fuente de datos para el autocompletado
            try {
                // Realiza una solicitud al servidor, enviando el término ingresado por el usuario
                const result = await fetch(`Modulos/buscarDestinatarios.php?query=${encodeURIComponent(request.term)}`);
                // Procesa la respuesta del servidor como JSON
                const data = await result.json();

                // Mapea los resultados para crear objetos que el autocompletado necesita
                response(data.destinatarios.map(dest => ({
                    label: dest.nombre, // 'label' muestra el nombre en la lista de sugerencias
                    value: dest.id     // 'value' guarda el ID del destinatario como el valor asociado
                })));
            } catch (error) {
                // Si ocurre un error durante la solicitud, lo registra en la consola
                console.error("Error al buscar destinatarios:", error);
            }
        },
        select: function (event, ui) { // Función que se ejecuta al seleccionar un elemento de la lista
            event.preventDefault(); // Evita que el valor predeterminado (ID) se inserte automáticamente

            // Establece el input 'destinatario' con el nombre seleccionado (label)
            $("#destinatario").val(ui.item.label);
            // Guarda el ID seleccionado (value) en un atributo de datos personalizado para uso posterior
            $("#destinatario").data("destinatario-id", ui.item.value);
        }
    });
});

// Función para cargar mensajes recibidos mediante AJAX
async function cargarMensajesRecibidos() {
    try {
        const response = await fetch(`Modulos/obtenerMensajes.php`);

        // Validar si la respuesta es exitosa antes de convertirla en JSON
        if (!response.ok) {
            throw new Error("Error en la solicitud: " + response.statusText);
        }

        const data = await response.json();
        const listaMensajes = document.getElementById('lista-mensajes');
        listaMensajes.innerHTML = '';

        if (data.status === 'success') {
            data.mensajes.forEach(msg => {
                const mensajeItem = document.createElement('a');
                mensajeItem.className = 'list-group-item list-group-item-action';
                mensajeItem.innerHTML = `<strong>Remitente:</strong> ${msg.remitente} <br>
                                         <strong>Mensaje:</strong> ${msg.contenido} <br>
                                         <small>Fecha: ${msg.fecha}</small>`;
                listaMensajes.appendChild(mensajeItem);
            });
        } else {
            console.error("Error: " + data.message);
        }
    } catch (error) {
        console.error("Error de conexión al cargar los mensajes:", error);
    }
}


//Funcion para pedir medienta AJAX los mensajes enviados en el bbdd.
async function cargarMensajesEnviados() {
    try {
        const response = await fetch('Modulos/consultarMensajesEnviados.php');
        const data = await response.json();

        if (data.status === 'success') {
            const listaMensajesEnviados = document.getElementById('mensajes-enviados');
            listaMensajesEnviados.innerHTML = ''; // Limpiar la lista antes de agregar nuevos mensajes

            if (Array.isArray(data.mensajes) && data.mensajes.length > 0) {
                data.mensajes.forEach(mensaje => {
                    agregarMensajeEnviado(mensaje.mensaje, mensaje.destinatario_nombre, mensaje.fecha);
                });
            } else {
                console.warn("No hay mensajes enviados disponibles.");
            }
        } else {
            console.error(data.message);
        }
    } catch (error) {
        console.error('Error al cargar los mensajes enviados:', error);
    }
}
// Función para agregar mensaje enviado a la lista
function agregarMensajeEnviado(mensaje, destinatarioNombre, fecha) {
    const listaMensajesEnviados = document.getElementById('mensajes-enviados');
    const nuevoMensaje = document.createElement('a');
    nuevoMensaje.className = 'list-group-item list-group-item-action';
    nuevoMensaje.innerHTML = `<strong>Destinatario:</strong> ${destinatarioNombre} <br>
                              <strong>Mensaje:</strong> ${mensaje} <br>
                              <small>Fecha: ${new Date(fecha).toLocaleDateString()}</small>`;
    listaMensajesEnviados.appendChild(nuevoMensaje);
}


// Función para inicializar la página y configurar eventos
function inicio() {
    const sections = document.querySelectorAll('section');
    document.getElementById('seccion-inicio').classList.remove('d-none');
    mostrarMenus(sections);

    document.getElementById('form-enviar-mensaje').addEventListener('submit', validarFormuMensaje);
    cargarMensajesRecibidos();
    cargarMensajesEnviados();
}

document.addEventListener('DOMContentLoaded', inicio);


