<?php
session_start();

// 🔐 PROTECCIÓN DE ACCESO
if (empty($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// (Opcional) datos del usuario
$userName = $_SESSION['user_name'];
$userRole = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/assets/css/styles.css">
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Font Awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
</head>
<body>

    <header class="my-2 ">
        <div class="container-lg  justify-content-between d-flex align-items-center">
            <h1 class="">Sistema de cotizaciones</h1>
            <div class="d-flex align-items-center">
              <a href="view-quotes.php" class="me-2 btn btn-outline-primary btn-lg">
                  <i class="bi bi-file-earmark-text "></i>
                  Cotizaciones
              </a> 
               <a href="auth/logout.php" class="me-2 btn btn-danger btn-lg">Cerrar sesión</a>
              <button type="button" class="btn btn-warning btn-lg px-60" id="abrirCarrito">
                    <i class="bi bi-cart-fill"></i>
                    <span id="contadorCarrito"
                    class=" translate-middle badge rounded-pill  d-none p-0 m-0">0</span>
                </button>
                 
            </div>
        </div>
        
    </header>
   
<!-- Modal -->
<section class="modal" id="modalCarrito">
  <div class="carrito">
    <h2>Carrito de Compras</h2>
    <div class="close" id="cerrarModal">
      <i class="bi bi-x-circle-fill"></i>
    </div>
    <div id="listaCarrito" class="cart-items"></div>

    <div class="cart-summary w-100">
      <div class="row g-2 align-items-center mb-3">
        <div class="col-12 col-md-6">
          <label class="form-label mb-1">Descuento</label>
          <div class="fw-bold">5%</div>
        </div>
        <div class="col-12 col-md-6 text-md-end">
          <label class="form-label mb-1">Subtotal</label>
          <div class="fw-bold" id="subtotal">0.00</div>
        </div>
      </div>
      <div class="d-flex justify-content-between mb-2">
        <strong>Descuento:</strong>
        <span id="descuento">$0.00</span>
      </div>
      <div class="d-flex justify-content-between mb-2">
        <strong>IVA (13%):</strong>
        <span id="iva">0.00</span>
      </div>
      <div class="d-flex justify-content-between fs-4 mb-3">
        <strong>Total:</strong>
        <span id="total">0.00</span>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <button id="btnVaciarCarrito" class="btn btn-danger flex-fill">Vaciar carrito</button>
        <button id="btnCotizar" class="btn btn-primary flex-fill">Cotizar</button>
      </div>
    </div>
  </div>
</section>
<section class="modal" id="modalCotizacion">
  <div class="carrito">
    <h2>Datos del Cliente</h2>
    <div class="close" id="cerrarModalCotizacion">
      <i class="bi bi-x-circle-fill"></i>
    </div>

    <form id="formCotizacion">
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Empresa</label>
        <input type="text" name="empresa" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success w-100">
        Generar Cotización
      </button>
    </form>
  </div>
</section>
<section class="modal" id="modalConfirmacion">
  <div class="carrito">
    <h2>Cotización Generada</h2>
    <div class="close" id="cerrarModalConfirmacion">
      <i class="bi bi-x-circle-fill"></i>
    </div>
    <div id="detalleConfirmacion"></div>
  </div>
</section>
<!--fin del modal -->    

    <div class="container-lg">
        <div class="">
            <ul class="navbar-nav d-flex flex-row justify-content-around text-center" id="categorias">
                 <!-- Categories will be loaded here -->
            </ul>
        </div>
        <!--aqui iran las cartas-->
        <div class="row align-items-start">
            <div class="col-md-12 col-lg-8 my-2">
                <div class="row justify-content-center" id="contenedorServicios">
                <!-- Cards will be loaded here -->
                </div>
            </div>
           <div class="stickySection col-4 d-none d-lg-block my-3">
                
                <ol class="list-group h-100 overflow-auto" id="listaSeleccionados">
                    <li class="bg-success list-group-item d-flex justify-content-between align-items-center">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">Servicios seleccionados</div>
                    </div>
                    </li>

                 
                    <div id="contenedorItems"></div>

                    <li class="border-0 my-3 d-flex justify-content-end align-items-center">
                        <button id="btnVerCarrito"
                            class="btn btn-sm bg-success rounded text-center py-2 px-2 d-none">
                            Ver items en carrito
                        </button>    
                    </li>
                </ol>

            </div>
        </div>
            
        

    </div>
    

<script>
    window.APP_BASE_URL = '/cotizacionServicios';
</script>
<script src="/cotizacionServicios/public/assets/js/modal.js"></script>
<script src="/cotizacionServicios/public/assets/js/services.js"></script>

</body>
</html>
