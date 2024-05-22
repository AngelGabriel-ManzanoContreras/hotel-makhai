<?php
    require_once('global_funcs.php');

    if ( !isset($_POST['imag_bor']) || !isset($_POST['elementos']) || !isset($_POST['elemento']) || !isset($_POST['id']) ) {
        cambioPagina('/admin/panel.php', 'No se ha conseguido la información necesaria para editar el elemento');
        exit();
    }

    $tabla = ( ($_POST['elemento'] == "otros") || ($_POST['elemento'] == "administrador") ) ? "Usuario" : $_POST['elemento'];
    $id = $_POST['id'];
    $imagenes = explode(",", $_POST['imag_bor']);
    $elementos = explode(",", $_POST['elementos']);

    $valores = array();

    foreach($elementos as $elemento) {
        if ( isset($_POST[$elemento]) ) $valores[$elemento] = $_POST[$elemento];
    }

    $actualizar = checkInfo($valores, $tabla, $id);

    if ($actualizar != "") {
        $res = exeQuery(
            "UPDATE $tabla SET $actualizar WHERE ID_$tabla = $id"
        );
        if ($res) echo "<script>alert('Información del elemento actualizado')</script>";
        else echo "<script>alert('Error al actualizar la información del elemento')</script>";

    } else echo "<script>alert('No se ha actualizado ningún campo debido a que no se encontrarón modificaciones')</script>";
    
    if (!empty($imagenes)) {
        checkDelImage($imagenes, $tabla, $id);
    } else echo "<script>alert('No se ha borrado ninguna imagen')</script>";

    if ($tabla != 'habitacion_stock' && $tabla != 'servicio') {
        if ( checkImage($valores['Nombre'], $id, $tabla, "/admin/panel.php") ) {
            echo "<script>alert('Imagen(s) actualizada(s)')</script>";
        } else echo "<script>alert('No se ha actualizado la(s) imagen(s)')</script>";
    }

    cambioPagina("/admin/panel.php", "Proceso completado");

    function checkInfo($data, $tabla, $id) {
        // Obtener la información actual del usuario
        $res = exeQuery("SELECT * FROM $tabla WHERE ID_$tabla = '$id'");
        $info = mysqli_fetch_assoc($res);
        $idRes = null;
    
        $actualizar = "";

        foreach( $info as $key => $value ) {
            if ($idRes == null) $idRes = $value; // Saltar el ID
            else {
                if ($key == "Contrasena" || $key == "Tipo") continue; // Saltar la contraseña (no se puede actualizar desde aquí
                if ($data[$key] != $value) // Si los datos son diferentes
                $actualizar .= "$key = '{$data[$key]}',"; // Agregarlo a la cadena de actualización
            }
        }
    
        // Eliminar la coma adicional al final, si existe
        $actualizar = rtrim($actualizar, ',');
        return $actualizar;
    }

    function checkDelImage ($borrar, $elemento, $id) {
        if ( !is_array($borrar) AND !empty($borrar) ) {
            foreach($borrar as $imagenID)
                $res = exeQuery("SELECT Direccion FROM imagen WHERE ID_Imagen = $imagenID AND ID_Elemento = '$id' AND Tipo_Elemento = '$elemento'");
                $imagen = mysqli_fetch_assoc($res);
                $imgDireccion = $imagen['Direccion'];

                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imgDireccion)) { // Si la imagen existe
                    if(unlink($_SERVER['DOCUMENT_ROOT'] . $imgDireccion)) { // Si se borra la imagen
                        echo "<script>alert('Imagen borrada')</script>";
                    } else echo "<script>alert('Error al borrar la imagen')</script>";
                } else echo "<script>alert('La imagen no existe')</script>";

                exeQuery("DELETE FROM imagen WHERE ID_Imagen = $imagenID AND ID_Elemento = '$id' AND Tipo_Elemento = '$elemento'");
        }
    }
?>