function eliminar() {
    const form = document.getElementById('formEE');

    if ( confirm('¿Está seguro de eliminar el registro seleccionado?') ) {
        // Crear input para eliminar usando DOM

        let eliminar = document.createElement('input');// Crear elemento
        //establezco sus atributos

        eliminar.type = 'hidden';
        eliminar.name = 'eliminar';
        eliminar.value = '1';
        
        form.appendChild(eliminar);// Agregar al formulario

        form.submit();
    }
}