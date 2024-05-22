<?php
    $config = include('config.php');

    $DB_HOST = $config['DB_HOST'];
    $DB_USER = $config['DB_USER'];
    $DB_PASSWORD = $config['DB_PASSWORD'];
    $DB_NAME = $config['DB_NAME'];
    
    $conexion = mysqli_connect( $DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
    if(!$conexion) echo "Error al conectar a la base de datos";
?>