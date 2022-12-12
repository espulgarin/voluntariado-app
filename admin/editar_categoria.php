<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM categorias where id_categoria = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'nueva_categoria.php';
?>