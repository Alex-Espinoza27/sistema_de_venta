<?php
#print_r($_SESSION); : se muestra los datos del que inicio sesion
include "Views/Templates/header.php";?>

<!-- aqui se va insertar todas las funcionalidades  -->
<div class="breadcrumb mb-4 rounded-2 gradiente sombrita" >
    <h5 class="breadcrumb-item active text-white m-3" >Cajas</h5>
</div> 

<button class="btn btn-primary mb-3" type="button"  onclick ="frmCaja();">Nueva Caja <i class ="fas fa-plus"></i></button>

<table class="table table-light" id="tblCajas">
    <thead class="thead-dark">
        <tr>
            <th>Id</th> 
            <th>Nombre</th>
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
                <form method="POST" id="frmCaja">
                    <input type="hidden" id="id" name ="id">
                    <div class="form-floating mb-3">
                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="nombre de la caja">
                        <label for="nombre">Nombre</label>
                    </div>
                    <button class="btn btn-primary" type="button" onclick="registrarCaja(event);" id="btnAccion">Registrar</button>
                    <!-- data-dismiss="modal": esto es la accion de cerra la ventada del modal -->
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div> 
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php";?>