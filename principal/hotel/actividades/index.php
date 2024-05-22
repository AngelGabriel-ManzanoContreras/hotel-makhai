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
  
  <link rel="stylesheet" href="../../../styles/actividades.css">
  <link rel="stylesheet" href="../../../styles/globals.css">
  <link rel="stylesheet" href="../../../styles/eventos.css">
  <link rel="stylesheet" href="../../../styles/perfil.css">
  <script src="../../front-funcs/funciones.js"></script>
  <title>Makhai - Actividades</title>
</head>
<body>
    <?php header_component(); ?>
    
    <main>
        <section class="titulo1" style="height: 65vh; background-image: url('../img/actividades/cabeza.jpg') ;">
          <h1>Actividades</h1>
        </section>
        <br>
        <section class="user-info">
            <p>Al seleccionar Makhai  como tu hogar lejos de casa, están dirigidas a los amantes de la buena comida, aficionados de las actividades físicas, niños y simplemente a quienes les gusta interactuar con otros huéspedes durante su estancia. Hay tantas cosas que hacer en Nuevo Vallarta en nuestro resort con todo incluido, que no te querrás ir.</p>
            <br>
            <p>Cuando te hospedes en Hotel Makhaí  Riviera Nayarit, relájate estando seguro de que encontrarás una diversidad de actividades en el resort, noches temáticas y otras divertidas cosas que hacer en Nuevo Vallarta, las cuales ofrecen el equilibrio perfecto entre la emoción y la plena relajación. Nuestro resort con opción a todo incluido en Nuevo Vallarta cuenta con una gran variedad de actividades gratuitas todos los días de 9:00 am a 6:00 pm y diversas noches temas que invitan a socializar y bailar toda la noche. Consulta con nuestro staff de actividades para obtener más información y una lista completa y actualizada de los programas disponibles en el resort todo incluido en Nuevo Vallarta.</p>
        </section>

        <section class="escrito">

        <?php
        $res = exeQuery(
            "SELECT * FROM actividad"
        );

        if (mysqli_num_rows($res) > 0) {
            while ($actividad = mysqli_fetch_assoc($res)) {
                $res2 = exeQuery(
                    "SELECT * FROM imagen WHERE ID_Elemento = {$actividad['ID_Actividad']} AND Tipo_Elemento = 'Actividad' LIMIT 1"
                );
                $imagen = mysqli_fetch_assoc($res2)['Direccion'];
        ?>
            <section class="act">
                <figure class="imagenes">
                    <img src="../../..<?php echo $imagen; ?>" alt="">
                </figure>

                <section class="descripcion">
                    <h3><?php echo $actividad['Nombre']; ?></h3>
                    <p><?php echo $actividad['Descripcion']; ?></p>
                </section>
            </section>
        <?php } } else { ?>
            <h2>No hay actividades disponibles.</h2>
        <?php } ?>

        </section>
</main>

<?php footer_component(); ?>  
  
</body>
</html>
    
