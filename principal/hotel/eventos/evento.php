<?php 
    require_once('../../../puente/funciones/componentes.php');
	require_once('../../../puente/funciones/global_funcs.php');

    session_start();

    // Si hay un elemento seleccionado, se obtiene su información
    if (isset($_GET['elemento'])) {
        $res = exeQuery(
            "SELECT * FROM evento WHERE ID_Evento = '{$_GET['elemento']}'"
        );
        $evento = mysqli_fetch_array($res);

    // Si no, se redirige a la página de eventos
    } else cambioPagina("/principal/hotel/eventos", "Ningún evento seleccionado.");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../styles/globals.css">
  <link rel="stylesheet" href="../../../styles/eventos.css">
  <link rel="stylesheet" href="../../../styles/perfil.css">
  <link rel="stylesheet" href="../../../styles/form.css">
  <script src="../../front-funcs/funciones.js"></script>
  <title>Makhai - Evento <?php echo $evento['Nombre']; ?></title>
</head>
<body>
  <?php header_component(); 
    $res = exeQuery(
        "SELECT * FROM imagen WHERE ID_Elemento = '{$_GET['elemento']}' AND Tipo_Elemento = 'Evento'"
    );
    $direccion = mysqli_fetch_array($res)['Direccion'];
  ?>
  <main>
  <section class="titulo1"
  style="background-image: url('../../..<?php echo $direccion?>') !important;">

    <h1 class="titulo"><?php echo $evento['Nombre']; ?></h1>
    <h2><?php echo $evento['Categoria']; ?></h2>
  </section>

    <section class="main-sect">

        <section class="user-info">
            <h2>Descripción</h2>
            <p><?php echo $evento['Descripcion']; ?></p>

            <form action="reservacion.php" method="POST">

                <section class="details-cont">
                    <section class="details">
                        <h2>Fecha</h2>
                        <fieldset>

                        <?php // Se obtienen las fechas del evento
                        $fechas = explode(", ", $evento['Dias']);

                        $dias = 0;
                        foreach ($fechas as $fecha) {
                            $dias++;
                            $fecha = new DateTime($fecha); // Se convierte a formato de fecha
                            $formattedDate = $fecha->format('Y-m-d');

                            echo '
                            <input type="radio" name="fecha" value="' . $formattedDate . '">
                            <label for="fecha">'.$formattedDate.'</label>
                            <br>';
                        }
                        ?>
                        </fieldset>
                        <br>
                        <h2>Duración</h2>
                        <?php
                        // Se obtiene la duración del evento
                        $horaIn = new DateTime($evento['hora_inicio']);
                        $horaFin = new DateTime($evento['hora_cierre']);
                        $duracion = $horaIn->diff($horaFin); // Se calcula la diferencia entre las horas
                        $duracion = $duracion->format('%H:%I'); // Se convierte a formato de hora
                        ?>
                        <p><?php echo $duracion . " Horas"; ?></p>
                    </section>

                    <section class="details">
                        <h2>Hora de inicio</h2>
                        <input type="time" value="<?php echo $evento['hora_inicio']?>" readonly>
                        <br>
                        <br>
                        <h2>Hora de cierre</h2>
                        <input type="time" value="<?php echo $evento['hora_cierre']?>" readonly>
                    </section>

                    <section class="details">
                        <h2>Ubicación</h2>
                        <p><?php echo $evento['Ubicacion']; ?></p>
                        <br>
                        <h2>Costo</h2>
                        <p><?php echo ($evento['Costo'] > 0) ? "$ {$evento['Costo']}" : 'Gratis'; ?></p>
                        <br>
                        <h2>Cupo</h2>
                        <p><?php echo intdiv($evento['Cupo'], $dias)." personas/dia"; ?></p>
                        <input type="hidden" name="capacidad" value="<?php echo intdiv($evento['Cupo'], $dias); ?>">
                    </section>
                </section>

                <input type="hidden" name="ID_Evento" value="<?php echo $evento['ID_Evento']; ?>">

                <section class="in-sect">
                    <?php 
                    // Si no hay una sesión iniciada, se muestra un mensaje
                    // Si hay una sesión iniciada, se muestra el botón de asistencia
                    echo ( !isset($_SESSION['session']) AND !isset($_SESSION['correo']) ) 
                    ? "Debes iniciar sesión para asistir a este evento." : ( ($evento['Disponible'] == 0) 
                    ? 'Por el momento ya no hay cupo para este evento.' 
                    : '<input type="submit" class="main-btn" value="Asistir">');
                    ?>
                </section>
            </form>
        </section>

        <section class="in-sect btn-sect">
            <button class="main-btn" onclick="irA(eventos + '#cat')">Más eventos</button>
            <button class="main-btn" onclick="irA(eventos + '?categoria=<?php echo $evento['Categoria']; ?>#cat')">Esta categoria</button>
        </section>

    </section>

  </main>

  <?php footer_component(); ?>
  
</body>
</html>