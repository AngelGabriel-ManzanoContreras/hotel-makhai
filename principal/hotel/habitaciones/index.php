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
  <link rel="stylesheet" href="../../../styles/habitaciones.css">
  <script src="../../front-funcs/funciones.js"></script>
  <title>Makhai - Habitaciones</title>
</head>
<body>

  <?php header_component(); ?>

  <main>

    <section class="titulo">
      <h1>Habitaciones</h1>
    </section>

    <section id="tipos">
      
      <section class="separacion1">
        <a class="refs" href="categoria.php?tipo=Habitacion clasica">Habitacion clasica</a>
      </section>
  
      <section class="separacion1">
        <a class="refs" href="categoria.php?tipo=Suite">Suite</a>
      </section>
  
      <section class="separacion1">
        <a class="refs" href="categoria.php?tipo=Suite presidencial">Suite presidencial</a>
      </section>

    </section>

  </main>

  <?php footer_component(); ?>
  
</body>
</html>