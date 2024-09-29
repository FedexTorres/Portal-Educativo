
function mostrarMenus(sections) {
    // Escuchar clics en los botones del menú
    document.querySelectorAll('.btn-menu').forEach(boton => {
        boton.addEventListener('click', function () {
            const botonId = this.id;   //segun el boton escuchado, se pasa el id a la variable botonId

            // Ocultar todas las secciones primero
            sections.forEach(section => section.classList.add('d-none'));

            // Mostrar la sección correspondiente según el botón clickeado
            switch (botonId) {
                case 'inicio-btn':
                    document.getElementById('seccion-inicio').classList.remove('d-none');
                    break;
                case 'cursos-btn':
                    document.getElementById('seccion-mis-cursos').classList.remove('d-none');
                    break;               
                case 'usuarios-btn':
                    document.getElementById('seccion-usuarios').classList.remove('d-none');
                    break;
                case 'mensaje-btn':
                    document.getElementById('seccion-mensajes').classList.remove('d-none');
                    break;
                case 'perfil-btn':
                    document.getElementById('seccion-perfil').classList.remove('d-none');
                    break;
                default:
                    console.log('Botón no reconocido');
                    break;
            }
        });
    });

}

//codigo para la creacion de los cursos
let form = document.getElementById("crearCurso");
form.addEventListener("submit",creaciondecurso);

function creaciondecurso(e){
    e.preventDefault();
    alert("se creo el curso");
}

//codigo para la edicion de los cursos
let form2 = document.getElementById("editarCurso");
form2.addEventListener("submit",ediciondecurso);

function ediciondecurso(e){
    e.preventDefault();
    alert("se edito el curso");
}

//codigo para la eliminacion de los cursos
let form3 = document.getElementById("EliminarCurso");
form3.addEventListener("submit",eliminaciondecurso);

function eliminaciondecurso(e){
    e.preventDefault();
    alert("se eliminoel curso");
}



function inicio() {
    const sections = document.querySelectorAll('section');
    mostrarMenus(sections);

}

window.onload = inicio;