<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
   $query_delete = mysqli_query($conexion, "UPDATE ventas SET estado_venta = 1 WHERE id = $id");



    $query1 = mysqli_query($conexion, "SELECT * FROM detalle_venta WHERE id_venta = $id");

    while ($row = mysqli_fetch_assoc($query1)) {

        $id_producto=$row['id_producto'];
        $cantidad=$row['cantidad'];

        $query2 = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id_producto");
        $result3 = mysqli_num_rows($query2);
        while ($row1 = mysqli_fetch_assoc($query2)) {
            $total=$row1['existencia']+$cantidad;

        }

        $query_delete1 = mysqli_query($conexion, "UPDATE producto SET existencia = $total WHERE codproducto = $id_producto");

    }

    mysqli_close($conexion);
    header("Location: lista_ventas.php");
}
