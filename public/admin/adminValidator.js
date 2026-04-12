let services = [];

document.querySelector(".header").addEventListener("click", function () {
  document.querySelector(".header").classList.add("negro");
});

async function loadService() {
  try {
    const peticion = await fetch("/service", { method: "GET" });
    const respuesta = await peticion.json();

    // Guardamos los datos recibidos
    services = respuesta;

    // Renderizamos el HTML
    renderService();
  } catch (EX) {
    console.error("Error al cargar servicios:", EX);
  }
}

function renderService() {
  const divService = document.querySelector("#servicesList");
  let concatenator = "";

  services.forEach((t) => {
    // Validación segura por si el estado viene nulo de la DB
    const estadoDb = t.estado ? t.estado.toLowerCase() : "activo";
    const isActivo = !estadoDb.includes("inactivo");

    concatenator += `
        <div class="service-item form-card">
            <div class="service-info">
              <h3>${t.nombre}</h3>
              <p>${t.descripcion}</p>
              ${isActivo ? '<span class="badge badge-activo">Activo</span>' : '<span class="badge badge-inactivo">Inactivo</span>'}
            </div>
            <div class="service-actions">
              <button data-id="${t.id}" class="btn btn-secondary btn-sm btn-edit" title="Editar">
                <i class="bi bi-pencil"></i>
              </button>
              <button data-id="${t.id}" class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                <i class="bi bi-trash"></i>
              </button>
            </div>
        </div>`;
  });

  divService.innerHTML = concatenator;

  setEvents();
}

// Evento principal al cargar la página
document.addEventListener("DOMContentLoaded", async function () {
  await loadService();
});

// Crear nuevo servicio
document
  .querySelector("#crudForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: form.method,
        body: formData,
      });

      const data = await response.json();
      console.log(data);

      // Notificamos y recargamos para ver el nuevo servicio
      alert("Servicio creado correctamente.");
      location.reload();
    } catch (error) {
      console.error("Error:", error);
      alert("Ocurrió un error al crear el servicio.");
    }
  });

// Guardar edición
document
  .querySelector("#formEditService")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: "PATCH",
        body: formData,
      });

      const data = await response.json();
      console.log(data);
      alert(`Servicio actualizado. Se va a reiniciar la página.`);
      location.reload();
    } catch (error) {
      console.error("Error:", error);
    }
  });

function setEvents() {
  // Btn Delete
  document.querySelectorAll(".btn-delete").forEach((button) => {
    button.addEventListener("click", async function (e) {
      const id = this.dataset.id;

      if (!id) {
        console.error("No se encontró el data-id del botón delete");
        return;
      }

      const confirmDelete = confirm(
        `¿Deseas eliminar el servicio con ID ${id}?`,
      );
      if (!confirmDelete) return;

      try {
        const response = await fetch("/service", {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id }),
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

  // Btn Edit
  document.querySelectorAll(".btn-edit").forEach((button) => {
    button.addEventListener("click", async function (e) {
      const id = this.dataset.id;

      if (!id) return;

      try {
        const response = await fetch(`/service?id=${id}`, {
          method: "GET",
        });
        const data = await response.json();

        // Llenamos el modal
        openEditModal(data.service[0]);
      } catch (error) {
        console.error("Error al cargar datos para editar:", error);
      }
    });
  });
}

// GESTIÓN DEL MODAL
const modal = document.getElementById("modalEdit");

function openEditModal(servicio) {
  document.getElementById("idService").value = servicio.id;
  document.getElementById("editNombre").value = servicio.nombre;
  document.getElementById("editDescripcion").value = servicio.descripcion;

  // CORRECCIÓN: Forzamos minúsculas para que el <select> haga "match" con sus options
  const selectEstado = document.getElementById("editEstado");
  const estadoGuardado = servicio.estado
    ? servicio.estado.toLowerCase()
    : "activo";
  selectEstado.value = estadoGuardado.includes("inactivo")
    ? "inactivo"
    : "activo";

  modal.style.display = "flex";
}

function closeModal() {
  modal.style.display = "none";
}

// Cerrar modal si hace click fuera
window.onclick = (e) => {
  if (e.target == modal) closeModal();
};
