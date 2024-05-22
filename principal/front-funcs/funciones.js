// Direccion de las paginas del proyecto

const PROYECTO = "";

const index = PROYECTO + "https://hotel-makhai-nayarit.000webhostapp.com/";
const login = PROYECTO + "/inicio/inicio-sesion.php";
const registro = PROYECTO + "/inicio/registro.php";
const perfil = PROYECTO + "/principal/usuario";
const reservacion = perfil + "/reservacion";
const edi_prof = PROYECTO + "/principal/usuario/editar_perfil.php";

/* Paginas faltantes */
const suites = PROYECTO + "/principal/hotel/habitaciones";
const suite = suites + "/suite.php";
const acts = PROYECTO + "/principal/hotel/actividades";
const servicios = PROYECTO + "/principal/hotel/servicios";
/* Paginas faltantes */

const eventos = PROYECTO + "/principal/hotel/eventos";
const evento = eventos + "/evento.php";

const gestion = PROYECTO + "/admin/gestion";
const admin = PROYECTO + "/admin";
const crea = PROYECTO + "/admin/gestion/crear-editar.php";

const panel = PROYECTO + "/admin/panel.php";
const registroAdmin = PROYECTO + "/admin/registro.php";


const cerrarSesion = PROYECTO + "/puente/funciones/cerrar_sesion.php";

function establecerReferencias(){
	// Establece las referencias de las etiquetas 'a' con su respectiva clase y pagina
	establecerReferencia('index-ref', index);
	establecerReferencia('suites-ref', suites);
	establecerReferencia('acts-ref', acts);
	establecerReferencia('serv-ref', servicios);
	establecerReferencia('event-ref', eventos);
	/*establecerReferencia('noso-ref', nosotros);*/
	establecerReferencia('ini_ses', login);
}

function establecerReferencia(clase, ref){
	// obtiene todas las etiquetas 'a' con la clase 'clase' y les asigna la referencia 'ref'
	const referencias = document.querySelectorAll("a."+clase);
	referencias.forEach(function(element) {
			element.href = ref;
			console.log(element.href);
	});
}

function capitalizeFirstLetter(str) {
	// Capitaliza la primera letra de la cadena 'str'
	if (str.length > 0) return str[0].toUpperCase() + str.slice(1);
	else return str;
}

function getElementFromGet(element, id, msg = ""){
	// Obtiene el elemento 'element' de la url y lo asigna al elemento 'id' del html
	const urlParams = new URLSearchParams(window.location.search);
	const name = urlParams.get(element);
	// asigna un mensaje al elemento 'id' del html e incluye el nombre del elemento 'element'
	document.getElementById(id).innerHTML = capitalizeFirstLetter(name) + " "+ msg;
}

function irA(page){
	// recive una de las constantes de arriba y redirecciona a la pagina
	window.location.href = page;
}

function limpiarCampos() {
	// Obtén todos los elementos de entrada en el formulario
	var elementos = document.querySelectorAll('input[type="date"], input[type="number"]');

	// Recorre los elementos y establece su valor en cadena vacía
	elementos.forEach(function (elemento) {
		console.log("limpiando");
		elemento.value = '';
	});
}

function validarYEnviar(idFi, idFf, idForm) {
	const tiempo = Date.now();// obtiene la fecha actual
	const hoy = new Date(tiempo);//le da formato
    var fechaIn = document.getElementById(idFi).value;
    var fechaFin = document.getElementById(idFf).value;

	if (fechaFin == "" || fechaIn == "") { 
		alert("Debe seleccionar una fecha de inicio y una fecha de fin.");
		return false;
	}

    // Convertir las fechas a objetos Date
    var fechaInicio = new Date(fechaIn);
    var fechaFinal = new Date(fechaFin);

	if (fechaInicio < hoy) {
		alert('La fecha de reservacion debe ser mayor al dia actual.');
		return false;
	}

    // Verificar si la fecha de inicio es menor que la fecha de fin
    if (fechaInicio < fechaFinal) {
		document.getElementById(idForm).submit(); // Enviar el formulario
		return true;
	} else {
		alert('La fecha de inicio debe ser menor que la fecha de fin.');
		return false;
	}
}

function validarSeleccion(radio) {
	// Obtener todos los elementos de radio con name='habitacion'
	var radios = document.getElementsByName(radio);
	var radioSeleccionado = false;

	// Verificar si al menos uno de los radios está seleccionado
	for (var i = 0; i < radios.length; i++) {
	  if (radios[i].checked) {
		radioSeleccionado = true;
		break;
	  }
	}

	// Mostrar un mensaje si no se ha seleccionado ningún radio
	if (!radioSeleccionado) {
	  alert("Por favor, seleccione una habitación.");
	  return false; // Evitar que el formulario se envíe
	}

	// Continuar con el envío del formulario si al menos un radio está seleccionado
	return true;
  }

document.addEventListener("DOMContentLoaded", function() {
	establecerReferencias();
  });