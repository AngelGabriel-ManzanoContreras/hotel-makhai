<?php
    require_once('../../puente/funciones/componentes.php');
    require_once('../../puente/funciones/global_funcs.php');
      
    checkSession(true);

/*
 *  En caso de que el elemento sea Administrador,
 *  el botón enviará a la página de registro del directorio Admin
 *  En caso de que el elemento sea Otros, el botón enviará a la página de registro del directorio Inicio
 */
    if (!isset($_GET['elemento'])) cambioPagina('/admin/panel.php', 'No se ha indicado el elemento a gestionar.');
    
    $elemento = $_GET['elemento'];

    $funcion = ($elemento == 'administrador') 
    ? 'irA(\'/makhai/admin/registro.php\')'
    : "irA(crea+'?accion=crear&elemento=$elemento')";

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../styles/globals.css">
  <link rel="stylesheet" href="../../styles/form.css">
  <link rel="stylesheet" href="../../styles/panel.css">
  <link rel="stylesheet" href="../../styles/perfil.css">
  <script src="../../principal/front-funcs/funciones.js"></script>
  <script src="../../principal/front-funcs/gestion.js"></script>
  <script src="../../principal/front-funcs/eliminar.js"></script>
  <title>Makhai - Gestión <?php echo $elemento?></title>
</head>
<body>

  <?php header_component(); ?>

  <aside></aside>

  <main>

    <section class="header-section">
      <h1 id="header-sect-title"></h1>
      <?php 
      if ($elemento != 'otros' && $elemento != 'reservacion') 
        echo '<button class="main-btn" onclick="'.$funcion.'">Crear</button>';
      ?>
    </section>

    <form class="main-form" id="infor" method="POST" action="./?elemento=<?php echo $elemento; ?>">
      <section class="in-sect-comp">
        <?php
        if ($elemento != 'reservacion') {
          echo "
        <label for='nombre'>Nombre de ".$elemento."</label>
        <input type='text' name='nombre' autocomplete='on'>
        ";
      } else if ($elemento == 'reservacion') {
        echo "
        <section class='in-cont-sect' style='width: 80% !important'>
          <section class='in-sect-comp'>
            <label for='nomhos'>Nombre de hospedante</label>
            <input type='text' name='nomhos' autocomplete='on'>
          </section>
          <section class='in-sect-comp'>
            <label for='app'>Apellido Paterno</label>
            <input type='text' name='app' autocomplete='on'>
          </section>
          <section class='in-sect-comp'>
            <label for='apm'>Apellido Materno</label>
            <input type='text' name='apm' autocomplete='on'>
          </section>
        </section>
        ";
      }
        ?>

        <label for="id">ID de <?php echo $elemento; ?></label>
        <input type="number" name="id">
      </section>
      <section class="in-sect-comp">
        <input class="scnd-btn" type="reset" value="Limpiar">
        <input class="main-btn" type="submit" value="Buscar">
      </section>
    </form>

    <section class="user-info ">

    <?php
      $sql = ($elemento == "administrador") ? 
      "SELECT ID_Usuario, Nombre, ApellidoP, ApellidoM, Telefono, Email FROM usuario WHERE Tipo = 'Administrador'"
      : ( ($elemento == "otros") ? 
        "SELECT ID_Usuario, Nombre, ApellidoP, ApellidoM, Telefono, Email FROM usuario WHERE Tipo = 'Hospedante'"
        : "SELECT * FROM $elemento"
      );

      $idWhere = "";
      $nombreWhere = "";

      if ( isset($_POST['nombre']) && !empty($_POST['nombre']) ) $nombreWhere = "Nombre LIKE '%".$_POST['nombre']."%'";
      
      if ( isset($_POST['id']) && !empty($_POST['id']) ) {
          $ele = ($elemento == "administrador" || $elemento == "otros") ? "Usuario" : $elemento;
          $idWhere = "ID_$ele = {$_POST['id']}";
      }

      if ($idWhere != "" && $nombreWhere != "") $sql .= " WHERE $idWhere AND $nombreWhere";//si hay ambos
      else if ($idWhere != "" || $nombreWhere != "") $sql .= " WHERE $idWhere$nombreWhere";//si solo hay uno de los dos

      if ($elemento == 'reservacion') {
        $sql = "SELECT * FROM reservacion AS r WHERE cancelado = 0";
        
        if (isset($_POST['nomhos']) || isset($_POST['app']) || isset($_POST['apm'])) {
          $where = "";
          
          if (isset($_POST['nomhos']) && !empty($_POST['nomhos'])) {
            $where .= ($where === "") 
            ? " Nombre LIKE '%".$_POST['nomhos']."%'" 
            : " AND Nombre LIKE '%".$_POST['nomhos']."%'";
          }
          if (isset($_POST['app']) && !empty($_POST['app'])) {
            $where .= ($where === "") 
            ? " ApellidoP LIKE '%{$_POST['app']}%'" 
            : " AND ApellidoP LIKE '%".$_POST['app']."%'";
          }
          if (isset($_POST['apm']) && !empty($_POST['apm'])) {
            $where .= ($where === "") 
            ? " ApellidoM LIKE '%".$_POST['apm']."%'" 
            : " AND ApellidoM LIKE '%".$_POST['apm']."%'";
          }

          $sql .= " AND r.ID_Hospedante = (
            SELECT ID_Usuario FROM usuario WHERE $where )";
          
        }
      }

      $res = exeQuery($sql);

      // se guardaran los nombres de las columnas
      $columnNames = array();

      if ($res) {
        // se obtiene el numero de columnas
        $numFields = mysqli_num_fields($res);
    
        for ($i = 0; $i < $numFields; $i++) {
            // se obtienen los nombres de las columnas
            $fieldInfo = mysqli_fetch_field_direct($res, $i);
            // se guardan los nombres en el array
            array_push($columnNames, $fieldInfo->name);
        }
      }
    ?>
      <!-- 
        Si se selecciona un elemento de los mencionados abajo, y se ejecuta el formulario
        se enviará a una pagina donde se podrá editar los datos del objeto seleccionado
        o eliminarlo
      -->
      <form id="formEE" action="crear-editar.php?accion=editar&elemento=<?php echo $elemento;?>" 
      class="in-cont-sect" style="align-items: unset;" method="POST">

        <table>
          <tr class="table-header">
          <?php
          // se imprime el nombre de las columnas
          if ($elemento != 'otros') echo '<th>Selección</th>';
          foreach($columnNames as $column) {
            echo "<th>$column</th>";
          }
          if ($elemento == 'reservacion') {
            echo "<th>Ver</th>";
          }
          ?>
          </tr>

          <?php
          if ($res && mysqli_num_rows($res) > 0) {
            // se imprimen los registros
            while ($row = mysqli_fetch_assoc($res)) {//assoc para que sea un array asociativo
              // se imprime el radio button para seleccionar el registro
              $printRow = ($elemento != 'otros') 
              ? '<tr><td><input type="radio" name="editar" value="'. reset($row).'"></td>'
              : '';

              // se imprimen los valores de cada columna
              foreach ($columnNames as $column) $printRow .= '<td>' . $row[$column] . '</td>';

              if ($elemento == 'reservacion') $printRow .= "<td><a href='reservacion.php?id={$row['ID_Reservacion']}'>Ver</a></td>";

              echo $printRow.'</tr>';
            }
          }
          // si no hay registros se imprime un mensaje
          else echo "<tr><td colspan=".(count($columnNames)+1)."><b>No se encontraron registros</b></td></tr>";
          ?>
        </table>

        <br>
        <?php 
          if ($elemento != "otros") {
          $editar = ($elemento != "reservacion" && $elemento != "otros") 
          ? '<button class="main-btn" type="submit">Editar</button>' : '';

          echo '<section class="in-sect-comp" id="btn-sect-D">'.
                  '<input type="reset" class="trnry-btn" value="Restablecer">'.
                  '<button type="button" class="scnd-btn" onclick="eliminar();">Eliminar</button>'
                  .$editar.
                '</section>';
          }
        ?>

      </form>

    </section>

  </main>

  <script> getElementFromGet('elemento', 'header-sect-title'); </script>

  <?php footer_component(); ?>
  
</body>
</html>