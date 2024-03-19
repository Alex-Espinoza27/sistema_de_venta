function frmLogin(e) {
    //Para que no se recargue la pagina
    e.preventDefault();

    const usuario = document.getElementById("usuario");
    const clave = document.getElementById("clave");

    if (clave.value == "" && usuario.value == "") {
        usuario.classList.remove("is-invalid");
        clave.classList.remove("is-invalid");

        clave.classList.add("is-invalid");
        usuario.classList.add("is-invalid");
        
        usuario.focus();
    }
    else if (usuario.value == "") {
        //agregamos una alerta cuando no inserte nada
        //primero limpiamos si esque ya se ingreso

        clave.classList.remove("is-invalid");
        usuario.classList.add("is-invalid");
        usuario.focus();
    }
    else if (clave.value == "") {
        usuario.classList.remove("is-invalid");
        clave.classList.add("is-invalid");
        clave.focus();
    }
    else {
        //Vamos hacer peticiones con ajax
        // aquimos ejecutamos el metodo VALIDAR DE USUARIOs
        const url = base_url + "Usuarios/validar";
        const frm = document.getElementById("frmLogin");
        const http = new XMLHttpRequest();
        //vamos enviar una peticion al documento de manera asincrona
        http.open("POST", url, true);
        http.send(new FormData(frm));

        //verificamos el estado
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) { 
                // console.log(this.responseText);
                const res = JSON.parse(this.responseText);
                if (res == "ok") {  
                    // window.location: redirecciona a otra pagina del lado del cliente
                    window.location = base_url + "Administracion/home";
                }
                else {
                    document.getElementById("alerta").classList.remove("d-none");
                    document.getElementById("alerta").innerHTML = res;
                    
                }
            }
        }
    }
}
