$(document).ready(function () {
    var table;

    table = $('#tabletpprocedimiento').DataTable({
        "ajax": {
            "url": "/tp-procedimiento/ajax_get",
            "method": 'GET', //usamos el metodo Get
            "dataSrc": ""
        },
        columns: [
            {
                data: "id",
                "width": "40px",
                class: 'text-center'
            },
            { data: "nombre" },
            { data: "descripcion" },
            {
                data: "id",
                "width": "70px",
                "render": function (data) {
                    return '<a href="/tp-procedimiento/edit/' + data + '" class="editar btn btn-sm btn-primary" title="Editar Tp Procedimiento"><i class="fas fa-edit"></i></a>' + ' '
                        + '<button type="button" id="' + data + '" class="btn btn-sm btn-danger delete" title="Eliminar Tp Procedimiento"><i class="fas fa-trash"></i></button>'
                }
            },
        ],
        responsive: true,
        "autoWidth": false,
        stateSave: true,
        "ordering": true,
    }); // end of datatable


    // funcion para eliminar el tipo procedimiento seleccionado
    $('#tabletpprocedimiento tbody').on('click', 'td .delete', function () {
        var id = $(this).attr("id");
        var fila = $(this);
        // console.log('click en button del: ' + id);
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
                fetch('/tp-procedimiento/ajax_delete/' + id);
                // table.ajax.reload();
                Swal.fire(
                    'Eliminado!',
                    'El Tipo Actuación fue eliminado.',
                    'success'
                );
                table.row(fila.parents('tr')).remove().ajax.reload(null, false);

            }
        })
    });// end of delete

    // funcion para actualizar el contador de la tabla tp procedimiento que existen luego de hacer click 
    // en el boton eliminar y desencadenado por medio de setInterval
    $('#tabletpprocedimiento tbody').on('click', 'td .delete', function () {
        const interval = setInterval(function () {
            $('#tpProcedlength').load(' #tpProcedlength');
            console.log('recargado: ')
        }, 2000);

        setTimeout(() => {
            clearInterval(interval);
        }, 5000);

    });

}); // end of document ready
