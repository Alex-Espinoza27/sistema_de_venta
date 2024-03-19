<?php
#print_r($_SESSION); : se muestra los datos del que inicio sesion
include "Views/Templates/header.php";?>

<style>
    .gradiente{
        background: linear-gradient(to right, #FF69B4, #9370DB, #0000FF); 
    }
    .sombrita{
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);
    }
</style>
<!-- aqui se va insertar todas las funcionalidades  -->
<ol class="breadcrumb mb-4 rounded-3 sombrita gradiente rounded-3" > 
    <h5 class="breadcrumb-item active text-white m-3" >Clientes</h5>
</ol> 

<button class="btn btn-primary mb-3 sombrita" type="button"  onclick ="frmCliente();">Nuevo <i class ="fas fa-plus"></i></button>

<table class="table table-light" id="tblClientes">
    <thead class="thead-dark">
        <tr>
            <th>Id</th>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Telefono</th> 
            <th>Direccion</th> 
            <th>Estado</th> 
            <th></th> 
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>


<div class="modal fade" id="my_modal" tabindex="-1" aria-labelledby="Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form method="POST" id="frmCliente">
                    <input type="hidden" id="id" name ="id">
                    <div class="form-floating mb-3">
                        <input id="dni" class="form-control" type="text" name="dni" placeholder="Dni">
                        <label for="dni">Dni</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="nombre del cliente">
                        <label for="nombre">Nombre</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="telefono" class="form-control" type="text" name="telefono" placeholder="el numero de telefono">
                        <label for="telefono">Telefono</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea id="direccion" class="form-control" name="direccion" placeholder="direccion"></textarea>
                        <label for="direccion">Direccion</label>
                    </div>
                    <button class="btn btn-primary" type="button" onclick="registrarCli(event);" id="btnAccion">Registrar</button>
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div> 
        </div>
    </div>
</div>
<?php include "Views/Templates/footer.php";?>