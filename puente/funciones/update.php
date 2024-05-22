<?php
/*error_reporting(E_ALL);
// Establecer el tamaño máximo de carga de archivos a 32MB
ini_set('upload_max_filesize', '32M');
ini_set('memory_limit', '32M');
ini_set('post_max_size', '32M');

require_once('../conexion/conexion.php');*/
require_once('global_funcs.php');

$nombre = $_POST['nombre'];
$ap_p = $_POST['ap_p'];
$apm = $_POST['apm'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$contra = $_POST['contra'];
$ide = $_POST['ide'];

if(checkPass($contra, $ide)) {

    $estadoDatos = false; // Para saber si se actualizaron los datos
    
    $datos = checkInfo([ "id" => $ide, "nombre" => $nombre, "ape_p" => $ap_p, "ape_m" => $apm, "tel" => $telefono, "mail" => $correo]);
    if ($datos !== "") $estadoDatos = updateUser($datos, $ide); // Si hay datos que actualizar, actualizarlos
    else echo "<script>alert('No hay datos que actualizar');</script>";

    if ($estadoDatos) {
        setUserDataSession($nombre, $correo);
        echo "<script>alert('Datos actualizados correctamente')</script>";
    }

    if( checkImage($nombre, $ide, "Usuario", "/principal/usuario") ) echo "<script>alert('Imagen actualizada correctamente')</script>";
    else echo "<script>alert('No se pudo insertar|actualizar la imagen');</script>";

    cambioPagina("/principal/usuario", "Proceso finalizado");

} else cambioPagina("/principal/usuario/editar_perfil.php", "Contraseña incorrecta");

function checkPass($comparar, $id) {
    $res = exeQuery("SELECT Contrasena FROM usuario WHERE ID_Usuario = $id");
    $data = mysqli_fetch_array($res);
    
    return password_verify($comparar, $data['Contrasena']);
}

function checkInfo($data) {
    // Obtener la información actual del usuario
    $res = exeQuery("SELECT * FROM usuario WHERE ID_Usuario = '{$data['id']}'");
    $info = mysqli_fetch_array($res);

    $actualizar = "";

    if ($data['nombre'] != $info['Nombre']) // Si el nombre es diferente
        $actualizar .= "Nombre = '{$data['nombre']}',"; // Agregarlo a la cadena de actualización
                                                        // y asi con todos los datos
    if ($data['ape_p'] != $info['ApellidoP'])
        $actualizar .= "ApellidoP = '{$data['ape_p']}',";

    if ($data['ape_m'] != $info['ApellidoM'])
        $actualizar .= "ApellidoM = '{$data['ape_m']}',";

    if ($data['tel'] != $info['Telefono'])
        $actualizar .= "Telefono = '{$data['tel']}',"; 

    if ($data['mail'] != $info['Email'])
        $actualizar .= "Email = '{$data['mail']}',";

    // Eliminar la coma adicional al final, si existe
    $actualizar = rtrim($actualizar, ',');
    return $actualizar;
}

function updateUser($datos, $id) {
    return exeQuery(
        "UPDATE usuario SET $datos WHERE ID_Usuario = $id"
    );
}
?>
