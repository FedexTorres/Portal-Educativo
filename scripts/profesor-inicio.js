
function errorTipo(donde,mensaje){
    const divInicio = document.getElementById(donde);
    let erroralert = document.createElement("div");
    erroralert.setAttribute("class","alert alert-danger");
    erroralert.innerHTML=mensaje;
    divInicio.append(erroralert);
}

function limpiarErrorProfe(campo){
    document.getElementById(campo).innerHTML="";
}

function AcomodarmisCursosProfesor(info){
    const divInicio = document.getElementById("misCursosTraidos");
    divInicio.innerHTML = '';

    info.forEach(curso => {
        const cardHTML = `
        <div class="col-lg-12">
        <div class="card mt-4">

        <div class="card-header collapsible">
          ${curso.nombre} | Fecha de Inicio: ${curso.fecha_inicio}| Fecha de Fin: ${curso.fecha_fin}
        </div>

        <div class="content">
        <div class="card-body">
          <img src="${curso.imagen_url}" alt="${curso.nombre}" class="card-img imgadmin">
          <p class="card-text">Descripcion</p>
          <p class="card-text">${curso.descripcion}</p>
          <h6 class="card-subtitle mb-2 text-muted" id="profe${curso.id}"></h6>
        </div>

        <div class="card-footer">
          <h6 class="card-text">Materiales de Estudo:</h6>
          <button type="button" class="btn btn-primary btn-materiales" data-bs-toggle="modal" data-bs-target="#myModal" value="${curso.id}">Agregar Material</button>
          <br>
          <div id="card${curso.id}"></div>
        </div>
        </div>
        
        </div>
        </div>
        `;
        divInicio.innerHTML += cardHTML;


    });

    acordeon();
}

let idCursoActual ="";

function acordeon(){
    document.querySelectorAll('.collapsible').forEach(element => {
        element.addEventListener("click",function(){
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
                content.style.maxHeight = null;
              } else {
                content.style.maxHeight = content.scrollHeight + "px";
              }
        })
    })

    document.querySelectorAll('.btn-materiales').forEach(boton =>{
        boton.addEventListener("click",function(){
            idCursoActual=this.value;
        })
    })
}

async function misCursosProfesor() {
    limpiarErrorProfe("misCursosTraidos");
    try {
        const response = await fetch('Modulos/getMisCursosProfesor.php');
        const data = await response.json();

        if (data.status === 'success') {
            AcomodarmisCursosProfesor(data.cursos);      
            tieneMaterial(data.cursos);

        } else {       
            errorTipo("misCursosTraidos",data.message);     
        }
    } catch (error) {
        errorTipo("misCursosTraidos",error.message);
    }
}

function tieneMaterial(cursos) {
    cursos.forEach(curso=>{
        traerMateriales(curso.id);
    })}


async function traerMateriales(params) {
    limpiarErrorProfe("card"+params);
    try {
        const data = new FormData();
        data.append("id",params);
        const response = await fetch('Modulos/getMateriales.php', {
            method: "POST",
            body: data,
        });
        const resultado = await response.json();

        if (resultado.status === 'success') {

            acomodarMateriales("card"+params,resultado.cursos);

        } else {       
            errorTipo("card"+params,resultado.message);     
        }
    } catch (error) {
        errorTipo("card"+params,error.message);
    }
}

function acomodarMateriales(id,datos){
    let destino = document.getElementById(id);

    datos.forEach(materia =>{
      
        let listamate = `<ul>
            <li>${materia.titulo}</li>
            <li>${materia.descripcion}</li>
            <li><a target="_black" href="${materia.ruta_archivo}" >Ver Archivo pagina</a></li>
            </ul>
             `;

        destino.innerHTML += listamate;
    })
}


async function subirmaterial(e){
    e.preventDefault();

    let nombre = document.getElementById("title");
    let descripcion = document.getElementById("description");
    let archivo = document.getElementById("file");

    let esValido = true;
    limpiarErrorProfe("errorGlobalProfesor");

    if (nombre.value.trim() === '') {
        errorTipo('errorGlobalProfesor', 'El nombre no puede estar vacía.');
        esValido = false;
    }
    if (descripcion.value.trim() === '') {
        errorTipo('errorGlobalProfesor', 'La descripcion no puede estar vacía.');
        esValido = false;
    }
    if (archivo.value.trim() === '') {
        errorTipo('errorGlobalProfesor', 'El Archivo no puede estar vacía.');
        esValido = false;
    }


    if (esValido) {
        try {
            let elform = document.getElementById("form1");

            const data = new FormData(elform);
            //data.append("nombre",nombre.value);
            //data.append("descripcion",descripcion.value);
            //data.append("archivo",archivo);
            data.append("idcurso",idCursoActual);

            const response = await fetch('Modulos/InsertMaterial.php', {
                method: "POST",
                body: data,
            });
            const resultado = await response.json();

            if (resultado.status === 'success') {
                //algo
                //muestro los materiles
                traerMateriales(idCursoActual);

            } else {       
                errorTipo('errorGlobalProfesor',resultado.message);     
            }
        } catch (error) {
            errorTipo('errorGlobalProfesor',error.message); 
        }
    }
}


window.onload = function() {

    misCursosProfesor();
    document.getElementById("form1").addEventListener("submit",subirmaterial);
}