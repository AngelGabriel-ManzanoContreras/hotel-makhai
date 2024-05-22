<?php 
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

    <section class="adm-act-cont">

    <?php
        action_card_component(Array(
            'titulo' => 'Panel de administración de Makhai.',
            'desc'=> 'Visualiza las ganacias del hotel y demás acciones de gestión.',
            'acciones'=> 'Multiples acciones',
            'path' => 'panel',
            'elemento'=> '',
        ));

        action_card_component(Array(
            'titulo' => 'Registro de Administradores',
            'desc'=> 'Página para registrar nuevos administradores.',
            'acciones'=> 'Crea nuevos administradores',
            'path' => 'registroAdmin',
            'elemento'=> '',
        ));

        action_card_component(Array(
            'titulo' => 'Sitio web',
            'desc'=> 'Sitio web de Makhai, visualiza el sitio web.',
            'acciones'=> 'Interactua como si fueras un cliente.',
            'path' => 'PROYECTO',
            'elemento'=> '',
        ));
    ?>

    </section>
    </main>

  <?php footer_component(); ?>
  
</body>
</html>
