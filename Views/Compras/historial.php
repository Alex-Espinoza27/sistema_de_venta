<?php include "Views/Templates/header.php"; ?>


<div class="card sombrita">
    <div class="card-header gradiente sombrita text-white">
        Generar Reporte por Fecha
    </div>
    <div class="card-body">
        <form action ="<?php echo base_url;?>Compras/generarPDFComprasFiltro/" method="POST" target="_blanck">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre">Desde</label>
                        <input id="desde" class="form-control" type="date" name="desde" value="<?php echo date("Y-m-d"); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre">Hasta</label>
                        <input id="hasta" class="form-control" type="date" name="hasta" value="<?php echo date("Y-m-d"); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre">Generar </label>
                        <button class="btn btn-primary btn-block" type="submit" ><i class="fas fa-file-pdf"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="card mt-3">
    <div class="card-header bg-dark text-white gradiente sombrita">
        Compras
    </div>
    <div class="table-responsive">
        <div class="card-body">
            <table class="table table-light" id="t_historial_c">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Total</th>
                        <th>Fecha Compra</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php"; ?>