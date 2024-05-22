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
  <link rel="stylesheet" href="../../../styles/servicios.css">
  <link rel="stylesheet" href="../../../styles/eventos.css">
  <script src="../../front-funcs/funciones.js"></script>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <title>Makhai - Servicios</title>
</head>
<body>
    <?php header_component(); ?>

  <main>
    <section class="titulo1"
    style="background-image: url('../img/servicios/ServMak.jpg');">
        <h1 class="titulo">Servicios y Amenidades</h1>
    </section>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <section id="main-sect-serv" class="main-sect">
        <section class="Mak2" data-aos="fade-right">
            <h2 class="separar">SERVICIOS DEL HOTEL</h2>
            <br>
            <p class="tex11">Prepárate para maravillarte cuando atravieses las puertas de este hotel de lujo en Nuevo Vallarta. 
                La impresionante decoración estilo Hacienda, el ambiente increíble y las magníficas comodidades permiten a los huéspedes vivir una vida de lujo, mientras que la arquitectura clásica, sutiles acentos y abundantes jardines se combinan perfectamente con la belleza natural de los alrededores de Riviera Nayarit para brindarle a sus huéspedes unas vacaciones tanto relajantes como lujosas.
                En Hotel Makhaí Riviera Nayarit, encontrarás todo lo que esperarías de uno de los mejores resorts familiares 
                en Nuevo Vallarta y mucho más. Este hotel de lujo cuenta con todas las instalaciones que necesitarás para alcanzar las vacaciones 
                soñadas de toda una vida, incluido el relajante Tatewari Spa, un impresionante gimnasio, diversos restaurantes en Nuevo Vallarta, 
                una alberca al aire libre, jacuzzi frente a la playa y un Palmita Market que ofrece un sin fin de artículos para hacer tu despensa u 
                otro tipo de compras. 
            </p>
        </section>

        <br>

        <?php
        $res = exeQuery("SELECT * FROM servicio");

        if (mysqli_num_rows($res) > 0) {
            $filas = Array();
            while ($fila = mysqli_fetch_assoc($res)) {
                $filas[] = $fila;
            }

            $mitad1 = array_slice($filas, 0, ceil(count($filas) / 2) );
            $mitad2 = array_slice($filas, ceil(count($filas) / 2) );
        }
        ?>

        <section id="serv-cont">
            <section id="ServMak" data-aos="fade-left">
                <img src="../img/servicios/Mkserv.jpg" alt="">
            </section>

            <br>

            <section class="Mak3" data-aos="fade-right">
                <h2 class="separar">Amenidades y servicios hotel</h2>
                <br>
                <ul class="tex11">
                    <div>
                    <?php
                        foreach ($mitad1 as $servicio) {
                            echo "<li>" . $servicio['Nombre'] . "</li>";
                        }
                    ?>
                    </div>

                    <div>
                    <?php
                        foreach ($mitad2 as $servicio) {
                            echo "<li>" . $servicio['Nombre'] . "</li>";
                        }
                    ?>
                    </div>
                </ul>
            </section>
        </section>
    </section>
  </main>

  <?php footer_component(); ?>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  
</body>
<script>
    AOS.init();
  </script>
</html>