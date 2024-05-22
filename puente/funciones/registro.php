<?php
    require_once('global_funcs.php');

    $correo = $_POST['correo'];

    $res = exeQuery(
        "SELECT * FROM usuario WHERE Email = '$correo'"
    );

    if(mysqli_num_rows($res)> 0) {
        cambioPagina("/inicio/registro.html", "El correo ya existe");
        exit();
    }

    $cont1 = $_POST['cont1'];
    $cont2 = $_POST['cont2'];

    if($cont1 != $cont2) {
        cambioPagina("/inicio/registro.php", "Las contraseñas no coinciden");
        exit();
    }

    $nombre = $_POST['nombre'];
    $ap_p = $_POST['apellido_p'];
    $ap_m = $_POST['apellido_m'];
    $contrasena = password_hash($cont1, PASSWORD_DEFAULT, ['cost'=>15]);
    $telefono = $_POST['telefono'];
    $tipo = $_POST['tipo'];

    $tipoEnum = $tipo == 1 ? 'Administrador':'Hospedante';

    $res = exeQuery(
        "SELECT * FROM usuario WHERE Email = '$correo'"
    );
    if (mysqli_num_rows($res) > 0) {
        cambioPagina("/inicio/registro.php", "El correo ya existe");
        exit();
    }
    
    exeQuery(
        "INSERT INTO usuario (Nombre, ApellidoP, ApellidoM, Email, Contrasena, Telefono, Tipo) 
        VALUES ('$nombre', '$ap_p', '$ap_m', '$correo', '$contrasena', '$telefono', '$tipoEnum')"
    );

    setUserDataSession($nombre, $correo, $tipoEnum);
    redireccionUsuario($tipo);
?>