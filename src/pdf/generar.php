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
$pdf->Cell(200, 5, "TERMINOS DE LA GARANTIA", 0, 1, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', '', 9);
$pdf->multicell(190, 5, utf8_decode('Esta Garantía cubre el normal funcionamiento del hardware del equipo (parte física) y no cubre el software instalado. Siendo esta responsabilidad del comprador.
En caso de falla del software instalado. La tienda se compromete a reparar el mismo sin cargo alguno para el comprador cuando el mismo fallare en situaciones normales de uso y bajo las condiciones que a continuación se detalla:
a)	Son beneficiarios del mismo comprador original dentro del plazo mencionado
b)	Sera causa de anulación de esta garantía la intervención del equipo por personas ajenas a la empresa
c)	 Para ser efectiva la GARANTIA, el consumidor presentara este certificado, de garantía
d)	La garantía carecerá de validez si se observan tachaduras o enmiendas en los datos de certificado, o la perdida de la misma'));
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(200, 5, "           NO ESTAN CUBIERTOS POR ESTA GARANTIA", 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->multicell(190, 5, utf8_decode('            I) Los daños ocasionados al exterior del equipo.
            II) Las roturas, golpes, caídas o rayaduras causadas por traslados. 
            III) Los daños o fallas ocasionadas por deficiencias o interrupciones no autorizadas.
            IV) Todos accesorios externos al equipo de computación (teclado, mouse, parlante y etc.)
e)	La tienda no asume la responsabilidad alguna por los daños personales o la propiedad que pudiera causar la mala instalación o falla de mantenimiento.
f)	Toda manipulación extra por parte de personas ajenas a la tienda ocasionara la perdida de la garantía dentro del plazo mencionado
g)	Una vez la garantía   haya expirado o anulado, si el comprador deseara alguna intervención técnica del equipo tendrá que hacer un abono de acuerdo a lo solicitado.
 '));
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(200, 5, "        UNA VEZ RETIRADA LA MERCADERIA NO ACEPTA CAMBIOS NI DEVOLUCIONES", 0, 1, 'L');
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(100, 5, "____________", 0, 0, 'C');
$pdf->Cell(100, 5, "____________", 0, 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 5, "Firma de laTienda", 0, 0, 'C');
$pdf->Cell(100, 5, "Firma del cliente ", 0, 0, 'C');
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial', '', 10);

$pdf->Cell(200, 5,"Fecha y hora :".$fechas, 0, 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(200, 5,"!Gracias por su compra!", 0, 0, 'C');



$pdf->Output("ventas.pdf", "I");

?>