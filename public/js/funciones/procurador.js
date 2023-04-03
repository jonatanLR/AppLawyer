/* Formatting function for row details - modify as you need */
function format(d) {
    // `d` is the original data object for the row
    return (
        '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
        '<tr>' +
        '<td>Correo:</td>' +
        '<td>' +
        d.email +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td>Direccion:</td>' +
        '<td>' +
        d.direccion +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td>Telefono:</td>' +
        '<td>' +
        d.telefono +
        '</td>' +
        '</tr>' +
        '</table>'
    );
}

$(document).ready(function () {
    var table;
        
        table = $('#tableprocurador').DataTable({
            "ajax": {
                "url": "/procurador/ajax_get",
                "method": 'GET', //usamos el metodo Get
                "dataSrc": ""
            },
            columns: [
                {
                    className: 'dt-control text-center',
                    orderable: false,
                    data: null,
                    defaultContent: '<i class="fas fa-plus-circle"></i>',
                },
                { data: "id" },
                { data: "numProf" },
                { data: "nombre" },
                { data: "dni" },
                { data: "id",
               "width": "50px",
                "render":function (data) {
                    return '<a href="/procurador/edit/'+data+'" class="editar btn btn-sm btn-primary" title="Editar Procurador"><i class="fas fa-edit"></i></a>' + ' ' 
                    + '<button type="button" id="'+data+'" class="btn btn-sm btn-danger delete"><i class="fas fa-trash"></i></button>'
                }
        },
            ],
            responsive: true,
            "autoWidth": false,
            order: [[1, 'asc']],
            stateSave: true
        }); // end of datatable

        // Add event listener for opening and closing details
        $('#tableprocurador tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });

        // funcion para eliminar el juez seleccionado
        $('#tableprocurador tbody').on('click', 'td .delete', function () {
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
                    fetch('/procurador/ajax_delete/'+id);
                    // table.ajax.reload();
                    Swal.fire(
                        'Eliminado!',
                        'El procurador fue eliminado.',
                        'success'
                      );
                    table.row(fila.parents('tr')).remove().ajax.reload(null,false);
                              
                }
            })
        });// end of delete

        // funcion para actualizar el contador de cliente que existen luego de hacer click 
        // en el boton eliminar y desencadenado por medio de setInterval
        $('#tableprocurador tbody').on('click', 'td .delete',function() {
            const interval = setInterval(function() {
                $('#contrariolength').load(' #contrariolength');
                console.log('recargado: ')
            },2000);

            setTimeout(() => {
                clearInterval(interval);
            }, 5000);

        });
    
}); // end of document ready
