
// dentro de esta pagina sacamos para AJAX: https://datatables.net/manual/ajax
// recibmos el json_encode de listar de Usuarios aqui 

//´Para mostrar en la tabla los usuario
let tblUsuarios, tblClientes, tblMedidas, tblCategorias, tblProductos, tblHistorialCompras, tblHistorialVentas, t_arqueo;
let tblFiltroHistorialCompras, tblFiltroHistorialVentas;

let myModal;
document.addEventListener("DOMContentLoaded", function () {
    // esto es para mostrar el modal con el bostrap 5
    if (document.getElementById('my_modal')) {
        myModal = new bootstrap.Modal(document.getElementById('my_modal'));
    }

    // esto es para mostrar los nombres de los clientes en ventas
    $('#cliente').select2();

    const buttons = [
        {
            //Botón para Excel
            extend: 'excelHtml5',
            footer: true,
            title: 'Archivo',
            filename: 'Export_File',
            //Aquí es donde generas el botón personalizado
            text: '<span class="badge bg-success"><i class="fas fa-file-excel"></i></span>'
        },
        //Botón para PDF
        {
            extend: 'pdfHtml5',
            download: 'open',
            footer: true,
            title: 'Reporte de usuarios',
            filename: 'Reporte de usuarios',
            text: '<span class="badge   bg-danger"><i class="fas fa-file-pdf"></i></span>',
            exportOptions: {
                columns: [0, ':visible']
            }
        },
        //Botón para copiar
        {
            extend: 'copyHtml5',
            footer: true,
            title: 'Reporte de usuarios',
            filename: 'Reporte de usuarios',
            text: '<span class="badge   bg-primary"><i class="fas fa-copy"></i></span>',
            exportOptions: {
                columns: [0, ':visible']
            }
        },
        //Botón para print
        {
            extend: 'print',
            footer: true,
            filename: 'Export_File_print',
            text: '<span class="badge  bg-dark"><i class="fas fa-print"></i></span>'
        },
        //Botón para cvs
        {
            extend: 'csvHtml5',
            footer: true,
            filename: 'Export_File_csv',
            text: '<span class="badge   bg-success"><i class="fas fa-file-csv"></i></span>'
        },
        {
            extend: 'colvis',
            text: '<span class="badge   bg-info"><i class="fas fa-columns"></i></span>',
            postfixButtons: ['colvisRestore']
        }
    ]
    const dom = "<'row mb-2'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-5'i><'col-sm-7'p>>";
    // Vista de los USUARIOS
    tblUsuarios = $('#tblUsuarios').DataTable({
        ajax: {
            url: base_url + "Usuarios/listar",
            dataSrc: ''
        },
        columns: [
            { 'data': 'id' },
            { 'data': 'usuario' },
            { 'data': 'nombre' },
            { 'data': 'caja' },
            { 'data': 'estado' },
            { 'data': 'acciones' }
        ]
    });
    // Vista de los CLIENTES
    tblClientes = $('#tblClientes').DataTable({
        ajax: {
            url: base_url + "Clientes/listar",
            dataSrc: ''
        },
        columns: [
            { 'data': 'id' },
            { 'data': 'dni' },
            { 'data': 'nombre' },
            { 'data': 'telefono' },
            { 'data': 'direccion' },
            { 'data': 'estado' },
            { 'data': 'acciones' }
        ],
        buttons,
        dom
    });
    // Vista de las MEDIDAS
    tblMedidas = $('#tblMedidas').DataTable({
        ajax: {
            url: base_url + "Medidas/listar",
            dataSrc: ''
        },
        columns: [
            { 'data': 'id' },
            { 'data': 'nombre' },
            { 'data': 'nombre_corto' },
            { 'data': 'estado' },
            { 'data': 'acciones' }
        ]
    });
    // Vista de las CAJAS
    tblCajas = $('#tblCajas').DataTable({
        ajax: {
            url: base_url + "Cajas/listar",
            dataSrc: ''
        },
        columns: [
            { 'data': 'id' },
            { 'data': 'caja' },
            { 'data': 'estado' },
            { 'data': 'acciones' }
        ]
    });
    // Vista de las CATEGORIAS
    tblCategorias = $('#tblCategorias').DataTable({
        ajax: {
            url: base_url + "Categorias/listar",
            dataSrc: ''
        },
        columns: [
            { 'data': 'id' },
            { 'data': 'nombre' },
            { 'data': 'estado' },
            { 'data': 'acciones' }
        ]
    });
    // Vista de las PRODUCTOS
    tblProductos = $('#tblProductos').DataTable({
        ajax: {
            url: base_url + "Productos/listar",
            dataSrc: ''
        },
        columns: [
            { 'data': 'id' },
            { 'data': 'imagen' }, //se enuentra en productos listar
            { 'data': 'codigo' },
            { 'data': 'descripcion' },
            { 'data': 'precio_venta' },
            { 'data': 'cantidad' },
            { 'data': 'estado' },
            { 'data': 'acciones' }
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        buttons,
        dom
    });

    //Vista del historial de las compras 
    tblHistorialCompras = $('#t_historial_c').DataTable({
        ajax: {
            url: base_url + "Compras/listar_historial",
            dataSrc: ''
        },
        columns: [
            { 'data': 'id' },
            { 'data': 'total' },
            { 'data': 'fecha' },
            { 'data': 'estado' },
            { 'data': 'acciones' }
        ],
        buttons,
        dom
    });
    // Vista del historia de ventas
    tblHistorialVentas = $('#t_historial_v').DataTable({
        ajax: {
            url: base_url + "Compras/listar_historial_ventas",
            dataSrc: ''
        },
        columns: [
            { 'data': 'id' },
            { 'data': 'nombre' }, // nombre del cliente
            { 'data': 'total' },
            { 'data': 'fecha' },
            { 'data': 'estado' },
            { 'data': 'acciones' }
        ],
        buttons,
        dom
    });
    // Vista de las ARQUEO CAJAS
    t_arqueo = $('#t_arqueo').DataTable({
        ajax: {
            url: base_url + "Cajas/listar_arqueo",
            dataSrc: ''
        },
        columns: [
            { 'data': 'id' },
            { 'data': 'monto_inicial' },
            { 'data': 'monto_final' },
            { 'data': 'fecha_apertura' },
            { 'data': 'fecha_cierre' },
            { 'data': 'total_ventas' },
            { 'data': 'monto_total' },
            { 'data': 'estado' }
        ]
    });
})


//=====================================TODO USUARIOS ==================================
function frmCambiarPass(e) {
    e.preventDefault();
    const actual = document.getElementById('clave_actual').value;
    const nueva = document.getElementById('clave_nueva').value;
    const confirmar = document.getElementById('confirmar_clave').value;
    if (actual == '' || nueva == '' || confirmar == '') {
        alertas('Todos los campos son obligatorios', 'warning');
    } else {
        if (nueva != confirmar) {
            alertas('Las contraseñas no coinciden', 'warning');
        } else {
            const url = base_url + "Usuarios/cambiarPass";
            const frm = document.getElementById("frmCambiarPass");
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("POST", url, true);
            http.send(new FormData(frm));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // Procesar la respuesta del servidor aquí 
                    const res = JSON.parse(this.responseText);
                    $("#cambiarPass").modal("hide");
                    alertas(res.msg, res.icono);
                    frm.reset();
                }
            }
        }
    }

}
// limpiamos y mostramos detalles para un nuevo usuario
function frmUsuario() {
    //mostramos el nuevo dormulario para ingresar un nuevo usuario
    document.getElementById("title").textContent = "Nuevo usuario";
    document.getElementById("btnAccion").textContent = "Registrar";

    document.getElementById("claves").classList.remove("d-none");
    //reseteamos los campos para iniciar
    document.getElementById("frmUsuario").reset();
    myModal.show();
    // lo ponemos vacio al comienzo     
    document.getElementById("id").value = "";
}
//Registrar nuevo usuario
function registrarUser(e) {
    //Para que no se recargue la pagina
    e.preventDefault();

    const usuario = document.getElementById("usuario");
    const nombre = document.getElementById("nombre");
    const caja = document.getElementById("caja");
    const correo = document.getElementById("correo");

    //primero descargamos el sweetalert2 y lo pegamos en footer
    // quitamos || clave.value == "": porque eso ya lo hacemos por backend
    if (usuario.value == "" || nombre.value == "" || caja.value == "") {
        //eligimos este de https://sweetalert2.github.io/#examples
        alertas('Todos los campos son obligatorios', 'warning');
    }
    else {
        // si todo esta ok, llamamos al metodo registrar en e controlador usuarios
        const url = base_url + "Usuarios/registrar";
        const frm = document.getElementById("frmUsuario");
        const http = new XMLHttpRequest();
        //vamos enviar una peticion al documento de manera asincrona
        http.open("POST", url, true); // el true: lo enviaremos de forma asincro  na
        http.send(new FormData(frm));
        //verificamos el estado
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Procesar la respuesta del servidor aquí 
                const res = JSON.parse(this.responseText);
                //actualizamos solo el formulario : hide: para que se oculte
                myModal.hide();
                alertas(res.msg, res.icono);
                //con esto actualizamos la tabla en cada accion
                tblUsuarios.ajax.reload();
            }
        }
    }
}
// esta funcion lo abrimos en el controlador Usuario y funcion listar
// porque ahi mostramos los botones de editar y eliminar
function btnEditarUser(id) {
    document.getElementById("title").textContent = "Actualizar datos del usuario";
    document.getElementById("btnAccion").textContent = "Modificar";
    const url = base_url + "Usuarios/editar/" + id;
    const http = new XMLHttpRequest();
    //vamos enviar una peticion al documento de manera asincrona
    http.open("GET", url, true); //  
    http.send();
    //verificamos el estado
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Procesar la respuesta del servidor aquí
            const res = JSON.parse(this.responseText);
            document.getElementById("id").value = res.id;
            document.getElementById("usuario").value = res.usuario;
            document.getElementById("nombre").value = res.nombre;
            document.getElementById("correo").value = res.correo;
            document.getElementById("caja").value = res.id_caja;
            document.getElementById("claves").classList.add("d-none");
            myModal.show(); // con esto mostramos los datos al momento de inciar el editar
        }
    }
}
function actualizarDatosPerfil(e) {
    e.preventDefault();
    const frm = document.getElementById('frmActualizarDatosUsuario');
    const url = base_url + "Usuarios/actualizarDatos";
    const http = new XMLHttpRequest();
    //vamos enviar una peticion al documento de manera asincrona
    http.open("POST", url, true); //  
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            alertas(res.msg, res.icono);
        }
    }
}
function btnEliminarUser(id) {
    Swal.fire({
        title: "¿Estas seguro de eliminar este usuario?",
        text: "El usuario no se eliminara de forma permanene, solo cambiara el estado a inactivo!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Usuarios/eliminar/" + id;
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("GET", url, true); //  
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblUsuarios.ajax.reload();
                }
            }
        }
    });
}
function btnReingresarUser(id) {
    Swal.fire({
        title: "¿Estas seguro de Reingresar este usuario",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Usuarios/reingresar/" + id;
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("GET", url, true); //  
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblUsuarios.ajax.reload();
                }
            }
        }
    });
}

//===================================== TODO CLIENTES ==================================
function frmCliente() {
    document.getElementById("title").textContent = "Nuevo Cliente";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmCliente").reset();
    document.getElementById("id").value = "";
    myModal.show();
}
function registrarCli(e) {
    //Para que no se recargue la pagina
    e.preventDefault();
    const dni = document.getElementById("dni");
    const nombre = document.getElementById("nombre");
    const telefono = document.getElementById("telefono");
    const direccion = document.getElementById("direccion");

    //primero descargamos el sweetalert2 y lo pegamos en footer
    // quitamos || clave.value == "": porque eso ya lo hacemos por backend
    if (dni.value == "" || nombre.value == "" || telefono.value == "" || direccion.value == "") {
        //eligimos este de https://sweetalert2.github.io/#examples
        alertas('Todos los campos son obligatorios', 'error');
    }
    else {
        // si todo esta ok, llamamos al metodo registrar en e controlador usuarios
        const url = base_url + "Clientes/registrar";
        const frm = document.getElementById("frmCliente");
        const http = new XMLHttpRequest();
        //vamos enviar una peticion al documento de manera asincrona
        http.open("POST", url, true); // el true: lo enviaremos de forma asincro  na
        http.send(new FormData(frm));
        //verificamos el estado
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Procesar la respuesta del servidor aquí 
                const res = JSON.parse(this.responseText);
                if (res.icono == "success") {
                    frm.reset();
                }
                myModal.hide();
                alertas(res.msg, res.icono);
                tblClientes.ajax.reload();
            }
        }
    }
}
function btnEditarCli(id) {
    document.getElementById("title").textContent = "Actualizar datos del cliente";
    document.getElementById("btnAccion").textContent = "Modificar";
    const url = base_url + "Clientes/editar/" + id;
    const http = new XMLHttpRequest();
    //vamos enviar una peticion al documento de manera asincrona
    http.open("GET", url, true); //  
    http.send();
    //verificamos el estado
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Procesar la respuesta del servidor aquí
            const res = JSON.parse(this.responseText);
            document.getElementById("id").value = res.id;
            document.getElementById("dni").value = res.dni;
            document.getElementById("nombre").value = res.nombre;
            document.getElementById("telefono").value = res.telefono;
            document.getElementById("direccion").value = res.direccion;
            myModal.show(); // con esto mostramos los datos al momento de inciar el editar
        }
    }
}
function btnEliminarCli(id) {
    Swal.fire({
        title: "¿Estas seguro de eliminar este cliente?",
        text: "El cliente no se eliminara de forma permanene, solo cambiara el estado a inactivo!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Clientes/eliminar/" + id;
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("GET", url, true); //  
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblClientes.ajax.reload();
                }
            }
        }
    });

}
function btnReingresarCli(id) {
    Swal.fire({
        title: "¿Estas seguro de Reingresar este cliente",
        // text: " !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Clientes/reingresar/" + id;
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("GET", url, true); //  
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblClientes.ajax.reload();
                }
            }

        }
    });

}

//===================================== TODO CAJAS ==================================
function frmCaja() {
    document.getElementById("title").textContent = "Nueva Categoria";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmCaja").reset();
    document.getElementById("id").value = "";
    myModal.show();
}
function registrarCaja(e) {
    //Para que no se recargue la pagina
    e.preventDefault();
    const nombre = document.getElementById("nombre");

    if (nombre.value == "") {
        //eligimos este de https://sweetalert2.github.io/#examples
        alertas('Todos los campos son obligatorios', 'error');
    }
    else {
        // si todo esta ok, llamamos al metodo registrar en el controlador cajas
        const url = base_url + "Cajas/registrar";
        const frm = document.getElementById("frmCaja");
        const http = new XMLHttpRequest();
        //vamos enviar una peticion al documento de manera asincrona
        http.open("POST", url, true); // el true: lo enviaremos de forma asincro na
        http.send(new FormData(frm));
        //verificamos el estado
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Procesar la respuesta del servidor aquí 
                const res = JSON.parse(this.responseText);
                myModal.hide();
                alertas(res.msg, res.icono);
                tblCajas.ajax.reload();
                if (res.icono == "success") {
                    frm.reset();
                }
            }
        }
    }
}
function btnEditarCaja(id) {
    document.getElementById("title").textContent = "Actualizar datos de la caja";
    document.getElementById("btnAccion").textContent = "Modificar";
    const url = base_url + "Cajas/editar/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    //verificamos el estado
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Procesar la respuesta del servidor aquí
            const res = JSON.parse(this.responseText);
            document.getElementById("id").value = res.id;
            document.getElementById("nombre").value = res.caja;
            myModal.show(); // con esto mostramos los datos al momento de inciar el editar
        }
    }
}
function btnEliminarCaja(id) {
    Swal.fire({
        title: "¿Estas seguro de eliminar esta caja?",
        text: "La caja se eliminara de forma permanene, solo cambiara el estado a inactivo!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Cajas/eliminar/" + id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblCajas.ajax.reload();
                }
            }
        }
    });

}
function btnReingresarCaja(id) {
    Swal.fire({
        title: "¿Estas seguro de Reingresar esta caja",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Cajas/reingresar/" + id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblCajas.ajax.reload();
                }
            }

        }
    });
}

//===================================== TODO MEDIDAS ==================================
function frmMedida() {
    document.getElementById("title").textContent = "Nueva Medida";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmMedida").reset();
    document.getElementById("id").value = "";
    myModal.show();
}
function registrarMed(e) {
    //Para que no se recargue la pagina
    e.preventDefault();

    const nombre = document.getElementById("nombre");
    const nombre_corto = document.getElementById("nombre_corto");

    if (nombre.value == "" || nombre_corto.value == "") {
        //eligimos este de https://sweetalert2.github.io/#examples
        alertas('Todos los campos son obligatorios', 'error');
    }
    else {
        // si todo esta ok, llamamos al metodo registrar en e controlador usuarios
        const url = base_url + "Medidas/registrar";
        const frm = document.getElementById("frmMedida");
        const http = new XMLHttpRequest();
        //vamos enviar una peticion al documento de manera asincrona
        http.open("POST", url, true); // el true: lo enviaremos de forma asincro  na
        http.send(new FormData(frm));
        //verificamos el estado
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Procesar la respuesta del servidor aquí 
                const res = JSON.parse(this.responseText);

                myModal.hide();
                alertas(res.msg, res.icono);
                tblMedidas.ajax.reload();
                if (res.icono == "success") {
                    frm.reset();
                }
            }
        }
    }
}
function btnEditarMed(id) {
    document.getElementById("title").textContent = "Actualizar datos de la medida";
    document.getElementById("btnAccion").textContent = "Modificar";
    const url = base_url + "Medidas/editar/" + id;
    const http = new XMLHttpRequest();
    //vamos enviar una peticion al documento de manera asincrona
    http.open("GET", url, true); //  
    http.send();
    //verificamos el estado
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Procesar la respuesta del servidor aquí
            const res = JSON.parse(this.responseText);
            document.getElementById("id").value = res.id;
            document.getElementById("nombre").value = res.nombre;
            document.getElementById("nombre_corto").value = res.nombre_corto;
            myModal.show();; // con esto mostramos los datos al momento de inciar el editar
        }
    }
}
function btnEliminarMed(id) {
    Swal.fire({
        title: "¿Estas seguro de eliminar esta medida?",
        text: "La medida se eliminara de forma permanene, solo cambiara el estado a inactivo!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Medidas/eliminar/" + id;
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("GET", url, true); //  
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblMedidas.ajax.reload();
                }
            }
        }
    });

}
function btnReingresarMed(id) {
    Swal.fire({
        title: "¿Estas seguro de Reingresar esta medida",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Medidas/reingresar/" + id;
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("GET", url, true); //  
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblMedidas.ajax.reload();
                }
            }
        }
    });
}

//===================================== TODO CATEGORIAS ==================================}
function frmCategoria() {
    document.getElementById("title").textContent = "Nueva Categoria";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmCategoria").reset();
    document.getElementById("id").value = "";
    myModal.show();
}
function registrarCat(e) {
    //Para que no se recargue la pagina
    e.preventDefault();

    const nombre = document.getElementById("nombre");

    if (nombre.value == "") {
        //eligimos este de https://sweetalert2.github.io/#examples
        alertas('Todos los campos son obligatorios', 'error');
    }
    else {
        // si todo esta ok, llamamos al metodo registrar en e controlador usuarios
        const url = base_url + "Categorias/registrar";
        const frm = document.getElementById("frmCategoria");
        const http = new XMLHttpRequest();
        //vamos enviar una peticion al documento de manera asincrona
        http.open("POST", url, true); // el true: lo enviaremos de forma asincro  na
        http.send(new FormData(frm));
        //verificamos el estado
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Procesar la respuesta del servidor aquí 
                const res = JSON.parse(this.responseText);
                myModal.hide();
                alertas(res.msg, res.icono);
                tblCategorias.ajax.reload();
                if (res.icono == "success") {
                    frm.reset();
                }
            }
        }
    }
}
function btnEditarCat(id) {
    document.getElementById("title").textContent = "Actualizar datos de la categoria";
    document.getElementById("btnAccion").textContent = "Modificar";
    const url = base_url + "Categorias/editar/" + id;
    const http = new XMLHttpRequest();
    //vamos enviar una peticion al documento de manera asincrona
    http.open("GET", url, true); //  
    http.send();
    //verificamos el estado
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Procesar la respuesta del servidor aquí
            const res = JSON.parse(this.responseText);
            document.getElementById("id").value = res.id;
            document.getElementById("nombre").value = res.nombre;
            myModal.show();  // con esto mostramos los datos al momento de inciar el editar
        }
    }
}
function btnEliminarCat(id) {
    Swal.fire({
        title: "¿Estas seguro de eliminar esta categoria?",
        text: "La categoria se eliminara de forma permanene, solo cambiara el estado a inactivo!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Categorias/eliminar/" + id;
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("GET", url, true); //  
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblCategorias.ajax.reload();
                }
            }
        }
    });

}
function btnReingresarCat(id) {
    Swal.fire({
        title: "¿Estas seguro de Reingresar esta categoria",
        // text: " !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Categorias/reingresar/" + id;
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("GET", url, true); //  
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblCategorias.ajax.reload();
                }
            }
        }
    });
}

//===================================== TODO PRODUCTOS ==================================
function frmProducto() {
    document.getElementById("title").textContent = "Nuevo Producto";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmProducto").reset();
    myModal.show();
    document.getElementById("id").value = "";
    deleteImg(); // esto es para que cuando elimine la imagen selecc, se limpie todo
}
function registrarPro(e) {
    //Para que no se recargue la pagina
    e.preventDefault();
    const codigo = document.getElementById("codigo");
    const nombre = document.getElementById("nombre"); // esto es la descripcion
    const precio_compra = document.getElementById("precio_compra");
    const precio_venta = document.getElementById("precio_venta");
    const id_medida = document.getElementById("medida");
    const id_cat = document.getElementById("categoria");

    if (codigo.value == "" || nombre.value == "" || precio_compra.value == "" ||
        precio_venta.value == "") {
        alertas('Todos los campos son obligatorios', 'error');
    }
    else {
        const url = base_url + "Productos/registrar";
        const frm = document.getElementById("frmProducto");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText);
                const res = JSON.parse(this.responseText);
                myModal.hide();
                alertas(res.msg, res.icono);
                tblProductos.ajax.reload();
                if (res.icono == "success") {
                    frm.reset();
                }
            }

        }
    }
}
// esta funcion lo abrimos en el controlador Usuario y funcion listar
// porque ahi mostramos los botones de editar y eliminar
function btnEditarPro(id) {
    document.getElementById("title").textContent = "Actualizar datos del productos";
    document.getElementById("btnAccion").textContent = "Modificar";
    const url = base_url + "Productos/editar/" + id;
    const http = new XMLHttpRequest();
    //vamos enviar una peticion al documento de manera asincrona
    http.open("GET", url, true); //  
    http.send();
    //verificamos el estado
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Procesar la respuesta del servidor aquí
            const res = JSON.parse(this.responseText);
            console.log(this.readyState);
            document.getElementById("id").value = res.id;
            document.getElementById("codigo").value = res.codigo;
            document.getElementById("nombre").value = res.descripcion;
            document.getElementById("precio_compra").value = res.precio_compra;
            document.getElementById("precio_venta").value = res.precio_venta;
            document.getElementById("medida").value = res.id_medida;
            document.getElementById("categoria").value = res.id_categoria;
            document.getElementById("img-preview").src = base_url + 'Assets/img/' + res.foto;
            // esto es para mostrar solo el incono de cerrar imagen y no de agregar al principio
            document.getElementById("icon-cerrar").innerHTML = `
            <button class="btn btn-danger" onclick="deleteImg();">
            <i class="fas fa-times" ></i></button>`;
            document.getElementById("icon-image").classList.add("d-none");
            // guardamos el nombre de la foto en los inputs ocultos
            document.getElementById("foto_actual").value = res.foto;

            myModal.show(); // con esto mostramos los datos al momento de inciar el editar
        }
    }
}
function btnEliminarPro(id) {
    Swal.fire({
        title: "¿Estas seguro de eliminar este producto?",
        text: "El usuario no se eliminara de forma permanene, solo cambiara el estado a inactivo!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Productos/eliminar/" + id;
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("GET", url, true); //  
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblProductos.ajax.reload();
                }
            }
        }
    });

}

function btnReingresarPro(id) {
    Swal.fire({
        title: "¿Estas seguro de Reingresar este producto",
        // text: " !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Productos/reingresar/" + id;
            const http = new XMLHttpRequest();
            //vamos enviar una peticion al documento de manera asincrona
            http.open("GET", url, true); //  
            http.send();
            //verificamos el estado
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblProductos.ajax.reload();
                }
            }
        }
    });
}
function preview(e) {
    // esto es para la previsualizacion de la imagen seleccionada
    const url = e.target.files[0]; // el 0 es porque en la consola aparece como 0
    const urlTmp = URL.createObjectURL(url);
    document.getElementById("img-preview").src = urlTmp;
    // ocultamos el icono de imagen
    document.getElementById("icon-image").classList.add("d-none");
    document.getElementById("icon-cerrar").innerHTML = `
    <button class="btn btn-danger" onclick="deleteImg();"><i class="fas fa-times" ></i></button>
    ${url['name']}`;
}
function deleteImg() {
    // esto es para hacer la accion de corregir la imagen eligida
    document.getElementById("icon-cerrar").innerHTML = '';
    document.getElementById("icon-image").classList.remove("d-none");
    document.getElementById("img-preview").src = '';
    document.getElementById("imagen").value = '';
    // esto es para borrar en el valor del nombre de la foto que se va editar, entonces se quita el nombre guardado anterioremnte erriba en el frmPro
    document.getElementById("foto_actual").value = '';
}

//===================================== TODO DE COMPRAS ==================================
function buscarCodigo(e) {
    // previene la recarga de la pagina
    e.preventDefault();
    const cod = document.getElementById("codigo").value;
    if (cod != '') {
        if (e.key == "Enter") {
            const url = base_url + "Compras/buscarCodigo/" + cod;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res) {
                        document.getElementById("nombre").value = res.descripcion;
                        document.getElementById("precio").value = res.precio_compra;
                        document.getElementById("id").value = res.id;
                        document.getElementById("cantidad").removeAttribute('disabled');
                        document.getElementById("cantidad").focus();
                    }
                    else {
                        alertas('El producto no exite', 'warning');
                        document.getElementById("codigo").value = '';
                        document.getElementById("codigo").focus();
                        document.getElementById("nombre").value = '';
                        document.getElementById("precio").value = '';
                        document.getElementById("id").value = '';
                        document.getElementById("cantidad").value = '';
                        document.getElementById("sub_total").value = 0;
                    }
                }
            }
        }
    }
    else {
        alertas('Ingrese el código', 'warning');
    }
}
function calcularPrecio(e) {
    // previene la recarga de la pagina
    e.preventDefault();
    const cant = document.getElementById("cantidad").value;
    const precio = document.getElementById("precio").value;
    document.getElementById("sub_total").value = precio * cant;

    if (e.key == "Enter") {
        if (cant > 0) {
            const url = base_url + "Compras/ingresar";
            const frm = document.getElementById("frmCompra");
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send(new FormData(frm));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    frm.reset();
                    cargarDetalle();
                    document.getElementById("cantidad").setAttribute('disabled', 'disabled');
                    document.getElementById("codigo").focus();
                }
            }
        }
    }
}
// esto se ejecuta desde el principio, por eso solo hacemos que aparesca cuando ingresa a la parte de Compras
if (document.getElementById('tblDetalle')) {
    cargarDetalle();
}
function cargarDetalle() {
    const url = base_url + "Compras/listar/detalle";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // console.log(this.responseText);
            const res = JSON.parse(this.responseText);
            let html = '';
            res.detalle.forEach(row => {
                html = html + `<tr>
                    <td>${row['id']}</td>
                    <td>${row['descripcion']}</td>
                    <td>${row['cantidad']}</td>
                    <td>${row['precio']}</td>
                    <td>${row['sub_total']}</td>
                    <td>
                        <button class ="btn btn-danger" type ="button" onclick ="deleteDetalle(${row['id']}),1">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>`;
            });
            document.getElementById("tblDetalle").innerHTML = html;
            document.getElementById("total").value = res.total_pagar.total;
        }
    }
}
// esta funcion lo llamamos para COMPRAS Y VENTAS
function deleteDetalle(id, accion) {
    let url;
    if (accion == 1) { // 1: accion para compras , 2: accion para ventas
        url = base_url + "Compras/delete/" + id;
    }
    else {
        url = base_url + "Compras/deleteVenta/" + id;
    }
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            alertas(res.msg, res.icono);
            if (accion == 1) {
                cargarDetalle();
            }
            else {
                cargarDetalleVenta();
            }
        }
    }
}
// la accion es para generar en compras o en ventas
function procesar(accion) {
    Swal.fire({
        title: "¿Estas seguro de realizar la accion",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            let url = '';
            if (accion == 1) {
                url = base_url + "Compras/registrarCompra";
            }
            else {
                const id_cliente = document.getElementById('cliente').value;
                url = base_url + "Compras/registrarVenta/" + id_cliente;
            }
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res.msg == "ok") {
                        alertas('Compra realizada con exito', 'success');
                        let ruta;
                        if (accion == 1) {
                            // el navegador abrirar otra nueva pestaña
                            ruta = base_url + 'Compras/generarPdf/' + res.id_compra;
                        }
                        else {
                            ruta = base_url + 'Compras/generarPdfVenta/' + res.id_venta;
                        }
                        window.open(ruta);
                        //le decimos que despues de 3 segundos se va recargar la pagina
                        setTimeout(() => {
                            window.location.reload();
                        }, 300);
                    }
                    else {
                        alertas(res.msg, res.icono);
                    }
                }
            }
        }
    });
}
function btnAnularC(id) {
    Swal.fire({
        title: "¿Estas seguro de anular la compra?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Compras/anularCompra/" + id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblHistorialCompras.ajax.reload();
                }
            }
        }
    });
}

// FILTRAR POR FECHA : NO FUNCIONA, YA DEVUELVE LOS DATOS FILTRADOS SOLO QUE, no al momento de actualizar en el table, no podemos ya que ya es definido
function generarPDFComprasFiltro(e, opcion) {
    e.preventDefault();
    const fechaI = document.getElementById('desde').value;
    const fechaF = document.getElementById('hasta').value;
    
    // let url = '';
    if (fechaI == '' || fechaF == '') {
        alertas('Primero selecione las fechas', 'error');
    }
    else {
        // if (opcion == 1) {
        //     cont = 1;
        //     url = base_url + "Compras/listar_historial/" + fechaI + "/"+ fechaF;
        // }
        // else{
        //     cont = fechaI + "/"+ fechaF;
        //     url = base_url + "Compras/listar_historial/" + 1;
        // }
        const url = base_url + "Compras/listar_historial/";
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send();
        http.onreadystatechange = function () {
            console.log(this.responseText);
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                tblHistorialCompras.ajax.reload();
            }
        }
    }
}
//===================================== TODO EMPRESA ==================================

function modificarEmpresa() {
    const frm = document.getElementById('frmEmpresa');
    const url = base_url + "Administracion/modificar";
    const http = new XMLHttpRequest();
    //vamos enviar una peticion al documento de manera asincrona
    http.open("POST", url, true); //  
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            alertas(res.msg, res.icono);
        }
    }
}
//===================================== TODO DE VENTAS ==================================
function buscarCodigoVenta(e) {
    // previene la recarga de la pagina
    e.preventDefault();
    const cod = document.getElementById("codigo").value;
    if (cod != '') {
        if (e.key == "Enter") {
            const url = base_url + "Compras/buscarCodigo/" + cod;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res) {
                        document.getElementById("nombre").value = res.descripcion;
                        document.getElementById("precio").value = res.precio_venta;
                        document.getElementById("id").value = res.id;
                        document.getElementById("cantidad").removeAttribute('disabled');
                        document.getElementById("cantidad").focus();
                    }
                    else {
                        alertas('El producto no exite', 'warning');
                        document.getElementById("codigo").value = '';
                        document.getElementById("codigo").focus();
                        document.getElementById("nombre").value = '';
                        document.getElementById("precio").value = '';
                        document.getElementById("id").value = '';
                        document.getElementById("cantidad").value = '';
                        document.getElementById("sub_total").value = 0;
                    }
                }
            }
        }
    }
    else {
        alertas('Ingrese el código', 'warning');
    }
}
function calcularPrecioVenta(e) {
    // previene la recarga de la pagina
    e.preventDefault();
    const cant = document.getElementById("cantidad").value;
    const precio = document.getElementById("precio").value;
    document.getElementById("sub_total").value = precio * cant;

    if (e.key == "Enter") {
        if (cant > 0) {
            const url = base_url + "Compras/ingresarVenta";
            const frm = document.getElementById("frmVenta");
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send(new FormData(frm));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    // console.log(this.responseText);
                    alertas(res.msg, res.icono);
                    frm.reset();
                    cargarDetalleVenta();
                    document.getElementById("cantidad").setAttribute('disabled', 'disabled');
                    document.getElementById("codigo").focus();
                }
            }
        }
    }
}
if (document.getElementById('tblDetalleVenta')) {
    cargarDetalleVenta();
}
function cargarDetalleVenta() {
    const url = base_url + "Compras/listar/detalle_temp"; // esto para la tabla de destalle_temp
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // console.log(this.responseText);
            const res = JSON.parse(this.responseText);
            let html = '';
            res.detalle.forEach(row => {
                html = html + `<tr>
                    <td>${row['id']}</td>
                    <td>${row['descripcion']}</td>
                    <td>${row['cantidad']}</td>
                    <td><input class = "form-control" placeholder ="Desc" type="text" onkeyup="calcularDescuento(event,${row['id']})"></td>
                    <td>${row['descuento']}</td>
                    <td>${row['precio']}</td>
                    <td>${row['sub_total']}</td>
                    <td>
                        <button class ="btn btn-danger" type ="button" onclick ="deleteDetalle(${row['id']}),2">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>`;
            });
            document.getElementById("tblDetalleVenta").innerHTML = html;
            document.getElementById("total").value = res.total_pagar.total;
        }
    }
}
function calcularDescuento(e, id) {
    e.preventDefault();
    if (e.target.value == '') {
        alertas('Ingrese el descuento', 'warning');
    }
    else {
        if (e.key == "Enter") {
            const url = base_url + "Compras/calcularDescuento/" + id + "/" + e.target.value;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // console.log(this.responseText);
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    cargarDetalleVenta()
                }
            }
        }
    }
}
function btnAnularV(id) {
    Swal.fire({
        title: "¿Estas seguro de anular la compra?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI!",
        cancelButtonText: "NO"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Compras/anularVenta/" + id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblHistorialVentas.ajax.reload();
                }
            }
        }
    });
}

//===================================== ALERTA ==================================
function alertas(mensaje, icono) {
    Swal.fire({
        position: "top-end",
        icon: icono,
        title: mensaje,
        showConfirmButton: false,
        timer: 3000
    });
}

//==================== TODO REPORTES EN LE VENTANA DE INCIO ==================================
if (document.getElementById('stockMinimo')) {
    reporteStock()
}
if (document.getElementById('productosMasVendidos')) {
    reporteProductosVendidos()
}
function reporteStock() {
    const url = base_url + "Administracion/reporteStock";
    const http = new XMLHttpRequest();
    http.open("GET", url, true); //
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let nombre = [];
            let cantidad = [];
            const res = JSON.parse(this.responseText);
            for (let i = 0; i < res.length; i++) {
                nombre.push(res[i]['descripcion']);
                cantidad.push(res[i]['cantidad']);
            }
            var ctx = document.getElementById("stockMinimo");
            var myPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: nombre,
                    datasets: [{
                        data: cantidad,
                        backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745', '#ffdd55', '#005500'],
                    }],
                },
            });
        }
    }
}

function reporteProductosVendidos() {
    const url = base_url + "Administracion/productosVendidos";
    const http = new XMLHttpRequest();
    http.open("GET", url, true); //
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let nombre = [];
            let cantidad = [];
            const res = JSON.parse(this.responseText);
            for (let i = 0; i < res.length; i++) {
                nombre.push(res[i]['descripcion']);
                cantidad.push(res[i]['total']);
            }
            backgroundColor1 = ['#007bff', '#dc3545', '#ffc107', '#28a745', '#ffdd55', '#005500','#00ff55','#ddff20'];
            backgroundColor2 = ['#FBAC43', '#589D2', '#5CBD76', '#EB5579', '#04A36E', '#ADE03D'];
            var ctx = document.getElementById("productosMasVendidos");
            var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: nombre,
                    datasets: [{
                        data: cantidad,
                        backgroundColor: backgroundColor1,
                    }],
                },
            });
        }
    }
}

//===================================== TODO ARQUEO DE CAJAS ==================================
if (document.getElementById('my_modal')) {
    verificarEstadoCaja()
}
function arqueoCaja() {
    // abrimos el modal cuando presione el boton
    document.getElementById('ocultar_campos').classList.add('d-none');
    document.getElementById('monto_inicial').value = '';
    document.getElementById('btnAccion').textContent = 'Abrir Caja';
    document.getElementById('id').value = '';
    myModal.show();
}
function verificarEstadoCaja() {
    const url = base_url + "Cajas/verificarEstadoCaja";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // console.log(this.responseText);
            const res = JSON.parse(this.responseText);
            if (res == "ok") {
                document.getElementById('abrir_caja_button').classList.add('d-none');
                document.getElementById('cerrar_caja_button').classList.remove('d-none');
            }
            else {
                document.getElementById('abrir_caja_button').classList.remove('d-none');
                document.getElementById('cerrar_caja_button').classList.add('d-none');
            }
        }
    }
}
function abrirArqueo(e) {
    e.preventDefault();
    const monto_incial = document.getElementById('monto_inicial').value;
    if (monto_incial == '') {
        alertas('Ingrese el monto inicial', 'warning')
    }
    else {
        const frm = document.getElementById('frmAbrirCaja');
        const url = base_url + "Cajas/abrirArqueo";
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText);
                const res = JSON.parse(this.responseText);
                alertas(res.msg, res.icono);
                t_arqueo.ajax.reload();
                myModal.hide();
                verificarEstadoCaja();
            }
        }
    }
}
function cerrarCaja() {
    const url = base_url + "Cajas/getVentas";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // console.log(this.responseText);
            const res = JSON.parse(this.responseText);
            document.getElementById('monto_final').value = res.monto_total.total;
            document.getElementById('total_ventas').value = res.total_ventas.total;
            document.getElementById('monto_inicial').value = res.inicial.monto_inicial;
            document.getElementById('monto_general').value = res.monto_general;
            document.getElementById('id').value = res.inicial.id; // clase oculta solo para guardar el id
            document.getElementById('ocultar_campos').classList.remove('d-none');
            document.getElementById('btnAccion').textContent = 'Cerra Caja';
            t_arqueo.ajax.reload();
            myModal.show();
            verificarEstadoCaja();
        }
    }
}

//===================================== TODO PERMISOS DE USUARIOS O CLIENTES ==================================

function registrarPermisos(e) {
    e.preventDefault();
    const url = base_url + "Usuarios/registrarPermisos";
    const frm = document.getElementById('formulario');
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // console.log(this.responseText);
            const res = JSON.parse(this.responseText);
            if (res != '') {
                alertas(res.msg, res.icono);
            } else {
                alertas('Error no identificado', 'error');
            }
        }
    }
}






