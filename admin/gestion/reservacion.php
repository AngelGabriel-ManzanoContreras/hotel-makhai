<?php
    require_once('../../puente/funciones/componentes.php');
    require_once('../../puente/funciones/global_funcs.php');

    checkSession();

    // Si se recibe un id de reservación (para mostrar los datos de la reservación)
    if (isset($_GET['id'])) $id_re = $_GET['id'];
    // Si no se recibe un id de reservación, se redirige al perfil
    else cambioPagina('/principal/usuario', 'No se recibió un id de reservación');

    $res = exeQuery("
        SELECT reservacion.*, cargo.* 
        FROM reservacion
        INNER JOIN cargo ON reservacion.ID_Reservacion = cargo.ID_Reservacion
        WHERE reservacion.ID_Reservacion = {$id_re};
    ");
    $reservacion = mysqli_fetch_array($res);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../styles/globals.css">
  <link rel="stylesheet" href="../../styles/form.css">
  <link rel="stylesheet" href="../../styles/perfil.css">
  <script src="../../principal/front-funcs/funciones.js"></script>
  <title>Makhai - Reservacion <?php echo $id_re; ?></title>
</head>
<body>

    <?php header_component(); ?>

  <aside></aside>

  <script>
    function confirmaBorro() {
      if (window.confirm("¿Está seguro de eliminar la reserva?")) form.submit();
    }
  </script>

  <main>
    <section class="header-section">
      <h1>Información de reservacion <?php echo $id_re; ?></h1>
    </section>

    <?php
    $resUs = exeQuery(
        "SELECT Nombre, ApellidoP, ApellidoM FROM usuario WHERE ID_Usuario = {$reservacion['ID_Hospedante']}"
    );

    $usuario = mysqli_fetch_assoc($resUs);
    ?>

    <section class="user-info">

      <section class="in-cont-sect" style="margin: 0;">

        <section class="in-sect">
          <b>ID de Reservación:</b>
          <p><?php echo $reservacion['ID_Reservacion']?></p>
        </section>

        <section class="in-sect">
            <b>Nombre del hospedante:</b>
            <p><?php echo $usuario['Nombre'] ." ". $usuario['ApellidoP']." ".$usuario['ApellidoM']?></p>
        </section>
        <br>

        <section class="in-sect">
          <b>Concepto:</b>
          <p><?php echo $reservacion['Concepto']; ?></p>
        </section>
        
        <section class="in-sect">
          <b>Fecha de reservación:</b>
          <!--
            La función strtotime convierte la cadena de fecha en una marca de tiempo,
            permitiendo así que la fecha se muestre en un formato personalizado.
            En este caso, date('d/m/Y H:i:s') formatea la fecha y hora en el formato
            dd/mm/aaaa hh:mm:ss. Esto proporciona una presentación más legible
            para el usuario en lugar del formato predeterminado de la base de datos.
          -->
          <p><?php echo date('d/m/Y H:i:s', strtotime($reservacion['fechaPagado'])); ?></p>
        </section>
        
        <section class="in-sect">
          <b>Fecha de inicio:</b>
          <p><?php echo  date('d/m/Y', strtotime($reservacion['Fecha_Inicio'])); ?></p>
        </section>

        <section class="in-sect">
          <b>Fecha de fin:</b>
          <p><?php echo date('d/m/Y', strtotime($reservacion['Fecha_Fin'])); ?></p>
        </section>

        <section class="in-sect">
          <b>Costo:</b>
          <p>$ <?php echo $reservacion['total']; ?></p>
        </section>

        <section class="in-sect">
          <b>Estado:</b>
          <p><?php echo ($reservacion['estado'] == 1) ? 'Pagado' : 'Pendiente'; ?></p>
        </section>

        <?php 
        $res = exeQuery("
          SELECT * FROM reservacion_elemento AS re 
          JOIN habitacion_stock AS hs ON re.ID_Reservacion_Elemento = hs.ID_Habitacion_Stock
          JOIN habitacion as h ON h.ID_Habitacion = hs.Tipo
          WHERE re.ID_Reservacion = $id_re;
        ");
        if ($res && mysqli_num_rows($res) > 0) {
          echo "<table>
              <tr class='table-header'>
                <th>Elemento</th>
                <th>Categoria</th>
                <th>Costo</th>
              </tr>
            ";
          while ($elemento = mysqli_fetch_array($res)) {
            echo '
              <tr>
                <td>
                  <button class="trnry-btn" 
                  onclick="irA(suites+\'/habitacion.php?tipo='.$elemento['Categoria'].'&habitacion='.$elemento['ID_Habitacion'].'\')">
                    '.$elemento['Nombre'].'
                  </button>
                </td>
                <td>'.$elemento['Categoria'].'</td>
                <td>$ '.$elemento['Precio'].'</td>
              </tr>
            ';
          }
          echo "</table>";
        }
        ?>

        <form action="../../puente/funciones/cancelRes.php" method="POST" style="width: 100%;">
          <section class="in-sect-comp">
            <input type="hidden" name="reservacion" value="<?php echo $reservacion['ID_Reservacion']; ?>">
            <button class="scnd-btn" onclick="confirmaBorro();">Cancelar reservación</button>
            <a class="main-btn" href="./index.php">Regresar</a>
          </section>
        </form>
      </section>

    </section>
  </main>
  
  <?php footer_component(); ?>

</body>
</html>