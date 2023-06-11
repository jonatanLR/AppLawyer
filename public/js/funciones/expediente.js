$(document).ready(function () {
    var table;

    table = $('#table_expediente').DataTable({
        responsive: true,
        "autoWidth": false,
    });

    // funcion para eliminar expediente seleccionado
    $('#table_expediente tbody').on('click', 'td .delete', function () {
        var id = $(this).attr("id");
        var fila = $(this);
        console.log('click en button del: ' + id);
        Swal.fire({
            title: 'Seguro de eliminar?',
            text: "No seras capaz de revertir esta accion!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminarlo!'
        }).then((result) => {
            if (result.isConfirmed) {
                // fetch('/expediente/ajax_delete/'+id);
                fetch('/expediente/ajax_delete/' + id)
                location.reload();
                Swal.fire(
                    'Eliminado!',
                    'El expediente fue eliminado.',
                    'success'
                );
                // table.row(fila.parents('tr')).remove().ajax.reload(null,false);

            }
        })
    });// end of delete

    // funcion para actualizar el contador de cliente que existen luego de hacer click 
    // en el boton eliminar y desencadenado por medio de setInterval
    $('#table_expediente tbody').on('click', 'td .delete', function () {
        const interval = setInterval(function () {
            $('#expedientelength').load(' #expedientelength');
            console.log('recargado: ')
        }, 2000);

        setTimeout(() => {
            clearInterval(interval);
        }, 5000);

    });

    // funcion para ver info expediente seleccionado
    $('#table_expediente tbody').on('click', 'td .ver', function () {
        var id = $(this).attr("id");
        var datos = table.row( this ).data()[1];
        // var datos = table.row($(this).parents('tr')).data();
        var fila = $(this);
        console.log(datos);
        
    });// end of delete
});