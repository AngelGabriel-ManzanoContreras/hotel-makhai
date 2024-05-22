<?php
    require_once('../../../puente/funciones/componentes.php');
	require_once('../../../puente/funciones/global_funcs.php');

    checkSession();
    if ( !isset($_POST['fecha']) || !isset($_POST['ID_Evento']) || !isset($_POST['capacidad']) ) {
        cambioPagina("/principal/hotel/eventos", "No se recuperaron los datos necesarios.");
        exit();
    }

    $fechaAsistencia = $_POST['fecha'];
    
    $res = exeQuery(
        "SELECT * FROM evento WHERE ID_Evento = {$_POST['ID_Evento']}"
    );

    $evento = mysqli_fetch_assoc($res);

    $concepto = "Asistencia evento : ". $evento['Nombre']." | ID evento : ". $evento['ID_Evento'];

    $res = exeQuery(
        "SELECT COUNT(*) AS total FROM cargo AS c
        JOIN reservacion AS r ON c.ID_Reservacion = r.ID_Reservacion
        WHERE c.Concepto = 'Asistencia evento : {$evento['Nombre']} | ID evento : {$evento['ID_Evento']}' AND r.Fecha_Inicio = '{$fechaAsistencia}' AND r.cancelado = 0"
    );

    $ocupados = mysqli_fetch_array($res)['total'];
    if ($ocupados >= $_POST['capacidad']) {
        cambioPagina("/principal/hotel/eventos", "No hay lugares disponibles para la fecha seleccionada.");
        exit();
    }

    $costo = $evento['Costo'];

    $res = exeQuery(
        "SELECT ID_Usuario FROM usuario WHERE Email = '{$_SESSION['correo']}' AND Nombre = '{$_SESSION['nombre']}'"
    );
    $id_us = mysqli_fetch_array($res)['ID_Usuario'];
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
  <?php header_component(); ?>

  <main>
  
    <form id="formResEv" class="main-form" action="../../../puente/funciones/even-res.php" method="POST">
        <section class="text-cont">
            <h1>Reservacion para el evento: <? echo $evento['Nombre']; ?></h1>
        </section>

        <input type="hidden" name="id_us" value="<?php echo $id_us; ?>">
        <input type="hidden" name="idEvento" value="<?php echo $evento['ID_Evento']; ?>">
        
        <section class="in-cont-sect">

            <section class="in-sect">
                <label for="coment">Concepto</label>
                <input type="text" name="coment" value="<?php echo $concepto; ?>" readonly>
            </section>
            
            <section class="in-sect">
                <label for="fecha">Día de asistencia</label>
                <input type="date" name="fecha" value="<?php echo $fechaAsistencia; ?>" readonly>
            </section>

            <br>
            <h2>Cargos</h2>

            <p>Makhai no maneja sistema de rembolsos.</p><br>
            
            <section class="in-sect">
                <label for="costo">Cargo <sub>mxn</sub></label>
                <input type="text" value="<?php echo ($costo == 0) ? 'Gratis' : $costo; ?>" readonly>
                <input type="hidden" name="costo" value="<?php echo $costo; ?>">
            </section>

            <br>
            <h2>Informacion de pago</h2>

            <section class="in-sect">
                <label for="tarjeta">Tarjeta de credito</label>
                <input type="number" id="tarjeta" name="tarjeta" required>
            </section>

            <section class="in-sect">
                <label for="nombreT">Nombre</label>
                <input type="text" id="nombreT" name="nombreT" placeholder="Nombre completo del titular" required>
            </section>
            
            <br>
            <p>No te preocupes por ingresar un dia exacto, solo nos interesa el mes y el año de vencimiento.</p>
            <section class="in-sect">
                <label for="fechaEx">Fecha de expiracion</label>
                <input type="date" id="fechaEx" name="fechaEx" required>
            </section>

            <section class="in-sect">
                <label for="csv">CSV</label>
                <input type="number" id="csv" name="csv" required>
            </section>

            <section class="in-sect-comp">
                <input type="reset" class="scnd-btn" value="Limpiar">
                <button type="button" class="main-btn" onclick="if(asistencia('¿Está seguro que desea asistir al evento?')) form.submit();">Enviar</button>
            </section>

        </section>
    </form>
  </main>
  <script src="../../front-funcs/validaReserva.js"></script>

  <?php footer_component(); ?>
</body>
</html>