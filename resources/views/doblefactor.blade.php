<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
 
</head>
<body>
    <br>
    <h1 style="text-align: center">Autenticacion por doble factor</h1>


  <form action="{{ route('verificarDobleFactor') }}" method="POST" class="mx-auto my-5" style="max-width: 400px;">
    @csrf
    <div class="mb-3">
        <label for="code" class="form-label">Introduce el código enviado al correo</label>
        <input type="text" class="form-control" value="{{ old('code') }}"  id="code" name="code" required maxlength="6">
        @error('error')
            <p>{{ $message }}</p>
        @enderror
        @if($errors->has('doble_error'))
    <p style="color:red">{{ $errors->first('doble_error') }}</p>
@endif
    </div>

    <br>
    @error('g-recaptcha-response')
        <p>el captcha es obligatorio</p>
    @enderror
    @if($errors->has('VF_error'))
    <p style="color:red">{{ $errors->first('VF_error') }}</p>
@endif
    <div class="g-recaptcha" data-sitekey="6LcTu14pAAAAAD9y4uR8usL9xJCbEZU_LY893mhu" ></div>
  <br>
    <button type="submit" class="btn btn-primary">Verificar Código</button>
    @if($errors->has('registro_error'))
    <p style="color:red">{{ $errors->first('registro_error') }}</p>
@endif
</form>

<div class="text-center">
            <!-- Botón para reenviar el código -->
            <button onclick="reenviarCodigo()" class="btn btn-secondary">Reenviar Código</button>
            @if(Session::has('success'))
    <div style="position: fixed; top: 60%; left: 50%; transform: translate(-50%, -50%); text-align: center;" class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
@endif
        </div>
       
    

<p id="counter" style="display:none">0</p>

    <script>
        // Función para actualizar el contador
        function updateCounter() {
            let counterElement = document.getElementById('counter');
            let counterValue = parseInt(counterElement.innerText);
            counterElement.innerText = counterValue + 1;
        }

        // Lógica para actualizar el contador cada segundo
        setInterval(updateCounter, 1000);

        // Lógica para redirigir después del tiempo de sesión
        setTimeout(function() {
            alert("¡La sesión ha expirado! Por favor, inicie sesión nuevamente.");
            window.location.href = "{{ route('logout') }}"; // Redirigir a la página de cierre de sesión
        }, {{ config('session.lifetime') }} * 60 * 1000); // Convertir minutos a milisegundos







        function reenviarCodigo() {// función en controlador que envíe el código nuevamente al correo del usuario
            window.location.href = "{{ route('reenviarCodigo') }}";
        }




        setTimeout(function() {
        document.querySelector('.alert').style.display = 'none';
    }, 3000);
    </script>
    
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>