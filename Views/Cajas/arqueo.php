<?php
#print_r($_SESSION); : se muestra los datos del que inicio sesion
include "Views/Templates/header.php"; ?>

<div class="breadcrumb mb-4 gradiente sombrita rounded-3">
    <h5 class="breadcrumb-item active text-white m-3 ">Arqueo de Caja</h5>
</div>

<button class="btn btn-primary mb-3 "  id = "abrir_caja_button" type="button" onclick="arqueoCaja();">Nuevo <i class="fas fa-plus"></i></button>
<button class="btn btn-warning mb-3 " id = "cerrar_caja_button"  type="button" onclick="cerrarCaja();">Cerrar Caja</button>

<table class="table table-light" id="t_arqueo">
    <thead class="thead-dark">
        <tr>
            <th>Id</th>
            <th>Monto_inicial</th>
            <th>Monto_final</th>
            <th>Fecha_apertura</th>
            <th>Fecha_cierre </th>
            <th>Total ventas</th>
            <th>Monto total</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div class="modal fade" id="my_modal" tabindex="-1" aria-labelledby="Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Arqueo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form method="POST" id="frmAbrirCaja" onsubmit="abrirArqueo(event);">
                    <input type="hidden" id="id" name="id">
                    <div class="form-floating mb-3">
                        <input id="monto_inicial" class="form-control" type="text" name="monto_inicial" placeholder="Monto inicial" required>
                        <label for="monto_inicial">Monto incial</label>
                    </div>
                    <div id = "ocultar_campos">
                        <div class="form-floating mb-3">
                            <input id="monto_final" class="form-control" type="text" disabled>
                            <label for="monto_final">Monto final</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input id="total_ventas" class="form-control" type="text" disabled>
                            <label for="total_ventas">Total Ventas</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input id="monto_general" class="form-control" type="text" disabled>
                            <label for="monto_general">Monto Total</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="sutmit" id="btnAccion" >Abrir</button>
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php"; ?>