<?php 
    require_once('../../puente/funciones/componentes.php');
    require_once('../../puente/funciones/global_funcs.php');
    
    checkSession(true);

    $accion = "";

    if ( isset($_POST['eliminar']) ) {
      eliminarElemento($_GET['elemento'], $_POST['editar']);
      exit();
    }

    if (isset($_GET['accion']) AND isset($_GET['elemento'])) {
      $accion = $_GET['accion'];
      $elemento = $_GET['elemento'];

      if ($accion === "editar" || isset($_POST['editar'])) $res = eidtarProc($_POST['editar'], $_GET['elemento']);
      else if ($accion === "crear") $res = exeQuery(" SELECT * FROM $elemento");
    }
    else {
      cambioPagina('/admin/gestion', 'No se ha enviado el ID del elemento a editar');
      exit();
    }

    function eidtarProc($id, $elemento) {
      return ($elemento == "administrador") ? 
        exeQuery("SELECT ID_Usuario, Nombre, ApellidoP, ApellidoM, Telefono, Email FROM usuario WHERE Tipo = 'Administrador' AND ID_Usuario = $id") 
        : ( ($elemento == "otros") ? 
          exeQuery("SELECT ID_Usuario, Nombre, ApellidoP, ApellidoM, Telefono, Email FROM usuario WHERE Tipo = 'Hospedante' AND ID_Usuario = $id") 
          : exeQuery("SELECT * FROM $elemento WHERE id_$elemento = {$_POST['editar']}")
        );
    }
    
    function getInputType($fieldType) {
        switch ($fieldType) {
            case MYSQLI_TYPE_DATE:
                return 'type="date"';
            case MYSQLI_TYPE_TIME:
                return 'type="time"';
            case MYSQLI_TYPE_DATETIME:
                return 'type="datetime-local"';
            case 3://Int
              return 'type="number"';
            case 246://Decimal
                return 'type="number" step="0.01"';
            case MYSQLI_TYPE_VAR_STRING:
            case MYSQLI_TYPE_STRING:
            case 252:
            default:
                return 'type="text"';
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../styles/globals.css">
  <link rel="stylesheet" href="../../styles/form.css">
  <link rel="stylesheet" href="../../styles/imagenes.css">
  <link rel="stylesheet" href="../../styles/perfil.css">
  <script src="../../principal/front-funcs/funciones.js"></script>
  <script src="../../principal/front-funcs/editar.js"></script>
  <title>Makhai - <?php echo $accion." ".$elemento; ?></title>
</head>
<body>

  <script>
  </script>

  <?php header_component(); ?>

  <aside></aside>

  <main>

    <section class="header-section">
      <h1 id="header-sect-title"></h1>
    </section>

    <form class="main-form" id="formy" action="<?php 
    echo (isset($_GET['accion']) && $accion == "editar")
      ? '../../puente/funciones/act-elemento.php' 
      : '../../puente/funciones/crear-elemento.php';
      ?>" method="POST" enctype="multipart/form-data">
      <?php $values = ""; ?>

      <section class="in-cont-sect">
        <?php
        if ($accion === "crear") {
            $numFields = mysqli_num_fields($res);
        
            for ($i = 0; $i < $numFields; $i++) {
                if ($i == 0) continue; //Evita que se imprima el campo ID

                $fieldInfo = mysqli_fetch_field_direct($res, $i);
                // si es el ultimo elemento de la lista, no se agrega una coma despues de su nombre
                $values .= ($i == ($numFields - 1) ) ?  $fieldInfo->name : $fieldInfo->name . ",";

                echo "
                <section class='in-sect'>
                  <label for='$fieldInfo->name'>$fieldInfo->name</label>
                  <input ".getInputType($fieldInfo->type)." name='$fieldInfo->name' required>
                </section>
                ";
            }

            if ( ($elemento != 'habitacion_stock') && ($elemento != 'servicio') ) {
              echo "
                <section class='in-sect'>
                  <label for='imagen'>Imagen</label>
                  <input type='file' name='imagen[]' accept='image/*' multiple required>
                </section>
                ";
            }
        } else if ($accion === "editar") {
          $row = mysqli_fetch_assoc($res); //Obtiene la fila del elemento a editar
          $id = null;

          echo '
          <details class="text-cont">
            <summary><h2>Actualización de '.$elemento.'</h2></summary>
            <p>Si no desea realizar cambios, puede ir hacia una página atras o cerrar esta pestaña. Esto antes de que des click en el botón "Editar '.$elemento.'".</p>
            <p>De haber dado click en el botón mencionado, ya no hay manera de restablecer los datos a como estaban antes, a menos de que los recuerde a la perfección y decida corregirlos (esto de manera manual).</p>
            <br>
            <p>El numero mencionado a lado de la Imagen del '.$elemento.' es el ID de dicha imagen dentro de la base de datos, no te preocupes por el.</p>
            <br>
            <p>Si deseas cambiar la imagen de un Usuario (Administrador u otros), solo selecciona una nueva imagen y se reemplazará la anterior. Es importante tener en cuenta que los usuarios solo pueden tener 1 imagen a la vez</p>
          </details>
          <br>
          ';

          foreach ($row as $campo => $valor) {
              // salta el campo ID
              if ($id === null) {
                  $id = $valor; //Guarda el ID del elemento a editar
                  echo "<input type='hidden' name='id' value='$id'>";
                  continue;
              }
              if ($campo == "Contrasena" || $campo == "Tipo") continue;

              // busca el campo en la tabla para obtener su tipo
              // mysqli_fetch_field_direct devuelve un objeto con la informacion del campo
              // array_search busca el indice del campo en el arreglo de campos de la fila
              // array_keys devuelve un arreglo con los nombres de los campos de la fila
              $fieldInfo = mysqli_fetch_field_direct($res, array_search($campo, array_keys($row)));
              $values .= "$campo,"; //Agrega el nombre del campo a la cadena de valores

              echo "
              <section class='in-sect'>
                <label for='$campo'>$campo</label>
                <input " . getInputType($fieldInfo->type) . " name='$campo' value='$valor' required>
              </section>
              ";
          }

          $values = trim($values, ","); //Elimina la ultima coma de la cadena

          $res = ($elemento == "administrador" || $elemento == "otros") 
          ? exeQuery("SELECT * FROM imagen WHERE ID_Elemento = $id AND Tipo_Elemento = 'Usuario'")
          : exeQuery("SELECT * FROM imagen WHERE ID_Elemento = $id AND Tipo_Elemento = '$elemento'");

          while ($row = mysqli_fetch_assoc($res)) {
              $imagenID = $row['ID_Imagen'];
              $imagenDireccion = $row['Direccion'];

              if ($elemento == "administrador" || $elemento == "otros")
              echo "<input id='img-d' type='hidden' value='$imagenID'>";
          
              echo "
              <section id='imagen$imagenID' class='in-sect image-section'>
                <label for='imagenElemento'>Imagen $imagenID</label>
                <figure>
                  <img src='$imagenDireccion' alt='Imagen $imagenID'>
                </figure>
                <button type='button' class='scnd-btn' onclick='borrarImagen($imagenID)'>Borrar</button>
              </section>
              ";
          }

          if ( ($elemento != 'habitacion_stock') && ($elemento != 'servicio') ) {
            if ($elemento == "administrador" || $elemento == "otros") {
              echo "
                <section class='in-sect'>
                  <label for='imagen'>Agregar Nueva Imagen</label>
                  <input id='img-in' type='file' name='imagen' accept='image/*'>
                </section>
              ";
            } else {
              echo "
                <section class='in-sect'>
                  <label for='imagen[]'>Agregar Nueva Imagen</label>
                  <input type='file' name='imagen[]' accept='image/*' multiple>
                </section>
              ";
            }
          }

        } else {
          cambioPagina('/admin/gestion', 'No se ha enviado el ID del elemento a editar');
          exit();
        }
        ?>
        <input type="hidden" name="imag_bor" id="imagenes">
        <input type="hidden" name="elementos" value="<?php echo $values; ?>">
        <input type="hidden" name="elemento" value="<?php echo $elemento; ?>">

        <section class="in-sect btn-sect">
          <input type="button" class="scnd-btn" value="Restablecer">
          <input id="btn-1" class="main-btn" type="submit" value="">
        </section>
      </section>
      
    </form>

  </main>

  <script> 
    getElementFromGet('accion', 'header-sect-title', '<?php echo $elemento; ?>');
    document.getElementById('btn-1').value = document.getElementById('header-sect-title').innerHTML;
  </script>

  <?php footer_component(); ?>
  
</body>
</html>