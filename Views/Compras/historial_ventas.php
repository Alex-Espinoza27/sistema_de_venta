<?php include "Views/Templates/header.php"; ?>

<div class="card sombrita">
    <div class="card-header gradiente sombrita text-white">
        Generar Reporte por Fecha
    </div>
    <div class="card-body">
        <form action="<?php echo base_url; ?>Compras/generarPdfVentasFiltro/" method="POST" target="_blanck">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="min">Desde</label>
                        <input id="min" type="date" value="<?php echo date('Y-m-d'); ?>" name="desde"
                            class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="hasta">Hasta</label>
                        <input id="hasta" type="date" value="<?php echo date('Y-m-d'); ?>" name="hasta"
                            class="form-control">
                    </div>
                </div>
                <div class="col-md-4 ml-auto">
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-file-pdf"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card my-2">
    <div class="card-header bg-dark text-white"
        style="background: linear-gradient(to right, #FF69B4, #9370DB, #0000FF); box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);">
        Ventas
    </div>
    <div class="table-responsive">
        <div class="card-body">
            <table class="table table-light" id="t_historial_v">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
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