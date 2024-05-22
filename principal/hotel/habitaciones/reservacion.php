<?php 
    require_once("../../../puente/funciones/global_funcs.php");
    require_once("../../../puente/funciones/componentes.php");

    checkSession();

    if (!isset($_POST['fechaIn']) || !isset($_POST['fechaFin']) || !isset($_POST['habitacion']) || !isset($_POST['categoria']) || !isset($_POST['id_hab'])) {
        cambioPagina("/principal/hotel/habitaciones", "No se recupero la informacion necesaria para la reservacion");
        exit();
    }

    $fechaIn = $_POST['fechaIn'];
    $fechaFin = $_POST['fechaFin'];
    $habitacion = $_POST['habitacion'];
    $id_hab = $_POST['id_hab'];
    $categoria = $_POST['categoria'];

    $fechaInicio = new DateTime($fechaIn);
    $fechaFinal = new DateTime($fechaFin);
    $dias = $fechaInicio->diff($fechaFinal);

    $res = exeQuery("SELECT * FROM habitacion WHERE ID_Habitacion = $id_hab AND Categoria = '$categoria'");
    $datosHab = mysqli_fetch_array($res);
    $res1 = exeQuery("SELECT * FROM habitacion_stock WHERE ID_Habitacion_Stock = $habitacion");
    $datosStock = mysqli_fetch_array($res1);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../styles/globals.css">
  <link rel="stylesheet" href="../../../styles/form.css">
  <script src="../../front-funcs/funciones.js"></script>
  <script src="../../front-funcs/reservacion.js"></script>
  <title>Makhai - Reservacion</title>
</head>
<body>

    <?php header_component(); ?>

  <aside></aside>

  <main>
    <section class="header-section">
        <h1>Reservacion</h1>
    </section>

    <form id="formHab" class="main-form" action="../../../puente/funciones/reserHab.php" method="POST">

        <section class="text-cont">
            <h1>Habitacion</h1>
            <p>Antes de reservar esta habitacion, revisa que toda la informacion proporcionada sea correcta.
            <br>Makhai no maneja sistemas de rembolso.</p>
        </section>

        <input type="hidden" name="id_stock" value="<?php echo $habitacion; ?>">

        <section class="in-cont-sect">

            <h2>Estadía</h2>

            <section class="in-sect">
                <label for="ini">Fecha de arribo</label>
                <input type="date" id="ini" name="ini" value="<?php echo $fechaIn?>" readonly>
            </section>

            <section class="in-sect">
                <label for="fin">Fecha de retiro</label>
                <input type="date" id="fin" name="fin" value="<?php echo $fechaFin?>" readonly>
            </section>

            <section class="in-sect">
                <label for="dias">Dias de estancia</label>
                <input type="number" id="dias" name="dias" value="<?php echo $dias->days;?>" readonly>
            </section>

            <h2>Habitación</h2>

            <section class="in-sect">
                <label for="categoria">Categoria</label>
                <input type="text" name="categoria" value="<?php echo $datosHab['Categoria'];?>" readonly>
            </section>

            <section class="in-sect">
                <label for="tipo">Tipo</label>
                <input type="text" name="tipo" value="<?php echo $datosHab['Nombre'];?>" readonly>
            </section>

            <section class="in-sect">
                <label for="capa">Capacidad de personas</label>
                <input type="number" name="capa" value="<?php echo $datosHab['Capacidad'];?>" readonly>
            </section>

            <section class="in-sect">
                <label for="edificio">Edificio</label>
                <input type="text" name="edificio" value="<?php echo $datosStock['Edificio'];?>" readonly>
            </section>

            <section class="in-sect">
                <label for="planta">Planta</label>
                <input type="num" name="planta" value="<?php echo $datosStock['Planta'];?>" readonly>
            </section>

            <h2>Cargos</h2>
            
            <p>Makhai no maneja sistema de rembolsos.</p><br>

            <section class="in-sect">
                <label for="precio">Cargo por noche</label>
                <input type="text" value="<?php echo "$ ". $datosHab['Precio'];?>" readonly>
                <input type="hidden" name="precio" value="<?php echo $datosHab['Precio'];?>">
            </section>

            <section class="in-sect">
                <label for="total">Cargo total</label>
                <input type="text" value="<?php echo "$ ". $datosHab['Precio'] * $dias->days ;?>" readonly>
                <input type="hidden" name="total" value="<?php echo $datosHab['Precio'] * $dias->days ;?>">
            </section>

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

            <h2>Extras</h2>

            <section class="in-sect">
                <label for="concepto">Concepto</label>
                <textarea name="concepto" id="concepto" cols="35" rows="4" readonly><?php echo "Reservación habitación de stock: $habitacion | Tipo: {$datosHab['Nombre']} | Categoría: {$datosHab['Categoria']}";?>"</textarea>
            </section>

            <section class="in-sect">
                <label for="coment">Comentarios</label>
                <textarea name="coment" id="coment" cols="35" rows="4"></textarea>
            </section>

            <section class="in-sect btn-sect">
                <input type="button" class="scnd-btn" value="Buscar otra suite" onclick="irA(suites)">
                <input type="button" class="main-btn" value="Reservar" onclick="if(asistencia('Estas seguro de realizar esta acción?')) form.submit();">
            </section>
        </section>
        
    </form>
  </main>
  <script src="../../front-funcs/validaReserva.js"></script>

    <?php footer_component(); ?>
  
</body>
</html>