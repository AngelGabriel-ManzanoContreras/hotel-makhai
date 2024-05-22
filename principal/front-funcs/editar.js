document.addEventListener('DOMContentLoaded', function () {
    // Obtén el formulario y el input de la imagen
    var formulario = document.getElementById('formy');
    var imagenInput = document.getElementById('img-in');
    var imagenes = [];

    imagenInput.addEventListener('change', function () {
        // Obtiene la imagen a borrar
        var imagen = document.getElementById('img-d').value;
        // Si hay una imagen a borrar, la agrega al array
        if (imagen != '') {
            borrarImagen(imagen);
        }
    });
        
    
    function borrarImagen(id){
      // Agrega la imagen al array de imagenes a borrar
      imagenes.push(id);
      // Agrega el array al input de imagenes
      document.getElementById('imagenes').value = imagenes;
      // Borra la imagen del DOM
      document.getElementById('imagen'+id).remove();
    }
});
