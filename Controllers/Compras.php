<?php
class Compras extends Controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }
    public function index()
    {
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user, 'nueva_compra');
        if (!empty ($verificar) || $id_user == 1) {
            // si en caso tiene permiso entonces mostramos todo
            $this->views->getView($this, "index");
        } else {
            header('location:' . base_url . 'Errors/Permisos');
        }
    }
    public function buscarCodigo($cod)
    {
        $data = $this->model->getProCod($cod);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    // -------------------------------------TODO COMPRA ----------------------------------------
    public function ingresar()
    {
        // en el post se guarda solo el id del detalle, cantidad y el precio
        $id = $_POST['id'];
        // en datos obetenemos todo los datos restantes del prducto
        $datos = $this->model->getProductos($id);
        $id_producto = $datos['id'];
        // esto lo llamamos desde el controlador usuario, para guardar que usuario lo registra
        $id_usuario = $_SESSION['id_usuario'];
        $precio = $datos['precio_compra'];
        $cantidad = $_POST['cantidad'];

        // si en caso el codigo es el mismo, solo haremos que aumente la cantidad para no registrar otra vez
        $comprobar = $this->model->consultarDetalle('detalle', $id_producto, $id_usuario);
        if (empty ($comprobar)) {
            $sub_total = $precio * $cantidad;
            $data = $this->model->resgistrarDetalle('detalle', $id_producto, $id_usuario, $precio, $cantidad, $sub_total);
            if ($data == "ok") {
                $msg = array('msg' => "Producto ingresado a la compra", 'icono' => "success");
            } else {
                $msg = array('msg' => "Error al ingresar el producto a la compra", 'icono' => "error");
            }
        } else {
            $total_cantidad = $comprobar['cantidad'] + $cantidad;
            $sub_total = $total_cantidad * $precio;
            $data = $this->model->actualizarDetalle('detalle', $precio, $total_cantidad, $sub_total, $id_producto, $id_usuario);
            if ($data == "modificado") {
                $msg = array('msg' => "Producto actualizado", 'icono' => "success");
            } else {
                $msg = array('msg' => "Error al actualizar el producto ", 'icono' => "error");
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function listar($table)
    {
        $id_usuario = $_SESSION['id_usuario'];
        $data['detalle'] = $this->model->getDetalle($table, $id_usuario);
        $data['total_pagar'] = $this->model->calcularCompra($table, $id_usuario);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        // print_r($data['detalle']);
        die();
    }
    public function delete($id)
    {
        $data = $this->model->deleteDetalle('detalle', $id);
        if ($data == "ok") {
            $msg = array('msg' => "La compra se elimino con éxito", 'icono' => "success");
        } else {
            $msg = array('msg' => "Error al eliminar la compra", 'icono' => "error");
        }
        echo json_encode($msg);
        die();
    }
    public function deleteVenta($id)
    {
        $data = $this->model->deleteDetalle('detalle_temp', $id);
        if ($data == "ok") {
            $msg = array('msg' => "La venta se elimino con éxito", 'icono' => "success");
        } else {
            $msg = array('msg' => "Error al eliminar la venta", 'icono' => "error");
        }
        echo json_encode($msg);
        die();
    }
    public function registrarCompra()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $existe_detalle = $this->model->existeSeleccion('detalle');
        if (empty ($existe_detalle)) {
            $msg = array('msg' => "Error, no exite ningun producto elegido", 'icono' => "error");
        } else {
            //------------------------------------------------------
            $total = $this->model->calcularCompra('detalle', $id_usuario);
            $data = $this->model->registrarCompra($total['total']);
            //------------------------------------------------------
            if ($data == "ok") {
                $detalle = $this->model->getDetalle('detalle', $id_usuario);
                $id_compra = $this->model->getId('compras');
                foreach ($detalle as $row) {
                    $cantidad = $row['cantidad'];
                    $precio = $row['precio'];
                    $id_producto = $row['id_producto'];
                    $sub_total = $cantidad * $precio;
                    // se registra la compra
                    $this->model->registrarDetalleCompra($id_compra['id'], $id_producto, $cantidad, $precio, $sub_total);
                    // obtenemos los productos
                    $stock_actual = $this->model->getProductos($id_producto);
                    $stock = $stock_actual['cantidad'] + $cantidad;
                    $this->model->actualizarStock($stock, $id_producto);
                }
                $vaciar = $this->model->vaciarDetalle('detalle', $id_usuario);
                if ($vaciar == "ok") {
                    $msg = array('msg' => 'ok', 'id_compra' => $id_compra['id']);
                }
            } else {
                $msg = array('msg' => "Error al registrar la compra", 'icono' => "error");
            }
        }
        echo json_encode($msg);
        die();
    }
    // PRIMERO TENEMOS QUE DESCAGAR FPDF : http://www.fpdf.org/, - crear el archivo LIBRARIES 

    public function generarPdf($id_compra)
    {
        $empresa = $this->model->getEmpresa();
        $productos = $this->model->getProCompra($id_compra);

        // en http://www.fpdf.org/    en tutoriales>ejemplos basicos
        require ('Libraries/fpdf/fpdf.php');
        $pdf = new FPDF('P', 'mm', array(90, 200));
        $pdf->AddPage();
        $pdf->SetMargins(5, 0, 0);
        $pdf->setTitle('Reporte Compra');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(65, 10, utf8_decode($empresa['nombre']), 0, 1, 'C');
        $pdf->Image(base_url . 'Assets/img/logo.jpg', 60, 18, 20, 20);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, 'Ruc: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $empresa['ruc'], 0, 1, 'L');

        // siesque utf8_decode ya es omitida en la version del php usar: htmlspecialchars($texto_con_tildes, ENT_QUOTES, 'UTF-8');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode('Teléfono: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $empresa['telefono'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode('Dirección: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, utf8_decode($empresa['direccion']), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, 'Folio: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $id_compra, 0, 1, 'L');
        $pdf->Ln();// salto de linea

        // Encabezado de productos
        $pdf->setFillcolor(0, 0, 0);
        $pdf->setTextColor(255, 255, 255);
        $pdf->Cell(10, 5, 'Cant', 0, 0, 'L', true);
        $pdf->Cell(40, 5, utf8_decode('Descripción'), 0, 0, 'L', true);
        $pdf->Cell(12, 5, 'Precio', 0, 0, 'L', true);
        $pdf->Cell(15, 5, 'Sub total', 0, 1, 'L', true);

        $total = 0.00;
        $pdf->setTextColor(0, 0, 0);
        // recorremos 
        foreach ($productos as $row) {
            $total += $row['sub_total'];

            $pdf->Cell(10, 5, $row['cantidad'], 0, 0, 'L');
            $pdf->Cell(40, 5, utf8_decode($row['descripcion']), 0, 0, 'L');
            $pdf->Cell(12, 5, $row['precio'], 0, 0, 'L');
            $pdf->Cell(15, 5, number_format($row['sub_total'], 2, '.', ','), 0, 1, 'L');
        }
        $pdf->Ln();
        $pdf->Cell(75, 5, 'Total a pagar: ', 0, 1, 'R');
        $pdf->Cell(75, 5, number_format($total, 2, '.', ','), 0, 1, 'R');

        $pdf->Output();
    }
    // -----------------------------------TODO DEL METODO HISTORIAL DE COMPRAS-------------------
    public function historial()
    {
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user, 'historial_compras');
        if (!empty ($verificar) || $id_user == 1) {
            // si en caso tiene permiso entonces mostramos todo
            $this->views->getView($this, "historial");
        } else {
            header('location:' . base_url . 'Errors/Permisos');
        }
    }
    public function listar_historial($parametro = '1')
    {   
        // public function listar_historial($parametro = '1')
        // estos son parametros opcionales
        // $data = '';
        // if ($parametro == 1) {
        //     $data = $this->model->getHitorialCompras();
        // }
        // else{
        //     $array = explode(",", $parametro);
        //     $desde = $array[0];
        //     $hasta = $array[1];
        //     $data = $this->model->getFiltrar('compras',$desde,$hasta); // ya no ponemos el combre de la tabla
        // }

        $data = $this->model->getHitorialCompras();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-success">Completado</span>';
                $data[$i]['acciones'] = '<div>
                    <button class="btn btn-warning" onclick = "btnAnularC(' . $data[$i]['id'] . ')"><i class="fas fa-ban"></i></button>
                    <a class="btn btn-danger" href="' . base_url . "Compras/generarPdf/" . $data[$i]['id'] . '" target="_blanck">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>';
            } else {
                $data[$i]['estado'] = '<span class="badge bg-danger">Anulado</span>';
                $data[$i]['acciones'] = '<div>
                    <a class="btn btn-danger" href="' . base_url . "Compras/generarPdf/" . $data[$i]['id'] . '" target="_blanck">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }   
    public function anularCompra($id_compra)
    {
        // existe la compra que se va anular?
        $data = $this->model->getAnularCompra($id_compra);
        $anular = $this->model->getAnular('compras', $id_compra);
        foreach ($data as $row) {
            $stock_actual = $this->model->getProductos($row['id_producto']);
            $stock = $stock_actual['cantidad'] - $row['cantidad'];
            $this->model->actualizarStock($stock, $row['id_producto']);
        }
        if ($anular == "ok") {
            $msg = array('msg' => 'Compra Anulada', 'icono' => 'success');

        } else {
            $msg = array('msg' => 'Error al Anular la Compra', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function filtrarHistorialCompras($datos)
    {
        $array = explode(",", $datos); // lo convertidos en un arreglo los datos
        $fechaI = $array[0];
        $fechaF = $array[1];
        $data = $this->model->getFiltrar('compras', $fechaI, $fechaF);
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-success">Completado</span>';
                $data[$i]['acciones'] = '<div>
                    <button class="btn btn-warning" onclick = "btnAnularC(' . $data[$i]['id'] . ')"><i class="fas fa-ban"></i></button>
                    <a class="btn btn-danger" href="' . base_url . "Compras/generarPdf/" . $data[$i]['id'] . '" target="_blanck">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>';
            } else {
                $data[$i]['estado'] = '<span class="badge bg-danger">Anulado</span>';
                $data[$i]['acciones'] = '<div>
                    <a class="btn btn-danger" href="' . base_url . "Compras/generarPdf/" . $data[$i]['id'] . '" target="_blanck">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>';
            }

        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }


    // --------------------------------------FIN HISTORIALD DE COMPRAS ----------------------------

    /*
        -   Para crear las opcopnes de imprimir en pdf - excel - copiar
        PARA DESCARGAR EL TADATABLES PARA ESTA VISTA PRIMERO
        1. ENTRAR : https://datatables.net/download/
        2. step 1: seleccionar Bootstrap 4
        3. step 2: Bootstrap 4, datatables
        4. extensiones : Buttons>column>hmtl>.......print view
        5: step 3: Downloand > 

        modificamos en header y en footer
        header: line 11 href="<?php echo base_url; ?>Assets/DataTables/dataTables.min.css"
    */

    // --------------------------------------------TODO DEL METODO VENTAS------------------
    public function ventas()
    {
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user, 'nueva_venta');
        if (!empty ($verificar) || $id_user == 1) {
            // si en caso tiene permiso entonces mostramos todo
            $data = $this->model->getClientes();
            $this->views->getView($this, "ventas", $data);
        } else {
            header('location:' . base_url . 'Errors/Permisos');
        }
    }
    public function ingresarVenta()
    {
        $id = $_POST['id'];
        // en datos obetenemos todo los datos restantes del prducto
        $datos = $this->model->getProductos($id);
        $id_producto = $datos['id'];
        // esto lo llamamos desde el controlador usuario, para guardar que usuario lo registra
        $id_usuario = $_SESSION['id_usuario'];
        $precio = $datos['precio_venta'];
        $cantidad = $_POST['cantidad'];

        // si en caso el codigo es el mismo, solo haremos que aumente la cantidad para no registrar otra vez
        $comprobar = $this->model->consultarDetalle('detalle_temp', $id_producto, $id_usuario);
        if (empty ($comprobar)) {
            // esto es para verificar si existe el stok
            if ($datos['cantidad'] >= $cantidad) {
                $sub_total = $precio * $cantidad;
                $data = $this->model->resgistrarDetalle('detalle_temp', $id_producto, $id_usuario, $precio, $cantidad, $sub_total);
                if ($data == "ok") {
                    $msg = array('msg' => "Producto ingresado a la Venta", 'icono' => "success");
                } else {
                    $msg = array('msg' => "Error al ingresar el producto a la Venta", 'icono' => "error");
                }
            }
            else{
                $msg = array('msg' => 'Estock no disponible '.$datos['cantidad'], 'icono' => "warning");
            }
        } else {
            $total_cantidad = $comprobar['cantidad'] + $cantidad;
            $sub_total = $total_cantidad * $precio;

            //ejemplo:  cuando hay stock 10 y ya selecionaste 9 y quieres aumentar 2, entonces ya no va permitir que se aumente la ultima seleccion
            if ($datos['cantidad'] >= $total_cantidad){
                $data = $this->model->actualizarDetalle('detalle_temp', $precio, $total_cantidad, $sub_total, $id_producto, $id_usuario);
                if ($data == "modificado") {
                    $msg = array('msg' => "Producto actualizado", 'icono' => "success");
                } else {
                    $msg = array('msg' => "Error al actualizar el producto ", 'icono' => "error");
                }
            }
            else{
                $msg = array('msg' => "Stock no disponible del producto", 'icono' => "warning");
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    /*
    VIDEO 23: para buscar a los clientes en venta
    https://select2.org/getting-started/installation
    descargar los dos links, en css y js
    lo llamamos los links en el header y footer
    todo esto es para usar los combobox
    */

    public function registrarVenta($id_cliente)
    {
        $id_usuario = $_SESSION['id_usuario'];
        // verificamos si existe el usuario y que la caja este abierto
        $verificar = $this->model->verificarCaja($id_usuario);
        if (empty ($verificar)) {
            $msg = array('msg' => 'La caja esta cerrada', 'icono' => 'warning');
        } else {

            // PRIMERO VALIDAMOS Y ELIGIO ALGUN PRODUCTO PARA GENERAR VENTA (talvez eres travieso)
            $existe_detalle = $this->model->existeSeleccion('detalle_temp');
            if (empty ($existe_detalle)) {
                $msg = array('msg' => "Error, no exite ningun producto elegido", 'icono' => "error");
            } else {
                $total = $this->model->calcularCompra('detalle_temp', $id_usuario); // esta funcion tiene doble funcionalidad
                $data = $this->model->registrarVenta($id_usuario, $id_cliente, $total['total']);
                if ($data == "ok") {
                    $detalle = $this->model->getDetalle('detalle_temp', $id_usuario);
                    $id_venta = $this->model->getId('ventas');
                    foreach ($detalle as $row) {
                        $cantidad = $row['cantidad'];
                        $desc = $row['descuento'];
                        $precio = $row['precio'];
                        $id_producto = $row['id_producto'];
                        $sub_total = $cantidad * $precio - $desc;
                        // se registra la compra
                        $this->model->registrarDetalleVenta($id_venta['id'], $id_producto, $cantidad, $desc, $precio, $sub_total);
                        // obtenemos los productos
                        $stock_actual = $this->model->getProductos($id_producto);
                        $stock = $stock_actual['cantidad'] - $cantidad;
                        $this->model->actualizarStock($stock, $id_producto);
                    }
                    $vaciar = $this->model->vaciarDetalle('detalle_temp', $id_usuario);
                    if ($vaciar == "ok") {
                        $msg = array('msg' => 'ok', 'id_venta' => $id_venta['id']);
                    }
                } else {
                    $msg = array('msg' => "Error al registrar la venta", 'icono' => "error");
                }
            }
        }
        echo json_encode($msg);
        die();
    }
    public function generarPdfVenta($id_venta)
    {
        $empresa = $this->model->getEmpresa();
        $descuento = $this->model->getDescuento($id_venta);
        $productos = $this->model->getProVenta($id_venta);

        // en http://www.fpdf.org/    en tutoriales>ejemplos basicos
        require ('Libraries/fpdf/fpdf.php');
        $pdf = new FPDF('P', 'mm', array(90, 200));
        $pdf->AddPage();
        $pdf->SetMargins(5, 0, 0);
        $pdf->setTitle('Reporte Venta');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(65, 10, utf8_decode($empresa['nombre']), 0, 1, 'C');
        $pdf->Image(base_url . 'Assets/img/logo.jpg', 60, 18, 20, 20);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, 'Ruc: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $empresa['ruc'], 0, 1, 'L');

        // siesque utf8_decode ya es omitida en la version del php usar: htmlspecialchars($texto_con_tildes, ENT_QUOTES, 'UTF-8');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode('Teléfono: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $empresa['telefono'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode('Dirección: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, utf8_decode($empresa['direccion']), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, 'Folio: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, $id_venta, 0, 1, 'L');
        $pdf->Ln();// salto de linea

        $pdf->Ln();
        // Encabezado de clientes
        $pdf->setFillcolor(0, 0, 0);
        $pdf->setTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(25, 5, 'Nombre', 0, 0, 'L', true);
        $pdf->Cell(25, 5, utf8_decode('Teléfono'), 0, 0, 'L', true);
        $pdf->Cell(25, 5, utf8_decode('Dirección'), 0, 1, 'L', true);

        $pdf->setTextColor(0, 0, 0);
        $clientes = $this->model->clientesVenta($id_venta);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(25, 5, $clientes['nombre'], 0, 0, 'L');
        $pdf->Cell(25, 5, $clientes['telefono'], 0, 0, 'L');
        $pdf->Cell(25, 5, utf8_decode($clientes['direccion']), 0, 1, 'L');
        $pdf->Ln();
        // Encabezado de productos
        $pdf->setFillcolor(0, 0, 0);
        $pdf->setTextColor(255, 255, 255);
        $pdf->Cell(10, 5, 'Cant', 0, 0, 'L', true);
        $pdf->Cell(40, 5, utf8_decode('Descripción'), 0, 0, 'L', true);
        $pdf->Cell(12, 5, 'Precio', 0, 0, 'L', true);
        $pdf->Cell(15, 5, 'Sub total', 0, 1, 'L', true);
        $total = 0.00;
        $pdf->setTextColor(0, 0, 0);
        // recorremos 
        foreach ($productos as $row) {
            $total += $row['sub_total'];
            $pdf->Cell(10, 5, $row['cantidad'], 0, 0, 'L');
            $pdf->Cell(40, 5, utf8_decode($row['descripcion']), 0, 0, 'L');
            $pdf->Cell(12, 5, $row['precio'], 0, 0, 'L');
            $pdf->Cell(15, 5, number_format($row['sub_total'], 2, '.', ','), 0, 1, 'L');
        }
        $pdf->Ln();
        $pdf->Cell(75, 5, 'Descuento Total: ', 0, 1, 'R');
        $pdf->Cell(75, 5, number_format($descuento['total'], 2, '.', ','), 0, 1, 'R');
        $pdf->Cell(75, 5, 'Total a pagar: ', 0, 1, 'R');
        $pdf->Cell(75, 5, number_format($total, 2, '.', ','), 0, 1, 'R');
        $pdf->Output();
    }
    public function calcularDescuento($datos)
    {
        $array = explode(",", $datos); // lo convertidos en un arreglo los datos
        $id = $array[0];
        $des = $array[1];
        if (empty ($id) || empty ($des)) {
            $msg = array('msg' => 'Error', 'icono' => 'error');
        } else {
            // verificamos si existe el descuesto
            $descuento_actual = $this->model->verificarDescuento($id);
            $descuento_total = $descuento_actual['descuento'] + $des;
            $sub_total = ($descuento_actual['cantidad'] * $descuento_actual['precio']) - $descuento_total;
            $data = $this->model->actualizarDescuento($descuento_total, $sub_total, $id);
            if ($data == "ok") {
                $msg = array('msg' => 'Descuento aplicado', 'icono' => 'success');
            } else {
                $msg = array('msg' => 'Error al aplicar el descuento', 'icono' => 'error');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    //---------------------------TODO DE HITORIAL DE VENTAS ------------------------
    public function historial_ventas()
    {
        // TODO ESTO ES PARA MOSTRAR SOLO LAS VISTAS HABILITADAS
        $id_user = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_user, 'historial_ventas');
        if (!empty ($verificar) || $id_user == 1) {
            // si en caso tiene permiso entonces mostramos todo
            $this->views->getView($this, "historial_ventas");
        } else {
            header('location:' . base_url . 'Errors/Permisos');
        }
    }
    public function listar_historial_ventas()
    {
        $data = $this->model->getHitorialVentas();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-success">Completado</span>';
                $data[$i]['acciones'] = '<div>
                    <button class="btn btn-warning" onclick = "btnAnularV(' . $data[$i]['id'] . ')"><i class="fas fa-ban"></i></button>
                    <a class="btn btn-danger" href="' . base_url . "Compras/generarPdfVenta/" . $data[$i]['id'] . '" target="_blanck">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>';
            } else {
                $data[$i]['estado'] = '<span class="badge bg-danger">Anulado</span>';
                $data[$i]['acciones'] = '<div>
                    <a class="btn btn-danger" href="' . base_url . "Compras/generarPdfVenta/" . $data[$i]['id'] . '" target="_blanck">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>';
            }

        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function anularVenta($id_venta)
    {
        // existe la compra que se va anular?
        $data = $this->model->getAnularVentas($id_venta);
        $anular = $this->model->getAnular('ventas', $id_venta);
        foreach ($data as $row) {
            $stock_actual = $this->model->getProductos($row['id_producto']);
            $stock = $stock_actual['cantidad'] + $row['cantidad'];
            $this->model->actualizarStock($stock, $row['id_producto']);
        }
        if ($anular == "ok") {
            $msg = array('msg' => 'Venta Anulada', 'icono' => 'success');

        } else {
            $msg = array('msg' => 'Error al Anular la Venta', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    // es para generar un pdf de un rango de fechas
    public function generarPdfVentasFiltro(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        if (empty($desde) || empty($hasta)) {
            $data = $this->model->getHitorialVentas();
        }
        else{
            $data = $this->model->getFiltrarVentas($desde, $hasta);
        }
        require ('Libraries/fpdf/fpdf.php');

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetMargins(10, 0, 0);
        $pdf->setTitle('Reporte Venta');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->setFillcolor(0, 0, 0);
        $pdf->setTextColor(255, 255, 255);

        $pdf->Cell(10, 5, 'Id', 0, 0, 'L',true);
        $pdf->Cell(80, 5, 'Cliente', 0, 0, 'L',true);
        $pdf->Cell(50, 5, 'Fecha', 0, 0, 'L',true); 
        $pdf->Cell(50, 5, 'Total', 0, 1, 'L',true); 
        $pdf->SetFont('Arial', '', 10);
        $pdf->setTextColor(0,0,0);
        foreach ($data as $row) {
            $pdf->Cell(10, 5, $row['id'], 0, 0, 'L');
            $pdf->Cell(80, 5, $row['nombre'], 0, 0, 'L');
            $pdf->Cell(50, 5, $row['fecha'], 0, 0, 'L');
            $pdf->Cell(50, 5, $row['total'], 0, 1, 'L');
        }
        $pdf->Output();
    }
    public function generarPDFComprasFiltro(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        if (empty($desde) || empty($hasta)) {
            $data = $this->model->getHitorialCompras();
        }
        else{
            $data = $this->model->getFiltrarCompras($desde, $hasta);
        }
        require ('Libraries/fpdf/fpdf.php');

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetMargins(10, 0, 0);
        $pdf->setTitle('Reporte Compras');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->setFillcolor(0, 0, 0);
        $pdf->setTextColor(255, 255, 255);

        $pdf->Cell(20, 5, 'Id', 0, 0, 'L',true);
        $pdf->Cell(30, 5, 'Total', 0, 0, 'L',true);
        $pdf->Cell(80, 5, 'Fecha', 0, 0, 'L',true); 
        $pdf->Cell(10, 5, 'Estado', 0, 1, 'L',true); 
        $pdf->SetFont('Arial', '', 10);
        $pdf->setTextColor(0,0,0);
        foreach ($data as $row) {
            $pdf->Cell(20, 5, $row['id'], 0, 0, 'L');
            $pdf->Cell(30, 5, $row['total'], 0, 0, 'L');
            $pdf->Cell(80, 5, $row['fecha'], 0, 0, 'L');
            $pdf->Cell(10, 5, $row['estado'], 0, 1, 'L');
        }
        $pdf->Output();
    }
}
?>