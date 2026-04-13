// 1. Persistencia y Variable Global para que modal.js pueda leerla
window.cart = [];
const CART_KEY = 'cotizacionServiciosCart';
const MONTO_MINIMO = 100;

document.addEventListener('DOMContentLoaded', () => {
    // Inicialización
    loadCartFromStorage();
    updateCartUI(); 
    loadCategorias();
    loadServicios(); // Carga inicial de tarjetas de servicios

    // 2. Eventos para botones y filtros
    document.addEventListener('click', (e) => {
        // Botón "Agregar" en las tarjetas
        if (e.target.classList.contains('btn-add-cart')) {
            addToCart({
                id: Number(e.target.dataset.id),
                nombre: e.target.dataset.nombre,
                precio: Number(e.target.dataset.precio)
            });
        }

        // Filtros de categoría
        if (e.target.classList.contains('filtro')) {
            const categoriaId = e.target.getAttribute('data-cat');
            setActiveCategory(e.target);
            loadServicios(categoriaId);
        }    
    });

    // 3. VALIDACIÓN DEL BOTÓN COTIZAR (Intercepta a modal.js)
    const btnCotizar = document.getElementById('btnCotizar');
    if (btnCotizar) {
        btnCotizar.addEventListener('click', (e) => {
            // Validación 1: Carrito vacío
            if (window.cart.length === 0) {
                e.preventDefault();
                e.stopImmediatePropagation();
                alert("⚠️ El carrito está vacío. Debes seleccionar servicios para cotizar.");
                return;
            }

            // Validación 2: Monto mínimo de $100
            const subtotalActual = window.cart.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);
            
            if (subtotalActual < MONTO_MINIMO) {
                e.preventDefault();
                e.stopImmediatePropagation(); // Bloquea la apertura del formulario en modal.js
                
                const faltante = MONTO_MINIMO - subtotalActual;
                alert(`¡Casi llegas! 🚀 El monto mínimo para cotizar es $${MONTO_MINIMO.toFixed(2)}.\nTe faltan $${faltante.toFixed(2)} para cumplir con el requisito.`);
                return;
            }

            console.log("Validación de monto y cantidad OK. Procediendo...");
        }, true); // 'true' asegura que este script se ejecute ANTES que modal.js
    }

    // Botón Vaciar Carrito
    const btnVaciar = document.getElementById('btnVaciarCarrito');
    if (btnVaciar) {
        btnVaciar.addEventListener('click', () => {
            if (confirm('¿Deseas vaciar todos los servicios?')) emptyCart();
        });
    }
});

// --- LÓGICA DE DATOS ---
function loadCartFromStorage() {
    const saved = localStorage.getItem(CART_KEY);
    window.cart = saved ? JSON.parse(saved) : [];
}

function saveCartToStorage() {
    localStorage.setItem(CART_KEY, JSON.stringify(window.cart));
}

function addToCart(servicio) {
    const existing = window.cart.find(i => i.id === servicio.id);
    if (existing) {
        if (existing.cantidad < 10) existing.cantidad++;
        else alert('Límite de 10 unidades alcanzado.');
    } else {
        window.cart.push({ ...servicio, cantidad: 1 });
    }
    saveCartToStorage();
    updateCartUI();
}

function emptyCart() {
    window.cart = [];
    saveCartToStorage();
    updateCartUI();
}

window.changeCartItemQuantity = function(id, delta) {
    const item = window.cart.find(i => i.id === id);
    if (!item) return;
    const nuevaCant = item.cantidad + delta;
    if (nuevaCant <= 0) {
        window.cart = window.cart.filter(i => i.id !== id);
    } else if (nuevaCant <= 10) {
        item.cantidad = nuevaCant;
    }
    saveCartToStorage();
    updateCartUI();
};

window.removeCartItem = function(id) {
    window.cart = window.cart.filter(i => i.id !== id);
    saveCartToStorage();
    updateCartUI();
};

// --- INTERFAZ Y RENDERIZADO ---
function updateCartBadge() {
    const badge = document.getElementById('contadorCarrito'); // El span dentro del botón amarillo
    if (!badge) return;
    const total = window.cart.reduce((sum, item) => sum + item.cantidad, 0);
    badge.textContent = total;
    total > 0 ? badge.classList.remove('d-none') : badge.classList.add('d-none');
}

async function updateCartUI() {
    renderCartList();
    updateCartBadge();
    updateTotals();

    // Feedback visual en el botón Cotizar
    const btnCotizar = document.getElementById('btnCotizar');
    if (btnCotizar) {
        const subtotal = window.cart.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);
        if (subtotal < MONTO_MINIMO) {
            btnCotizar.classList.add('opacity-50');
            btnCotizar.style.cursor = 'not-allowed';
        } else {
            btnCotizar.classList.remove('opacity-50');
            btnCotizar.style.cursor = 'pointer';
        }
    }
}

function renderCartList() {
    const modalLista = document.getElementById('listaCarrito');
    const lateralLista = document.getElementById('contenedorItems');
    const btnVerCarrito = document.getElementById('btnVerCarrito');
    
    const vacioHTML = '<div class="text-center p-3 text-muted small">Sin servicios seleccionados</div>';
    
    if (window.cart.length === 0) {
        if (modalLista) modalLista.innerHTML = vacioHTML;
        if (lateralLista) lateralLista.innerHTML = vacioHTML;
        if (btnVerCarrito) btnVerCarrito.classList.add('d-none');
        return;
    }

    if (btnVerCarrito) btnVerCarrito.classList.remove('d-none');
    
    let html = '';
    window.cart.forEach(item => {
        html += `
            <div class="d-flex justify-content-between align-items-center border-bottom py-2 mb-1 w-100">
                <div style="flex:1" class="pe-2 text-truncate">
                    <div class="fw-bold small">${item.nombre}</div>
                    <span class="text-muted small">$${item.precio.toFixed(2)}</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="btn-group border rounded bg-white" style="transform: scale(0.85);">
                        <button class="btn btn-sm btn-light py-0 px-2" onclick="changeCartItemQuantity(${item.id}, -1)">-</button>
                        <span class="px-2 fw-bold small">${item.cantidad}</span>
                        <button class="btn btn-sm btn-light py-0 px-2" onclick="changeCartItemQuantity(${item.id}, 1)" ${item.cantidad >= 10 ? 'disabled' : ''}>+</button>
                    </div>
                    <button class="btn btn-sm text-danger ms-1" onclick="removeCartItem(${item.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
    });

    if (modalLista) modalLista.innerHTML = html;
    if (lateralLista) lateralLista.innerHTML = html;
}

async function updateTotals() {
    const subtotalEl = document.getElementById('subtotal');
    const descuentoEl = document.getElementById('descuento'); // ID para el descuento
    const ivaEl = document.getElementById('iva');             // ID para el IVA
    const totalEl = document.getElementById('total');
    
    if (window.cart.length === 0) {
        if (subtotalEl) subtotalEl.textContent = '$0.00';
        if (totalEl) totalEl.textContent = '$0.00';
        return;
    }

    try {
        const response = await fetch('../controllers/quote_controller.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                action: 'calculate', // Aseguramos que solo calcule
                items: window.cart 
            })
        });

        const res = await response.json();

        if (res.status === 'success') {
            // Pintamos cada valor si el elemento existe en el HTML
            if (subtotalEl) subtotalEl.textContent = `$${res.subtotal}`;
            if (descuentoEl) descuentoEl.textContent = `$${res.descuento}`;
            if (ivaEl) ivaEl.textContent = `$${res.iva}`;
            if (totalEl) totalEl.textContent = `$${res.total}`;
            
            // Color rojo en el subtotal si no llega al mínimo
            if (subtotalEl) {
                subtotalEl.style.color = parseFloat(res.subtotal) < MONTO_MINIMO ? '#dc3545' : '#198754';
            }
        }
    } catch (e) { 
        console.error("Error al obtener totales del servidor:", e); 
    }
}

// --- CARGA DE API (TARJETAS Y CATEGORÍAS) ---
function loadCategorias() {
    fetch('../API/servicios.php?action=getCategorias')
        .then(res => res.json())
        .then(data => {
            const ul = document.getElementById('categorias');
            if (!ul) return;
            ul.innerHTML = '';
            data.forEach(cat => {
                ul.insertAdjacentHTML('beforeend', `
                    <li class="nav-item mx-2">
                        <button class="border-0 nav-link filtro" data-cat="${cat.categoria_id}">${cat.nombre_categoria}</button>
                    </li>`);
            });
        });
}

function loadServicios(categoriaId = null) {
    let url = '../API/servicios.php?action=getServicios';
    if (categoriaId) url += '&categoria_id=' + categoriaId;
    
    fetch(url).then(res => res.json()).then(data => {
        const contenedor = document.getElementById('contenedorServicios');
        if (!contenedor) return;
        contenedor.innerHTML = '';
        data.forEach(serv => {
            contenedor.insertAdjacentHTML('beforeend', `
                <div class="col-md-4 col-12 my-2 d-flex">
                    <div class="card border-1 rounded-3 h-100 w-100 shadow-sm">
                        <div class="card-body text-center d-flex flex-column">
                            <h4 class="card-title fw-bold">${serv.nombre_servicio}</h4>
                            <p class="card-text text-muted small">${serv.descripcion_servicio}</p>
                            <p class="h3 my-3 text-primary fw-bold">$${parseFloat(serv.precio).toFixed(2)}</p>
                            <button class="btn btn-outline-primary mt-auto btn-add-cart" 
                                data-id="${serv.servicio_id}" data-nombre="${serv.nombre_servicio}" data-precio="${serv.precio}">Agregar</button>
                        </div>
                    </div>
                </div>`);
        });
    });
}

function setActiveCategory(button) {
    document.querySelectorAll('.filtro').forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
}



// Buscamos el formulario por su ID
const formCotizacion = document.getElementById('formCotizacion');

if (formCotizacion) {
    formCotizacion.addEventListener('submit', async (e) => {
        e.preventDefault(); // Evita que la página se recargue

        // Validamos que el carrito no esté vacío
        if (!window.cart || window.cart.length === 0) {
            alert("El carrito está vacío.");
            return;
        }

        // Extraemos los datos usando FormData (más limpio)
        const formData = new FormData(formCotizacion);
        
        const payload = {
            action: 'save',
            items: window.cart,
            cliente: {
                nombre: formData.get('nombre'),
                empresa: formData.get('empresa'),
                email: formData.get('email'),
                telefono: formData.get('telefono')
            }
        };

        try {
            const response = await fetch('../controllers/quote_controller.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const res = await response.json();

            if (res.status === 'success') {
                alert(`✅ Cotización generada: ${res.id_cotizacion}`);
                
                // Limpiar todo y cerrar
                window.cart = [];
                localStorage.removeItem('cotizacionServiciosCart'); 
                location.reload(); 
            } else {
                alert("Error: " + res.message);
            }
        } catch (error) {
            console.error("Error en la petición:", error);
            alert("No se pudo conectar con el servidor.");
        }
    });
}