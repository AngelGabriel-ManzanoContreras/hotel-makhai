<?php
    require_once('./puente/funciones/componentes.php');
    require_once('./puente/funciones/global_funcs.php');
    
    session_start();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./styles/globals.css">
        <link rel="stylesheet" href="./styles/form.css">
        <link rel="stylesheet" href="./styles/index.css">
        <script src="./principal/front-funcs/funciones.js"></script>
        <title>Makhai - Pagina Principal</title>
      </head>
      <body>
      
        <?php header_component(); ?>
        
    <main>
        <!--<form id="mainform" class="main-form" action="./principal/hotel/habitaciones/disponibles.php" method="POST">
            <section class="in-sect-comp">
                <label for="Fechallegada">Fecha de llegada</label>
                <input type="date" id="Fechallegada" name="Fechallegada">
                <label for="Fechasalida">Fecha de salida</label>
                <input type="date" id="Fechasalida" name="Fechasalida">
            </section>
      
            <input type="number" name="Personas" placeholder="Personas">

            <section class="in-sect-comp" id="mrg-btn-mid">
                <button type="button" id="btn-mid" class="main-btn" onclick="validarYEnviar('Fechallegada', 'Fechasalida', 'mainform');">Reservar ahora</button>
            </section>
            
        </form>-->

        <figure>
            <img class="imgin" src="./principal/hotel/img/MKinicio.png">
        </figure>
</main>

<?php footer_component(); ?>
</body>
</html>