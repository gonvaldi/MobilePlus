<?php include('../conexion.php');

$output= array();
$sql = "SELECT v.*, c.idcliente, c.nombre,u.nombre usuario FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente INNER JOIN usuario u ON v.id_usuario = u.idusuario ";

$totalQuery = mysqli_query($conexion,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
	0 => 'id',
	1 => 'nombre',
	2 => 'fecha',
	3 => 'usuario',
	4 => 'total',
);

if(isset($_POST['search']['value']))
{
	$search_value = $_POST['search']['value'];
	$sql .= " WHERE v.id like '%".$search_value."%'";
	$sql .= " OR c.nombre like '%".$search_value."%'";
	$sql .= " OR v.fecha like '%".$search_value."%'";
	$sql .= " OR u.nombre like '%".$search_value."%'";
}

if(isset($_POST['order']))
{
	$column_name = $_POST['order'][0]['column'];
	$order = $_POST['order'][0]['dir'];
	$sql .= " ORDER BY ".$columns[$column_name]." ".$order."";
}
else
{
	$sql .= " ORDER BY id desc";
}

if($_POST['length'] != -1)
{
	$start = $_POST['start'];
	$length = $_POST['length'];
	$sql .= " LIMIT  ".$start.", ".$length;
}

$query = mysqli_query($conexion,$sql);
$count_rows = mysqli_num_rows($query);
$data = array();
while($row = mysqli_fetch_assoc($query))
{
	$sub_array = array();
	$sub_array[] = $row['id'];
	$sub_array[] = $row['nombre'];
	$sub_array[] = $row['fecha'];
	$sub_array[] = $row['usuario'];
	$sub_array[] = $row['total'];
	 $sub_array[] = '   <a href="pdf/generar.php?cl='.$row['id_cliente'].'&v='.$row['id'].'" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>';
    if($row['estado_venta']==0){

        $sub_array[] =  '<form action="anular_venta.php?id='.$row['id'].'" method="post" class="confirmar d-inline">
            <button class="btn btn-dark" type="submit"><i class="fas fa-trash-alt"></i> Anular </button>
        </form> ';
     }
     else{
         $sub_array[] ="";
     }

    $data[] = $sub_array;
}

$output = array(
	'draw'=> intval($_POST['draw']),
	'recordsTotal' =>$count_rows ,
	'recordsFiltered'=>   $total_all_rows,
	'data'=>$data,
);
echo  json_encode($output);
