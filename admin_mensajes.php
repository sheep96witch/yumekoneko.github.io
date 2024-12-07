<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);


include('contacto1.php'); 

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    try {
        $sql = "DELETE FROM Contactos WHERE ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $mensaje = "Mensaje eliminado con éxito.";
        } else {
            $error = "Error al eliminar el mensaje.";
        }
    } catch (PDOException $e) {
        $error = "Error en la base de datos: " . $e->getMessage();
    }
}

try {
    $sql = "SELECT * FROM Contactos ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al obtener los mensajes: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes de Contacto</title>

    <link rel="stylesheet" href="stylesadmin.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="admin-header">
        <h1 class="header-title">
            <i class="fa-solid fa-envelope"></i> Gestión de Mensajes de Contacto
        </h1>
    </header>

    <main class="contact-container">
        <?php if (isset($mensaje)): ?>
            <div class="alert success"><i class="fa-solid fa-check-circle"></i> <?php echo htmlspecialchars($mensaje); ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert error"><i class="fa-solid fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Mensaje</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mensajes)): ?>
                    <?php foreach ($mensajes as $mensaje): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($mensaje['id']); ?></td>
                            <td><?php echo htmlspecialchars($mensaje['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($mensaje['email']); ?></td>
                            <td><?php echo htmlspecialchars($mensaje['mensaje']); ?></td>
                            <td>
                             
                                <a href="admin_mensajes.php?delete=<?php echo $mensaje['id']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('¿Estás seguro de eliminar este mensaje?');">
                                   <i class="fa-solid fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">
                            <i class="fa-solid fa-inbox"></i> No hay mensajes disponibles.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


        <a href="admin_dashboard.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Regresar al Panel
        </a>
    </main>
</body>
</html>
