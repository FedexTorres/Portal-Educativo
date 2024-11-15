

function mostrarCursos(cursos) {
    const contenedorCursos = document.getElementById('courses-section');
    contenedorCursos.innerHTML = ''; // Limpiar el contenedor antes de agregar las cards
    cursos.forEach(curso => {
        // Generar el HTML para cada card

        //let asignar = tieneProfesor(curso.id);

        //console.log("profe"+curso.id);

        const cardHTML = `
        <div class="col-lg-6 col-md-12">
        <div class="card mt-4">
        <div class="card-header">
          <h5 class="card-title">${curso.nombre}</h5>
        </div>
        <div class="card-body">
          <img src="Portal-Educativo/${curso.imagen_url}" alt="${curso.nombre}" class="card-img imgadmin">
          <p class="card-text">Descripcion</p>
          <p class="card-text">${curso.descripcion}</p>
          <h6 class="card-subtitle mb-2 text-muted" id="profe${curso.id}"></h6>
          <h6 class="card-subtitle mb-2 text-muted">Vacantes disponibles: ${curso.vacantes}</h6>
          <h6 class="card-subtitle mb-2 text-muted">Fecha de Inicio: ${curso.fecha_inicio}| Fecha de Fin: ${curso.fecha_fin}</h6>
          
            


        </div>
        <div class="card-footer">
          <button type="button" class="btn btn-info btn-ajuste" data-bs-toggle="modal" data-bs-target="#modalCursoAjuste" value=${curso.id}>Ajustes de Curso</button>
          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalCursoEliminar${curso.id}">Eliminar Curso</button>

        </div>
        </div>
        </div>
    `;
        contenedorCursos.innerHTML += cardHTML;

        //moverNOmbre("profe"+curso.id,tieneProfesor(curso.id));
        //console.log(tieneProfesor(curso.id));
        // Generar el HTML para cada modal
        const modalHTML = `
        <div class="modal fade" id="modalCursoEliminar${curso.id}" tabindex="-1" aria-labelledby="modalCursoEliminar${curso.id}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCurso${curso.id}Label">${curso.nombre}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <b>Seguro desea eliminar el curso?</b><br><br>
                        <br><br>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger btn-eliminar" data-bs-dismiss="modal" value=${curso.id}>Si</button>
                    </div>
                </div>
            </div>
        </div>
    `;
        contenedorCursos.innerHTML += modalHTML;

        // Esto agregará el modal dentro de la misma sección
    });

}

function moverNOmbre(data) {
    //document.getElementById(elid).innerHTML=await lista;
    //let profe = document.createElement("div");
    //profe.innerHTML=
    //console.log(elid);
    //console.log(await lista);

    data.forEach(curso => {
        jose(curso.id);
    }

    )
}

async function jose(params) {
    document.getElementById("profe" + params).innerHTML = "Profesor: " + await tieneProfesor(params);
}

async function tieneProfesor(id) {
    try {
        let info = new FormData();
        info.append("id", id);
        const response = await fetch('Modulos/getTieneProfesor.php', {
            method: "POST",
            body: info,
        });
        const data = await response.json();

        if (data.status === 'success') {

            return await data.cursos[0].nombre;

        } else {
            return await data.message;
        }
    } catch (error) {
        console.log(error);
    }
}



async function cargarCursos() {
    try {
        const response = await fetch('Modulos/obtenerCursosAdmin.php');
        const data = await response.json();

        if (data.status === 'success') {


            mostrarCursos(data.cursos);
            const errorGlobal = document.getElementById('errorGlobal');
            limpiarErrores(errorGlobal, errorGlobal);

            asignarbotones2();
            asignarAjuste()

            moverNOmbre(data.cursos);


        } else {
            mostrarErrorGlobal(data.message || 'No se encontraron cursos disponibles');

        }
    } catch (error) {
        mostrarErrorGlobal(`Error en la conexión: ${error.message}`);
    }
}




///////codigo para la creacion de los cursos


let botoncrearcurso = document.getElementById("botonCreacionCurso");
let modalcrearcurso = document.getElementById("myModalCreacionCurso");
let nocrearcurso = document.getElementById("cancelarCreacionCurso");



let form = document.getElementById("crearCurso");
form.addEventListener("submit", creaciondecurso);
botoncrearcurso.addEventListener("click", function () { modalcrearcurso.classList.add('mostrar'); });
nocrearcurso.addEventListener("click", function () { modalcrearcurso.classList.remove("mostrar"); });


async function creaciondecurso(e) {
    e.preventDefault();

    let nombreC = document.getElementById("nombrecrear");
    let descripcionC = document.getElementById("textCrearCurso");
    let programaC = document.getElementById("programaCrearCurso");
    let fechaInicioC = document.getElementById("fechaInicioCrearCurso");
    let fechaFinC = document.getElementById("fechaFinCrearCurso");
    let imeganC = document.getElementById("archivoCrear");

    let cursoValido = true;

    if (nombreC.value.trim() === '') {
        console.log("El nombre no puede estar vacio");
        cursoValido = false;
    }

    if (descripcionC.value.trim() === '') {
        console.log("La descipcion no puede estar vacio");
        cursoValido = false;
    }

    if (programaC.value.trim() === '') {
        console.log("El programa de estudios no puede estar vacio");
        cursoValido = false;
    }

    if (fechaInicioC.value.trim() === '') {
        console.log("La fecha de inicio no puede estar vacio");
        cursoValido = false;
    }

    if (fechaFinC.value.trim() === '') {
        console.log("La fecha fin no puede estar vacio");
        cursoValido = false;
    }

    const fechaInicioCDate = new Date(fechaInicioC.value);
    const fechaFinCDate = new Date(fechaFinC.value);
    if (fechaInicioCDate > fechaFinCDate) {
        console.log("La fecha de inicio no puede ser despues de la de inicio");
        cursoValido = false;
    }

    if (imeganC.value.trim() === '') {
        console.log("Elija una imagen valida");
        cursoValido = false;
    }


    //subida de informacion
    if (cursoValido) {

        try {
            const datos = new FormData(e.target);
            const response = await fetch('Modulos/insertCurso.php', {
                method: 'POST',
                body: datos,
            });
            // Verificar si la respuesta es exitosa
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const resultado = await response.json();

            if (resultado.status === 'success') {

                modalcrearcurso.classList.remove("mostrar");
                cargarCursos();
                limpiarCampos(nombreC);
                limpiarCampos(descripcionC);
                limpiarCampos(programaC);
                limpiarCampos(fechaInicioC);
                limpiarCampos(fechaFinC);
                limpiarCampos(imeganC);


            } else if (resultado.status === 'error') {
                // mostrarErrorGlobal(resultado.message);
                console.log("Fallo: ".resultado.message);
            }
        } catch (error) {
            // mostrarErrorGlobal(`Error en la conexión: ${error.message}`);
            console.error("Fallo:", error);
        }
    }

}


function limpiarCampos(objeto) {
    objeto.value = "";
}

///////////////codigo para la edicion de los cursos/////////////////////////////

function asignarAjuste() {
    document.querySelectorAll('.btn-ajuste').forEach(boton => {
        boton.addEventListener("click", function () {
            let idAjuste = this.value;
            getCursoAhEditar(idAjuste);
        })
    });
}

async function getCursoAhEditar(id) {
    try {
        const datos = new FormData();
        datos.append("id", id);
        const response = await fetch('Modulos/getCursoAhEditar.php', {
            method: 'POST',
            body: datos,
        });
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const resultado = await response.json();

        if (resultado.status === 'success') {

            ajustarModal(resultado.data);


        } else if (resultado.status === 'error') {
            console.log(resultado.message);
        }
    } catch (error) {
        console.error("Fallo:", error);
    }
}


function ajustarModal(data) {
    data.forEach(curso => {
        document.getElementById("modalCursoAjusteLabel").innerHTML = curso.nombre;
        document.getElementById("guardarAjustes").value = curso.id;
    });
}




function autocompletar() {
    const inputMascota = document.querySelector('#profesor');
    let indexFocus = -1;

    inputMascota.addEventListener('input', async function () {
        const tipoMascota = this.value;

        if (!tipoMascota) return false;
        cerrarLista();

        //crear la lista de sugerencias
        const divList = document.createElement('div');
        divList.setAttribute('id', this.id + '-lista-autocompletar');
        divList.setAttribute('class', 'lista-autocompletar-items');
        this.parentNode.appendChild(divList);


        try {
            const datos = new FormData();
            datos.append("id", tipoMascota);
            const response = await fetch('Modulos/getProfesoresAhAsignar.php', {
                method: 'POST',
                body: datos,
            });
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const resultado = await response.json();

            if (resultado.status === 'success') {

                const arreglo = resultado.destinatarios;
                //validar arreglo vs input
                if (arreglo.length == 0) return false;
                arreglo.forEach(item => {

                    const elementoLista = document.createElement('div');
                    elementoLista.setAttribute("value", item.id);
                    elementoLista.innerHTML = item.nombre;

                    elementoLista.addEventListener('click', function () {
                        inputMascota.value = this.innerText;
                        //console.log(elementoLista.value);
                        //$("#profesor").data("destinatario-id", this.value);
                        cerrarLista();
                        return false;
                    });
                    divList.appendChild(elementoLista);

                });


            } else if (resultado.status === 'error') {
                console.log(resultado.message);
            }
        } catch (error) {
            console.error("Fallo:", error);
        }




    });

    inputMascota.addEventListener('keydown', function (e) {
        const divList = document.querySelector('#' + this.id + '-lista-autocompletar');
        let items;

        if (divList) {
            items = divList.querySelectorAll('div');

            switch (e.keyCode) {
                case 40: //tecla de abajo
                    indexFocus++;
                    if (indexFocus > items.length - 1) indexFocus = items.length - 1;
                    break;

                case 38: //tecla de arriba
                    indexFocus--;
                    if (indexFocus < 0) indexFocus = 0;
                    break;

                case 13: // presionas enter
                    e.preventDefault();
                    items[indexFocus].click();
                    indexFocus = -1;
                    break;

                default:
                    break;
            }

            seleccionar(items, indexFocus);
            return false;
        }
    });

    document.addEventListener('click', function () {
        cerrarLista();
    });
}

function seleccionar(items, indexFocus) {
    if (!items || indexFocus == -1) return false;
    items.forEach(x => { x.classList.remove('autocompletar-active') });
    items[indexFocus].classList.add('autocompletar-active');
}

function cerrarLista() {
    const items = document.querySelectorAll('.lista-autocompletar-items');
    items.forEach(item => {
        item.parentNode.removeChild(item);
    });
    indexFocus = -1;
}


async function ajustesCurso(e) {
    e.preventDefault();

    let profe = document.getElementById("profesor");
    let vacan = document.getElementById("vacante");
    let cursoAjustado = document.getElementById("guardarAjustes").value;

    try {

        const buscarprofe = new FormData();
        buscarprofe.append("id", profe.value);
        const respuesta = await fetch('Modulos/getProfesoresAhAsignar.php', {
            method: 'POST',
            body: buscarprofe,
        });
        if (!respuesta.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const existeProfe = await respuesta.json();

        if (existeProfe.status === 'success') {

            let idtraido = existeProfe.destinatarios[0].id
            const datos = new FormData();
            datos.append("id", cursoAjustado);
            datos.append("profe", idtraido);
            datos.append("vacante", vacan.value);

            const response = await fetch('Modulos/editarCurso.php', {
                method: 'POST',
                body: datos,
            });
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const resultado = await response.json();

            if (resultado.status === 'success') {
                document.getElementById('courses-section').innerHTML = "";
                cargarCursos();
                alert("Ajustes guardados");

            } else if (resultado.status === 'error') {
                console.log(resultado.message);
            }

        } else if (existeProfe.status === 'error') {
            console.log(existeProfe.message);
        }

    } catch (error) {
        console.error("Fallo:", error);
    }
}



//codigo para la eliminacion de los cursos


function asignarbotones2() {
    document.querySelectorAll('.btn-eliminar').forEach(boton => {
        boton.addEventListener("click", function () {
            let id = this.value;
            procesarEliminacion(id);
        })
    });

}

async function procesarEliminacion(curso) {
    try {
        const datos = new FormData();
        datos.append("id", curso);
        const response = await fetch('Modulos/deletCurso.php', {
            method: 'POST',
            body: datos,
        });
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const resultado = await response.json();

        if (resultado.status === 'success') {
            document.getElementById('courses-section').innerHTML = "";
            cargarCursos();
            alert("Se elimino correctamente el curso");

        } else if (resultado.status === 'error') {
            console.log(resultado.message);
        }
    } catch (error) {
        console.error("Fallo:", error);
    }
}




window.onload = function () {

    cargarCursos();
    autocompletar();
    document.getElementById("form-guardar-ajuste").addEventListener("submit", ajustesCurso);


}