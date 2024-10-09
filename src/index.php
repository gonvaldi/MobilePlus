<?php
require "../conexion.php";
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
$total['usuarios'] = mysqli_num_rows($usuarios);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente");
$total['clientes'] = mysqli_num_rows($clientes);
$productos = mysqli_query($conexion, "SELECT * FROM producto");
$total['productos'] = mysqli_num_rows($productos);
$ventas = mysqli_query($conexion, "SELECT * FROM ventas WHERE estado_venta=0 AND fecha > CURDATE()");
$total['ventas'] = mysqli_num_rows($ventas);
session_start();
include_once "includes/header.php";
?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-user fa-2x"></i>
                </div>
                <a href="usuarios.php" class="card-category text-warning font-weight-bold">
                    Usuarios
                </a>
                <h3 class="card-title"><?php echo $total['usuarios']; ?></h3>
            </div>
            <div class="card-footer bg-warning text-white">
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <a href="clientes.php" class="card-category text-success font-weight-bold">
                    Clientes
                </a>
                <h3 class="card-title"><?php echo $total['clientes']; ?></h3>
            </div>
            <div class="card-footer bg-secondary text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <div class="card-icon">
                    <i class="fab fa-product-hunt fa-2x"></i>
                </div>
                <a href="productos.php" class="card-category text-danger font-weight-bold">
                    Productos
                </a>
                <h3 class="card-title"><?php echo $total['productos']; ?></h3>
            </div>
            <div class="card-footer bg-primary">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-cash-register fa-2x"></i>
                </div>
                <a  class="card-category text-info font-weight-bold">
                    Ventas del dia
                </a>
                <h3 class="card-title"><?php echo $total['ventas']; ?></h3>
            </div>
            <div class="card-footer bg-danger text-white">
            </div>
        </div>
    </div>
    <div class="card">
        <?php

         if( $_SESSION['idUser']==1){
        ?>
        <div class="card-header" align="right">

            <form action="reporte_venta_fecha_detallado.php" method="post" target="_blank">

                <p>Reporte de venta detallado del : <input type="date" name="fecha_inicio" required> al <input type="date" required name="fecha_final"> <input type="submit" value="Enviar datos"></p>

            </form>



        </div>

        <?php

        } ?>



        <nav style="display: none">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                    Lista de Productos por vencer a 3 meses</a>

                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                    Stock minimo</a>
             </div>
        </nav>
        <div style="border: 2px solid rgba(46,88,50,0.27);background-color: #d9edf7;display: none" class="tab-content" id="nav-tabContent" >
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-danger table-bordered" id="tbl">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Accion Teraupetica</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Vence</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            include "../conexion.php";
                            $date_now = date('d-m-Y');
                            $date_now_base = date('Y-m-d');
                            $date_past = strtotime('+96 day', strtotime($date_now));
                            $hoy = date('Y-m-d', $date_past);



                            //$hoy = date('Y-m-d');
                            $query = mysqli_query($conexion, "SELECT p.*, t.tipo, pr.nombre FROM producto p INNER JOIN tipos t ON p.id_tipo = t.id INNER JOIN presentacion pr ON p.id_presentacion = pr.id WHERE p.estado = 0 and p.vencimiento != '0000-00-00' AND p.vencimiento BETWEEN '$date_now_base' AND '$hoy'");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $data['codproducto']; ?></td>
                                        <td><?php echo $data['codigo']; ?></td>
                                        <td><?php echo $data['descripcion']; ?></td>
                                        <td><?php echo $data['tipo']; ?></td>
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['precio']; ?></td>
                                        <td><?php echo $data['existencia']; ?></td>
                                        <td><?php


                                            $date1 = new DateTime($date_now);
                                            $date2 = new DateTime($data['vencimiento']);
                                            $diff = $date2->diff($date1);
                                            // will output 2 days
                                            echo $diff->days . ' dias ';

                                            ?></td>
                                        <td>
                                            <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                            </tbody>

                        </table>
                    </div>
                </div>


                </div>
            <div style="background-color: #cccccc" class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-danger table-bordered" id="tb_13">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Accion Teraupetica</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Vence</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php





                            //$hoy = date('Y-m-d');
                            $query1 = mysqli_query($conexion, "SELECT p.*, t.tipo, pr.nombre FROM producto p INNER JOIN tipos t ON p.id_tipo = t.id INNER JOIN presentacion pr ON p.id_presentacion = pr.id WHERE p.estado = 0 and existencia  <=10 ");
                            $result1 = mysqli_num_rows($query1);
                            if ($result1 > 0) {
                                while ($data1 = mysqli_fetch_assoc($query1)) { ?>
                                    <tr>
                                        <td><?php echo $data1['codproducto']; ?></td>
                                        <td><?php echo $data1['codigo']; ?></td>
                                        <td><?php echo $data1['descripcion']; ?></td>
                                        <td><?php echo $data1['tipo']; ?></td>
                                        <td><?php echo $data1['nombre']; ?></td>
                                        <td><?php echo $data1['precio']; ?></td>
                                        <td><?php echo $data1['existencia']; ?></td>
                                        <td><?php

echo $data1['vencimiento'];
                                            ?></td>


                                    </tr>
                                <?php }
                            } ?>
                            </tbody>

                        </table>
                    </div>
                </div>



            </div>


        </div>




    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Productos con stock mínimo</h3>
            </div>
            <div class="card-body">
                <canvas id="stockMinimo"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Productos más vendidos</h3>
            </div>
            <div class="card-body">
                <canvas id="ProductosVendidos"></canvas>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>
