
<?php
require_once "../conexion.php";

$id = $_GET['id'];
$sql = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id");


?>

<?php
while ($row = mysqli_fetch_assoc($sql)) {
?>
<h2 style="color: #2e6c80;">&nbsp;</h2>
<table class="editorDemoTable" style="height: 414px;border: 1px solid" >
    <thead>
    <tr style="height: 18px;">
        <td style="width: 206.205px; height: 18px; text-align: center;border-right: 1px solid"><strong >MODELO <?php echo $row['descripcion']; ?></strong></td>
        <td   colspan="2"style="width: 320px; height: 18px; text-align: center;"><strong style="font-size: 24px">MODELO <?php echo $row['descripcion']; ?></strong></td>

    </tr>
    </thead>
    <tbody>
    <tr style="height: 38px;">
        <td style="width: 206.205px; height: 38px;border-right: 1px solid">
            <center>
            S/N: <?php echo $row['codigo']; ?>
            </center>
        </td>

        <td   rowspan="2" >
            <center>

             <textarea cols="32" rows="10"  style="border: none;font-size: 16px">
                 DESCRIPCIÓN:

<?php echo $row['detalle']; ?>
           </textarea>

            </center>
        </td>
        <td style="width: 50.6875px; height: 38px;">  <center>
                <strong style="font-size: 24px;color: #a94442">
                    PRECIO:
                </br>
                <?php echo $row['precio']; ?> Bs
                </strong>
            </center>
        </td>
    </tr>
    <tr style="height: 22px;">
        <td style="width: 206.205px; height: 22px;border-right: 1px solid">
            <center>Modelo:</center>
            <img src="barcode.php?text=<?php echo $row['codigo_producto']; ?>&size=40&codetype=Code39&print=true" />

        </td>

        <td style="width: 50.6875px; height: 22px;">


            <center>Producto:</center>
            <img src="barcode.php?text=<?php echo $row['codigo']; ?>&size=40&codetype=Code39&print=true" />

        </td>
    </tr>
    <tr style="height: 22px;">
        <td  style="width: 206.205px; height: 22px;border-right: 1px solid"><center>Producto:</center>
            <img src="barcode.php?text=<?php echo $row['codigo']; ?>&size=40&codetype=Code39&print=true" />

        </td>
        <td colspan="2" style="width: 600px; height: 22px;">

            <center>      <b style="font-size: 19px;font-family:'Roboto', helvetica, arial, sans-serif "> “No vas a encontrarlo en un precio mejor” </b>  </center>


        </td>
    </tr>


    </tbody>
</table>


    <table class="editorDemoTable" style="height: 414px;border: 1px solid" >
        <thead>
        <tr style="height: 18px;">
            <td style="width: 206.205px; height: 18px; text-align: center;border-right: 1px solid"><strong >MODELO <?php echo $row['descripcion']; ?></strong></td>
            <td   colspan="2"style="width: 320px; height: 18px; text-align: center;"><strong style="font-size: 24px">MODELO <?php echo $row['descripcion']; ?></strong></td>

        </tr>
        </thead>
        <tbody>
        <tr style="height: 38px;">
            <td style="width: 206.205px; height: 38px;border-right: 1px solid">
                <center>
                    S/N: <?php echo $row['codigo']; ?>
                </center>
            </td>

            <td   rowspan="2" >
                <center>

             <textarea cols="32" rows="10"  style="border: none;font-size: 16px">
                 DESCRIPCIÓN:

<?php echo $row['detalle']; ?>
           </textarea>

                </center>
            </td>
            <td style="width: 50.6875px; height: 38px;">  <center>
                    <strong style="font-size: 24px;color: #a94442">
                        PRECIO:
                        </br>
                        <?php echo $row['precio']; ?> Bs
                    </strong>
                </center>
            </td>
        </tr>
        <tr style="height: 22px;">
            <td style="width: 206.205px; height: 22px;border-right: 1px solid">
                <center>Modelo:</center>
                <img src="barcode.php?text=<?php echo $row['codigo_producto']; ?>&size=40&codetype=Code39&print=true" />

            </td>

            <td style="width: 50.6875px; height: 22px;">


                <center>Producto:</center>
                <img src="barcode.php?text=<?php echo $row['codigo']; ?>&size=40&codetype=Code39&print=true" />

            </td>
        </tr>
        <tr style="height: 22px;">
            <td  style="width: 206.205px; height: 22px;border-right: 1px solid"><center>Producto:</center>
                <img src="barcode.php?text=<?php echo $row['codigo']; ?>&size=40&codetype=Code39&print=true" />

            </td>
            <td colspan="2" style="width: 600px; height: 22px;">

                <center>      <b style="font-size: 19px;font-family:'Roboto', helvetica, arial, sans-serif "> “No vas a encontrarlo en un precio mejor” </b>  </center>


            </td>
        </tr>


        </tbody>
    </table>




<p><strong>&nbsp;</strong></p>


<?php }  ?>





<script type="text/javascript">
    window.print();
</script>