<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_mensajes.php");
    exit();
}

if (isset($_SESSION['admin_nombre'])) {
    $admin_nombre = $_SESSION['admin_nombre'];
} else {
 
    $admin_nombre = 'Administrador';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="styledese.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($admin_nombre); ?>!</h1>
    <p>Este es el panel exclusivo para administradores.</p>
    
    <div class="container">

        <div class="section">
            <h2>Accesos Rápidos</h2>
            <ul>
                <li><a href="admin_mensajes.php" class="button">Ver Mensajes de Contacto</a></li>
                <li><a href="gestionar_libros.php" class="button">Gestionar Libros</a></li>
                <li><a href="gestionar_usuarios_ad.php">Gestionar Usuarios</a></li>

            </ul>
        </div>
        <div class="section">
            <h2>Salir</h2>
            <ul>
                <li><a href="logout.php" class="button">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>
    
</body>
</html>
