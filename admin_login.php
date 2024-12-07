<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$error = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Nombre']) && isset($_POST['Contraseña'])) {
        $nombre = trim($_POST['Nombre']);
        $contrasena = sha1(trim($_POST['Contraseña'])); 

        $conexion = new mysqli('localhost', 'root', '', 'Biblioteca');

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $sql = "SELECT * FROM Administradores WHERE Nombre = ? AND Contraseña = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $nombre, $contrasena);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {
            $admin = $resultado->fetch_assoc();
            $_SESSION['admin_id'] = $admin['ID_Trabajador'];
            $_SESSION['admin_nombre'] = $admin['Nombre'];

            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Nombre o contraseña incorrectos.";
        }

        $stmt->close();
        $conexion->close();
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Administradores</title>
    <link rel="stylesheet" href="stylesadmin.css">
</head>
<body>
    <div class="contact-container">
        <h2>Inicio de Sesión - Administradores</h2>
        <?php if (!empty($error)) echo "<div class='alert error'>$error</div>"; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="Nombre">Nombre:</label>
            <input type="text" id="Nombre" name="Nombre" required>
            <label for="Contraseña">Contraseña:</label>
            <input type="password" id="Contraseña" name="Contraseña" required>
            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>
        <p><a href="index.html" class="btn-back">Volver a la página principal</a></p>
    </div>
</body>
</html>
