<?php require_once('../puente/funciones/componentes.php'); ?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/globals.css">
    <link rel="stylesheet" href="../styles/form.css">
    <script src="../principal/front-funcs/funciones.js"></script>
    <title>Makhai - Registro</title>
  </head>
  <body>
  
  <?php header_component(true); ?>

  <aside></aside>

  <main>
    <form class="main-form" action="../puente/funciones/registro.php" method="post">
      <h1>Makhai</h1>

      <section class="text-cont">
        <h2>Registro</h2>
        <h3><span>¿Ya tienes una cuenta? <a class="ini_ses">Inicia sesión</a></span></h3>
      </section>

      <section class="in-cont-sect">
       
        <section class="in-sect">
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" placeholder="Nombre(s)" maxlength="255" required>
        </section>

        <section class="in-sect">
          <label for="apellido_p">Apellidos</label>
        </section>
        
        <section class="in-sect">
          <input type="text" name="apellido_p" placeholder="Apellido Paterno" maxlength="255" required>
          <input type="text" name="apellido_m" placeholder="Apellido Materno" maxlength="255" required>
        </section>

        <section class="in-sect">
          <label for="correo">Correo</label>
          <input type="email" name="correo" placeholder="correo.electronico@gmail.com" maxlength="255" required>
        </section>

        <section class="in-sect">
          <label for="telefono">Teléfono</label>
          <input type="tel" name="telefono" placeholder="+5213314673635" required>
        </section>

        <section class="in-sect">
          <label for="cont1">Contraseña</label>
          <input type="password" name="cont1" placeholder="contraseña" maxlength="255" required>
        </section>

        <section class="in-sect">
          <label for="cont2">Contraseña</label>
          <input type="password" name="cont2" placeholder="confirma tu contraseña" maxlength="255" required>
        </section>

        <section class="in-sect btn-sect">
          <input type="hidden" name="tipo" value="0">
          <input class="scnd-btn" type="reset" value="Restablecer">
          <input class="main-btn" type="submit" value="Registrar">
        </section>

      </section>
    </form>
  </main>

  <?php footer_component(); ?>
  
</body>
</html>