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
    $.get('/juez/ajax_get', function (data) {
    
        table = $('#tablejuez').DataTable({
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
                { data: "id",
               "width": "50px",
                "render":function (data) {
                    return '<a href="/juez/edit/'+data+'" class="editar btn btn-sm btn-primary" title="Editar Juez"><i class="fas fa-edit"></i></a>' + ' ' 
                    + '<button type="button" id="'+data+'" class="btn btn-sm btn-danger delete"><i class="fas fa-trash"></i></button>'
                }
        },
            ],
            responsive: true,
            "autoWidth": false,
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

        // funcion para eliminar el juez seleccionado
        $('#tablejuez tbody').on('click', 'td .delete', function () {
            var id = $(this).attr("id");
            // console.log('click en button del: ' + id);
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/juez/ajax_delete/'+id);
                    table.ajax.reload();
                    // table.ajax.reload();
                              
                }
            })
        });// end of delete
    });// end of get funcion
    
}); // end of document ready


// $('#tablejuez tbody').on('click', 'td .delete', function () {
//     console.log('click en button');
    // const Toast = Swal.mixin({
    //     toast: true,
    //     position: 'top-end',
    //     showConfirmButton: false,
    //     timer: 3000,
    //     timerProgressBar: true,
    //     onOpen: (toast) => {
    //       toast.addEventListener('mouseenter', Swal.stopTimer)
    //       toast.addEventListener('mouseleave', Swal.resumeTimer)
    //     }
    //   });

    //   Toast.fire({
    //     icon: 'success',
    //     title: 'Enviado a opinión legal de operación: '
    //   });

    //   Swal.fire(
    //     'Good job!',
    //     'You clicked the button!',
    //     'success'
    //   )
    // Swal.fire({
    //     title: 'Login Form',
    //     html: `<input type="text" id="login" class="swal2-input" placeholder="Username">
    //     <input type="password" id="password" class="swal2-input" placeholder="Password">`,
    //     confirmButtonText: 'Sign in',
    //     focusConfirm: false,
    //     preConfirm: () => {
    //       const login = Swal.getPopup().querySelector('#login').value
    //       const password = Swal.getPopup().querySelector('#password').value
    //       if (!login || !password) {
    //         Swal.showValidationMessage(`Please enter login and password`)
    //       }
    //       return { login: login, password: password }
    //     }
    //   }).then((result) => {
    //     Swal.fire(`
    //       Login: ${result.value.login}
    //       Password: ${result.value.password}
    //     `.trim())
    //   })
      
        
// });