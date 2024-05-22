<?php
    require_once('../conexion/conexion.php');
    require_once('global_funcs.php');
    session_start();

    $id_stock = $_POST['id_stock'];

    $fechaIn = $_POST['ini'];
    $fechaFin = $_POST['fin'];

    $total = $_POST['total'];
    $pretar = $_POST['tarjeta'];
    $tarjeta = password_hash($pretar, PASSWORD_DEFAULT, ['cost'=>15]);
    $precsv = $_POST['csv'];
    $csv = password_hash($precsv, PASSWORD_DEFAULT, ['cost'=>15]);
    $nombreT = $_POST['nombreT'];
    $fechaExpiracion = $_POST['fechaEx'];
    $concepto = $_POST['concepto'];
    $comentarios = $_POST['coment'];

    $res = exeQuery("SELECT ID_Usuario FROM usuario WHERE Email = '{$_SESSION['correo']}' AND Nombre = '{$_SESSION['nombre']}'");
    $ID_Usuario = mysqli_fetch_assoc($res)['ID_Usuario'];

    $res = exeQuery(
        "INSERT INTO reservacion (ID_Hospedante, Fecha_Inicio, Fecha_Fin, Es_Paquete, total, cancelado, Comentarios) 
        VALUES ($ID_Usuario, '$fechaIn', '$fechaFin', 0, $total, 0, '$comentarios')"
    );

    if (!$res) {
        echo "<script>alert('Error al registrar la reservacion en Reservacion');</script>";
        cambioPagina("/principal/hotel/habitaciones", "Error al registrar la reservacion");
        exit();
    }

    $id_Res = mysqli_insert_id($conexion);

    $res = exeQuery(
        "INSERT INTO tarjeta (ID_Usuario, CSV_Tarjeta, FechaVencimiento, Nombre, Numero) 
        VALUES ($ID_Usuario, '$csv', '$fechaExpiracion', '$nombreT', '$tarjeta')"
    );
    if (!$res) {
        echo "<script>alert('Error al registrar la reservacion en Tarjeta');</script>";
        cambioPagina("/principal/hotel/habitaciones", "Error al registrar la reservacion");
        exit();
    }
    $id_tarjeta = mysqli_insert_id($conexion);

    $res = exeQuery(
        "INSERT INTO reservacion_elemento (ID_Reservacion_Elemento, ID_Reservacion, Tipo_Elemento)
        VALUES ($id_stock, $id_Res, 'Habitacion_Stock')"
    );

    if (!$res) {
        echo "<script>alert('Error al registrar la reservacion en Reservacion_Elemento');</script>";
        cambioPagina("/principal/hotel/habitaciones", "Error al registrar la reservacion");
        exit();
    }

    $res = exeQuery(
        "INSERT INTO cargo (ID_Reservacion, Concepto, Monto, Estado, fechaPago, fechaPagado, Tarjeta)
        VALUES ($id_Res, '$concepto', $total, 1, NOW(), NOW(), $id_tarjeta)"
    );

    if (!$res) {
        echo "<script>alert('Error al registrar la reservacion en Cargo');</script>";
        cambioPagina("/principal/hotel/habitaciones", "Error al registrar la reservacion");
        exit();
    }

    $res = exeQuery(
        "SELECT Tipo FROM habitacion_stock WHERE ID_Habitacion_Stock = $id_stock"
    );
    $id_tipo = mysqli_fetch_assoc($res)['Tipo'];
    
    if ($res) {
        sendEmailRes($id_Res, 'hab' , Array(
            'stock' => $id_stock,
            'tipo' => $id_tipo,
            'usuario' => $ID_Usuario,
            'concepto' => $concepto, 
            'total' => $total, 
            'tarjeta' => $pretar, 
            'csv' => $precsv, 
            'nombT' => $nombreT, 
            'fecEx' => $fechaExpiracion, 
            'arribo' => $fechaIn, 
            'retiro' => $fechaFin, 
            'coment' => $comentarios)
        );
        cambioPagina("/principal/hotel/habitaciones", "Reservacion realizada");
    } else cambioPagina("/principal/hotel/habitaciones", "No se pudo completar la reservación");
?>