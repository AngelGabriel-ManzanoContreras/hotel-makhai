document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".main-form");
  const inputs = form.querySelectorAll(".it-v"); // Inputs que se van a validar
  const updateButton = document.getElementById("update-btn"); // Botón de actualizar
  const updtSection = document.getElementById("updt-sect"); // Sección de actualizar
  updtSection.style.display = "none"; // Oculta la sección de actualizar

  const initialValues = {};

  inputs.forEach((input) => {
    // Guarda los valores iniciales de los inputs
    initialValues[input.name] = input.value;
    input.addEventListener("input", checkChanges); // Agrega el evento input a los inputs
  });

  function checkChanges() {
    let changesDetected = false; // Bandera para saber si se detectaron cambios

    inputs.forEach((input) => {
      // Si el valor del input es diferente al valor inicial
      if (input.value.trim() !== (initialValues[input.name] || "").trim()) {
          changesDetected = true; // Cambia la bandera a true
          return;
      }
    });

    // Si se detectaron cambios, muestra la sección de actualizar
    if (changesDetected) updtSection.style.display = "flex";
    // Si no se detectaron cambios, oculta la sección de actualizar
    else updtSection.style.display = "none";
  }

  checkChanges();

  updateButton.addEventListener("click", function () {
    // Al dar click en el botón de actualizar, pregunta si está seguro de actualizar los datos
    const confirmUpdate = window.confirm("¿Estás seguro de actualizar los datos?");
    // Si confirma, envía el formulario
    if (confirmUpdate) form.submit();
  });
});
