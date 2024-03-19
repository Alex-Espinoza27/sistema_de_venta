<?php
#print_r($_SESSION); : se muestra los datos del que inicio sesion
include "Views/Templates/header.php";?>

<!-- aqui se va insertar todas las funcionalidades  -->
<div class="breadcrumb mb-4 gradiente sombrita rounded-2" >
    <h5 class="breadcrumb-item active m-3 text-white">Usuarios</h5>
</div>

<!-- onclick ="frmUsuario();":   esto es para mostrar la pantalla de creaar nuevo usuario -->
<button class="btn btn-primary mb-3" type="button"  onclick ="frmUsuario();">Nuevo <i class ="fas fa-plus"></i></button>

<table class="table table-light" id="tblUsuarios">
    <thead class="thead-dark">
        <tr>
            <th>Id</th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Caja</th> 
            <th>Estado</th> 
            <th></th> 
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<!-- registrar nuevo usuario -->

<div class="modal fade" id="my_modal" tabindex="-1" aria-labelledby="Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="frmUsuario">
                    <input type="hidden" id="id" name ="id">
                    <div class="form-floating mb-3">
                        <input id="usuario" class="form-control" type="text" name="usuario" placeholder="usuario">
                        <label for="usuario">Usuario</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="nombre del usuario">
                        <label for="nombre">Nombre</label>
                    </div>
                    <div class="row" id= "claves">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input id="clave" class="form-control" type="password" name="clave" placeholder="Contraseña">
                                <label for="clave">Contraseña</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input id="confirmar" class="form-control" type="password" name="confirmar" placeholder="confirmar la contraseña">
                                <label for="confirmar">Confirmar contraseña</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <select id="caja" class="form-control" name="caja">
                            <?php foreach ($data['cajas'] as $row) {?>
                                <!--   value : el value va almacenar el id de la caja elegida-->
                                <option value = "<?php echo $row['id']; ?>"><?php echo $row['caja']; ?></option>
                                <?php }?>
                        </select>
                        <label for="caja">Caja</label>
                    </div>
                    <button class="btn btn-primary" type="button" onclick ="registrarUser(event);" id="btnAccion">Registrar</button>
                    <!-- data-dismiss="modal": esto es la accion de cerra la ventada del modal -->
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>




<?php include "Views/Templates/footer.php";?>