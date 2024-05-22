<?php
    require_once('global_funcs.php');

    $correo = $_POST['correo'];
    $cont1 = $_POST['cont1'];
    $cont2 = $_POST['cont2'];
    $nombre = $_POST['nombre'];
    $ap_p = $_POST['apellido_p'];
    $ap_m = $_POST['apellido_m'];
    $telefono = $_POST['telefono'];

    $res = exeQuery(
        "SELECT * FROM usuario WHERE Email = '$correo'"
    );

    if (mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_array($res);

        if ($nombre != $data['Nombre']) notificarError('Nombre');
        if ($ap_p != $data['ApellidoP']) notificarError('Apellido Paterno');
        if ($ap_m != $data['ApellidoM']) notificarError('Apellido Materno');
        if ($telefono != $data['Telefono']) notificarError('Telefono');
        if ($cont1 != $cont2) notificarError('Contraseña');
        else {
            echo '<script> alert("Actualizando") </script>';
            $cont1 = password_hash($cont2, PASSWORD_DEFAULT, ['cost'=>15]);
            $res = exeQuery(
                "UPDATE usuario SET Contrasena = '$cont1' WHERE Email = '$correo'"
            );

            if($res) {
                setUserDataSession($data['Nombre'], $correo, $data['Tipo']);
                redireccionUsuario($data['Tipo']);
            }
        }

    } else cambioPagina("/inicio/rest-contra.php", "El correo '$correo' no se encontró");

?>