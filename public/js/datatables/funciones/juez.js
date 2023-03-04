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
    $.get('/juez/ajax_get', function (data) {
    
        var table = $('#tablejuez').DataTable({
            data: data,
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
            ],
            order: [[1, 'asc']],
        }); // end of datatable

        // Add event listener for opening and closing details
        $('#tablejuez tbody').on('click', 'td.dt-control', function () {
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
    });// end of get funcion
}); // end of document ready