<?php
date_default_timezone_set('America/La_Paz');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=reporte_venta_resumen_".date("d-m-Y").".xls");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$fecha_inicio=$_POST['fecha_inicio'];
$fecha_final=$_POST['fecha_final'];
$query = mysqli_query($conexion, "SELECT v.*, c.idcliente, c.nombre  FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente WHERE v.estado_venta = 0 AND v.fecha BETWEEN '$fecha_inicio' AND '$fecha_final'");

$query_1 = mysqli_query($conexion, "SELECT v.*, c.idcliente, c.nombre,SUM(v.total) total FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente WHERE v.estado_venta = 0 AND v.fecha BETWEEN '$fecha_inicio' AND '$fecha_final'group by v.id_usuario");


?>


<table border="1">
    <thead >
    <tr>
        <td></td>
        <th>Usuario</th>

        <th>Total ventas</th>
        <th>Stock Anterior</th>
        <th>Stock Actual</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $total=0;
    $ventas=0;
    $total_ganancia=0;
    while ($row = mysqli_fetch_assoc($query_1)) {
        $ventas=$ventas+1;
        $total=$total+$row['total'];
        ?>
        <tr>
            <td></td>

            <td><?php
                $id_usuario= $row['id_usuario'];
                $query1 = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id_usuario ");
                $result1 = mysqli_num_rows($query1);

                if ($result1 > 0) {
                    while ($data = mysqli_fetch_assoc($query1)) {
                        echo $data['usuario'];
                    }}

                ?></td>




            <td><?php echo $row['total']; ?></td>
        </tr>
    <?php } ?>

    <tr>


        <td></td>

        <td>Total:</td>

        <td><?php echo $total; ?></td>
    </tr>


    </tbody>
</table>



<table border="1">
    <thead >
    <tr>
        <th>Codigo venta</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Ganancia</th>
        <th>Total</th>


    </tr>
    </thead>
    <tbody>
    <?php
     $total=0;
    $ventas=0;
    $total_ganancia=0;
    while ($row = mysqli_fetch_assoc($query)) {
        $ventas=$ventas+1;
        $total=$total+$row['total'];
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['nombre']; ?></td>
            <td><?php echo $row['fecha']; ?></td>
            <td><?php
                $id_usuario= $row['id_usuario'];
                $query1 = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id_usuario ");
                $result1 = mysqli_num_rows($query1);

                if ($result1 > 0) {
                while ($data = mysqli_fetch_assoc($query1)) {
                    echo $data['usuario'];
                }}

                 ?></td>

            <td><?php

                $ganancia=0;
                $total_costo=0;
                $id_venta = $row['id'];
                $ventas_1 = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion,p.precio costo FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id_venta");


                    while ($data1 = mysqli_fetch_assoc($ventas_1)) {
                        $total_costo=$data1['costo']*$data1['cantidad'];
                        $ganancia=$ganancia+$data1['total']-$total_costo;
                    }
                $total_ganancia=$total_ganancia+$ganancia;
                        echo   $ganancia;
                ?></td>



            <td><?php echo $row['total']; ?></td>
        </tr>
    <?php } ?>

    <tr>
        <td></td>
        <td>Cantidad ventas:</td>
        <td><?php echo $ventas; ?></td>

        <td>Total:</td>
        <td><?php echo $total_ganancia; ?></td>
        <td><?php echo $total; ?></td>
    </tr>


    </tbody>
</table>