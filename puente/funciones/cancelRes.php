<?php
    require_once("global_funcs.php");

    $idRes = $_POST['reservacion'];
    $band = true;

    $res = exeQuery(
        "UPDATE reservacion SET cancelado = 1 WHERE ID_Reservacion = $idRes"
    );
    
    $res = exeQuery(
        "SELECT Comentarios FROM reservacion WHERE ID_Reservacion = $idRes"
    );
    
    $com = mysqli_fetch_assoc($res)['Comentarios'];
    
    if (!empty($com)) {
        // Verificar si la cadena contiene la palabra 'evento'
        if (strpos($com, 'ID evento') !== false) {
            // Encontrar la posición de 'evento' en la cadena
            $posicionEvento = strpos($com, 'ID evento');
    
            // Obtener la subcadena que comienza después de 'ID evento :'
            $subcadenaDespuesEvento = substr($com, $posicionEvento + strlen('ID evento :'));
    
            // Filtrar solo los dígitos al final de la subcadena
            // en este caso, elimina los espacios en blanco
            $numeroEvento = trim($subcadenaDespuesEvento);
    
            if (!empty($numeroEvento)) {
                $res = exeQuery(
                    "UPDATE evento SET Disponible = Disponible + 1 WHERE ID_Evento = $numeroEvento"
                );
                if(!$res) {
                    echo "<script>console.log('No se pudo actualizar la disponibilidad del Evento')</script>";
                }
            } else {
                // No se encontraron números después de 'evento'
                echo "<script>console.log('No se pudo recuperar el ID del evento')</script>";
            }
        } else {
            // La cadena no contiene la palabra 'evento'
            echo "<script>console.log('La reservacion no es de un evento')</script>";
        }
    }

    if (!$res) {
        $band = false;
        echo "<script>console.log('Error al actualizar el estado de la reservacion')</script>";
        cambioPagina("/principal/usuario", "Error al cancelar reservacion");
        exit();
    }

    $res = exeQuery(
        "SELECT * FROM reservacion_elemento WHERE ID_Reservacion = $idRes"
    );

    if ($res && mysqli_num_rows($res) > 0) {
        echo "<script>console.log('Reservacion con elementos')</script>";
        $res = exeQuery(
            "DELETE FROM reservacion_elemento WHERE ID_Reservacion = $idRes"
        );

        if (!$res) {
            $band = false;
            echo "<script>console.log('Error al eliminar los elementos de la reservacion')</script>";
            cambioPagina("/principal/usuario", "Error al cancelar reservacion");
            exit();
        }
    }

    echo "<script>console.log('Proceso concluido')</script>";
    if (!$band) cambioPagina("/principal/usuario", "Proceso concluido");
    else cambioPagina("/principal/usuario", "Reservacion cancelada");
?>