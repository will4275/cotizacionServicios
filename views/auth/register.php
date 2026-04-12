<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/assets/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    
    <title>Document</title>
</head>
<body class="d-flex align-items-center justify-content-center bodyCustom" style="height: 100vh;">


    <div class="p-lg-5 mx-5 mt-3 mb-3 loginContainer">
        
        <div class="">
            <h1 class="d-4"><span>Tu</span>Técnico</h1>
        </div>
        <div class="d-flex justify-content-center align-items-center flex-column gap-2">
            <div class="text-center">
                <h1>
                    Registrate
                </h1>
                <div class="mt-3 ">
                    <p class="custom-line">Registrate para continuar</p>
                   
                </div>
            </div>
                <form class="col-8">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-control" 
                            placeholder="nombre" 
            
                        >

                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="Ingresa tu email" 
            
                        >

                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control p-2" 
                            placeholder="Contraseña"
                            
                        >

                    </div>  
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirmar contraseña</label>
                        <input 
                            type="password" 
                            id="confirmPassword" 
                            name="confirmPassword" 
                            class="form-control" 
                            placeholder="confirma tu contraseña" 
            
                        >
                    </div>

                    
                    
                </div>

                <div class="col-12">
                    
                   
                   

                    

                    <div class="d-flex flex-column">
                        <p class="text-end mb-2">ya tienes cuenta? <a href="">inicia sesión</a></p>
                        <button type="button" id="button12" class="btn btn-primary">
                            Registrarse
                        </button>
                        <p class="text-center" id="validateAJAX">
                            <?php /*echo $validate;*/?>
                        </p>
                    </div>

                </div>
                </form>            
        </div>

    </div>
    <script>
    </script>
</body>
</html>