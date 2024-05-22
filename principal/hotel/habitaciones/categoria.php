<?php 
    require_once('../../../puente/funciones/componentes.php');
	  require_once('../../../puente/funciones/global_funcs.php');

    session_start();

    $tipo = $_GET['tipo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../styles/globals.css">
  <link rel="stylesheet" href="../../../styles/eventos.css">
  <link rel="stylesheet" href="../../../styles/habitacion.css">
  <script src="../../front-funcs/funciones.js"></script>
  <title>Makhai - <?php echo $tipo; ?></title>
</head>
<body>

  <?php header_component(); ?>

  <main>
    <section class="titulo1" style="background-image: url('../img/habitaciones/habitacionclasica/imagen8.jpg');">
      <h1> <?php echo $tipo; ?></h1>
    </section>

    <section class="main-sect">

      <?php
          $res = exeQuery(
            "SELECT * FROM habitacion WHERE Categoria = '$tipo'"
          );
      ?>

      <?php
          while ($row = mysqli_fetch_array($res)) {
            $res2 = exeQuery(
              "SELECT Direccion FROM imagen WHERE ID_Elemento = $row[ID_Habitacion] AND Tipo_Elemento = 'Habitacion' LIMIT 1"
            );
            $row2 = mysqli_fetch_assoc($res2)['Direccion'];
            $row['imagen'] = $row2;
            habitacion_card_component(
              $row['ID_Habitacion'],
              $row['Nombre'],
              $row['imagen'],
              $row['Categoria'],
              $row['Capacidad'],
              $row['Precio']
            );
          }

          if (mysqli_num_rows($res) == 0)
            echo '
              <br>
              <article>
                <h2>Lamentablemente no se encontraron habitaciones de este tipo</h2>
              </article>
            ';
      ?>
      
    </section>

  </main>

  <?php footer_component(); ?>
  
</body>
</html>