<?php
require_once "../conexion.php";
session_start();
if (isset($_GET['q'])) {
    $datos = array();
    $nombre = $_GET['q'];
    $cliente = mysqli_query($conexion, "SELECT * FROM cliente WHERE nombre LIKE '%$nombre%'");
    while ($row = mysqli_fetch_assoc($cliente)) {
        $data['id'] = $row['idcliente'];
        $data['label'] = $row['nombre'];
        $data['direccion'] = $row['direccion'];
        $data['telefono'] = $row['telefono'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['pro'])) {
    $datos = array();
    $nombre = $_GET['pro'];
    $hoy = date('Y-m-d');
    $producto = mysqli_query($conexion, "SELECT * FROM producto WHERE existencia >0 AND estado = 0 AND (codigo LIKE '%" . $nombre . "%' OR descripcion LIKE '%" . $nombre . "%'OR codigo_producto LIKE '%" . $nombre . "%') AND (vencimiento > '$hoy' OR vencimiento = '0000-00-00') ORDER BY producto.codigo DESC LIMIT 10");

   while ($row = mysqli_fetch_assoc($producto)) {

if($row['vencimiento']=="0000-00-00"){
    $fecha_vencimiento= "S/V";

}
else{
    $fecha_vencimiento= date("d/m/Y", strtotime($row['vencimiento']));

}

        $data_laboratorio = $row['id_lab'];


        $query_laboratorio = mysqli_query($conexion, "SELECT * FROM laboratorios WHERE id = $data_laboratorio");

        $data_laboratorio_value="";
        while ($row123 = mysqli_fetch_assoc($query_laboratorio)) {

            $data_laboratorio_value = $row123['laboratorio'];
            $data_laboratorio_porcentaje =$row123['valor_porcentaje'];
        }


       $data_tipo=$row['id_tipo'];

       $query_tipo = mysqli_query($conexion, "SELECT * FROM tipos WHERE id = $data_tipo");

       $data_tipo_value="";
       while ($row1234 = mysqli_fetch_assoc($query_tipo)) {

           $data_tipo_value = $row1234['tipo'];
       }




       $sdo_total=$data_laboratorio_porcentaje;
        $precio_inicial=$row['precio'];
        //$cnv =$row['precio'] *= (1 + $sdo_total / 100);

        $data['id'] = $row['codproducto'];
       $data['label'] = $row['descripcion'].' - ' .$data_laboratorio_value. ' - ' .$fecha_vencimiento. ' - ' .$row['estante']. ' - ' .$data_tipo_value. ' Precio:  ' .round($precio_inicial,2);
       $data['value'] = $row['descripcion'].' - LAB ' .$data_laboratorio_value. ' - ' .$fecha_vencimiento. ' - ' .$row['estante']. ' - ' .$data_tipo_value;
        $data['precio'] = $row['costo'];
        $data['precio_final'] =round($precio_inicial,2);
        $data['existencia'] = $row['existencia'];
       $data['minima'] = $row['minima'];
        $data['codigo'] = $row['codigo'];
        $data['laboratorio'] = $row['id_lab'];
        $data['presentacion'] = $row['id_presentacion'];
        $data['tipo'] = $row['id_tipo'];

        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['detalle'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion,p.vencimiento,p.costo,p.detalle,p.id_tipo ,p.id_lab FROM detalle_temp d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_usuario = $id");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['descripcion'] = $row['descripcion'];

        $originalDate1 = $row['vencimiento'];
        if($originalDate1=="0000-00-00"){
            $newDate1="S/V";
        }
        else{
            $newDate1 = date("d/m/Y", strtotime($originalDate1));
        }




        $data_laboratorio = $row['id_lab'];


        $query_laboratorio = mysqli_query($conexion, "SELECT * FROM laboratorios WHERE id = $data_laboratorio");

        $data_laboratorio_value="";
        while ($row123 = mysqli_fetch_assoc($query_laboratorio)) {

            $data_laboratorio_value = $row123['laboratorio'];
        }


        $data_tipo=$row['id_tipo'];

        $query_tipo = mysqli_query($conexion, "SELECT * FROM tipos WHERE id = $data_tipo");

        $data_tipo_value="";
        while ($row1234 = mysqli_fetch_assoc($query_tipo)) {

            $data_tipo_value = $row1234['tipo'];
        }







        $data['fecha'] = $row['detalle'];
        $data['cantidad'] = $row['cantidad'];
        $data['descuento'] = $row['descuento'];
        $data['precio_venta'] = $row['precio_venta'];
        $data['laboratorio'] = $data_laboratorio_value;
        $data['tipo_envase'] = $data_tipo_value;

        $data['costo'] = $row['costo'];
        $data['sub_total'] = $row['total'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['delete_detalle'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['procesarVenta'])) {
    $id_cliente = $_GET['id'];
    $id_user = $_SESSION['idUser'];
    $consulta = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM detalle_temp WHERE id_usuario = $id_user");
    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];
    $insertar = mysqli_query($conexion, "INSERT INTO ventas(id_cliente, total, id_usuario) VALUES ($id_cliente, '$total', $id_user)");
    if ($insertar) {
        $id_maximo = mysqli_query($conexion, "SELECT MAX(id) AS total FROM ventas");
        $resultId = mysqli_fetch_assoc($id_maximo);
        $ultimoId = $resultId['total'];
        $consultaDetalle = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_usuario = $id_user");
        while ($row = mysqli_fetch_assoc($consultaDetalle)) {
            $id_producto = $row['id_producto'];
            $cantidad = $row['cantidad'];
            $desc = $row['descuento'];
            $precio = $row['precio_venta'];
            $total = $row['total'];
            $costo_producto=$row['costo_producto'];
            $insertarDet = mysqli_query($conexion, "INSERT INTO detalle_venta (id_producto, id_venta, cantidad, precio, descuento, total,costo) VALUES ($id_producto, $ultimoId, $cantidad, '$precio', '$desc', '$total','$costo_producto')");
            $stockActual = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id_producto");
            $stockNuevo = mysqli_fetch_assoc($stockActual);
            $stockTotal = $stockNuevo['existencia'] - $cantidad;
            $stock = mysqli_query($conexion, "UPDATE producto SET existencia = $stockTotal WHERE codproducto = $id_producto");
        } 
        if ($insertarDet) {
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id_usuario = $id_user");
            $msg = array('id_cliente' => $id_cliente, 'id_venta' => $ultimoId);
        } 
    }else{
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
}else if (isset($_GET['descuento'])) {
    $id = $_GET['id'];
    $desc = $_GET['desc'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    $total_desc = $desc + $result['descuento'];
    $total = $result['total'] - $desc;
    $insertar = mysqli_query($conexion, "UPDATE detalle_temp SET descuento = $total_desc, total = '$total'  WHERE id = $id");
    if ($insertar) {
        $msg = array('mensaje' => 'descontado');
    }else{
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
}else if(isset($_GET['editarCliente'])){
    $idcliente = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarUsuario'])) {
    $idusuario = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $idusuario");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarProducto'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarTipo'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM tipos WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarPresent'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM presentacion WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarLab'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM laboratorios WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
}
if (isset($_POST['regDetalle'])) {
    $id = $_POST['id'];
    $cant = $_POST['cant'];
    $precio = $_POST['precio'];
    $id_user = $_SESSION['idUser'];
    $total = $precio * $cant;
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_producto = $id AND id_usuario = $id_user");
    $result = mysqli_num_rows($verificar);
    $datos = mysqli_fetch_assoc($verificar);



    $producto = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id");

    $costo_producto=0;
    while ($row12 = mysqli_fetch_assoc($producto)) {
    $valor_cantidad=$row12['existencia'];
        $costo_producto=$row12['costo'];
    }


    if ($result > 0) {
        $cantidad_1 = $datos['cantidad'] + $cant;

        if($cantidad_1 > $valor_cantidad){
            $msg = "Cantidad mayor que del stock";
        }
        else{
            $cantidad = $datos['cantidad'] + $cant;
            $total_precio = ($cantidad * $precio);
            $query = mysqli_query($conexion, "UPDATE detalle_temp SET cantidad = $cantidad, total = '$total_precio' WHERE id_producto = $id AND id_usuario = $id_user");
            if ($query) {
                $msg = "actualizado";
            } else {
                $msg = "Error al ingresar";
            }

        }

    }else{
        $query = mysqli_query($conexion, "INSERT INTO detalle_temp(id_usuario, id_producto, cantidad ,precio_venta, total,costo_producto) VALUES ($id_user, $id, $cant,'$precio', '$total', '$costo_producto')");
        if ($query) {
            $msg = "registrado";
        }else{
            $msg = "Error al ingresar";
        }
    }
    echo json_encode($msg);
    die();
}else if (isset($_POST['cambio'])) {
    if (empty($_POST['actual']) || empty($_POST['nueva'])) {
        $msg = 'Los campos estan vacios';
    } else {
        $id = $_SESSION['idUser'];
        $actual = md5($_POST['actual']);
        $nueva = md5($_POST['nueva']);
        $consulta = mysqli_query($conexion, "SELECT * FROM usuario WHERE clave = '$actual' AND idusuario = $id");
        $result = mysqli_num_rows($consulta);
        if ($result == 1) {
            $query = mysqli_query($conexion, "UPDATE usuario SET clave = '$nueva' WHERE idusuario = $id");
            if ($query) {
                $msg = 'ok';
            }else{
                $msg = 'error';
            }
        } else {
            $msg = 'dif';
        }
        
    }
    echo $msg;
    die();
    
}
else if (isset($_GET['pro_data'])) {
    $datos = array();
    $nombre = $_GET['pro_data'];
    $hoy = date('Y-m-d');
    $producto = mysqli_query($conexion, "SELECT *  FROM producto WHERE  estado = 0 AND codproducto LIKE '%" . $nombre . "%' or codigo LIKE '%" . $nombre . "%' OR descripcion LIKE '%" . $nombre . "%' ORDER BY producto.codigo DESC LIMIT 8 ");

    while ($row = mysqli_fetch_assoc($producto)) {

        if(empty($row['vencimiento'])){
            $fecha_vencimiento= "S/V";

        }
        else{
            $fecha_vencimiento= date("d/m/Y", strtotime($row['vencimiento']));

        }

        $data_laboratorio = $row['id_lab'];


        $query_laboratorio = mysqli_query($conexion, "SELECT * FROM laboratorios WHERE id = $data_laboratorio");

        $data_laboratorio_value="";
        while ($row123 = mysqli_fetch_assoc($query_laboratorio)) {

            $data_laboratorio_value = $row123['laboratorio'];
        }


        $data_tipo=$row['id_tipo'];

        $query_tipo = mysqli_query($conexion, "SELECT * FROM tipos WHERE id = $data_tipo");

        $data_tipo_value="";
        while ($row1234 = mysqli_fetch_assoc($query_tipo)) {

            $data_tipo_value = $row1234['tipo'];
        }



        $precio_inicial=$row['precio'];


        $data['id'] = $row['codproducto'];
        $data['label'] = $row['descripcion'].' - ' .$data_laboratorio_value. ' - ' .$fecha_vencimiento. ' - ' .$row['estante']. ' - ' .$data_tipo_value. ' Precio:  ' .round($precio_inicial,2);

        $data['value'] = $row['descripcion'];
        $data['precio'] = $row['costo'];
        $data['precio_venta'] = $row['precio'];
        $data['existencia'] = $row['existencia'];

        $data['fecha_registro'] = $row['fecha_registro'];

        $data['codigo'] = $row['codigo'];
        $data['minima'] = $row['minima'];
        $data['codigo_producto'] = $row['codigo_producto'];
        $data['laboratorio'] = $row['id_lab'];
        $data['presentacion'] = $row['id_presentacion'];
        $data['tipo'] = $row['id_tipo'];
        $data['estante'] = $row['estante'];

        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}

else if (isset($_GET['pro_tipo'])) {
    $datos = array();
    $nombre = $_GET['pro_tipo'];

    $producto = mysqli_query($conexion, "SELECT *  FROM tipos WHERE  estado = 0 AND tipo LIKE '%" . $nombre . "%' ORDER BY tipos.id DESC LIMIT 5");

    while ($row = mysqli_fetch_assoc($producto)) {


        $data['tipo'] = $row['id'];
        $data['label'] = $row['tipo'];


        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}


else if (isset($_GET['pro_presentacion'])) {
    $datos = array();
    $nombre = $_GET['pro_presentacion'];

    $producto = mysqli_query($conexion, "SELECT *  FROM presentacion WHERE  estado = 0 AND nombre LIKE '%" . $nombre . "%' ORDER BY presentacion.id DESC LIMIT 5");

    while ($row = mysqli_fetch_assoc($producto)) {


        $data['tipo'] = $row['id'];
        $data['label'] = $row['nombre'];


        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}



else if (isset($_GET['pro_laboratorio'])) {
    $datos = array();
    $nombre = $_GET['pro_laboratorio'];

    $producto = mysqli_query($conexion, "SELECT *  FROM laboratorios WHERE  estado = 0 AND laboratorio LIKE '%" . $nombre . "%' ORDER BY laboratorios.id DESC LIMIT 5");

    while ($row = mysqli_fetch_assoc($producto)) {


        $data['tipo'] = $row['id'];
        $data['label'] = $row['laboratorio'];


        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}