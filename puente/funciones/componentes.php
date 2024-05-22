<?php

/*
* Componentes
* Componentes de la página
* - header_component
* - footer_component>
*/

function footer_component () {
/*
* Componente footer
* Imprime el footer de la página
*/
echo '
<footer>
	<section class="footer-columns-sect">
		<section>
		<h3>Contactanos</h3>
		<ul>
			<li>Teléfono: 1 877 845 6030</li>
			<li><a href="https://maps.app.goo.gl/3FpJ6cDJuam4DvUC8" target="_blank">Ubicación</a></li>
		</ul>
		</section>

		<section>
		<h3>Redes sociales</h3>
		<ul>
			<li><a href="https://www.facebook.com/villalaestanciavallartanayarit/" target="_blank">Facebook</a></li>
			<li><a href="https://www.instagram.com/villalaestanciarivieranayarit/" target="_blank">Instagram</a></li>
			<li><a href="https://twitter.com/VillaEstancia/" target="_blank">Twitter</a></li>
			<li><a href="https://www.youtube.com/channel/UCCcKKW1yS-NB84izENDKqIg/videos" target="_blank">Youtube</a></li>
			<li><a href="https://www.pinterest.com.mx/villagroup/villa-la-estancia-beach-resort-spa-riviera-nayarit/" target="_blank">Pinterest</a></li>
			<li><a href="https://www.tripadvisor.com.mx/Hotel_Review-g9723540-d776424-Reviews-Villa_La_Estancia_Beach_Resort_Spa_Riviera_Nayarit-Flamingos_Nuevo_Vallarta_Pacific_Co.html" target="_blank">Tripadvisor</a></li>
		</ul>
		</section>

		<section>
		<h3>Información</h3>
		<ul>
			<li><a class="index-ref">Inicio</a></li>
			<li><a class="suites-ref">Habitaciones</a></li>
			<li><a class="acts-ref">Actividades</a></li>
			<li><a class="serv-ref">Servicios</a></li>
			<li><a class="event-ref">Eventos</a></li>
		</ul>
		</section>
	</section>

	<section class="footer-copyright">
		<h5>Makhai® 2023</h5>
	</section>

</footer>
';
//<li><a class="noso-ref">Nosotros</a></li>
}
function header_component ($ignore_btns = false) {
/*
* Componente header
* Imprime el header de la página
*/

$option = (isset($_SESSION['session']) AND isset($_SESSION['correo'])) ?
	'<button class="scnd-btn" onclick="irA(cerrarSesion)">Cerrar sesión</button>
	<button class="main-btn" onclick="irA(perfil)">Perfil</button>'

	: '<button class="main-btn" onclick="irA(login)">Inicia sesión</button>';

$admin = (isset($_SESSION['session']) AND isset($_SESSION['correo']) AND $_SESSION['tipo'] == 1) ?
	'<button class="main-btn" onclick="irA(admin)">Administrar</button>'
	: '';

$dropdwon = '
<div class="dropdown">
	<button class="trnry-btn">Explora</button>
	<div class="dropdown-content">
		<a class="suites-ref">Habitaciones</a>
		<a class="acts-ref">Actividades</a>
		<a class="serv-ref">Servicios</a>
		<a class="event-ref">Eventos</a>
	</div>
</div>
';

if ($ignore_btns) {
	$dropdwon = '';
	$option = '';
}

echo '
<header>
	<figure>
		<a class="index-ref">
			<img src="/principal/hotel/img/logo.png" alt="">
		</a>
	</figure>
	<nav>
		'.$dropdwon.$option.'
		'.$admin.'
	</nav>
</header>
';
}

function action_card_component ($info, $msg = "gestionar") {
	$elemento = ($info['elemento'] != '') ? '+\'?elemento='. $info['elemento'].'\'' : '';
	$acciones = ( isset($info['acciones']) ) ? (
		(strlen($info['acciones']) > 74) ? 
			substr($info['acciones'], 0, 74). "..." 
			: $info['acciones'] ) 
		: '';

	echo '
	<article class="admin-action">
        <h2>'.$info['titulo'].'</h2>
        <p>'.$info['desc'].'<br/>'.$acciones.'</p>

        <button class="main-btn" onclick="irA('.$info["path"].$elemento.')">'.$msg.'</button>
      </article>
	';
}

function habitacion_card_component ($id, $nombre, $img, $tipo, $capacidad, $precio) {
echo "
<section class='habitacion'>

	<figure class='img-hab'>
		<a href='habitacion.php?tipo={$tipo}&habitacion={$id}'>
			<img src='$img' alt='$nombre'>
		</a>
	</figure>

	<section class='text-cont-h'>
		<h2>$nombre</h2>
		
		<p>Capacidad para $capacidad personas.</p>
		
		<p>Tarifa Regular Por Habitación/Por Noche:</p>
		<p>$ $precio</p>
		
		<button class='main-btn' onclick=\"irA(suites+'/habitacion.php?tipo={$tipo}&habitacion={$id}')\">Ver habitacion</button>
	</section>
	
	</section>
";
}

?>