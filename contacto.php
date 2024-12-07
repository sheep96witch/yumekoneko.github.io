<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo "<p>Por favor, inicia sesión para poder contactarnos.</p>";
    echo "<a href='user_login.php'>Iniciar sesión</a>";
    exit();
}
if ($_SESSION['role'] === 'admin') {
    echo "<p>Bienvenido, Administrador {$_SESSION['user_id']}</p>";
} else {
    echo "<p>Bienvenido, Usuario {$_SESSION['user_id']}</p>";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $mensaje = trim($_POST['mensaje']);

 
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        $status = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = 'invalid_email';
    } else {
        
        include('contacto1.php');
        try {
            $sql = "INSERT INTO contactos (nombre, email, mensaje) VALUES (:nombre, :email, :mensaje)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mensaje', $mensaje);

            if ($stmt->execute()) {
                $status = 'success';
            } else {
                $status = 'error';
            }
        } catch (PDOException $e) {
            echo "Error al insertar en la base de datos: " . $e->getMessage();
            exit;
        }
    }

    
    header("Location: contacto.php?status=$status");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="stylescon.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pangolin&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <h1 style="color: #1D4E89; text-shadow: 0 0 10px #4A90E2, 0 0 20px #4A90E2, 0 0 30px #4A90E2, 0 0 40px #4A90E2;">
        Bienvenido a nuestra página de contacto
    </h1>
</header>

<div class="container">
    <nav class="navbar">
        <ul class="nav-links">
            <li class="nav-link">
                <span class="material-icons">home</span>
                <a href="index.html">Inicio</a>
            </li>
            <li class="nav-link">
                <span class="material-icons">contact_mail</span>
                <a href="contact.php">Contáctanos</a>
            </li>
        </ul>
    </nav>
</div>

<?php

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    switch ($status) {
        case 'success':
            echo "<p>Mensaje enviado con éxito. ¡Gracias por contactarnos!</p>";
            break;
        case 'error':
            echo "<p>Hubo un error al enviar el mensaje. Inténtalo nuevamente.</p>";
            break;
        case 'invalid_email':
            echo "<p>El correo electrónico no es válido. Por favor, verifica el formato.</p>";
            break;
    }
}
?>

<div class="contact-container">
    <h2>Contáctanos</h2>
    <form action="" method="POST">
        <input type="text" name="nombre" placeholder="Tu Nombre" required>
        
        <input type="email" name="email" placeholder="Tu Correo Electrónico" required 
               pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
               title="Por favor, ingresa un correo electrónico válido">
        
        <textarea name="mensaje" placeholder="Tu Mensaje" required></textarea>
        <button type="submit" class="btn">Enviar</button>
    </form>
</div>

</body>
</html>
