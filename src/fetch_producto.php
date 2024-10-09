<?php include('../conexion.php');

$output= array();
$sql = "SELECT v.*, c.laboratorio laboratorio,u.tipo tipo,p.nombre presentacion FROM producto v INNER JOIN laboratorios c ON v.id_lab = c.id INNER JOIN  tipos u ON v.id_tipo = u.id INNER JOIN  presentacion p ON v.id_presentacion = p.id WHERE v.existencia = 1 ";


$totalQuery = mysqli_query($conexion,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
	0 => 'codproducto ',
	1 => 'codigo',
	2 => 'codigo_producto',
	3 => 'descripcion',
	4 => 'tipo',
	5 => 'presentacion',
	6 => 'laboratorio',
    7 => 'precio',
    9 => 'vencimiento',
    10 => 'cantidad_registro',
    10 => 'estante'
);

if(isset($_POST['search']['value']))
{
	$search_value = $_POST['search']['value'];
	$sql .= " and (v.codproducto like '%".$search_value."%'";
	$sql .= " OR v.codigo  like '%".$search_value."%'";
	$sql .= " OR v.codigo_producto like '%".$search_value."%'";

    $sql .= " OR v.descripcion like '%".$search_value."%'";

    $sql .= " OR laboratorio like '%".$search_value."%'";
    $sql .= " OR v.precio like '%".$search_value."%'";
	$sql .= " OR tipo like '%".$search_value."%'";
    $sql .= " OR v.vencimiento like '%".$search_value."%'";
	    $sql .= " OR p.nombre like '%".$search_value."%'";
    $sql .= " OR v.estante like '%".$search_value."%')";


}

if(isset($_POST['order']))
{
	$column_name = $_POST['order'][0]['column'];
	$order = $_POST['order'][0]['dir'];
	$sql .= " ORDER BY ".$columns[$column_name]." ".$order."";
}
else
{
	$sql .= " ORDER BY codproducto desc";
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
    if($row['estado']==0){


        $sub_array = array();
        $sub_array[] = $row['codproducto'];
        $sub_array[] = $row['codigo'];
        $sub_array[] = $row['codigo_producto'];
        $sub_array[] = $row['descripcion'];
       
     
        $sub_array[] = $row['tipo'];
		 $sub_array[] = $row['presentacion'];
		 $sub_array[] = $row['laboratorio'];
		 $sub_array[] = $row['precio'];

        $sub_array[] = $row['detalle'];
        $sub_array[] = $row['estante'];


        $sub_array[] = '   <a href="#"  onclick="editarProducto('.$row['codproducto'].')" class="btn btn-primary"><i class="fas fa-edit"></i></a>
   
   <a href="#"  onclick="preguntar_eliminar('.$row['codproducto'].')" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
     <a href="pdf_producto.php?id='.$row['codproducto'].'"   target="_blank" class="btn btn-default"><i class="fas fa-print"></i></a>
   


   
   ';
        $data[] = $sub_array;
    }




}

$output = array(
	'draw'=> intval($_POST['draw']),
	'recordsTotal' =>$count_rows ,
	'recordsFiltered'=> $total_all_rows,
	'data'=>$data,
);
echo  json_encode($output);
