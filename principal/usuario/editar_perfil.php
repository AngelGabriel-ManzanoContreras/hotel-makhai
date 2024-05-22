<?php
	require_once('../../puente/funciones/componentes.php');
	require_once('../../puente/funciones/global_funcs.php');

	checkSession(); // Verificar que haya una sesión iniciada

    // Si hay una sesión iniciada, se obtienen los datos del usuario
    $res = exeQuery(
        "SELECT * FROM usuario WHERE Nombre = '{$_SESSION['nombre']}';"
    );
    $data = mysqli_fetch_array($res);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/globals.css">
    <link rel="stylesheet" href="../../styles/form.css">
    <link rel="stylesheet" href="../../styles/perfil.css">
    <script src="../front-funcs/funciones.js"></script>
    <title>Makhai - <?php echo $_SESSION['nombre']; ?></title>
  </head>
  <body>
  
  <?php header_component(); ?>

  <aside></aside>

  <main>
    
    <form action="../../puente/funciones/update.php" class="main-form" method="POST" enctype="multipart/form-data">

        <section class="text-cont">
            <h2>Actualiza tus datos <?php echo $data['Nombre']; ?></h2>
            <p>Una vez que los actualizas, no hay manera de regresarlos a su estado anterior a menos de que los recuerde.</p>
        </section>

        <section class="in-cont-sect">
            <input type="hidden" name="ide" value="<?php echo $data['ID_Usuario']; ?>">

            <section class="in-sect">
                <label for="nombre">Nombre</label>
                <input class="it-v" name="nombre" type="text" value="<?php echo $data['Nombre'];?>" maxlength="255" required>
            </section>

            <section class="in-sect">
                <label for="ap_p">Apellido Paterno</label>
                <input class="it-v" type="text" name="ap_p" value="<?php echo $data['ApellidoP'];?>" maxlength="255" required>
            </section>

            <section class="in-sect">
                <label for="apm">Apellido Materno</label>
                <input class="it-v" type="text" name="apm" value="<?php echo $data['ApellidoM'];?>" maxlength="255" required>
            </section>

            <section class="in-sect">
                <label for="tel">Teléfono</label>
                <input class="it-v" type="tel" name="telefono" value="<?php echo $data['Telefono'];?>" required>
            </section>

            <section class="in-sect">
                <label for="correo">Correo Electrónico</label>
                <input class="it-v" type="email" name="correo" value="<?php echo $data['Email'];?>" maxlength="255" required>
            </section>

            <section class="in-sect">
                <label for="imagen">Imagen</label>
                <input class="it-v" type="file" name="imagen" accept="image/*" size="20">
            </section>

            <section class="in-sect">
                <label for="contra">Contraseña</label>
                <input type="password" name="contra" placeholder="Contraseña para Actualizar" required>
            </section>

            <section class="text-cont">
                <a class="trnry-btn" href="../../inicio/rest-contra.php">Restablecer contraseña</a>
            </section>            
            
            <section class="in-sect btn-sect" id="updt-sect">
                <input type="reset" class="scnd-btn" value="Restablecer">
                <input type="button" class="main-btn" id="update-btn" value="Actualizar">
            </section>
        </section>

    </form>

  </main>
  <script src="../front-funcs/usuario.js"></script>

  <?php footer_component(); ?>
  
</body>
</html>