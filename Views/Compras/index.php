<?php
#print_r($_SESSION); : se muestra los datos del que inicio sesion
include "Views/Templates/header.php";?>

<!-- aqui se va insertar todas las funcionalidades  -->


<div class="card">
    <div class="card-header text-white sombrita gradiente">
        <h4>Nueva Compra</h4>
    </div>
    <div class="card-body">
        <form id="frmCompra">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <!-- agregamos in input para guardar el id del producto -->
                        <input type="hidden" id = "id" name="id">
                        <!-- onkeyup: se activa despues de soltar una tecla en el imput -->
                        <input id="codigo" class="form-control" type="text" name="codigo" placeholder="codigo de barras" onkeyup="buscarCodigo(event)" >
                        <label for="codigo"><i class="fas fa-barcode "></i>Codigo de barras</label>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-floating mb-3">
                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="descripcion del producto" disabled>
                        <label for="nombre">Descripcion</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <input id="cantidad" class="form-control" type="number" name="cantidad" onkeyup = "calcularPrecio(event)" disabled>
                        <label for="cantidad">Cant</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating mb-3">
                        <input id="precio" class="form-control" type="text" name="precio" placeholder="precio compra" disabled>
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


<table class="table table-light table-bordered table-hover mt-3">
    <thead class="thead-dark">
        <th>Id</th> 
        <th>Descripcion</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Sub total</th>
        <th></th>
    </thead>
    <tbody id="tblDetalle"> 
    </tbody>

</table>
    
<div class="row">
    <div class="col-md-4 ms-auto ">
        <div class="form-floating mb-3">
            <input id="total" class="form-control" type="text" name="total" placeholder="total" disabled>
            <label for="total" class="font-weight-bold">Total</label>
            <button class="btn btn-primary mt-2 btn-block" type="button" onclick = "procesar(1)"> Generar compra</button>
        </div>
    </div>
</div>


<?php include "Views/Templates/footer.php";?>

