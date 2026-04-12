document.addEventListener("DOMContentLoaded", function () {
  // ======================
  // MODALES
  // ======================
  const modalCarrito = document.getElementById("modalCarrito");
  const modalCotizacion = document.getElementById("modalCotizacion");
  // ======================
  // BOTONES
  // ======================
  const abrirCarritoBtn = document.getElementById("abrirCarrito");
  const btnCotizar = document.getElementById("btnCotizar");
  // ======================
  // BOTONES DE CERRAR
  // ======================
  const cerrarModal = document.getElementById("cerrarModal");
  const cerrarModalCotizacion = document.getElementById("cerrarModalCotizacion");
  // ======================
  // FUNCIONES
  // ======================
  function abrirModal(modal) {
    if (modal) modal.classList.add("modal-show");
  }

  function cerrarModalFunc(modal) {
    if (modal) modal.classList.remove("modal-show");
  }
  // ======================
  // EVENTOS
  // ======================
  //Abrir carrito
  if (abrirCarritoBtn) {
    abrirCarritoBtn.addEventListener("click", function () {
      abrirModal(modalCarrito);
    });
  }
  //Cerrar carrito
  if (cerrarModal) {
    cerrarModal.addEventListener("click", function () {
      cerrarModalFunc(modalCarrito);
    });
  }
  //Ir a cotización
  if (btnCotizar) {
    btnCotizar.addEventListener("click", function () {
      cerrarModalFunc(modalCarrito);
      abrirModal(modalCotizacion);
    });
  }
  //cerrar cotización
  if (cerrarModalCotizacion) {
    cerrarModalCotizacion.addEventListener("click", function () {
      cerrarModalFunc(modalCotizacion);
    });
  }

});