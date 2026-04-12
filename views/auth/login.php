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


    <div class="d-flex flex-col align-items-center justify-content-center gap-sm-5 p-sm-3 p-lg-5 mx-5 mt-3 mb-3 loginContainer">
        
      
        <div class="col-6 pe-5 ">

            <h1>
                Iniciar sesión
            </h1>
            <div class="mt-5 marginUpdate">
                <p class="custom-line">bienvenido de nuevo.</p>
                <p class="custom-line">ingresa para continuar.</p>
            </div>
                <form action="">
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

                <div class="d-grid">
                    <p class="text-end">no tienes cuenta? <a href="">Registrate</a></p>
                    <button type="button" class="btn btn-primary" id="btnSubmit">
                        Iniciar sesión
                    </button>
                    <p id="errorMessage"></p>
                </div>
                </form>            
        </div>

    </div>

    <script>

    </script>
    <script></script>
</body>
</html>