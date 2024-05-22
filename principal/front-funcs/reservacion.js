function submitForm () {
    const tarjeta = document.getElementById('tarjeta').value;
    const form = document.getElementById('formHab');
    
    // Si la tarjeta tiene 16 digitos y son numeros
    if (tarjeta.length === 16 && !isNaN(tarjeta)) {
        if ( window.confirm('Estas seguro de realizar esta acción. No se manejan rembolsos') ) {
            form.submit();
            /*window.alert("¡Gracias por reservar con nosotros!");
            window.location.href = "/makhai/principal/hotel/habitaciones";*/

        } else window.location.href = "/makhai/principal/hotel/habitaciones"; // Si cancela la operacion
        
    } else window.alert("La tarjeta debe tener 16 dígitos numéricos");// Si la tarjeta no tiene 16 digitos o no son numeros   
}