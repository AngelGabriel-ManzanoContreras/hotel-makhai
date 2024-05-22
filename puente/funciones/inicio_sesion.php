<?php
    require_once('global_funcs.php');

    $correo = $_POST['correo'];
    $contrasena = $_POST['cont1'];

    // Si se envió el formulario de editar perfil, se redirecciona a la página de edición
    // sino, se redirecciona a la página de inicio de sesión
    $pagina = (isset($_POST['ed'])) ? "/principal/usuario" : "/inicio/inicio-sesion.php";

    $res = exeQuery(
        "SELECT contrasena, nombre, tipo FROM usuario WHERE Email = '$correo';"
    );

    if(mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_array($res);

        if (password_verify($contrasena, $data['contrasena'])) {

            if ( isset($_POST['ed']) ) {
                cambioPagina($pagina . "/editar_perfil.php", "Contraseña correcta");
                exit(); // Se detiene la ejecución del script
            }
            
            setUserDataSession($data['nombre'], $correo, $data['tipo']);
            redireccionUsuario($_SESSION['tipo']);

        } else cambioPagina($pagina, "Contraseña incorrecta");

    } else cambioPagina($pagina, "El correo '$correo' no se encontró");
?>