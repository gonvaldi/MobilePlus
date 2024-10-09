
<?php
date_default_timezone_set('America/La_Paz');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=producto_vencer_".date("d-m-Y").".xls");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
require_once "../conexion.php";

$id_user = $_SESSION['idUser'];


$date_now = date('d-m-Y');
$date_now_base = date('Y-m-d');
$date_past = strtotime('+96 day', strtotime($date_now));
$hoy = date('Y-m-d', $date_past);

$query = mysqli_query($conexion, "SELECT p.*, t.tipo, pr.nombre presentacion ,la.laboratorio FROM producto p INNER JOIN tipos t ON p.id_tipo = t.id INNER JOIN presentacion pr ON p.id_presentacion = pr.id INNER JOIN laboratorios la ON p.id_lab = la.id WHERE p.existencia > 0 and  p.estado = 0 and p.vencimiento != '0000-00-00' AND p.vencimiento BETWEEN '$date_now_base' AND '$hoy' order by p.vencimiento");

?>

<table border="1">
    <thead >
    <tr>
        <th>Codigo barra</th>
        <th>Codigo</th>
        <th>Producto</th>
        <th>Laboratorio</th>
        <th>Presentacion</th>
        <th>Tipo</th>
        <th>Fecha Vencimiento</th>
        <th>Fecha Registro</th>
        <th>Lote</th>
        <th>Precio de Compra</th>
        <th>Precio de Venta</th>
        <th>Stock</th>
        <th>Estante</th>

        <th>Dias</th>



    </tr>
    </thead>
    <tbody>
    <?php

    function TildesHtml($cadena)
    {
        return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ","ü"),
            array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;","&uuml;"), $cadena);
    }

    while ($row = mysqli_fetch_assoc($query)) {

        ?>
        <tr>

            <td><?php echo $row['codigo_producto']; ?></td>
            <td><?php echo $row['codigo']; ?></td>
            <td><?php echo TildesHtml($row['descripcion']);  ?></td>
            <td><?php echo TildesHtml($row['laboratorio']); ?></td>
            <td><?php echo TildesHtml($row['presentacion']); ?></td>
            <td><?php echo TildesHtml($row['tipo']);  ?></td>
            <td><?php echo $row['vencimiento']; ?></td>
            <td><?php echo $row['fecha_registro']; ?></td>
            <td><?php echo $row['lote'];  ?></td>
            <td><?php echo $row['costo'];  ?></td>
            <td><?php echo $row['precio'];  ?></td>

            <td><?php echo $row['existencia'];  ?></td>
            <td><?php echo TildesHtml($row['estante']);  ?></td>

            <td><?php


                $date1 = new DateTime($date_now);
                $date2 = new DateTime($row['vencimiento']);
                $diff = $date2->diff($date1);
                // will output 2 days
                echo $diff->days . ' dias ';

                ?></td>

        </tr>




    <?php } ?>




    </tbody>
</table>