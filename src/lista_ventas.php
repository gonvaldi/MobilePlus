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
//$query = mysqli_query($conexion, "SELECT v.*, c.idcliente, c.nombre FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente");
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-header">
        Historial ventas
    </div>
    <div class="card-body">
        <div class="table-responsive">


            <table style="background: white" id="user_data" class="table table-bordered table-striped">
                <thead style="background: white;border: rgba(14,64,88,0.27) 2px solid">
                <tr>
                    <th style="background: rgba(14,64,88,0.27)" width="10%">#</th>
                    <th style="background: rgba(14,64,88,0.27)" width="10%">Cliente</th>
                    <th style="background: rgba(14,64,88,0.27)" width="10%">Fecha</th>

                    <th style="background: rgba(14,64,88,0.27)" width="10%">Usuario</th>
                    <th style="background: rgba(14,64,88,0.27)" width="10%">Total</th>

                    <th width="10%">PDF</th>
                    <th width="10%">Anular</th>
                </tr>
                </thead>
            </table>



        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>