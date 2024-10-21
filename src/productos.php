<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
if (!empty($_POST)) {
    $alert = "";
    $id = $_POST['id'];
    $codigo = $_POST['codigo'];
    $codigo_producto = $_POST['codigo_producto'];
    $producto = $_POST['producto_name'];
    $precio = $_POST['precio'];
    $precio_venta = $_POST['precio_venta'];
    $IMEI = $_POST['IMEI'];
    $IMEI2 = $_POST['IMEI2'];
    $cantidad = $_POST['cantidad'];
    $tipo = $_POST['tipo'];
    $presentacion = $_POST['presentacion'];
    $laboratorio = $_POST['laboratorio'];
    $lote = $_POST['lote'];
    $estante = $_POST['estante'];
    $fecha_registro  = $_POST['fecha_registro'];
    $minima  = $_POST['minima'];
    $detalle  = $_POST['detalle'];

    $reg_producto = $_POST['reg_producto'];


    $sdo_total=0;
    $cnv =$precio *= (1 + $sdo_total / 100);


    $vencimiento = '0000-00-00';
    if (!empty($_POST['accion'])) {
        $vencimiento = $_POST['vencimiento'];
    }
    if ( empty($producto) || empty($tipo) || empty($presentacion) || empty($laboratorio)  || empty($precio) || $precio <  0 ||  $cantidad <  -1 | empty($IMEI) ) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        if ($reg_producto=="registrar") {


            if ($id != null) {

            $query1 = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id");


            while ($row1 = mysqli_fetch_assoc($query1)) {
                $name_producto=$row1['descripcion'];
                $name_lab=$row1['id_lab'];
                $name_tipo=$row1['id_tipo'];

            }
            $query_update1 = mysqli_query($conexion, "UPDATE producto SET minima= '$minima', precio = '$precio_venta' WHERE descripcion = '$name_producto' and id_lab = $name_lab and id_tipo = $name_tipo ");

            }


            $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,descripcion,precio,IMEI, IMEI2,existencia,id_lab,id_presentacion,id_tipo, vencimiento, lote, costo,fecha_registro,cantidad_registro,codigo_producto,estante,minima,detalle) values ('$codigo', '$producto', '$precio_venta', '$IMEI', '$IMEI2', '$cantidad', $laboratorio, $presentacion, $tipo, '$vencimiento', '$lote', '$precio', '$fecha_registro', '$cantidad', '$codigo_producto','$estante','$minima','$detalle')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto registrado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el producto
                  </div>';
                }

        } else {

            $query1 = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id");


            while ($row1 = mysqli_fetch_assoc($query1)) {
            $name_producto=$row1['descripcion'];
                $name_lab=$row1['id_lab'];
                $name_tipo=$row1['id_tipo'];

                $name_cantidad=$row1['existencia'];
                $name_precio=$row1['precio'];


            }


            $query_insert3 = mysqli_query($conexion, "INSERT INTO bitacora_producto(id_producto,cantidad_actual,cantidad_modificado,precio_actual,precio_modificado,vencimiento_modificado,usuario) values ( '$id', '$name_cantidad', '$cantidad', '$name_precio', '$precio_venta', '$vencimiento', '$id_user')");


           // $query_update1 = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', id_tipo= $tipo, id_presentacion= $presentacion, id_lab= $laboratorio,codigo_producto= '$codigo_producto',minima= '$minima', precio = '$precio_venta' WHERE descripcion = '$name_producto' and id_lab = $name_lab and id_tipo = $name_tipo ");


            $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', id_tipo= $tipo, id_presentacion= $presentacion, id_lab= $laboratorio, precio= $precio_venta, IMEI= $IMEI, IMEI2 = $IMEI2 existencia = $cantidad, vencimiento = '$vencimiento' , lote = '$lote' ,fecha_registro= '$fecha_registro',codigo_producto= '$codigo_producto',costo= '$precio',estante= '$estante',minima= '$minima',detalle= '$detalle' WHERE codproducto = $id");

            if ($query_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
        }
    }
}
include_once "includes/header.php";
?>
<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        Productos
                    </div>
                    <div class="card-body" id="fondo_registro" style="background-color: white; text-align:center">

                        <?php echo isset($alert) ? $alert : ''; ?>
                        <form action="" method="post" autocomplete="off" id="formulario">

                            Fecha registro     <input type="date" required id="fecha_registro" class="form-control"  name="fecha_registro">


                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group"  style="background-color: #bbe1bd">
                                        <label for="codigo" style="font-size: large" class=" text-dark font-weight-bold"><i class="fas fa-barcode"></i> S/N Producto</label>
                                        <input type="text"  placeholder="Ingrese S/N producto" name="codigo" id="codigo" class="form-control">

                                        <input type="hidden" id="id" name="id">
                                        <input type="hidden" id="reg_producto" value="registrar" name="reg_producto">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group" style="background-color: #d9edf7">
                                        <label for="codigo"  style="font-size: large" class=" text-dark font-weight-bold"> S/N Modelo</label>
                                        <input type="text" placeholder="Ingrese S/N modelo" name="codigo_producto" id="codigo_producto" class="form-control">

                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group" style="background-color: #a9d7fb">
                                        <label for="producto"  style="font-size: large" class=" text-dark font-weight-bold">Modelo</label>
                                        <input type="text" placeholder="Ingrese nombre del producto" name="producto_name" id="producto_name" class="form-control">

                                    </div>
                                </div>


                                <div class="col-md-3" style="border: 3px solid rgba(14,64,88,0.27)">
                                    <div class="form-group">
                                        <label for="detalle" class=" text-dark font-weight-bold">Descripción</label>
                                        <textarea   rows="8"  placeholder="Ingrese descripción" class="form-control" name="detalle" id="detalle">
Procesador:
RAM:
Almacenamiento interno:
Cámara:
Sistema Operativo:
                                        </textarea>
                                    </div>
                                </div>


                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="precio" class=" text-dark font-weight-bold">Precio Compra</label>
                                        <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="precio_venta" class=" text-dark font-weight-bold">Precio Venta</label>
                                        <input type="text" placeholder="Ingrese precio" class="form-control" name="precio_venta" id="precio_venta">
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="precio" class=" text-dark font-weight-bold">IMEI 1</label>
                                        <input type="text" placeholder="Ingrese IMEI" class="form-control" name="IMEI" id="IMEI">
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="precio_venta" class=" text-dark font-weight-bold">IMEI 2</label>
                                        <input type="text" placeholder="Ingrese IMEI" class="form-control" name="IMEI2" id="IMEI2">
                                    </div>
                                </div>

                                <div class="col-md-1" style="display: none">
                                    <div class="form-group">
                                        <label for="cantidad" class=" text-dark font-weight-bold">Stock</label>
                                        <input type="number" value="1" required placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="tipo" class=" text-dark font-weight-bold">TIPO</label>
                                        <select readonly="" id="tipo" class="form-control" name="tipo" required>
                                            <?php
                                            $query_tipo = mysqli_query($conexion, "SELECT * FROM tipos WHERE estado = 0");
                                            while ($datos = mysqli_fetch_assoc($query_tipo)) { ?>
                                                <option value="<?php echo $datos['id'] ?>"><?php echo $datos['tipo'] ?></option>
                                            <?php } ?>
                                        </select>


                                    </div>
                                </div>
                                <div  class="col-md-1">
                                    <div class="form-group">
                                        <label for="presentacion" class="text-dark font-weight-bold">TAMAÑO</label>

                                        <select readonly="" id="presentacion" class="form-control" name="presentacion" >
                                            <?php
                                            $query_pre = mysqli_query($conexion, "SELECT * FROM presentacion WHERE estado =0");
                                            while ($datos = mysqli_fetch_assoc($query_pre)) { ?>
                                                <option value="<?php echo $datos['id'] ?>"><?php echo $datos['nombre'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="laboratorio" class="text-dark font-weight-bold">MARCA</label>
                                        <select readonly="" id="laboratorio" class="form-control" name="laboratorio" required>
                                            <?php
                                            $query_lab = mysqli_query($conexion, "SELECT * FROM laboratorios WHERE estado =0");
                                            while ($datos = mysqli_fetch_assoc($query_lab)) { ?>
                                                <option value="<?php echo $datos['id'] ?>"><?php echo $datos['laboratorio'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" style="display: none">
                                    <div class="form-group">
                                        <input id="accion" class="form-check-input" type="checkbox" name="accion" value="si">
                                        <label for="vencimiento">Vencimiento</label>
                                        <input id="vencimiento" class="form-control" type="date" name="vencimiento">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="lote" class=" text-dark font-weight-bold">Garantia</label>
                                        <input type="text" placeholder="Lote" value="1 año" class="form-control" name="lote" id="lote">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="lote" class=" text-dark font-weight-bold">Estante</label>
                                        <input type="text" placeholder="Estante" class="form-control" name="estante" id="estante">
                                    </div>
                                </div>


                                <div class="col-md-2" style="display: none">
                                    <div class="form-group">
                                        <label for="lote" class=" text-dark font-weight-bold">Cantidad minima</label>
                                        <input type="text" placeholder="Minima" class="form-control" name="minima" id="minima">
                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                    <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>


        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                    Lista de Productos en stock</a>

                <a class="nav-item nav-link" style="color: #a94442;border: 1px solid #a94442" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                    Lista de Productos vendidos</a>
            </div>
        </nav>


    <div style="border: 2px solid rgba(46,88,50,0.27);background-color: #d9edf7" class="tab-content" id="nav-tabContent">




        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">



        <div class="col-md-12">

            <div class="table-responsive">


                <table style="background: white" id="user_data_producto" class="table table-bordered table-striped">
                    <thead style="background: white;border: rgba(14,64,88,0.27) 2px solid">
                    <tr>
                        <th style="background: rgba(14,64,88,0.27)" width="10%">#</th>
                        <th style="background: rgba(14,64,88,0.27)" width="10%">S/N Prod.</th>
                        <th style="background: rgba(14,64,88,0.27)" width="10%">S/N Mod.</th>
                        <th style="background: rgba(14,64,88,0.27)" width="10%">Modelo</th>
						   <th style="background: rgba(14,64,88,0.27)" width="10%">Tipo</th>
                        <th style="background: rgba(14,64,88,0.27)" width="10%">Tamaño</th>
                        <th style="background: rgba(14,64,88,0.27)" width="10%">Marca</th>
                        <th style="background: rgba(14,64,88,0.27)" width="10%">Precio</th>

                        <th style="background: rgba(14,64,88,0.27)" width="10%">Descripción</th>
                        <th style="background: rgba(14,64,88,0.27)" width="10%">Estante</th>
                        <th width="10%"></th>

                    </tr>
                    </thead>
                </table>



            </div>




        </div>


        </div>


        <div style="background-color: #cccccc" class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

            <div class="col-md-12">

                <div class="table-responsive">


                    <table  style="background: white;width: 100%" id="user_data_producto1" class="table table-bordered table-striped">
                        <thead style="background: white;border: rgba(14,64,88,0.27) 2px solid">
                        <tr>
                            <th style="background: rgba(14,64,88,0.27)" width="10%">#</th>
                            <th style="background: rgba(14,64,88,0.27)" width="10%">S/N Prod.</th>
                            <th style="background: rgba(14,64,88,0.27)" width="10%">S/N Mod.</th>
                            <th style="background: rgba(14,64,88,0.27)" width="10%">Modelo</th>
                            <th style="background: rgba(14,64,88,0.27)" width="10%">Tipo</th>
                            <th style="background: rgba(14,64,88,0.27)" width="10%">Tamaño</th>
                            <th style="background: rgba(14,64,88,0.27)" width="10%">Marca</th>
                            <th style="background: rgba(14,64,88,0.27)" width="10%">Precio</th>

                            <th style="background: rgba(14,64,88,0.27)" width="10%">Descripción</th>
                            <th style="background: rgba(14,64,88,0.27)" width="10%">Estante</th>
                            <th width="10%"></th>

                        </tr>
                        </thead>
                    </table>



                </div>




            </div>


        </div>


    </div>

</div>
<?php include_once "includes/footer.php"; ?>