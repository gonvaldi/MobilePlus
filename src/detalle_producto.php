<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "ventas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
$id_dato=$_GET['id'];
$query = mysqli_query($conexion, "SELECT v.*, c.idcliente, c.nombre FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente WHERE v.id_cliente=$id_dato");
include_once "includes/header.php";
?>
    <div class="card">
        <div class="card-header">
            Lista productos
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-light" id="tbl">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>

                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr >
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>

                            <td><?php echo $row['fecha']; ?></td>
                            <td><?php
                                $id_usuario= $row['id_usuario'];
                                $query1 = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id_usuario ");
                                $result1 = mysqli_num_rows($query1);

                                if ($result1 > 0) {
                                    while ($data = mysqli_fetch_assoc($query1)) {
                                        echo $data['nombre'];
                                    }}

                                ?></td>

                            <td><?php echo $row['total']; ?></td>
                            <td>
                                <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                                <?php if($row['estado_venta']==0){ ?>

                                    <form action="anular_venta.php?id=<?php echo $row['id']; ?>" method="post" class="confirmar d-inline">
                                        <button class="btn btn-dark" type="submit"><i class='fas fa-trash-alt'></i> Anular </button>
                                    </form>
                                <?php  } ?>

                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php include_once "includes/footer.php"; ?>