<?php
require_once "../conexion.php";



$cliente = mysqli_query($conexion, "SELECT * FROM producto WHERE id_tipo = 0");
while ($row = mysqli_fetch_assoc($cliente)) {
$row['codproducto'];



}