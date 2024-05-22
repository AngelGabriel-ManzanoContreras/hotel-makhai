document.addEventListener('DOMContentLoaded', function () {
    // Obtén una referencia a los input radio
    var radios = document.querySelectorAll('input[type="radio"]');

    // Obtén una referencia a la sección que deseas mostrar/ocultar
    var btnSectD = document.getElementById('btn-sect-D');
    btnSectD.style.display = 'none';

    // Agrega un controlador de eventos para el evento "change" en los input radio
    radios.forEach(function(radio) {
        // Verifica si se ha seleccionado un input radio
        radio.addEventListener('change', function() {    
            // Si está seleccionado, muestra la sección
            if (radio.checked) btnSectD.style.display = 'flex';
            
            // Si no está seleccionado, oculta la sección
            else btnSectD.style.display = 'none';
    });
  });
});