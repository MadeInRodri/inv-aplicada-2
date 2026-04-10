
let services = [
    {
        id: 0,
        nombre: '',
        descripcion: '',
        activo: false
    }
]
document.querySelector('.header').addEventListener('click', function (){
    document.querySelector('.header').classList.add('negro');
});

async function loadService(){
    try{
        const peticion  = await fetch('/service', {
            method: 'GET',
        });
        const respuesta = await peticion.json();
        let indice = 0;
        respuesta.forEach(t => {
            services[indice] = t;
            indice++;
        });
        renderService();
    }catch(EX){
        console.log('VALIO SHET', EX)
    }

}


function renderService(){
    let awa = 'asdasd';
    awa.to
    const divService = document.querySelector('#servicesList');
    let concatenator;
    console.log(services);
    services.forEach(t => {
        concatenator += `
        <div class="service-item form-card">
            <div class="service-info">
              <h3>${t.nombre}</h3>
              <p>
                ${t.descripcion}
              </p>
             
              ${t.estado.toLowerCase().includes('inactivo') ? '<span class="badge badge-inactivo">Inactivo</span>': '<span class="badge badge-activo">Activo</span>'}
            </div>
            <div class="service-actions">
              <button data-id=${t.id} class="btn btn-secondary btn-sm btn-edit" title="Editar">
                <i class="bi bi-pencil"></i>
              </button>
              <button data-id=${t.id} class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                <i class="bi bi-trash"></i>
              </button>
            </div>
        </div>`
    })
    divService.innerHTML = concatenator;
}


//Eventitos

document.addEventListener("DOMContentLoaded", async function () {
    await loadService();
    setEvents();
});

document.querySelector('#crudForm').addEventListener('submit', async function(e){
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form); 

    try {
        const response = await fetch(form.action, {
            method: form.method,
            body: formData
        });

        const data = await response.json(); 

        console.log(data);

    } catch (error) {
        console.error("Error:", error);
    }
});

document.querySelector('#formEditService').addEventListener('submit', async function(e){
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form); 

    try {
        const response = await fetch(form.action, {
            method: 'PATCH',
            body: formData
        });

        const data = await response.json(); 

        console.log(data);
        confirm(`Servicio actualiado. Se va a reinicar la pagina.`);
        location.reload();

    } catch (error) {
        console.error("Error:", error);
    }
});

function setEvents(){
    //Btn Delete
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', async function (e) {
            const id = this.dataset.id;
            console.log(e.target);
            if (!id) {
                console.error("No se encontró el data-id del botón delete");
                return;
            }

            const confirmDelete = confirm(`¿Deseas eliminar el servicio con ID ${id}?`);
            if (!confirmDelete) return;

            try {
                const response = await fetch('/service', {
                    method: 'DELETE', 
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message || "Servicio eliminado correctamente");

                    location.reload();
                } else {
                    alert(data.message || "No se pudo eliminar");
                }

            } catch (error) {
                console.error("Error al eliminar:", error);
            }
        });
    });
    //Btn Edit
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', async function (e) {
            const id = this.dataset.id;

            if (!id) {
                console.error("No se encontró el data-id del botón edit");
                return;
            }

            try {
                const response = await fetch(`/service?id=${id}`, {
                    method: 'GET',
                });

                const data = await response.json();

              

                // Aquí llenas tu formulario con los datos
                openEditModal(data.service[0]);
                

            } catch (error) {
                console.error("Error al cargar datos para editar:", error);
            }
        });
    });
}


//PAL MODAL 
const modal = document.getElementById('modalEdit');
const form = document.getElementById('formEditService');


function openEditModal(servicio) {
    document.getElementById('editNombre').value = servicio.nombre;
    document.getElementById('editDescripcion').value = servicio.descripcion;
    document.getElementById('editEstado').value = servicio.estado;
    document.getElementById('idService').value = servicio.id;
    modal.style.display = 'flex';
}

function closeModal() {
    modal.style.display = 'none';
}

// Cerrar si hace click fuera del contenido
window.onclick = (e) => { if (e.target == modal) closeModal(); };