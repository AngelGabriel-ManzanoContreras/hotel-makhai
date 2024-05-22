<?php 
    // Establecemos la zona horaria en español
    setlocale(LC_TIME, 'es_ES.utf8');

    require_once('../puente/funciones/componentes.php');
    require_once(__DIR__.'/../puente/funciones/global_funcs.php');
    
    checkSession(true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/globals.css">
  <link rel="stylesheet" href="../styles/form.css">
  <link rel="stylesheet" href="../styles/panel.css">
  <link rel="stylesheet" href="../styles/perfil.css">
  <script src="../principal/front-funcs/funciones.js"></script>
  <title>Makhai - Panel de administración</title>
</head>
<body>

  <?php header_component(); ?>

  <aside></aside>

  <main>

    <section class="header-section">
      <h1>Acciones de gestión</h1>
    </section>

    <section id="adm-act-fath">
      <section class="adm-act-cont">
        <?php
          action_card_component(Array(
              'titulo' => 'Habitaciones',
              'desc'=> 'Gestiona los tipos de habitaciones con las que cuenta el hotel.',
              'acciones'=> 'Crea, edita y elimina.',
              'path' => 'gestion',
              'elemento'=> 'habitacion',
          ));

          action_card_component(Array(
            'titulo' => 'Stock de Habitaciones',
            'desc' => 'Gestiona el stock de habitaciones con las que cuenta el hotel.',
            'acciones' => 'Crea, edita y elimina.',
            'path' => 'gestion',
            'elemento' => 'habitacion_stock'
          ));

          action_card_component(Array(
              'titulo' => 'Servicios',
              'desc'=> 'Gestiona los servicios y amenidades del hotel.',
              'acciones'=> 'Crea, edita y elimina.',
              'path' => 'gestion',
              'elemento'=> 'servicio',
          ));

          action_card_component(Array(
              'titulo'=> 'Actividades',
              'desc'=> 'Gestiona las actividades con las que cuenta el hotel.',
              'acciones'=> 'Crea, edita y elimina.',
              'path' => 'gestion',
              'elemento'=> 'actividad',
          ));

          action_card_component(Array(
              'titulo'=> 'Eventos',
              'desc'=> 'Gestiona los eventos que se llevarán a cabo en el hotel.',
              'acciones'=> 'Crea, edita y elimina.',
              'path' => 'gestion',
              'elemento'=> 'evento',
          ));

          action_card_component(Array(
              'titulo'=> 'Administradores',
              'desc' => 'Gestiona el registro de los administradores Makhai®',
              'acciones'=> 'Crea, edita y elimina.',
              'path' => 'gestion',
              'elemento'=> 'administrador',
          ));

          action_card_component(Array(
            'titulo'=> 'Otros usuarios',
            'desc' => 'Gestiona el registro de hospedantes del hotel.',
            'acciones'=> 'Visualiza.',
            'path' => 'gestion',
            'elemento'=> 'otros',
        ));

          action_card_component(Array(
              'titulo'=> 'Reservaciones',
              'desc'=> 'Gestiona las reservaciones de los usuarios.',
              'acciones'=> 'Visualiza y Elimina.',
              'path' => 'gestion',
              'elemento'=> 'reservacion',
          ));
        ?>
      </section>

    </section>

    <section id="earning" class="header-section">
      <h1>Ganancias Makhai®</h1>
    </section>

    <?php
        $total = 0;
        // Si se ha enviado un rango de fechas, se establece
        if (isset($_GET['fechaIn']) && isset($_GET['fechaFin'])) {
          $fechaIn = $_GET['fechaIn'];
          $fechaFin = $_GET['fechaFin'];
        }
    ?>

    <form class="main-form" action="panel.php#earning">
      <section class="in-sect-comp">
        <label for="fechaIn">Fecha de inicio</label>
        <input type="date" name="fechaIn" value="<?php echo (isset($fechaIn) ? $fechaIn : '');?>">
        <label for="fechaFin">Fecha de fin</label>
        <input type="date" name="fechaFin" value="<?php echo (isset($fechaFin) ? $fechaFin : '');?>">
      </section>
      
      <section class="in-sect-comp">
        <input class="scnd-btn" type="reset" value="Restablecer">
        <input class="main-btn" type="submit" value="Buscar">
      </section>

    </form>

    <?php
        // Se obtienen los datos de la base de datos
        $sentence = "SELECT * FROM cargo ";
        if (isset($fechaIn) AND isset($fechaFin)) {
            //Si existe un rango de fechas, se pide el total de ganancias en ese rango
            $res = exeQuery("$sentence WHERE fechaPago BETWEEN '$fechaIn' AND '$fechaFin';");
        } else $res = exeQuery("$sentence WHERE estado = TRUE;");  // Si no, se pide el total de ganancias
    ?>

    <section class="user-info">
      <h2>Ganancias según el marco : 
      <?php 
      // Se muestra el marco de fechas
      echo (isset($fechaIn) AND isset($fechaFin)) ? 
      date('d/m/y', strtotime($fechaIn))." - ".date('d/m/y', strtotime($fechaFin)) : 'Todos los registros';?></h2>
      <h2 id="total"><?php echo "$ $total"; ?></h2>
      <br>

      <table>
        <tr class="table-header">
          <th>ID</th>
          <th>Reservación</th>
          <th>Concepto</th>
          <th>Estado</th>
          <th>Fecha de l. pago</th>
          <th>Fecha de pago</th>
          <th>Monto</th>
        </tr>

        <?php
        // Se muestran los datos obtenidos
        if (mysqli_num_rows($res) > 0) {
          while ($row = mysqli_fetch_array($res)) {
              $total += $row['Monto'];
              echo "<tr>
                      <td>" . $row["ID_Cargo"] . "</td>
                      <td>" . $row["ID_Reservacion"] . "</td>
                      <td>" . $row["Concepto"] . "</td>
                      <td>" . ($row["estado"] ? 'Realizado' : 'Pendiente') . "</td>
                      <td>" . date('d/m/Y', strtotime($row["fechaPago"])) . "</td>
                      <td>" . date('d/m/Y', strtotime($row["fechaPagado"])) . "</td>
                      <td>$" . $row["Monto"] . "</td>
                    </tr>";
          }
          // Se actualiza el total
          updateTotal();
      } else echo "<tr><td colspan=".mysqli_num_fields($res)."><b>No hay registros aún</b></td></tr>";
        ?>
      </table>

    </section>

  </main>

  <?php footer_component();

  function updateTotal() {
    global $total;
    echo "<script>document.getElementById('total').innerHTML = '$ $total';</script>";
  }
  ?>
  
</body>
</html>