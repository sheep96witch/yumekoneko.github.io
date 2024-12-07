<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


$host = 'localhost'; 
$db = 'Biblioteca'; 
$user = 'root';     
$pass = '';         

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    echo "<p>Conexión exitosa a la base de datos.</p>";
} catch (PDOException $e) {
    
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>
