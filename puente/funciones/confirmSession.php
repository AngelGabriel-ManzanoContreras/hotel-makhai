<?php
    require_once('global_funcs.php');
    session_start();

    if(!isset($_SESSION['nombre']) || !isset($_SESSION['correo']) || !isset($_SESSION['tipo']) ){
        cambioPagina("/index.php", "No hay sesión iniciada");
        exit();
    }

    $usuario = $_SESSION['nombre'];
    $tipo = ($_SESSION['tipo'] == 1) ? 'Administrador' : 'Hospedante';
    $correo = $_POST['correo'];
    $contrasena = $_POST['cont1'];

    if ( $correo != $_SESSION['correo'] ) {
        cambioPagina("/index.php", "El correo no coincide");
        exit();
    }

    $res = exeQuery(
        "SELECT * FROM usuario WHERE Email = '$correo' AND Tipo = '$tipo' AND Nombre = '$usuario'"
    );

    if(mysqli_num_rows($res) <= 0) {
        echo "<script>console.log('No se encontró el usuario');</script>";
        exit();
    } else {
        $datos = mysqli_fetch_assoc($res);

        if ($_SESSION['correo'] != $correo) {
            cambioPagina("/index.php", "El correo no coincide");
            exit();
        }

        if (password_verify($contrasena, $datos['Contrasena'])) {
            cambioPagina("/principal/usuario/editar_perfil.php", "Contraseña correcta");
            exit(); // Se detiene la ejecución del script
        } else cambioPagina("/index.php", "Contraseña incorrecta");
    }

?>