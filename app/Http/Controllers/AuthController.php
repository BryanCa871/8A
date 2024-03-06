<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
   

    public function register(Request $request)
    {
        
         // Verificar captcha
         if (empty($request->input('g-recaptcha-response'))) {
            return redirect()->back()->withErrors(['registro_error' => 'Captcha es necesario'])->withInput();
        }
        try {
            // Validación de datos
            $request->validate([
                'name' => 'required|string|max:255|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'email' => 'required|string|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|min:10|max:100',
                'password' => 'required|string|min:8|max:20',
                'g-recaptcha-response' => 'required',
            ]);
    
           
    
            $usuarios = new User();
            $usuarios->name = $request->name;
            $usuarios->email = $request->email;
            $usuarios->password = $request->password;
            $usuarios->save();
    
            // Autenticar al primer usuario registrado como admin
            if (User::count() == 1) {
                $usuarios->role = 'admin';
                $usuarios->save();
            }
    
            $request->session()->flash('success', '¡Registro exitoso!');
    
            Log::info('Usuario registrado correctamente. Email: ' . $request->email);
            return redirect()->route('/');
        } catch (\Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al registrar usuario'])->withInput();
        }
    }
    






    public function login(Request $request)
    {
        // Verificar captcha
        if (empty($request->input('g-recaptcha-response'))) {
            return redirect()->back()->withErrors(['login_error' => 'Captcha es necesario'])->withInput();
        }
    
        try {
            $request->validate([
                'email' => 'required|string|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|min:10|max:100',
                'password' => 'required|string|min:8|max:20',
                'g-recaptcha-response' => 'required',
            ]);
    
            $credentials = $request->only('email', 'password');
    
            // Autenticar usuario sin realizar el inicio de sesión
            $user = User::where('email', $credentials['email'])->first();
    
            if (!$user) {
                // El usuario no existe
                return redirect()->back()->withErrors(['error' => 'Credenciales incorrectas']);
            }
    
            // Verificar las credenciales sin iniciar sesión
            if (!Auth::validate($credentials)) {
                // Las credenciales no son válidas
                return redirect()->back()->withErrors(['error' => 'Credenciales incorrectas']);
            }
    
            if ($user->role === 'admin') {
                // Si el usuario es un administrador, redirigirlo a la vista de doble factor
                // para que complete la autenticación de doble factor
                session()->put('id', $user->id);


                    // Generar y almacenar código de doble factor en la base de datos
            $twoFactorCode = encrypt(rand(100000, 999999));
            $user->twocode = $twoFactorCode;
            $user->save();

$email = $request->email;


            // Enviar el código al correo
            $mail = new PHPMailer;
    try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.office365.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'bryancanedo871@hotmail.com';                     // SMTP username
        $mail->Password   = 'luis12345';                               // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // Recipients
        $mail->setFrom('bryancanedo871@hotmail.com', 'Codigo verificacion');
        $mail->addAddress($email);     // Add a recipient

        // Content

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Datos de autenticacion';
        $twoFactorCode = decrypt($user->twocode);
        $mail->Body = "
<p>Tu codigo es: $twoFactorCode </p>
";

        $mail->send();
        echo 'El mensaje se envió correctamente';

       
        } catch (Exception $e) {
        echo 'Hubo un error en el envio email: ' . $mail->ErrorInfo;
    }
                return redirect()->route('doblefactor');
            }
    
            // Si es un usuario normal, iniciar sesión normalmente
            if (Auth::attempt($credentials)) {
                Log::info('Usuario con email: ' . $request->email . ' ha iniciado sesión correctamente');
                return redirect()->route('bienvenida');
            } else {
                Log::error('Error al iniciar sesión credenciales incorrectas: ');
                return redirect()->back()->withErrors(['error' => 'Credenciales incorrectas']);
            }
        } catch (Exception $e) {
            Log::error('Error al iniciar sesión: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al iniciar sesión']);
        }
    }
    




    public function verificarDobleFactor(Request $request)
    {
        if (empty($request->input('g-recaptcha-response'))) {
            return redirect()->back()->withErrors(['VF_error' => 'Captcha es necesario'])->withInput();
        }
    
        try {
            $request->validate([
                'code' => 'required|digits:6',
                'g-recaptcha-response' => 'required'
            ]);
        
            $userId = $request->session()->get('id');
            $user = User::find($userId);
    
            $twoFactorCode = decrypt($user->twocode);

            if ($twoFactorCode == $request->code) {
                // Código correcto, autenticar al usuario
                auth()->login($user);
        
                // Restablecer el código de doble factor
                $user->twocode = null;
                $user->save();
    
                Log::info('Usuario' . $user->name . ' a pasado la autenticación de doble factor');
    
                return redirect()->route('bienvenida');
            } else {
                
                    return redirect()->back()->withErrors(['doble_error' => 'Codigo incorrecto'])->withInput();
                
                // Código incorrecto
                Log::error('Usuario ' . $user->name . 'metio codigo incorrecto en la autenticación de doble factor');
                return redirect()->back()->withErrors(['code' => 'Código incorrecto']);
            }
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al verificar doble factor']);
        }
    }

    







public function logout()
{
    try {
        // Obtener el usuario autenticado
        $user = Auth::user();

        if ($user) {
            // Si hay un usuario autenticado, registrar la información en el log
            Log::info('Usuario ' . $user->name . ' cerró su sesión correctamente');
        } else {
            // Si no hay un usuario autenticado, registrar un mensaje en el log
            Log::info('Usuario cerró sesión correctamente');
        }

        // Cerrar sesión
        Auth::logout();

        return redirect('/'); // Redirigir a la página de inicio de sesión después del cierre de sesión
    } catch (\Exception $e) {
        // Manejar cualquier error que pueda ocurrir durante el proceso
        Log::error('Error al cerrar sesión: ' . $e->getMessage());
        return redirect('/')->withErrors(['error' => 'Error al cerrar sesión']);
    }
}



public function reenviarCodigo(Request $request)
{
    try {
        // Obtener el usuario autenticado
        $userId = $request->session()->get('id');
            $user = User::find($userId);
        $email = $user->email;

        if ($user) {
            // Generar y almacenar código de doble factor en la base de datos
            $twoFactorCode = rand(100000, 999999);
            $user->twocode = $twoFactorCode;
            $user->save();

                     // Enviar el código al correo
                     $mail = new PHPMailer;
                     $mail->SMTPDebug = 4;
                     try {
                         // Server settings
                         $mail->isSMTP();                                            // Send using SMTP
                         $mail->Host       = 'smtp.office365.com';                     // Set the SMTP server to send through
                         $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                         $mail->Username   = 'bryancanedo871@hotmail.com';                     // SMTP username
                         $mail->Password   = 'luis12345';                               // SMTP password
                         $mail->SMTPSecure = 'tls';
                         $mail->Port       = 587;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                 
                         // Recipients
                         $mail->setFrom('bryancanedo871@hotmail.com', 'Codigo verificacion');
                         $mail->addAddress($email);     // Add a recipient
                 
                         // Content
                 
                         $mail->isHTML(true);                                  // Set email format to HTML
                         $mail->Subject = 'Datos de autenticacion';
                         $mail->Body = "
                 <p>Tu codigo es: $twoFactorCode </p>
                 ";
                 
                         $mail->send();
                         echo 'El mensaje se envió correctamente';
                 
                        
                         } catch (Exception $e) {
                         echo 'Hubo un error en el envio email: ' . $mail->ErrorInfo;
                     }

            Log::info('Usuario ' . $user->name . ' reenvió el código de doble factor');
            // Guardar mensaje de éxito en la sesión
            Session::flash('success', 'Código reenviado correctamente');
            return redirect()->route('doblefactor');
        } else {
            Log::error('Usuario no logeado intento reenviar codigo de doble factor');
            return redirect()->back()->withErrors(['error' => 'Error al reenviar código']);
        }
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al reenviar código']);
    }




}
}