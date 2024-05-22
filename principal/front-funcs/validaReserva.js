function asistencia(confirm) {
    const tarjetaIn = document.getElementById('tarjeta').value;
    const csvIn = document.getElementById('csv').value;
    const nombreT = document.getElementById('nombreT').value;
    const fecha = document.getElementById('fechaEx').value;

    const msg = function(msg) {
        alert(msg);
        return false
    }

    if (tarjetaIn === '') return msg('Debes proporcionar un numero de tarjeta.');

    if (nombreT.trim() === '') return msg('Debes proporcionar el nombre del Titular de la tarjeta.');

    if (fecha.trim() === '') return msg('Debes proporcionar la fecha de vencimiento de la tarjeta.');

    if ( (csvIn.trim() === '') ) return msg('Debes proporcionar el CSV de tu tarjeta.');

    if ( (csvIn.toString().length < 3) || (csvIn.toString().length > 4) ) return msg('El CSV debe tener de 3 a 4 dígitos numéricos.');

    // Si la tarjeta tiene 16 digitos y son numeros
    if ( (tarjetaIn.toString().length === 16) && (!isNaN(tarjetaIn)) ) {
        // Si el usuario acepta, se envia el formulario
        if ( window.confirm(confirm) ) {
            return true;
        //en caso contrario no lo hace
        } else return false

    } else window.alert("La tarjeta debe tener 16 dígitos numéricos");

}
