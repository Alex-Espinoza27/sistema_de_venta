<?php include "Views/Templates/header.php"; ?>

<ol class="breadcrumb mb-4 rounded-2 sombrita gradiente">
    <li class="breadcrumb-item active text-white m-3">Informacion personal del usuario</li>
</ol>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center bg-secondary text-white">
                Informacion personal
            </div>
            <div class="card-body">
                <form method="POST" id="frmActualizarDatosUsuario">
                    <input id="id" class="form-control" type="hidden" name="id"
                        value="<?php echo $data['datos']['id']; ?>">
                    <input id="caja" class="form-control" name="caja" type="hidden" placeholder="Caja"
                        value="<?php echo $data['datos']['id_caja']; ?>">

                    <div class="form-floating mb-3">
                        <input id="usuario" class="form-control" type="text" name="usuario" placeholder="usuario"
                            value="<?php echo $data['datos']['usuario']; ?>">
                        <label for="usuario">Usuario</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="nombre" class="form-control" type="text" name="nombre"
                            placeholder="nombre del usuario" value="<?php echo $data['datos']['nombre']; ?>">
                        <label for="nombre">Nombre</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="correo" class="form-control" type="email" name="correo"
                            placeholder="correo del usuario" value="<?php echo $data['datos']['correo']; ?>">
                        <label for="correo">Correo</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" placeholder="Caja"
                            value="<?php echo $data['caja_usuario']['caja']; ?>" disabled>
                        <label for="caja">Caja</label>
                    </div>
                    <button class="btn btn-primary" type="button"
                        onclick="actualizarDatosPerfil(event);">Modificar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center bg-secondary text-white">
                Cambiar Contraseña
            </div>
            <div class="card-body">
                <form id="frmCambiarPass" onsubmit="frmCambiarPass(event);">
                    <div class="form-floating mb-3">
                        <input id="clave_actual" class="form-control" type="password" name="clave_actual" placeholder="Confirmar actual">
                        <label for="clave_actual">Contraseña Actual</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="clave_nueva" class="form-control" type="password" name="clave_nueva" placeholder="Nueva contraseña">
                        <label for="clave_nueva">Nueva Contraseña</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="confirmar_clave" class="form-control" type="password" name="confirmar_clave" placeholder="Confirmar contraseña">
                        <label for="confirmar_clave">Confirmar Contraseña</label>
                    </div>
                    <button class="btn btn-primary" type="submit">Modificar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <a class="btn btn-danger my-2" href="<?php echo base_url; ?>Administracion/home">Cancelar</a>
    </div>
</div>



<?php include "Views/Templates/footer.php"; ?>