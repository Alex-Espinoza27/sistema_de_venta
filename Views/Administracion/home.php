<?php include "Views/Templates/header.php"; ?>
<!-- Aqui mostraremos  -->

<style>
    .gradiente {
        background: linear-gradient(to right, #FF69B4, #9370DB, #0000FF);
    }

    .sombrita {
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);
    }
</style>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <i class="fas fa-user fa-2x"></i>
                Usuarios
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url;?>Usuarios">Ver detalle</a>
                <div class="small text-white">
                    <span class="text-white">
                        <?php echo $data['usuarios']['total']; ?>
                    </span>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                <i class="fas fa-users fa-2x"></i>
                Clientes
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url;?>Usuarios">Ver detalle</a>
                <div class="small text-white">
                    <span class="text-white">
                        <?php echo $data['clientes']['total']; ?>
                    </span>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <i class="fab fa-product-hunt fa-2x"></i>
                Productos
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url;?>Productos">Ver detalle</a>
                <div class="small text-white">
                    <span class="text-white">
                        <?php echo $data['productos']['total']; ?>
                    </span>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white mb-4">
            <div class="card-body">
                <i class="fas fa-cash-register fa-2x"></i>
                Ventas por Dia
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url;?>Compras/ventas">Ver detalle</a>
                <div class="small text-white">
                    <span class="text-white">
                        <?php echo $data['ventas']['total']; ?>
                    </span>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card text-white mb-4" style="background-color: #39FF14; ">
            <div class="card-body">
                <i class="fas fa-user fa-2x"></i>
                Categoria
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url; ?>Categorias">Ver detalle</a>
                <div class="small text-white">
                    <span class="text-white">
                    <?php echo $data['categorias']['total']; ?>
                    </span>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card text-white mb-4" style="background-color: #00E1D9;">
            <div class="card-body">
                <i class="fas fa-ruler fa-2x"></i>
                Medidas
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url; ?>Medidas">Ver detalle</a>
                <div class="small text-white">
                    <span class="text-white">
                        <?php echo $data['medidas']['total']; ?>
                    </span>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card text-white mb-4" style="background-color: #A71FB2;">
            <div class="card-body">
                <i class="fas fa-box fa-2x"></i>
                Cajas
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url; ?>Cajas">Ver detalle</a>
                <div class="small text-white">
                    <span class="text-white">
                    <?php echo $data['caja']['total']; ?>
                    </span>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card text-white mb-4" style="background-color: #FF74C7;">
            <div class="card-body">
                <i class="fas fa-shopping-bag fa-2x"></i>
                Compras por Día
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url; ?>>Compras/historial">Ver detalle</a>
                <div class="small text-white">
                    <span class="text-white">
                    <?php echo $data['compras']['total']; ?>
                    </span>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- REPORTE GRAFICO -->
<div class="row">
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                Productos con Stock Minimo
            </div>
            <div class="card-body"><canvas id="stockMinimo" width="100%" height="40"></canvas></div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Productos Mas vendidos
            </div>
            <div class="card-body"><canvas id="productosMasVendidos" width="100%" height="40"></canvas></div>
        </div>
    </div>
</div>


<?php include "Views/Templates/footer.php"; ?>