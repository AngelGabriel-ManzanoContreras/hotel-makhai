<?php
    require_once('global_funcs.php');

    session_start();
    session_unset();
    session_destroy();

    cambioPagina("/index.php", "Cerraste sesión");
?>