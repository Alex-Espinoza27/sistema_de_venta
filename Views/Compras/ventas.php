<?php
#print_r($_SESSION); : se muestra los datos del que inicio sesion
include "Views/Templates/header.php"; ?>

<!-- aqui se va insertar todas las funcionalidades  -->


<div class="card">
    <div class="card-header bg-primary text-white" style="background: linear-gradient(to right, #FF69B4, #9370DB, #0000FF); box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);">
        <h4>Nueva Venta</h4>
    </div>
    <div class="card-body">
        <form id="frmVenta">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <!-- agregamos in input para guardar el id del producto -->
                        <input type="hidden" id="id" name="id">
                        <!-- onkeyup: se activa despues de soltar una tecla en el imput -->
                        <input id="codigo" class="form-control" type="text" name="codigo" placeholder="codigo de barras" onkeyup="buscarCodigoVenta(event)">
                        <label for="codigo"><i class="fas fa-barcode mr-1"></i> Codigo de barras</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="descripcion del producto" disabled>
                        <label for="nombre">Descripcion</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <input id="cantidad" class="form-control" type="number" name="cantidad" onkeyup="calcularPrecioVenta(event)" disabled>
                        <label for="cantidad">Cant</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <input id="precio" class="form-control" type="text" name="precio" placeholder="precio venta" disabled>
                        <label for="precio">Precio</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <input id="sub_total" class="form-control" type="text" name="sub_total" placeholder="Sub total" disabled>
                        <label for="sub_total">Sub Total</label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<table class="table table-light table-bordered table-hover">
    <thead class="thead-dark">
        <th>Id</th>
        <th>Descripcion</th>
        <th>Cantidad</th>
        <th>Aplicar</th>
        <th>Descuento</th>
        <th>Precio</th>
        <th>Sub total</th>
        <th></th>
    </thead>
    <tbody id="tblDetalleVenta">
    </tbody>
</table>

<div class="row">
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="cliente">Seleccionar Cliente</label>
            <select id="cliente" class="form-control" name="cliente">
                <?php foreach ($data as $row) { ?>
                    <option value="<?php echo $row['id']; ?>"> <?php echo $row['nombre']; ?></option>
                    <?php } ?>
                </select>
        </div>
    </div>

    <!-- ml-auto: lo pone a la derecha -->
    <div class="col-md-3 ms-auto"> 
        <div class="form-floating mb-3">
            <input id="total" class="form-control" type="text" name="total" placeholder="total" disabled>
            <label for="total" class="font-weight-bold">Total a Pagar</label>
            <button class="btn btn-primary mt-2 btn-block" type="button" onclick="procesar(2)"> Generar Venta</button>
        </div>
    </div>
</div>


<?php include "Views/Templates/footer.php"; ?>