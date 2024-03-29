<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Register - MagtimusPro</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">


    <style>
        *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    text-decoration: none;
    font-family: 'Roboto', sans-serif;
}

body{
    background-image: url('{{ asset('images/bg4.jpg') }}');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed;
}

main{
    width: 100%;
    padding: 20px;
    margin: auto;
    margin-top: 100px;
}

.contenedor__todo{
    width: 100%;
    max-width: 800px;
    margin: auto;
    position: relative;
}

.caja__trasera{
    width: 100%;
    padding: 10px 20px;
    display: flex;
    justify-content: center;
    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
    background-color: rgba(0, 128, 255, 0.5);

}

.caja__trasera div{
    margin: 100px 40px;
    color: white;
    transition: all 500ms;
}


.caja__trasera div p,
.caja__trasera button{
    margin-top: 30px;
}

.caja__trasera div h3{
    font-weight: 400;
    font-size: 26px;
}

.caja__trasera div p{
    font-size: 16px;
    font-weight: 300;
}

.caja__trasera button{
    padding: 10px 40px;
    border: 2px solid #fff;
    font-size: 14px;
    background: transparent;
    font-weight: 600;
    cursor: pointer;
    color: white;
    outline: none;
    transition: all 300ms;
}

.caja__trasera button:hover{
    background: #fff;
    color: #46A2FD;
}

/*Formularios*/

.contenedor__login-register{
    display: flex;
    align-items: center;
    width: 100%;
    max-width: 380px;
    position: relative;
    top: -185px;
    left: 10px;

    /*La transicion va despues del codigo JS*/
    transition: left 500ms cubic-bezier(0.175, 0.885, 0.320, 1.275);
}

.contenedor__login-register form{
    width: 100%;
    padding: 80px 20px;
    background: white;
    position: absolute;
    border-radius: 20px;
}

.contenedor__login-register form h2{
    font-size: 30px;
    text-align: center;
    margin-bottom: 20px;
    color: #46A2FD;
}

.contenedor__login-register form input{
    width: 100%;
    margin-top: 20px;
    padding: 10px;
    border: none;
    background: #F2F2F2;
    font-size: 16px;
    outline: none;
}

.contenedor__login-register form button{
    padding: 10px 40px;
    margin-top: 40px;
    border: none;
    font-size: 14px;
    background: #46A2FD;
    font-weight: 600;
    cursor: pointer;
    color: white;
    outline: none;
}


.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.formulario__login{
    opacity: 1;
    display: block;
}
.formulario__register{
    display: none;
}




@media screen and (max-width: 850px){

    main{
        margin-top: 50px;
    }

    .caja__trasera{
        max-width: 350px;
        height: 300px;
        flex-direction: column;
        margin: auto;
    }

    .caja__trasera div{
        margin: 0px;
        position: absolute;
    }


    /*Formularios*/

    .contenedor__login-register{
        top: -10px;
        left: -5px;
        margin: auto;
    }

    .contenedor__login-register form{
        position: relative;
    }
}
    </style>

</head>

<body>

        <main>

       

            <div class="contenedor__todo">
                <div class="caja__trasera">
                    <div class="caja__trasera-login">
                        <h3>¿Ya tienes una cuenta?</h3>
                        <p>Inicia sesión para entrar en la página</p>
                        <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                    </div>
                    <div class="caja__trasera-register">
                        <h3>¿Aún no tienes una cuenta?</h3>
                        <p>Regístrate para que puedas iniciar sesión</p>
                        <button id="btn__registrarse">Regístrarse</button>
                    </div>
                </div>

                <!--Formulario de Login y registro-->
                <div class="contenedor__login-register">
                    <!--Login-->
                    <form action="{{ route('login') }}" method="POST" class="formulario__login">
    @csrf
    <h2>Iniciar Sesión</h2>
    <input type="text" name="honeypot" style="display:none">
    <input type="email" name="email" value="{{ old('email') }}"  placeholder="Correo Electrónico" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" minlength="10" maxlength="100">
    @error('email')
        <p>{{ $message }}</p>
    @enderror


    <input type="password" name="password" value="{{ old('password') }}"  placeholder="Contraseña" required  maxlength="20" >
    @error('error')
        <p>{{ $message }}</p>
    @enderror
    @error('password')
            <p>Credenciales incorrectas</p>
        @enderror

        @if($errors->has('login_error'))
    <p style="color:red">{{ $errors->first('login_error') }}</p>
@endif
    <br><br>
    @error('g-recaptcha-response')
        <p>el captcha es obligatorio</p>
    @enderror
    <div class="g-recaptcha" data-sitekey="6LcTu14pAAAAAD9y4uR8usL9xJCbEZU_LY893mhu" ></div>
    <button type="submit">Entrar</button>
</form>

                  <!--Register-->
                  <form  action="{{ route('añadir') }}" method="POST" class="formulario__register">
                    @csrf
                        <h2>Regístrarse</h2>
                        <input type="text"name="name" id="name" value="{{ old('name') }}"  placeholder="Nombre completo" required minlength="1" maxlength="100" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+">
                        <small>*Solo letras</small>
                        @error('name')
            <p>ded</p>
        @enderror

                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="Correo Electronico" minlength="10" maxlength="100" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
                        <small>*Formato email</small>
                        @error('email')
            <p>{{ $message }}</p>
        @enderror

        <input type="password" name="password" id="password" placeholder="Contraseña" required
       minlength="8" maxlength="20"
       pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%.^&*])[a-zA-Z\d!@#$%.^&*]+$"
       oninvalid="setCustomValidity('La contraseña debe contener al menos 8 caracteres, una letra mayúscula, una letra minúscula, un número y un carácter especial.')"
       oninput="setCustomValidity('')">
       <small>*La contraseña debe contener al menos 8 caracteres, una letra mayúscula, una letra minúscula, un número y un carácter especial.</small>
                        @error('error')
            <p>{{ $message }}</p>
        @enderror


        @if($errors->has('registro_error'))
    <p style="color:red">{{ $errors->first('registro_error') }}</p>
@endif
        <br><br>
    @error('g-recaptcha-response')
        <p style="color:red">el captcha es obligatorio</p>
    @enderror
    <div class="g-recaptcha" data-sitekey="6LcTu14pAAAAAD9y4uR8usL9xJCbEZU_LY893mhu"  ></div>
                        <button  >Regístrarse</button><br>
                        @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
                    </form>
                </div>
            </div>

        </main>


        <script>
             window.addEventListener('load', function() {
        var estadoActual = localStorage.getItem('estadoPestana') || 'login';
        cambiarFormulario(estadoActual);
    });



    //Ejecutando funciones
    document.getElementById("btn__iniciar-sesion").addEventListener("click", function() {
        cambiarFormulario('login');
    });
    document.getElementById("btn__registrarse").addEventListener("click", function() {
        cambiarFormulario('register');
    });
    window.addEventListener("resize", anchoPage);

//Declarando variables
var formulario_login = document.querySelector(".formulario__login");
var formulario_register = document.querySelector(".formulario__register");
var contenedor_login_register = document.querySelector(".contenedor__login-register");
var caja_trasera_login = document.querySelector(".caja__trasera-login");
var caja_trasera_register = document.querySelector(".caja__trasera-register");

    //FUNCIONES

function anchoPage(){

    if (window.innerWidth > 850){
        caja_trasera_register.style.display = "block";
        caja_trasera_login.style.display = "block";
    }else{
        caja_trasera_register.style.display = "block";
        caja_trasera_register.style.opacity = "1";
        caja_trasera_login.style.display = "none";
        formulario_login.style.display = "block";
        contenedor_login_register.style.left = "0px";
        formulario_register.style.display = "none";   
    }
}

anchoPage();


    function iniciarSesion(){
        if (window.innerWidth > 850){
            formulario_login.style.display = "block";
            contenedor_login_register.style.left = "10px";
            formulario_register.style.display = "none";
            caja_trasera_register.style.opacity = "1";
            caja_trasera_login.style.opacity = "0";
        }else{
            formulario_login.style.display = "block";
            contenedor_login_register.style.left = "0px";
            formulario_register.style.display = "none";
            caja_trasera_register.style.display = "block";
            caja_trasera_login.style.display = "none";
        }
    }

    function register(){
        if (window.innerWidth > 850){
            formulario_register.style.display = "block";
            contenedor_login_register.style.left = "410px";
            formulario_login.style.display = "none";
            caja_trasera_register.style.opacity = "0";
            caja_trasera_login.style.opacity = "1";
        }else{
            formulario_register.style.display = "block";
            contenedor_login_register.style.left = "0px";
            formulario_login.style.display = "none";
            caja_trasera_register.style.display = "none";
            caja_trasera_login.style.display = "block";
            caja_trasera_login.style.opacity = "1";
        }
}


function cambiarFormulario(formulario) {
        if (window.innerWidth > 850) {
            if (formulario === 'login') {
                formulario_login.style.display = "block";
                contenedor_login_register.style.left = "10px";
                formulario_register.style.display = "none";
                caja_trasera_register.style.opacity = "1";
                caja_trasera_login.style.opacity = "0";
            } else if (formulario === 'register') {
                formulario_register.style.display = "block";
                contenedor_login_register.style.left = "410px";
                formulario_login.style.display = "none";
                caja_trasera_register.style.opacity = "0";
                caja_trasera_login.style.opacity = "1";
            }
        } else {
            if (formulario === 'login') {
                formulario_login.style.display = "block";
                contenedor_login_register.style.left = "0px";
                formulario_register.style.display = "none";
                caja_trasera_register.style.display = "block";
                caja_trasera_login.style.display = "none";
            } else if (formulario === 'register') {
                formulario_register.style.display = "block";
                contenedor_login_register.style.left = "0px";
                formulario_login.style.display = "none";
                caja_trasera_register.style.display = "none";
                caja_trasera_login.style.display = "block";
                caja_trasera_login.style.opacity = "1";
            }
        }

        // Almacenar el estado de la pestaña en el Local Storage
        localStorage.setItem('estadoPestana', formulario);
    }


    
</script>

</body>
</html>