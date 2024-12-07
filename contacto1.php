<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


$host = 'localhost';
$db = 'Biblioteca';
$user = 'root';
$pass = ''; 

try {
 
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
   
    echo "Error de conexión: " . $e->getMessage();
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $mensaje = trim($_POST['mensaje']);

    
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        header("Location: contacto.php?status=error&message=Campos requeridos faltantes.");
        exit;
    }

   
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contacto.php?status=invalid_email&message=Correo inválido.");
        exit;
    }

    try {
      
        $sql = "INSERT INTO contactos (nombre, email, mensaje) VALUES (:nombre, :email, :mensaje)";
        $stmt = $pdo->prepare($sql);

       
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mensaje', $mensaje);

        
        if ($stmt->execute()) {
            header("Location: contacto.php?status=success&message=Mensaje enviado correctamente.");
        } else {
            header("Location: contacto.php?status=error&message=No se pudo enviar el mensaje.");
        }
    } catch (PDOException $e) {
    
        header("Location: contacto.php?status=error&message=" . urlencode($e->getMessage()));
    }
    exit;
}
?>
