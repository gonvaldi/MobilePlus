
<?php
date_default_timezone_set('America/La_Paz');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=stock_producto_".date("d-m-Y").".xls");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
require_once "../conexion.php";

$id_user = $_SESSION['idUser'];

$query = mysqli_query($conexion, "SELECT v.*, c.laboratorio laboratorio,u.tipo tipo,p.nombre presentacion FROM producto v INNER JOIN laboratorios c ON v.id_lab = c.id INNER JOIN  tipos u ON v.id_tipo = u.id INNER JOIN  presentacion p ON v.id_presentacion = p.id   WHERE v.existencia > 0 order by v.descripcion");

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

        </tr>




    <?php } ?>




    </tbody>
</table>