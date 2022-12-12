<?php 
$DB_HOST = $_ENV["DB_HOST"];
$DB_USER = $_ENV["DB_USER"];
$DB_PASSWORD = $_ENV["DB_PASSWORD"];
$DB_NAME = $_ENV["DB_NAME"];
$DB_PORT = $_ENV["DB_PORT"];


$conn= new mysqli("$DB_HOST","$DB_USER","$DB_PASSWORD","$DB_NAME", "$DB_PORT")or die("No se pudo hacer la conexión a la base de datos.".mysqli_error($con));
