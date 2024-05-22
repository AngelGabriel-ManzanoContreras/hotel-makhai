<?php
    require_once("global_funcs.php");

    $tabla = $_POST['elemento'];
    $elementos = explode(",", $_POST['elementos']);
    $valores = Array();

    foreach($elementos as $elemento) 
        if ( isset($_POST[$elemento]) ) $valores[$elemento] = $_POST[$elemento];

    $nombre = $valores['Nombre'];

    $campos = implode(", ", array_keys($valores));
    $valores = implode("', '", array_values($valores));

    $res = exeQuery(
        "INSERT INTO $tabla ($campos) VALUES ('$valores')"
    );
    if ($res) {
        $id = mysqli_insert_id($conexion);
        if ($tabla != 'habitacion_stock' && $tabla != 'servicio') {
            checkImage($nombre, $id, $tabla, "/admin/gestion/index.php?accion=crear&elemento=$tabla");
        }

        cambioPagina("/admin/gestion/index.php?accion=crear&elemento=$tabla", "Elemento creado");
    } else {
        cambioPagina("/admin/gestion/index.php?accion=crear&elemento=$tabla", "Error al crear elemento");
    }
?>