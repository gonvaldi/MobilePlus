
<?php

date_default_timezone_set('America/La_Paz');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=stock_minimo_".date("d-m-Y").".xls");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
require_once "../conexion.php";

$id_user = $_SESSION['idUser'];
$query = mysqli_query($conexion, "SELECT DISTINCT  v.descripcion,v.id_lab,v.id_tipo ,c.laboratorio laboratorio,u.tipo tipo,p.nombre presentacion FROM producto v INNER JOIN laboratorios c ON v.id_lab = c.id INNER JOIN  tipos u ON v.id_tipo = u.id INNER JOIN  presentacion p ON v.id_presentacion = p.id   WHERE v.existencia > 0  order by v.descripcion");


//$query = mysqli_query($conexion, "SELECT v.*, c.laboratorio laboratorio,u.tipo tipo,p.nombre presentacion FROM producto v INNER JOIN laboratorios c ON v.id_lab = c.id INNER JOIN  tipos u ON v.id_tipo = u.id INNER JOIN  presentacion p ON v.id_presentacion = p.id   WHERE v.existencia > 0 and v.existencia <=  v.minima order by v.descripcion");

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


        <th>Precio de Venta</th>
        <th>Stock</th>
        <th>Stock Minimo</th>




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

        $name_producto=$row['descripcion'];
        $name_lab=$row['id_lab'];
        $name_tipo=$row['id_tipo'];

        $query1 = mysqli_query($conexion, "SELECT codigo_producto,codigo,precio,minima, SUM(existencia) total FROM producto WHERE descripcion = '$name_producto' and id_lab = $name_lab and id_tipo = $name_tipo  order by descripcion");



    while ($row1 = mysqli_fetch_assoc($query1)) {

        $codigo_producto=$row1['codigo_producto'];
        $precio_producto=$row1['precio'];
        $minima=$row1['minima'];
        $codigo=$row1['codigo'];
        $total=$row1['total'];

    }
             if($total <= $minima ){
        ?>
        <tr>

            <td><?php echo $codigo_producto; ?></td>
            <td><?php echo $codigo; ?></td>
            <td><?php echo TildesHtml($row['descripcion']);  ?></td>
            <td><?php echo TildesHtml($row['laboratorio']); ?></td>
            <td><?php echo TildesHtml($row['presentacion']); ?></td>
            <td><?php echo TildesHtml($row['tipo']);  ?></td>



            <td><?php echo $precio_producto;  ?></td>

            <td><?php echo $total;  ?></td>
            <td><?php echo $minima;  ?></td>


        </tr>




    <?php }


    } ?>




    </tbody>
</table>