<?php
	require_once('../../puente/funciones/componentes.php');
	require_once('../../puente/funciones/global_funcs.php');

	checkSession();
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/globals.css">
    <link rel="stylesheet" href="../../styles/form.css">
    <link rel="stylesheet" href="../../styles/perfil.css">
    <script src="../front-funcs/funciones.js"></script>
    <title>Makhai - <?php echo $_SESSION['nombre']; ?></title>
  </head>
  <body>
  
  <?php header_component(); ?>

  <aside></aside>

  <main>
    
    <section class="header-section">
      <h1>Datos de usuario</h1>
      <button class="main-btn" onclick="irA(login + '?ed=1')">editar datos</button>
    </section>

    <?php
        // Se obtienen los datos del usuario
        $res = exeQuery(
          "SELECT * FROM usuario WHERE Email = '{$_SESSION['correo']}' AND Nombre = '{$_SESSION['nombre']}';"
        );
        $data = mysqli_fetch_array($res);

        // Se obtiene la imagen del usuario
        $resImage = exeQuery(
          "SELECT * FROM imagen WHERE ID_Elemento = '{$data['ID_Usuario']}' AND Tipo_Elemento = 'Usuario';"
        );

        $dataImage = mysqli_fetch_array($resImage);
    ?>

    <section class="user-info-p">
      <figure>
        <!-- En caso de que el usuario tenga imagen, se muestra, sino se muestra una por defecto -->
        <img src="<?php echo (mysqli_num_rows($resImage) > 0 ? $dataImage['Direccion'] : 'user.png' ); ?>" alt="<?php echo $data['Nombre'];?>">
      </figure>

      <section>
        <section id="sect-def">
          <h2>Usuario</h2>
          <p>Correo</p>
          <p>Teléfono</p>
        </section>

        <section>
          <h2><?php echo $data['Nombre'];?></h2>
          <p><a href="mailto:<?php echo $data['Email'];?>"><?php echo $data['Email'];?></a></p>
          <p><?php echo $data['Telefono']; ?></p>
        </section>
      </section>

    </section>

    <section class="header-section" id="reservaciones">
      <h1>Reservaciones</h1>
    </section>

    <?php
        // Se obtienen las reservaciones del usuario
        $sql = "SELECT * FROM reservacion AS r WHERE r.ID_Hospedante = '{$data['ID_Usuario']}' AND r.Cancelado = 0";

        // Se agregan los filtros
        // si se seleccionaron fechas, se agregan al query para filtrar por ellas
        if ( (isset($_POST["fechaIn"]) AND $_POST["fechaIn"] != "") AND (isset($_POST["fechaFin"]) AND $_POST["fechaFin"] != "") )
            $sql .= " AND r.Fecha_Inicio >= '{$_POST['fechaIn']}' AND  r.Fecha_Fin <= '{$_POST['fechaFin']}' ";
        // si se ingresó un ID de reservación, se agrega al query para filtrar por él
        if (isset($_POST["buscar"]) AND $_POST["buscar"] != "") $sql .= " AND r.ID_Reservacion = '{$_POST['buscar']}' ";

        $resRes = exeQuery($sql);
    ?>

    <form class="main-form" action="index.php#reservaciones" method="POST">
      <section class="in-sect-comp">
        <label for="fechaIn">Fecha de inicio</label>
        <input type="date" name="fechaIn" value="<?php echo (isset($_POST['fechaIn'])) ? $_POST['fechaIn'] : ''; ?>">

        <label for="fechaFin">Fecha de fin</label>
        <input type="date" name="fechaFin" value="<?php echo (isset($_POST['fechaFin'])) ? $_POST['fechaFin'] : ''; ?>">
      </section>

      <section class="in-sect-comp">
        <label for="buscar">Buscar por ID de Reservación</label>
        <input type="number" name="buscar" value="<?php echo (isset($_POST['buscar'])) ? $_POST['buscar'] : ''; ?>">
      </section>
      
      <section class="in-sect-comp">
        <!-- El onclick es para que se recargue la página y se borren los filtros -->
        <button class="scnd-btn" onclick="limpiarCampos()">Restablecer</button>
        <input class="main-btn" type="submit" value="Buscar">
      </section>

    </form>

    <section class="user-info">

      <table>
        <tr class="table-header">
          <th>ID</th>
          <th>Paquete</th>
          <th>Fecha de inicio</th>
          <th>Fecha de fin</th>
          <th>Total a pagar</th>
          <th class='tab-col-ver'></th>
        </tr>

        <?php 
        if(mysqli_num_rows($resRes) > 0) {
          // Se muestran las reservaciones
          while($reservacion = mysqli_fetch_array($resRes)) {
            echo "<tr>
              <td class='tab-col-id'>{$reservacion['ID_Reservacion']}</td>
              <td class='tab-col-nam'>". ($reservacion['Es_Paquete'] == 0 ? 'No' : 'Si') ."</td>
              <td class='tab-col-date'>".date('d/m/Y', strtotime($reservacion['Fecha_Inicio']))."</td>
              <td class='tab-col-date'>".date('d/m/Y', strtotime($reservacion['Fecha_Fin']))."</td>
              <td class='tab-col-mon'>{$reservacion['total']}</td>
              <td class='tab-col-ver'> <a href='reservacion.php?id={$reservacion['ID_Reservacion']}'>Ver</a> </td>
            </tr>";
            ?>

        <?php } 
        } else echo "<tr><td colspan=".mysqli_num_fields($res)."><b>No hay registros aún</b></td></tr>";
        ?>

      </table>

    </section>
    
  </main>

  <?php footer_component(); ?>
  
</body>
</html>