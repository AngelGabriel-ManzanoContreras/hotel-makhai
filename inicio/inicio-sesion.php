<?php require_once('../puente/funciones/componentes.php'); ?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/globals.css">
    <link rel="stylesheet" href="../styles/form.css">
    <script src="../principal/front-funcs/funciones.js"></script>
    <title>Makhai - Inicia sesion</title>
  </head>
  <body>
  
  <?php header_component(true); ?>

  <aside></aside>

  <main>
    <form class="main-form" action="<?php echo (isset($_GET['ed'])) ? 
    '../puente/funciones/confirmSession.php' : '../puente/funciones/inicio_sesion.php';?>" method="post">
      <h1>Makhai</h1>

      <section class="text-cont">
        <!--
          Si se envia el parametro ed, se muestra un mensaje indicando que se necesita confirmar la identidad
          para poder manipular los datos
        -->
        <h2><?php echo (isset($_GET['ed'])) ? 'Confirma que eres tu' : 'Inicia sesión' ?></h2>
        <h3><span><?php echo (isset($_GET['ed'])) ? 
        'Necesitamos que pruebes tu identidad antes de poder manipular estos datos' 
        : '¿Aún no tienes una cuenta? <a href="registro.php">Registrate</a>'?></span></h3>
      </section>

      <section class="in-cont-sect">

        <section class="in-sect">
          <label for="correo">Correo</label>
          <input type="email" name="correo" placeholder="correo.electronico@gmail.com" required>
        </section>

        <section class="in-sect">
          <label for="cont1">Contraseña</label>
          <input type="password" name="cont1" placeholder="contraseña" required>
        </section>

        <?php
            // si se envia el parametro ed, se envia un input hidden con el valor 1
            // esto para que el archivo de inicio_sesion.php sepa si se debe redirigir a la pagina de edicion de perfil
            if (isset($_GET['ed'])) echo '<input type="hidden" name="ed" value="1">';
        ?>

        <section class="in-sect btn-sect-e">
          <a href="rest-contra.php">Olvidé mi contraseña</a>
          <input class="scnd-btn" type="reset" value="Restablecer">
          <input class="main-btn" type="submit" value="<?php echo (isset($_GET['ed'])) ? 'Confirmar' : 'Iniciar sesion'?>">
        </section>

      </section>
    </form>
  </main>

  <?php footer_component(); ?>
  
</body>
</html>