<?php 

require_once(__DIR__.'/../conexion/conexion.php'); // Se importa la conexión a la base de datos
require_once('componentes.php');

const DEF_MSG = 'Para acceder esta página, debes iniciar sesión.'; // Mensaje por defecto para redireccionar a la página de inicio de sesión

function notificarError($dato){
    $msg = "El dato $dato no coincide";
    cambioPagina("/inicio/rest-contra.php", $msg);
    exit();
}

function checkSession($admin = false) { // Verificar si hay una sesión iniciada
    session_start();
    // Si no hay una sesión iniciada, se redirecciona a la página de inicio de sesión
	if(!isset($_SESSION['session']) AND !isset($_SESSION['correo'])) cambioPagina('/index.php');
    // Si se requiere que el usuario sea administrador, se verifica que el tipo de usuario sea 1 (Administrador)
    if($admin AND $_SESSION['tipo'] != 1) cambioPagina('/index.php', 'No tienes permisos para acceder a esta página.');
}

function cambioPagina($pagina, $mensage = DEF_MSG) {
    // Se muestra un mensaje y se redirecciona a la página indicada
    echo '<script type="text/javascript">alert("'.$mensage.'");</script>';
    // las paginas se indican mediante una constante definida en el archivo funciones.js de la carpeta principal/front-funcs
    echo '<script>window.location.href="'.$pagina.'"</script>';
}

function redireccionUsuario($tipo) {
    $nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';

    // Si el tipo de usuario es 1 (Administrador), se redirecciona a la página de administrador
    if( $tipo == 1) cambioPagina("/admin/panel.php", "Bienvenido Administrador ".$nombre);
    else cambioPagina("/index.php", "Bienvenido ".$nombre); // Sino, se redirecciona a la página de inicio
}

function setUserDataSession($nombre, $correo, $tipo = null) {
    // Se guardan los datos del usuario en la sesión
    session_start();

    if ( $tipo == null ) $tipo = getTipoUsuario($correo, $nombre); // Se obtiene el tipo de usuario si no se especificó

    $_SESSION["session"] = TRUE;
    $_SESSION["nombre"] = $nombre;
    $_SESSION["correo"] = $correo;
    if ( is_string($tipo) ) {
        //0 = Hospedante, 1 = Administrador
        $_SESSION["tipo"] = ($tipo == 'Administrador') ? 1 : 0;
    } else if ( is_int($tipo) ) {
        $_SESSION["tipo"] = $tipo;
    }
}

function getTipoUsuario($correo, $nombre) {
    // Se obtiene el tipo de usuario según el correo
    $res = exeQuery(
        "SELECT Tipo FROM usuario WHERE email = '$correo' AND Nombre = '$nombre';"
    );

    if(mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_array($res);
        return $data['Tipo'];
    } else return false;
}

function handleResError($res) {
    // Función para manejar los errores de las consultas a la base de datos
    global $conexion;

    if(!$res) { // Si la consulta falla, se muestra el error y se detiene la ejecución del script
        echo "Error". mysqli_error($conexion);
        exit();
    }
}

function exeQuery($sql) {
    // Función para ejecutar una consulta a la base de datos
    global $conexion;

    $res = mysqli_query($conexion, $sql);
    handleResError($res); // Se verifica si la consulta falló
    return $res; // Se retorna el resultado de la consulta
}

function checkImage($nombre, $id, $tabla, $path) {
    if ( empty($_FILES['imagen']['name']) ) {
        echo "<script>alert('No se ha seleccionado ninguna imagen')</script>";
        return false;
    }
    // la tabla es el nombre de la tabla en la base de datos, o el elemento al que pertenece la imagen
    // el nombre es el nombre del elemento al que pertenece la imagen
    // el path es la ruta donde se redireccionará la página en caso de error
    $carpetaImagenes = '/puente/img/' . $tabla;// Carpeta donde se guardarán las imagenes

    crearCarpeta($carpetaImagenes, $path); // Crear carpeta si no existe

    $carpeta = $carpetaImagenes . '/' . $id; // Carpeta donde se guardarán las imagenes del elemento

    crearCarpeta($carpeta, $path); // Crear carpeta si no existe

    try {
        // Verificar si es un solo archivo o múltiples archivos
        if (is_array($_FILES['imagen']['name'])) {
            echo "<script>console.log('Es un array');</script>";
            foreach ($_FILES['imagen']['name'] as $key => $value) { // Recorrer cada archivo subido y guardarlo
                $extension = pathinfo($_FILES['imagen']['name'][$key], PATHINFO_EXTENSION);
                $nombreArchivo = generateUniqueFileName($id, $nombre, $extension); // Generar nombre único para el archivo
                $rutaCompleta = $carpeta . '/' . $nombreArchivo; // Ruta completa donde se guardará el archivo

                if (move_uploaded_file($_FILES['imagen']['tmp_name'][$key], $_SERVER['DOCUMENT_ROOT'] . $rutaCompleta))
                    // Si se subió el archivo, se inserta en la tabla
                    exeQuery("INSERT INTO imagen (ID_Elemento, Tipo_Elemento, Direccion) VALUES ($id, '$tabla', '$rutaCompleta');");
                // Si no, se lanza una excepción
                else throw new Exception("Error al subir las imagenes");
            }
        } else {
            echo "<script>console.log('No es un array');</script>";
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION); // Obtener extensión del archivo
            $nombreArchivo = generateUniqueFileName($id, $nombre, $extension); 
            $rutaCompleta = $carpeta . '/' . $nombreArchivo;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $rutaCompleta)) {
                if ($tabla === "Usuario"){ // Si es un usuario, se debe gestionar la imagen de perfil
                    // Se verifica si el usuario ya tiene una imagen de perfil
                    if (!manageUserImage($id, $rutaCompleta)) {
                        echo "<script>console.log('Error al gestionar la imagen del usuario');</script>";
                        throw new Exception("Error al gestionar la imagen del usuario");
                    } else echo "<script>console.log('Imagen gestionada correctamente');</script>";

                // Si no es un usuario, se debe insertar la imagen en la tabla como siempre
                } else if ($tabla !== "Usuario")
                    exeQuery("INSERT INTO imagen (ID_Elemento, Tipo_Elemento, Direccion) VALUES ($id, '$tabla', '$rutaCompleta');");

            } else {
                echo "<script>console.log('Error al subir la imagen');</script>";
                throw new Exception("Error al subir la imagen");
            }
        }

        return true; // Todo salió bien

    } catch (Exception $e) {
        /*cambioPagina($path, $e->getMessage());*/
        return false; // Hubo un error
    }
}

function manageUserImage($id, $rutaCompleta) {
    // Verificar si el usuario ya tiene una imagen de perfil
    $existingRow = exeQuery("SELECT * FROM imagen WHERE ID_Elemento = $id AND Tipo_Elemento = 'Usuario'");

    if ($existingRow && mysqli_num_rows($existingRow) > 0){ // Si ya tiene una imagen, se debe actualizar
        $row = mysqli_fetch_assoc($existingRow); // Obtener la fila
        $idImagen = $row['ID_Imagen']; // Obtener el ID de la imagen
        $direccion = $row['Direccion']; // Obtener la dirección de la imagen

        if ( file_exists($_SERVER['DOCUMENT_ROOT'] . $direccion) ) {// Verificar si la imagen existe
            echo "<script>console.log('Existe la imagen');</script>";
            if ( unlink($_SERVER['DOCUMENT_ROOT'] . $direccion) ) { // Si existe, se elimina
                echo "<script>console.log('Imagen eliminada');</script>";
            } else echo "<script>console.log('Error al eliminar la imagen');</script>";
        } else echo "<script>console.log('No existe la imagen');</script>";

        return exeQuery(
            "UPDATE imagen SET Direccion = '$rutaCompleta' WHERE ID_Imagen = $idImagen AND ID_Elemento = $id AND Tipo_Elemento = 'Usuario'"
        ); // Actualizar la imagen

    } else { // Si no tiene una imagen, se debe insertar
        echo "<script>console.log('Insertando');</script>";
        return exeQuery("INSERT INTO imagen (ID_Elemento, Tipo_Elemento, Direccion) VALUES ($id, 'Usuario', '$rutaCompleta');");
    }
}

function crearCarpeta ($carpeta, $path){
    if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $carpeta)) { // Verificar si la carpeta existe
        // Si no existe, se crea
        if (mkdir($_SERVER['DOCUMENT_ROOT'] . $carpeta, 0755, true)) echo "<script>console.log('Carpeta $carpeta creada');</script>";
        else { // Si no se pudo crear, se muestra un error y se detiene la ejecución del script
            echo "<script>alert('Error al crear la carpeta.)</script>";
            return false;
        }
    } else echo "<script>console.log('Carpeta $carpeta ya existe');</script>";
}

function generateUniqueFileName($id, $nombre, $extension) { 
    return $id . '-' . $nombre . '-' . uniqid() . '.' . $extension;
}

function eliminarElemento($elemento, $id){

    if ($elemento == 'reservacion') {
        handleCancelRes($id);
    } else if ($elemento != 'reservacion') {
        handleDelImgs($elemento, $id);

        $res = exeQuery(
            "DELETE FROM $elemento WHERE ID_$elemento = $id"
        );
        if ($res) {
            echo "<script>console.log('Elemento eliminado');</script>";
        } else {
            echo "<script>console.log('Error al eliminar elemento');</script>";
        }
    }
    cambioPagina('/admin/panel.php', "Proceso finalizado");
}

function handleCancelRes($ID) {
    $res = exeQuery(
        "DELETE FROM reservacion_elemento WHERE ID_Reservacion = $ID"
    );
    if ($res) {
        echo "<script>console.log('Reservacion Elemento eliminado(s)');</script>";
    } else {
        echo "<script>console.log('Error al eliminar reservacion elemento(s)');</script>";
    }

    $res = exeQuery(
        "UPDATE reservacion SET Cancelado = 1 WHERE ID_Reservacion = $ID"
    );

    if ($res) {
        echo "<script>console.log('Reservacion cancelada');</script>";
    } else {
        echo "<script>console.log('Error al cancelar la reservacion');</script>";
    }
}

function handleDelImgs($elemento, $id) {
    $imagenes = exeQuery(
        "SELECT * FROM imagen WHERE ID_Elemento = $id AND Tipo_Elemento = '$elemento'"
    );

    if ($imagenes && mysqli_num_rows($imagenes) > 0) {
        echo "<script>console.log('Eliminando imagenes');</script>";
        while ($row = mysqli_fetch_assoc($imagenes)) {
            $direccion = $row['Direccion'];
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $direccion)) {
                if (unlink($_SERVER['DOCUMENT_ROOT'] . $direccion)) {
                    exeQuery(
                        "DELETE FROM imagen WHERE ID_Imagen = {$row['ID_Imagen']}
                        AND ID_Elemento = $id AND Tipo_Elemento = '$elemento'"
                    );
                    echo "<script>console.log('Imagen eliminada');</script>";
                } else {
                    echo "<script>console.log('Error al eliminar la imagen');</script>";
                }
            } else {
                echo "<script>console.log('La imagen no existe');</script>";
            }
        }
    }
}



function sendEmailRes($id_Res, $reserv, $valores) {
    $html = createEmailPage($id_Res, $reserv, $valores);

    $res = exeQuery(
        "SELECT Email FROM usuario WHERE ID_Usuario = {$valores['usuario']}"
    );
    $correo = mysqli_fetch_assoc($res)['Email'];
    echo "<script>console.log('Enviando correo a :".$correo."')</script>";

    $header = "FROM: noreply@makhai.com\r\n";
    $header .= "Content-type: text/html\r\n";
    $header .= "Reply-To: noreply@makhai.com\r\n";
    $header .= "X-Mailer: PHP/" . phpversion();

    $mailRes = mail($correo, $valores['concepto'], $html, $header);

    if (!$mailRes) {
        echo "<script>console.log('Error al enviar el correo');
        console.log('".error_get_last()['message']."');
        </script>";
    } else if ($mailRes) echo "<script>console.log('Correo enviado con exito')</script>";
}

function createEmailPage($id_res , $reserv, $valores) {
    $res = exeQuery(
        "SELECT * FROM reservacion WHERE ID_Reservacion = $id_res"
    );
    $reservacion = mysqli_fetch_assoc($res);
    $inicio = new DateTime($reservacion['Fecha_Inicio']);
    $fin = new DateTime($reservacion['Fecha_Fin']);
    $dias = $inicio->diff($fin);
    
    $page = 
'
Información de Reservación

ID de reservación: '. $id_res.'
Concepto: '. $valores['concepto'] .'
';

    if ($reserv == 'hab') {
        $res = exeQuery(
            "SELECT * FROM habitacion WHERE ID_Habitacion = {$valores['tipo']}"
        );
        $habitacion = mysqli_fetch_assoc($res);
        $page .= '
Información de habitación</h2>

Nombre de la habitación: '. $habitacion['Nombre'] .'
ID de habitación: '. $habitacion['stock'] .'
Tipo|Categoría de habitación: '. $habitacion['Categoria'] .'
Cargo por noche: '. $valores['Precio'] .'

Información de reservación

Fecha de arribo: '. $valores['Fecha_Inicio'] .'
Fecha de salida: '. $valores['Fecha_Fin'] .'
Número de noches: '. $dias->days .'
' ;
    } else if ($reserv == 'eve') {
        $page .= '
Información de evento

Nombre del evento: '. $valores['nomeve'] .'
ID de evento: '. $valores['ideve'] .'
Tipo|Categoría de evento: '. $valores['cateve'] .'
Hora de inicio: '. $valores['horaini'] .'
Hora de cierre: '. $valores['horafin'] .'
Día de asistencia: '. $valores['fecha'] .'';
        }

    //Convierto el numero de tarjeta a string para poder obtener los ultimos 4 digitos
    $last4Digits = substr( (string)$valores['tarjeta'], -4);
    $last2Digits = substr( (string)$valores['csv'], -2);

    $res = exeQuery(
        "SELECT * FROM usuario WHERE ID_Usuario = {$valores['usuario']}"
    );
    $reservante = mysqli_fetch_assoc($res);

    $page .= 
'
Información del Reservante

Nombre: '. $reservante['Nombre'] .'
ID de usuario: '. $reservante['ID_Usuario'] .'
ID de usuario: '. $reservante['ID_Usuario'] .'
Apellidos: '. $reservante['ApellidoP'] .' '. $reservante['ApellidoM'] .'
Correo: '. $reservante['Email'] .'
Telefono: '. $reservante['Telefono'] .'

Cargos

Cargo mxn: $'. ($valores['total'] == 0) ? 'Gratis' : $valores['total'].'

Informacion de pago

Tarjeta de credito: ************'. $last4Digits.'
Nombre: '. $valores['nombT'] .'
Fecha de expiracion: '. $valores['fecEx'] .'
CSV: **'. $last2Digits .'

Extras

Comentarios: '. $valores['coment'] .'';

    return $page;
}

?>