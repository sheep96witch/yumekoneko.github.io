<?php

session_start();


$host = 'localhost';
$dbname = 'Biblioteca';
$username = 'root';
$password = ''; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("No se pudo conectar a la base de datos: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $numero = $_POST['Telefono'];

   
    if (empty($nombre) || empty($usuario) || empty($correo) || empty($password) || empty($numero)) {
        $error_message = "Todos los campos son obligatorios.";
    } else {
        
        $checkSql = "SELECT COUNT(*) FROM Usuarios WHERE Correo = :correo";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':correo', $correo);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
           
            $error_message = "Ya existe una cuenta registrada con este correo.";
        } else {
            
            $insertSql = "INSERT INTO Usuarios (Correo, Password, Nombre, Usuario, TelefonoU) VALUES (:correo, SHA1(:password), :nombre, :usuario, :TelefonoU)";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->bindParam(':correo', $correo);
            $insertStmt->bindParam(':password', $password);
            $insertStmt->bindParam(':nombre', $nombre);
            $insertStmt->bindParam(':usuario', $usuario);
            $insertStmt->bindParam(':TelefonoU', $numero);

            if ($insertStmt->execute()) {
              
                $success_message = "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                $error_message = "Error al crear la cuenta. Intenta nuevamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="stylesuser.css">
</head>
<body>
    <div class="login-container">
        <h2>Registro de Usuario</h2>
        
       
        <form action="" method="POST">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="usuario">Nombre de Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="Telefono">Número:</label>
            <input type="text" id="Telefono" name="Telefono" required>

            <button type="submit">Registrar</button>
        </form>

        <?php
        
        if (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }

       
        if (isset($success_message)) {
            echo "<p class='success'>$success_message</p>";
        }
        ?>

        <br>
       
        <a href="user_login.php">Volver al inicio de sesión</a>
    </div>
</body>
</html>
