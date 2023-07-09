$(document).ready(function () {
    // Validar el formulario de crear expediente que no tenga campos vacios y que al menos un documento requerido sea seleccionado
    // const validarForm = document.querySelector('#btn_submit').addEventListener('click', function (e) {
    //     const act_nombre = document.querySelector('#act_nombre').value
    //     const act_fecha = document.querySelector('#act_fecha').value
    //     const act_descripcion = document.querySelector('#act_descripcion').value
    //     const act_tipo = document.querySelector('#act_tpAct').value
    //     const act_direccion = document.querySelector('#act_direccion').value

    //     if (act_nombre === '' || act_fecha === '' || act_descripcion === '' || act_tipo === '' || act_direccion === '') {

    //         // Alerte de sweetalert
    //         const Toast = Swal.mixin({
    //             toast: true,
    //             position: 'bottom-start',
    //             showConfirmButton: false,
    //             timer: 3000,
    //             timerProgressBar: true,
    //             onOpen: (toast) => {
    //                 toast.addEventListener('mouseenter', Swal.stopTimer)
    //                 toast.addEventListener('mouseleave', Swal.resumeTimer)
    //             }
    //         });

    //         Toast.fire({
    //             icon: 'warning',
    //             title: 'Falta llenar un campo'
    //         });


    //     } else {
    //         document.getElementById('btnPanelGuardar').style.display = 'block'
    //         document.getElementById('btnValidardiv').style.display = 'none'
    //         var nodes = document.getElementById("divNuevosDocs").getElementsByTagName('*');
    //         for (var i = 0; i < nodes.length; i++) {
    //             nodes[i].disabled = true;
    //         }
    //         document.getElementById('divDocsSeleccionados').style.display = 'none'
    //     }
    // });
    var table = $('#tbl_actuacion').DataTable({
        responsive: true,
        "autoWidth": false,
    });
    $('#btn_submit').click(function (event) {

        const id_exped = $("#id_Exped").val();
        const act_nombre = $('#act_nombre').val();
        const act_fecha = $('#act_fecha').val();
        const act_descripcion = $('#act_descripcion').val();
        const act_tipo = $('#act_tpAct').val();
        const act_direccion = $('#act_direccion').val();

        // if para velidar si los campos no estan vacion y mostrar una Toast con un mensaje
        if (act_nombre === '' || act_fecha === '' || act_descripcion === '' || act_tipo === '') {
            // Alerte de sweetalert
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-start',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'warning',
                title: 'Falta llenar un campo'
            });
        } else {
            // Si los campos no estan vacios se hace un request para crear la actuacion
            fetch('/actuacion/create', {
                method: 'POST',
                body: JSON.stringify({"id_exped": id_exped, "nombre": act_nombre, "fecha" : act_fecha, "descripcion" : act_descripcion, "tipoAct" : act_tipo, "direccion" : act_direccion }), // data can be `string` or {object}!
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.text()) // Read response as text
                .then(data => {
                    if(data.includes("Success"))
                    {
                                                
                        location.reload();
                        $('#crearActuacion').modal('hide');
                    }
                    
                });
            // console.log("fuera de fetch");
        }
        event.preventDefault();
    });


    // $("#frmCrearActuacion").submit(function (event) {
    //     const act_nombre = $('#act_nombre').val();
    //     const act_fecha = $('#act_fecha').val();
    //     const act_descripcion = $('#act_descripcion').val();
    //     const act_tipo = $('#act_tpAct').val();
    //     const act_direccion = $('#act_direccion').val();
    //     console.log("hola");
    //     // const data = JSON.parse('{"nombre" : ' + act_nombre + ', "fecha" : ' + act_fecha + ', "descripcion" : '
    //     //     + act_descripcion + ', "tipoAct" : ' + act_tipo + ', "direccion" : ' + act_direccion + '}');

    //     // $.ajax({
    //     //     type: "POST",
    //     //     url: "/actuacion/create",
    //     //     dataType: 'json',
    //     //     data: {"nombre": "JOnatan"},
    //     //     success: function (response) {
    //     //         console.log(response);
    //     //     }
    //     // });
    //     // fetch('/actuacion/create', {
    //     //     method: 'POST',
    //     //     body: JSON.stringify({"nombre":"joe walk"}), // data can be `string` or {object}!
    //     //     headers: {
    //     //         'Content-Type': 'application/json'
    //     //     }
    //     // })
    //     // .then(res => res.json)
    //     // .then(console.log(res));
    //     event.preventDefault();
    // });

});