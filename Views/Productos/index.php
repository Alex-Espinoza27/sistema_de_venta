<?php

#print_r($_SESSION); : se muestra los datos del que inicio sesion
include "Views/Templates/header.php"; ?>

<!-- aqui se va insertar todas las funcionalidades  -->
<div class="breadcrumb mb-4 rounded-2 sombrita gradiente">
    <h5 class="breadcrumb-item active text-white m-3">Productos</h5>
</div>

<!-- onclick ="frmProducto();":   esto es para mostrar la pantalla de creaar nuevo Producto -->
<button class="btn btn-primary mb-3" type="button" onclick="frmProducto();">Nuevo <i class="fas fa-plus"></i></button>

<div class="table-responsive table-sm ">
    <table class="table table-light  " id="tblProductos">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Foto</th>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- registrar nuevo Producto -->

<div class="modal fade" id="my_modal" tabindex="-1" aria-labelledby="Label" aria-hidden="true">
    <!-- modal-lg : es para tener un modal mas grande ya que vamos a seleccionar un imagen mas -->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form method="POST" id="frmProducto">
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input id="codigo" class="form-control" type="text" name="codigo"placeholder="codigo de barras">
                                <label for="codigo">Codigo de barras</label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-floating mb-2">
                                <input id="nombre" class="form-control" type="text" name="nombre"placeholder="nombre del Producto">
                                <label for="nombre">Descripcion</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input id="precio_compra" class="form-control" type="text" name="precio_compra"placeholder="precio compra">
                                <label for="precio_compra">Precio Compra</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input id="precio_venta" class="form-control" type="text" name="precio_venta"placeholder="precio venta">
                                <label for="precio_venta">Precio Venta</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select id="medida" class="form-control" name="medida">
                                    <?php foreach ($data['medidas'] as $row) { ?>
                                        <!--   value : el value va almacenar el id de la caja elegida-->
                                        <option value="<?php echo $row['id']; ?>">
                                            <?php echo $row['nombre']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <label for="medida">Medidas</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select id="categoria" class="form-control" name="categoria">
                                    <?php foreach ($data['categorias'] as $row) { ?>
                                        <!--   value : el value va almacenar el id de la caja elegida-->
                                        <option value="<?php echo $row['id']; ?>">
                                            <?php echo $row['nombre']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <label for="categoria">Categorias</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <label for="imagen" id="icon-image" class="btn btn-primary"><i class="fa fa-image"></i></label>
                                        <span id="icon-cerrar"></span>
                                        <input id="imagen" class="d-none" type="file" name="imagen"
                                        onchange="preview(event);">
                                        <!-- Estos inputs son para modificar la imagen -->
                                        <input type="hidden" id="foto_actual" name="foto_actual">
                                        <img class="img-thumbnail" id="img-preview">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary" type="button" onclick="registrarPro(event);"
                        id="btnAccion">Registrar</button>
                    <!-- data-dismiss="modal": esto es la accion de cerra la ventada del modal -->
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>



<?php include "Views/Templates/footer.php"; ?>