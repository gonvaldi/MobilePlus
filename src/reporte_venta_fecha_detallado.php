<?php
date_default_timezone_set('America/La_Paz');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=reporte_venta_".date("d-m-Y").".xls");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$fecha_inicio=$_POST['fecha_inicio'];
$fecha_final=$_POST['fecha_final'];
$query = mysqli_query($conexion, "SELECT v.*, c.idcliente, c.nombre FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente WHERE v.estado_venta = 0 AND v.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_final 23:59:59'");
?>

<table border="1">
    <thead >
    <tr>
        <th>Codigo venta</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Usuario</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Descuento</th>
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
            <td><?php
                $separar = (explode(" ",$row['fecha']));

                $fecha = $separar[0];
                echo $fecha; ?></td>
            <td><?php
                $hora = $separar[1];
                echo $hora; ?></td>
            <td><?php
                $id_usuario= $row['id_usuario'];
                $query1 = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id_usuario ");
                $result1 = mysqli_num_rows($query1);

                if ($result1 > 0) {
                while ($data = mysqli_fetch_assoc($query1)) {
                    echo $data['usuario'];
                }}

                 ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <?php

        $ganancia=0;
        $total_costo=0;
        $id_venta = $row['id'];
        $ventas_1 = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion,d.costo costo FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id_venta");


        while ($data1 = mysqli_fetch_assoc($ventas_1)) {
            $total_costo=$data1['costo']*$data1['cantidad'];
            $ganancia=$ganancia+$data1['total']-$total_costo;
            $ganancia_unitaria=$data1['total']-$total_costo;

        ?>

        <tr>


            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo $data1['descripcion']; ?></td>
            <td><?php echo $data1['cantidad']; ?></td>
            <td><?php echo $data1['precio']; ?></td>
            <td><?php echo $data1['descuento']; ?></td>
            <td><?php echo $ganancia_unitaria; ?></td>
            <td><?php echo $data1['total']; ?></td>


        </tr>
        <?php

        }
        $total_ganancia=$total_ganancia+$ganancia;

        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Subtotal</td>
            <td><?php echo $ganancia; ?></td>
            <td><?php echo $row['total']; ?></td>


        </tr>
    <?php } ?>

    <tr>
        <td>Cantidad ventas:</td>
        <td><?php echo $ventas; ?></td>
        <td></td>
        <td></td>

        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total General:</td>
        <td><?php echo $total_ganancia; ?></td>
        <td><?php echo $total; ?></td>
    </tr>


    </tbody>
</table>