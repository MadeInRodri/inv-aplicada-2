document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contactForm");

  form.addEventListener("submit", function (e) {
    // Evitamos que el navegador recargue o viaje a /contacts
    e.preventDefault();

    // Recolectamos los datos del formulario
    const formData = new FormData(this);

    // Convertimos los datos al formato urlencoded que espera tu PHP
    const data = new URLSearchParams(formData);

    // Enviamos la petición asíncrona (AJAX) usando Fetch
    fetch("/contacts", {
      method: "POST",
      body: data,
    })
      .then((response) => response.json()) // Convertimos la respuesta cruda a JSON
      .then((result) => {
        // Evaluamos el JSON que mandó el DataController
        if (result.success) {
          alert("¡Gracias! " + result.message);
          // Redireccionamos al index
          window.location.href = "/";
        } else {
          alert("Hubo un problema: " + result.message);
        }
      })
      .catch((error) => {
        console.error("Error de red:", error);
        alert("Ocurrió un error al intentar comunicar con el servidor.");
      });
  });
});
