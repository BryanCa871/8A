<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .logout-btn {
            float: right;
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido</h1>
        <button class="logout-btn" onclick="logout()">Cerrar Sesión</button>
        <button class="logout-btn"  onclick="nobackbutton()" style="display: none">dewdekjd Sesión</button>

       <p> Tiempo de sesion </p> <p id="counter">0</p>
    </div>

    <script>
 function nobackbutton()
{
    console.log("no back button");
   window.location.hash="bienvenida";
   window.location.hash="bienvenida"
   window.onhashchange=function(){window.location.hash="bienvenida";}   
}

// Llamar a nobackbutton cuando la página se carga por completo
window.onload = function () {
            nobackbutton();
        };  

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
            logout(); // Cerrar sesión automáticamente
        }, {{ config('session.lifetime') }} * 60 * 1000); // Convertir minutos a milisegundos

        // Función para cerrar sesión
        function logout() {
            window.location.href = "{{ route('logout') }}";
        }

       
    </script>
</body>
</html>
