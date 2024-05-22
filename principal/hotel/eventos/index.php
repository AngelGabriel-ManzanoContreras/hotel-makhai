<?php 
    require_once('../../../puente/funciones/componentes.php');
	  require_once('../../../puente/funciones/global_funcs.php');

    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../styles/globals.css">
  <link rel="stylesheet" href="../../../styles/eventos.css">
  <link rel="stylesheet" href="../../../styles/panel.css">
  <script src="../../front-funcs/funciones.js"></script>
  <title>Makhai - Eventos</title>
</head>
<body>

  <?php header_component(); ?>

  <main>
    <section class="titulo1" id="">
      <h1>Eventos</h1>
    </section>

    <?php
        $sent = "SELECT * FROM evento";
        // Si hay una categoría seleccionada, se obtienen los eventos de esa categoría
        // Si no, se obtienen todos los eventos
        $res = ( isset($_GET['categoria']) ) ?
        exeQuery("$sent WHERE Categoria = '{$_GET['categoria']}'") : exeQuery($sent);
    ?>
    <section class="main-sect" id="cat">

      <section class="header-section">
        <h1>Categorias</h1>
      </section>

      <?php 
        // Se obtienen las categorías de los eventos
        $cate = exeQuery("SELECT DISTINCT Categoria FROM evento"); 
      ?>
      <section class="option-sect">

        <article class="option-cont">
          <a href="./#cat">Todo</a>
        </article>

        <?php 
          // Se muestran las categorías como opciones para filtrar los eventos
          while ($categoria = mysqli_fetch_array($cate)) {
            echo 
            "<article class='option-cont'>
              <a href='?categoria={$categoria['Categoria']}#cat'>
                {$categoria['Categoria']}
              </a>
            </article>";
          }
        ?>
      </section>

      <br>
      <section class="adm-act-cont">
        <?php
            // Se muestran los eventos de la categoría seleccionada (o todos los eventos)
            while ( $row = mysqli_fetch_array($res) ) {
              $horaIn = new DateTime($row['hora_inicio']);
              $horaFin = new DateTime($row['hora_cierre']);
              $duracion = $horaIn->diff($horaFin);
              $duracion = $duracion->format('%H:%I');

              action_card_component(
                Array(
                  'titulo' => $row['Nombre'],
                  'elemento' => $row['ID_Evento'],
                  'desc'=> $row['Categoria'],
                  'acciones'=> '<b>Duración :</b> '.$duracion ." Horas<br>". ((strlen($row['Descripcion']) > 74) ?
                    substr($row['Descripcion'], 0, 74). "..." : $row['Descripcion']),
                  'path' => 'evento',
                ),
                "Ver evento"
              );
            }
        ?>
        <p><b>Si deseas realizar un evento en el hotel, comunicate al 1 877 845 6030 para más información.</b></p>
      </section>

    </section>

  </main>

  <?php footer_component(); ?>

</body>
</html>