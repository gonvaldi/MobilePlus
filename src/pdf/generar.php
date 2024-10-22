<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(5, 0, 0);
$pdf->SetTitle("Ventas");
$pdf->SetFont('Arial', 'B', 15);
$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto,p.codigo, p.descripcion,p.detalle, pre.nombre as tamano,la.laboratorio as marca,ti.tipo as tipo,p.lote as garantia, v.fecha as fechas FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto INNER JOIN presentacion pre ON pre.id = p.id_presentacion INNER JOIN laboratorios la ON la.id = p.id_lab INNER JOIN tipos ti ON ti.id = p.id_tipo INNER JOIN ventas v ON v.id = d.id_venta WHERE d.id_venta = $id");
$pdf->Cell(100, 5, utf8_decode("Oficina : ".$datos['nombre']), 0, 1, 'C');
$pdf->image("../../assets/img/logo_farm.jpg", 140, 10, 50, 20, 'JPG');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, $datos['telefono'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(40, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, utf8_decode($datos['email']), 0, 1, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(100, 5, utf8_decode("Nota de Venta"), 0, 1, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(176, 176, 180);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(130, 5, "Datos del cliente", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(30, 5, utf8_decode('Nombre'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('Teléfono'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('Nº C.I.'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(30, 5, utf8_decode($datosC['nombre']), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($datosC['telefono']), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($datosC['direccion']), 0, 1, 'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(200, 5, "Detalle de Producto", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(35, 5, utf8_decode('S/N'), 0, 0, 'L');
$pdf->Cell(35, 5, utf8_decode('MODELO'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('TIPO'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('TAMAÑO'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('MARCA'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('GARANTIA'), 0, 0, 'L');
$pdf->Cell(10, 5, 'Cant.', 0, 0, 'L');
$pdf->Cell(15, 5, 'Precio', 0, 0, 'L');
$pdf->Cell(15, 5, 'Sub Total.', 0, 1, 'L');
$pdf->SetFont('Arial', '', 7);
$total = 0.00;
$desc = 0.00;
while ($row = mysqli_fetch_assoc($ventas)) {

    $pdf->Cell(35, 5, $row['codigo'], 0, 0, 'L');
    $pdf->Cell(35, 5, $row['descripcion'], 0, 0, 'L');
    $pdf->Cell(20, 5, $row['tipo'], 0, 0, 'L');
    $pdf->Cell(20, 5, $row['tamano'], 0, 0, 'L');
    $pdf->Cell(20, 5, $row['marca'], 0, 0, 'L');
    $pdf->Cell(20, 5,  utf8_decode($row['garantia']), 0, 0, 'L');
    $pdf->Cell(10, 5, $row['cantidad'], 0, 0, 'L');
    $pdf->Cell(15, 5, $row['precio'], 0, 0, 'L');
    $sub_total = $row['total'];
    $total = $total + $sub_total;
    $desc = $desc + $row['descuento'];
    $pdf->Cell(15, 5, number_format($sub_total, 2, '.', ','), 0, 1, 'L');

    $fechas=$row['fechas'];

}
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 5, 'Descuento Total', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(190, 5, number_format($desc, 2, '.', ','), 0, 1, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 5, 'Total Pagar', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(190, 5, number_format($total, 2, '.', ','), 0, 1, 'R');

$pdf->Ln();
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(200, 5, "TERMINOS DE GARANTIA", 0, 1, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', '', 9);
$pdf->multicell(190, 5, utf8_decode('Esta garantía cubre defectos de fabricación en el hardware del smartphone y no cubre problemas relacionados con el software o aplicaciones de terceros. La garantía es válida únicamente para el comprador original y bajo las siguientes condiciones:

a) La garantía cubre fallos en el hardware durante el periodo establecido, siempre que el uso haya sido normal y adecuado.
b) La intervención del smartphone por técnicos no autorizados o la manipulación del software anulará esta garantía.
c) Para hacer efectiva la GARANTÍA, el comprador deberá presentar este certificado junto con la factura de compra.
d) La garantía será inválida si se observan tachaduras o modificaciones en los datos del certificado, o en caso de pérdida del mismo.
'));
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(200, 5, "EXCLUSIONES DE ESTA GARANTÍA", 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->multicell(190, 5, utf8_decode('I) Daños causados por caídas, golpes, o exposición a líquidos.
II) Daños externos al dispositivo, como rayaduras, abolladuras o pantallas rotas.
III) Fallos derivados del uso indebido, sobrecarga eléctrica, o exposición a condiciones extremas.
IV) Cualquier daño causado por software de terceros, modificaciones del sistema operativo, o uso no autorizado del dispositivo.
e) La tienda no se responsabiliza por daños a la propiedad o lesiones personales derivadas del uso incorrecto del smartphone.
f) El uso de accesorios no originales o la manipulación del dispositivo por personal no autorizado invalidará la garantía.
g) Una vez que la garantía haya expirado o se haya anulado, cualquier reparación estará sujeta a tarifas de servicio de acuerdo a lo solicitado.
'));

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(200, 5, "NO SE ACEPTAN CAMBIOS NI DEVOLUCIONES UNA VEZ RETIRADO EL SMARTPHONE", 0, 1, 'L');
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(100, 5, "____________", 0, 0, 'C');
$pdf->Cell(100, 5, "____________", 0, 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 5, "Firma de la Tienda", 0, 0, 'C');
$pdf->Cell(100, 5, "Firma del Cliente", 0, 0, 'C');
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial', '', 10);

$pdf->Cell(200, 5, "Fecha y hora: " . $fechas, 0, 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(200, 5, "!Gracias por su compra!", 0, 0, 'C');

$pdf->Output("ventas.pdf", "I");
?>
