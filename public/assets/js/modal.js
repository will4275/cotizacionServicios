document.addEventListener("DOMContentLoaded", function () {
    // 1. Selección de elementos
    const modalCarrito = document.getElementById("modalCarrito");
    const modalCotizacion = document.getElementById("modalCotizacion");
    const abrirCarritoBtn = document.getElementById("abrirCarrito");
    const btnVerCarrito = document.getElementById("btnVerCarrito");
    const btnCotizar = document.getElementById("btnCotizar");
    const cerrarModal = document.getElementById("cerrarModal");
    const cerrarModalCotizacion = document.getElementById("cerrarModalCotizacion");

    // 2. Funciones base para mostrar/ocultar
    const abrirModal = (modal) => modal?.classList.add("modal-show");
    const cerrarModalFunc = (modal) => modal?.classList.remove("modal-show");

    // 3. Eventos de apertura y cierre del carrito
    if (abrirCarritoBtn) {
        abrirCarritoBtn.addEventListener("click", () => abrirModal(modalCarrito));
    }
    
    if (btnVerCarrito) {
        btnVerCarrito.addEventListener("click", () => abrirModal(modalCarrito));
    }

    if (cerrarModal) {
        cerrarModal.addEventListener("click", () => cerrarModalFunc(modalCarrito));
    }

    // 4. EVENTO CRÍTICO: Validación de salida hacia Cotización
    if (btnCotizar) {
        // Usamos el tercer parámetro 'true' para fase de captura.
        // Esto permite que este script valide ANTES de que cualquier otra función abra el modal.
        btnCotizar.addEventListener('click', (e) => {
            
            // Verificación de existencia del carrito global
            const carrito = window.cart || [];
            const montoMinimo = 100;

            // Validación A: Carrito vacío
            if (carrito.length === 0) {
                e.preventDefault();
                e.stopImmediatePropagation(); 
                alert("⚠️ El carrito está vacío. Debes seleccionar al menos un servicio.");
                return;
            }

            // Validación B: Monto mínimo ($100)
            const subtotalActual = carrito.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);

            if (subtotalActual < montoMinimo) {
                e.preventDefault();
                e.stopImmediatePropagation(); // Bloquea la apertura del siguiente modal
                
                const faltante = montoMinimo - subtotalActual;
                alert(`¡Casi llegas! 🚀 El monto mínimo para cotizar es $${montoMinimo.toFixed(2)}.\nTe faltan $${faltante.toFixed(2)} en servicios.`);
                return;
            }

            // Si pasa las validaciones:
            console.log("Validación exitosa. Abriendo formulario de cliente...");
            cerrarModalFunc(modalCarrito); // Cerramos el carrito
            abrirModal(modalCotizacion);  // Abrimos el formulario de datos
        }, true); 
    }

    // 5. Cerrar modal de datos de cotización
    if (cerrarModalCotizacion) {
        cerrarModalCotizacion.addEventListener("click", () => cerrarModalFunc(modalCotizacion));
    }

    // Cerrar modales si se hace clic fuera del contenido (opcional pero recomendado)
    window.addEventListener("click", (e) => {
        if (e.target === modalCarrito) cerrarModalFunc(modalCarrito);
        if (e.target === modalCotizacion) cerrarModalFunc(modalCotizacion);
    });
});