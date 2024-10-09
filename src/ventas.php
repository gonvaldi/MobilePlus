<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "nueva_venta";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";
?>
<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" id="idcliente" value="1" name="idcliente" required>
                                <label >Nombre del cliente</label>
                                <input type="text" name="nom_cliente" id="nom_cliente" class="form-control" placeholder="Ingrese nombre del cliente" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CI</label>
                                <input type="text" name="dir_cliente" id="dir_cliente" class="form-control" disabled required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="number" name="tel_cliente" id="tel_cliente" class="form-control" disabled required>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                Buscar Productos
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="producto">Código o Nombre</label>
                            <input id="producto" class="form-control" type="text" name="producto" placeholder="Ingresa el código o nombre">
                            <input id="id" type="hidden" name="id">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="precio" style="font-size: large">Precio <span id="precio_base" style="color: red;display: none" >0</span></label>
                            <input id="precio" class="form-control" type="text" name="precio" onkeyup="calcularPrecio(event)" placeholder="precio" >
                        </div>
                    </div>
                    <div class="col-lg-2" style="display: none">
                        <div class="form-group">
                            <label for="cantidad" style="font-size: large">Disp:<span id="existencia" name="existencia" style="color: red" >0</span>
                            </label>
                            <input id="cantidad" class="form-control" type="text" name="cantidad" placeholder="Cantidad"  onkeyup="calcularPrecio(event)">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="sub_total">Sub Total</label>
                            <input id="sub_total" class="form-control" type="text" name="sub_total" placeholder="Sub Total" disabled>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="tblDetalle">
                <thead class="thead-dark">
                    <tr>
                        <th>Modelo</th>
                        <th>Detalle</th>

                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Aplicar</th>
                        <th>Desc</th>

                        <th>Precio venta</th>
                        <th>Precio Total</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody id="detalle_venta">

                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td>Total Pagar</td>
                        <td id="total_pagar"></td>
                    </tr>
                </tfoot>
            </table>


            <div class="row " style="background-color: white;padding-left: 20px">
                <div class="col-md-4">
                    <div class="form-group">
                        <label style="color: #0a6ebd" >Monto</label>
                        <input type="number" onkeyup="dar_cambio()" name="monto_cambio" id="monto_cambio" class="form-control" placeholder="cambio" >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label  style="color: #0a6ebd">Cambio</label>
                        <input type="number" name="cambio" id="cambio" class="form-control" disabled required>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <div align="right" class="col-md-12">
        <a href="#" class="btn btn-primary" id="btn_generar"><i class="fas fa-save"></i> Registrar Venta</a>
    </div>

</div>
<?php include_once "includes/footer.php"; ?>