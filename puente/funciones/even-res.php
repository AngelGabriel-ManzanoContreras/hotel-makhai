<?php
    require_once("global_funcs.php");

    $fecha = $_POST['fecha'];
    $pretar = $_POST['tarjeta'];
    $tarjeta = password_hash($pretar, PASSWORD_DEFAULT, ['cost'=>15]);
    $precsv = $_POST['csv'];
    $csv = password_hash($precsv, PASSWORD_DEFAULT, ['cost'=>15]);
    $nombreT = $_POST['nombreT'];
    $fechaExpiracion = $_POST['fechaEx'];

    $idEvento = $_POST['idEvento'];
    $costo = $_POST['costo'];
    $fechaPago = date('Y-m-d'); //Fecha en la que se realizó el pago

    $comment = $_POST['coment'];
    $id_us = $_POST['id_us'];

    $res = exeQuery(
        "INSERT INTO reservacion (ID_Hospedante, Fecha_Inicio, Fecha_Fin, Es_Paquete, total, cancelado, Comentarios) 
        VALUES ($id_us , '$fecha', '$fecha' , 0, $costo, 0, '$comment')"
    );
    if (!$res) {
        echo "<script>alert('Error al registrar la reservacion en Reservacion');</script>";
        cambioPagina("/principal/hotel/eventos", "Error al registrar la reservacion");
        exit();
    }
    $reservacion = mysqli_insert_id($conexion);

    $res = exeQuery(
        "INSERT INTO tarjeta (ID_Usuario, CSV_Tarjeta, FechaVencimiento, Nombre, Numero) 
        VALUES ($id_us, '$csv', '$fechaExpiracion', '$nombreT', '$tarjeta')"
    );
    if (!$res) {
        echo "<script>alert('Error al registrar la reservacion en Tarjeta');</script>";
        cambioPagina("/principal/hotel/eventos", "Error al registrar la reservacion");
        exit();
    }

    $idTarjeta = mysqli_insert_id($conexion);

    $res = exeQuery(
        "INSERT INTO cargo (ID_Reservacion, Concepto, Monto, estado, fechaPago, fechaPagado, Tarjeta) 
        VALUES ($reservacion, '$comment', $costo, 1, NOW(), NOW(), $idTarjeta)"
    );
    if (!$res) {
        echo "<script>alert('Error al registrar la reservacion en Cargo');</script>";
        cambioPagina("/principal/hotel/eventos", "Error al registrar la reservacion");
        exit();
    }

    $res = exeQuery(
        "UPDATE evento SET Disponible = Disponible - 1 WHERE ID_Evento = $idEvento"
    );
    if (!$res) {
        echo "<script>alert('Error al registrar la reservacion en Evento');</script>";
        cambioPagina("/principal/hotel/eventos", "Error al registrar la reservacion");
        exit();
    }

    $res = exeQuery(
        "SELECT Nombre, Categoria, hora_inicio, hora_cierre FROM evento WHERE ID_Evento = $idEvento"
    );
    $evento = mysqli_fetch_assoc($res);
    
    sendEmailRes($reservacion, 'eve', Array(
        'fecha' => $fecha,
        'nomeve' => $evento['Nombre'],
        'cateve' => $evento['Categoria'],
        'horaini' => $evento['hora_inicio'],
        'horafin' => $evento['hora_cierre'],
        'ideve' => $idEvento,
        'concepto' => $comment,
        'usuario' => $id_us,
        'tarjeta' => $tarjeta,
        'csv' => $csv,
        'nombT' => $nombreT,
        'fecEx' => $fechaExpiracion,
        'evento' => $idEvento,
        'total' => $costo,
        'coment' => '',
        )
    );

    cambioPagina("/principal/hotel/eventos", "Reservacion realizada");
?>