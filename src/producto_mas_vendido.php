
<?php
date_default_timezone_set('America/La_Paz');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Producto_mas_vendido".date("d-m-Y").".xls");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
require_once "../conexion.php";

$id_user = $_SESSION['idUser'];

$fecha_inicio=$_POST['fecha_inicio'];
$fecha_final=$_POST['fecha_final'];



$datetime1 = date_create($fecha_inicio);
$datetime2 = date_create($fecha_final);
$contador = date_diff($datetime1, $datetime2);
$differenceFormat = '%a';




$query = mysqli_query($conexion, " SELECT la.laboratorio laboratorio,t.tipo tipo, p.codigo_producto, p.descripcion, p.id_lab, p.id_tipo, p.id_presentacion,pre.nombre presentacion, p.existencia,p.precio,p.costo, d.id_producto, d.cantidad, SUM(d.cantidad) as total FROM producto p,detalle_venta d ,ventas v,tipos t,laboratorios la, presentacion pre  WHERE p.estado=0 and p.id_presentacion = pre.id  and p.id_tipo = t.id  and p.id_lab = la.id and  p.codproducto = d.id_producto and d.id_venta = v.id   AND v.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_final 23:59:59' group by p.descripcion,p.id_lab,p.id_presentacion,p.id_tipo ORDER BY `total` DESC ");

?>

<table border="1">
    <thead >
    <tr>
        <th colspan="7"> Lista Producto mas vendido</th>



        <th><?php echo $fecha_inicio;     ?> al <?php echo $fecha_final;     ?></th>
        <th><?php echo date("d-m-Y",strtotime($fecha_final."+ 30 days"));     ?></th>
        <th><?php echo date("d-m-Y",strtotime($fecha_final."+ 90 days"));     ?></th>
        <th colspan="3">Duracion de Stock</th>

    </tr>
    </thead>
    <tbody>


    <tr>
        <td>Codigo barra</td>

        <td>Producto</td>
        <td>Laboratorio</td>
        <td>Tipo</td>
        <td>Presentacion</td>
        <td>Precio Compra</td>
        <td>Precio Venta</td>

        <td><?php


            echo $contador->format($differenceFormat)+1;     ?> Dias</td>


        <td>30 Dias</td>
        <td>90 Dias</td>
        <td>Stock Actual</td>
        <td>Dias duracion  </td>
        <td>Fecha duracion</td>

    </tr>


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

            <td><?php echo TildesHtml($row['descripcion']);  ?></td>

            <td><?php echo $row['laboratorio']; ?></td>
            <td><?php echo $row['tipo']; ?></td>
            <td><?php echo $row['presentacion']; ?></td>

            <td><?php echo $row['costo']; ?></td>
            <td><?php echo $row['precio']; ?></td>

            <td><?php echo $row['total']; ?></td>
            <td>
            <?php  $dias_rango=$contador->format($differenceFormat)+1;

            $total_dias=(30*$row['total'])/$dias_rango;
             echo round($total_dias, 2);

        ?> </td>


            <td>
                <?php
                echo round($total_dias, 2)*3;

                ?> </td>

            <td><?php

                $name_producto=$row['descripcion'];
                $name_lab=$row['id_lab'];
                $name_tipo=$row['id_tipo'];
                $name_presentacion=$row['id_presentacion'];


                $query111 = mysqli_query($conexion, "SELECT sum(existencia) existencia FROM producto WHERE  id_presentacion = $name_presentacion and descripcion = '$name_producto' and id_lab = $name_lab and id_tipo = $name_tipo and existencia > 0 and estado=0 ");


                while ($row111 = mysqli_fetch_assoc($query111)) {

                $existencia=$row111['existencia'];
                }



                echo $existencia; ?></td>


            <td>
                <?php

                $stock_duracion=($existencia*$dias_rango)/$row['total'];

              echo $fecha_stock_cal=round($stock_duracion, 0);
                ?> </td>


            <td><?php
                $fechaActual = date("d-m-Y");

                echo date("d-m-Y",strtotime($fechaActual." + ".$fecha_stock_cal." days"));     ?></td>


        </tr>




    <?php } ?>




    </tbody>
</table>