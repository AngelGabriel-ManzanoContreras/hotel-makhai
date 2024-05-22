<?php 
    require_once('../../../puente/funciones/componentes.php');
	  require_once('../../../puente/funciones/global_funcs.php');

    session_start();

    if ( !isset($_GET['tipo']) || !isset($_GET['habitacion']) ) cambioPagina("/principal/hotel/habitaciones", "No se ha especificado una habitacion");

    if( (isset($_POST['fecha-in']) && isset($_POST['fecha-fin']))) {
      $fechaIn = $_POST['fecha-in'];
      $fechaFin = $_POST['fecha-fin'];
    }

    if ( isset($_POST['personas'])) $personas = $_POST['personas'];

    $categoria = $_GET['tipo'];
    $habitacion = $_GET['habitacion'];
    $res = exeQuery(
      "SELECT * FROM habitacion WHERE Categoria = '$categoria' AND ID_Habitacion = $habitacion"
    );
    $datos = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../styles/globals.css">
  <link rel="stylesheet" href="../../../styles/form.css">
  <link rel="stylesheet" href="../../../styles/eventos.css">
  <link rel="stylesheet" href="../../../styles/perfil.css">
  <link rel="stylesheet" href="../../../styles/habitacion.css">
  <script src="../../front-funcs/funciones.js"></script>
  <script src="../../front-funcs/carrusel.js"></script>
  <title>Makhai - Habitacion <?php echo $datos['Nombre']; ?></title>
</head>
<body>

  <?php header_component(); ?>

  <main>
    <section class="titulo1"
      style="background-image: url('../img/habitaciones/habitacionclasica/imagen8.jpg');"
    >
      <h1><?php echo $datos['Nombre']; ?></h1>
    </section>

    <section class="main-sect">

      <section class="hab-desc-cont">

        <section class="desc">
          <h1>Descripción</h1>
          <p><?php echo $datos['Descripcion']; ?></p>
        </section>

        <section class="desc">
          <h2>Datos sobre la habitacion</h2>
          <p>Capacidad: <?php echo $datos['Capacidad']; ?> personas</p>

          <br>
          <p>Todo Incluido Desde</p>
          <p>Tarifa Regular</p>
          <p>$<?php echo $datos['Precio']; ?> MXN</p>
          <p>Por Habitación/Por Noche</p>
        </section>

        <?php
        $lista = explode(", ", $datos['Amenidades']);
        $servicios = Array();

        foreach($lista as $serv) {
          $res = exeQuery(
            "SELECT * FROM servicio WHERE ID_Servicio = $serv"
          );
          $serv = mysqli_fetch_assoc($res)['Nombre'];
          array_push($servicios, $serv);
        }

        $mitad1 = array_slice($servicios, 0, ceil(count($servicios) / 2));
        $mitad2 = array_slice($servicios, ceil(count($servicios) / 2));
        ?>

        <section class="desc">
          <h2>Amenidades y servicios</h2>

          <section class="list-cont">
            <section>
              <ul>
                <?php
                foreach($mitad1 as $serv) {
                  echo "<li>".$serv."</li>";
                }
                ?>
              </ul>
            </section>
            <section>
              <ul>
                <?php
                foreach($mitad2 as $serv) {
                  echo "<li>".$serv."</li>";
                }
                ?>
              </ul>
            </section>
          </section>

        </section>

      </section>

      <section class="hab-desc-cont">
        <section class="desc">
          <h1>Imágenes</h1>
        </section>
        <section class="carrusel">
          <button id="prev" type="button" class="arrow">
            <img src="../img/prev.svg" alt="">
          </button>

          <?php
            $res = exeQuery(
              "SELECT * FROM imagen WHERE ID_Elemento = $habitacion AND Tipo_Elemento = 'habitacion'"
            );
            while ( $img = mysqli_fetch_array($res) ) {
              echo "<figure class='carrusel-item'>
                      <img src='../../..".$img['Direccion']."' alt=''>
                    </figure>";
            }
          ?>
          
          <button id="next" type="button" class="arrow">
            <img src="../img/next.svg" alt="">
          </button>
        </section>
      </section>

      <section id="sect" class="hab-desc-cont user-info">
        <section class="desc">
          <h1>Habitaciones disponibles</h1>
          <p>Para poder reservar una habitacion, además de seleccionar una de las opciones disponibles, necesita establecer un rango de fechas. Estas las puede establecer en el buscador de aquí abajo.</p>
        </section>
        <form id="formy" action="habitacion.php?tipo=<?php echo $categoria;?>&habitacion=<?php echo $habitacion;?>#sect" method="POST">
          <section class="in-sect-comp">
            <label for="fecha-in">Fecha de entrada</label>
            <input type="date" id="fecha-in" name="fecha-in"
            value="<?php echo (isset($fechaIn)) ? $fechaIn : '';?>">
          
            <label for="fecha-out">Fecha de salida</label>
            <input type="date" id="fecha-fin" name="fecha-fin"
            value="<?php echo (isset($fechaFin)) ? $fechaFin : '';?>">
          </section>
          <section class="in-sect-comp">
            <button class="scnd-btn" onclick="limpiarCampos()">Restablecer</button>
            <button type="button" class="main-btn" onclick="validarYEnviar('fecha-in', 'fecha-fin', 'formy')">Buscar</button>
          </section>
        </form>
        <br>
        <form id="formre" action="reservacion.php" method="POST">
          <input type="hidden" id="fechaIn" name="fechaIn"
          value="<?php echo (isset($fechaIn)) ? $fechaIn : '';?>">

          <input type="hidden" id="fechaFin" name="fechaFin"
          value="<?php echo (isset($fechaFin)) ? $fechaFin : '';?>">

          <input type="hidden" id="id_hab" name="id_hab" value="<?php echo $datos['ID_Habitacion']; ?>">
          <input type="hidden" name="categoria" value="<?php echo $datos['Categoria']; ?>">

          <table>
            <tr class="table-header">
              <th>Selección</th>
              <th>Categoria</th>
              <th>Nombre</th>
              <th>Ubicación</th>
            </tr>
            <tr>
              <?php
              $sentence = ( (isset($fechaIn) && $fechaIn !== '') && (isset($fechaFin) && $fechaFin !== '') ) 

              ? /*"SELECT * FROM habitacion_stock AS hs
              LEFT JOIN Reservacion_Elemento AS re ON hs.ID_Habitacion_Stock = re.ID_Reservacion_Elemento
              LEFT JOIN Reservacion AS r ON re.ID_Reservacion = r.ID_Reservacion
              WHERE (
                re.ID_Reservacion IS NULL 
                OR (r.Fecha_Fin < '$fechaIn' OR r.Fecha_Inicio > '$fechaFin')
                )
              AND hs.Tipo = $habitacion"*/
              "SELECT DISTINCT hs.*
              FROM habitacion_stock AS hs
              LEFT JOIN reservacion_elemento AS re ON hs.ID_Habitacion_Stock = re.ID_Reservacion_Elemento
              LEFT JOIN reservacion AS r ON re.ID_Reservacion = r.ID_Reservacion
              WHERE (re.ID_Reservacion_Elemento IS NULL
                OR (re.ID_Reservacion_Elemento IS NOT NULL AND NOT EXISTS (
                  SELECT *
                  FROM reservacion_elemento AS re2
                  JOIN reservacion AS r2 ON re2.ID_Reservacion = r2.ID_Reservacion
                  WHERE re2.ID_Reservacion_Elemento = hs.ID_Habitacion_Stock
                    AND (r2.Fecha_Fin >= '$fechaIn' AND r2.Fecha_Inicio <= '$fechaFin')
                  )
                )
                ) AND hs.Tipo = $habitacion;"

              : "SELECT * FROM habitacion_stock AS hs 
              WHERE hs.Tipo = $habitacion
              AND hs.ID_Habitacion_Stock NOT IN (
                SELECT ID_Reservacion_Elemento FROM reservacion_elemento
              )";
              
              if (isset($personas) && $personas != '') {
                $sentence .= " AND Capacidad >= $personas";
              }
              $res = exeQuery($sentence);

              while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                      <td>
                        <input type='radio' name='habitacion' id='habitacion' value='".$row['ID_Habitacion_Stock']."'>
                      </td>
                      <td>
                      ".$datos['Categoria']."
                      </td>
                      <td>
                      ".$datos['Nombre']."
                      </td>
                      <td>
                        <p><b>Edificio:</b> ".$row['Edificio']."</p>
                        <p><b>Piso:</b> ".$row['Planta']."</p>
                      </td>
                      </tr>";
              }
              ?>
            </tr>
          </table>
          <br>
          <section class="in-sect">
              <?php 
              // Si no hay una sesión iniciada, se muestra un mensaje
              // Si hay una sesión iniciada, se muestra el botón de asistencia
              echo ( !isset($_SESSION['session']) AND !isset($_SESSION['correo']) ) 
              ? "Debes iniciar sesión para poder reservar una habitación." : 
              '<button class="main-btn" type="button" onclick="
              if (validarSeleccion(\'habitacion\')) {
                validarYEnviar(\'fechaIn\', \'fechaFin\', \'formre\');
              } else return false;
            ">Reservar</button>';
              ?>
          </section>
        </form>
        
      </section>

    </section>

  </main>

  <?php footer_component(); ?>
  
</body>
</html>