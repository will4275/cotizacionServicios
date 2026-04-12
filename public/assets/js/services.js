const CART_KEY = 'cotizacionServiciosCart';
const cart = [];
const discountPercent = 5;

console.log('services.js loaded');

function loadCartFromStorage() {
    const saved = localStorage.getItem(CART_KEY);
    if (!saved) return;
    try {
        const parsed = JSON.parse(saved);
        if (Array.isArray(parsed)) {
            parsed.forEach(item => {
                if (item && typeof item.id !== 'undefined') {
                    cart.push({
                        id: Number(item.id),
                        nombre: item.nombre,
                        precio: Number(item.precio),
                        cantidad: Number(item.cantidad)
                    });
                }
            });
        }
    } catch (error) {
        console.error('Cart storage parse error:', error);
    }
}

function saveCartToStorage() {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

document.addEventListener('DOMContentLoaded', function() {
    loadCartFromStorage();
    updateCartUI();
    loadCategorias();
    loadServicios();

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('filtro')) {
            const categoriaId = e.target.getAttribute('data-cat');
            setActiveCategory(e.target);
            loadServicios(categoriaId);
        }

        if (e.target.classList.contains('btn-add-cart')) {
            const servicioId = Number(e.target.dataset.id);
            const nombre = e.target.dataset.nombre;
            const precio = Number(e.target.dataset.precio);
            addToCart({ id: servicioId, nombre, precio });
        }

        if (e.target.classList.contains('cart-item-qty-button')) {
            const servicioId = Number(e.target.dataset.id);
            const action = e.target.dataset.action;
            if (action === 'increase') {
                changeCartItemQuantity(servicioId, 1);
            } else if (action === 'decrease') {
                changeCartItemQuantity(servicioId, -1);
            }
        }
    });

    const btnVaciarCarrito = document.getElementById('btnVaciarCarrito');
    if (btnVaciarCarrito) {
        btnVaciarCarrito.addEventListener('click', function() {
            if (confirm('¿Deseas vaciar el carrito completo?')) {
                emptyCart();
            }
        });
    }
});

function setActiveCategory(button) {
    document.querySelectorAll('.filtro').forEach(btn => {
        btn.classList.remove('active');
    });
    button.classList.add('active');
}

function loadCategorias() {
    const apiBase = window.APP_BASE_URL || '';
    fetch(`${apiBase}/API/servicios.php?action=getCategorias`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const categoriasUl = document.getElementById('categorias');
            categoriasUl.innerHTML = '';
            data.forEach(cat => {
                const li = document.createElement('li');
                li.className = 'nav-item mx-2';
                const button = document.createElement('button');
                button.className = 'border-0 nav-link filtro';
                button.setAttribute('data-cat', cat.categoria_id);
                button.textContent = cat.nombre_categoria;
                li.appendChild(button);
                categoriasUl.appendChild(li);
            });
        })
        .catch(error => console.error('Error loading categories:', error));
}

function loadServicios(categoriaId = null) {
    const apiBase = window.APP_BASE_URL || '';
    let url = `${apiBase}/API/servicios.php?action=getServicios`;
    if (categoriaId) {
        url += '&categoria_id=' + categoriaId;
    }
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const contenedor = document.getElementById('contenedorServicios');
            contenedor.innerHTML = '';
            data.forEach(servicio => {
                const col = document.createElement('div');
                col.className = 'col-md-4 col-lg-6 col-xl-4 col-5 my-2 d-flex';
                const card = document.createElement('div');
                card.className = 'card border-1 rounded-3 shadow-sm h-100 w-100';
                const cardBody = document.createElement('div');
                cardBody.className = 'card-body text-center py-3 align-items-center d-flex flex-column';
                cardBody.innerHTML = `
                    <h4 class="card-title">${servicio.nombre_servicio}</h4>
                    <p class="lead card-subtitle">${servicio.descripcion_servicio}</p>
                    <p class="display-5 my-4 text-primary fw-bold">$${parseFloat(servicio.precio).toFixed(2)}</p>
                    <p class="card-text mx-5 text-muted d-none d-lg-block">${servicio.nombre_categoria}</p>
                `;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-primary btn-lg mt-auto btn-add-cart';
                btn.dataset.id = servicio.servicio_id;
                btn.dataset.nombre = servicio.nombre_servicio;
                btn.dataset.precio = parseFloat(servicio.precio);
                btn.textContent = 'Agregar';

                cardBody.appendChild(btn);
                card.appendChild(cardBody);
                col.appendChild(card);
                contenedor.appendChild(col);
            });
        })
        .catch(error => console.error('Error loading services:', error));
}

function addToCart(servicio) {
    const existing = cart.find(item => item.id === servicio.id);
    if (existing) {
        if (existing.cantidad >= 10) {
            alert('Máximo 10 unidades por servicio.');
            return;
        }
        existing.cantidad += 1;
    } else {
        cart.push({ ...servicio, cantidad: 1 });
    }
    saveCartToStorage();
    updateCartUI();
}

function changeCartItemQuantity(servicioId, delta) {
    const item = cart.find(entry => entry.id === servicioId);
    if (!item) return;

    const newQuantity = item.cantidad + delta;
    if (newQuantity <= 0) {
        removeCartItem(servicioId);
        return;
    }
    if (newQuantity > 10) {
        alert('Máximo 10 unidades por servicio.');
        return;
    }

    item.cantidad = newQuantity;
    saveCartToStorage();
    updateCartUI();
}

function removeCartItem(servicioId) {
    const index = cart.findIndex(entry => entry.id === servicioId);
    if (index !== -1) {
        cart.splice(index, 1);
        saveCartToStorage();
        updateCartUI();
    }
}

function emptyCart() {
    cart.length = 0;
    saveCartToStorage();
    updateCartUI();
}

function updateCartUI() {
    const contador = document.getElementById('contadorCarrito');
    const contenedorItems = document.getElementById('contenedorItems');
    const listaCarrito = document.getElementById('listaCarrito');
    const btnVerCarrito = document.getElementById('btnVerCarrito');
    const subtotalElem = document.getElementById('subtotal');
    const descuentoElem = document.getElementById('descuento');
    const ivaElem = document.getElementById('iva');
    const totalElem = document.getElementById('total');

    if (!contador || !contenedorItems || !listaCarrito || !subtotalElem || !descuentoElem || !ivaElem || !totalElem) return;

    const totalItems = cart.reduce((sum, item) => sum + item.cantidad, 0);
    contador.textContent = totalItems;
    contador.classList.toggle('d-none', totalItems === 0);

    contenedorItems.innerHTML = '';
    listaCarrito.innerHTML = '';

    if (cart.length === 0) {
        contenedorItems.innerHTML = '<li class="list-group-item">No hay servicios seleccionados.</li>';
        listaCarrito.innerHTML = '<div class="p-3 text-center text-muted">No hay servicios en el carrito.</div>';
        btnVerCarrito?.classList.add('d-none');
        subtotalElem.textContent = '0.00';
        descuentoElem.textContent = '$0.00';
        ivaElem.textContent = '0.00';
        totalElem.textContent = '0.00';
        return;
    }

    cart.forEach(item => {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `
            <div>
                <strong>${item.nombre}</strong>
                <div class="small text-muted">$${parseFloat(item.precio).toFixed(2)} x ${item.cantidad}</div>
            </div>
            <div class="d-flex gap-1 align-items-center">
                <button type="button" class="btn btn-sm btn-outline-secondary cart-item-qty-button" data-action="decrease" data-id="${item.id}">-</button>
                <span class="badge bg-primary rounded-pill">${item.cantidad}</span>
                <button type="button" class="btn btn-sm btn-outline-secondary cart-item-qty-button" data-action="increase" data-id="${item.id}">+</button>
            </div>
        `;
        contenedorItems.appendChild(li);

        const modalItem = document.createElement('div');
        modalItem.className = 'd-flex justify-content-between align-items-center mb-2';
        modalItem.innerHTML = `
            <div>
                <strong>${item.nombre}</strong>
                <div class="small text-muted">$${parseFloat(item.precio).toFixed(2)} x ${item.cantidad}</div>
            </div>
            <div class="d-flex gap-1 align-items-center">
                <button type="button" class="btn btn-sm btn-outline-secondary cart-item-qty-button" data-action="decrease" data-id="${item.id}">-</button>
                <span class="badge bg-primary rounded-pill">${item.cantidad}</span>
                <button type="button" class="btn btn-sm btn-outline-secondary cart-item-qty-button" data-action="increase" data-id="${item.id}">+</button>
            </div>
        `;
        listaCarrito.appendChild(modalItem);
    });

    const subtotal = cart.reduce((sum, item) => sum + parseFloat(item.precio) * item.cantidad, 0);
    const discountAmount = subtotal * (discountPercent / 100);
    const taxableAmount = Math.max(0, subtotal - discountAmount);
    const iva = taxableAmount * 0.13;
    const total = taxableAmount + iva;

    subtotalElem.textContent = subtotal.toFixed(2);
    descuentoElem.textContent = `$${discountAmount.toFixed(2)}`;
    ivaElem.textContent = iva.toFixed(2);
    totalElem.textContent = total.toFixed(2);

    btnVerCarrito?.classList.remove('d-none');
}
