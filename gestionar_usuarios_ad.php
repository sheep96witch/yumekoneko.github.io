<?php
session_start();


$host = 'localhost';
$db = 'Biblioteca';
$user = 'root';
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if (isset($_GET['delete_user_admin'])) {
    $id = $_GET['delete_user_admin'];
    $tabla = $_GET['tabla'];

    try {
        if ($tabla == 'usuarios') {
            $sql = "DELETE FROM Usuarios WHERE ID_Usuario = :id";
        } elseif ($tabla == 'administradores') {
            $sql = "DELETE FROM Administradores WHERE ID_Trabajador = :id";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "El usuario/administrador ha sido eliminado correctamente.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error al eliminar el usuario/administrador.";
            $_SESSION['message_type'] = "error";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error en la base de datos: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    
    header("Location: gestionar_usuarios_ad.php");
    exit();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


$admin_nombre = isset($_SESSION['admin_nombre']) ? $_SESSION['admin_nombre'] : 'Administrador';

$sql_usuarios = "SELECT * FROM Usuarios";
$sql_admins = "SELECT * FROM Administradores";

$result_usuarios = $pdo->query($sql_usuarios);
$result_admins = $pdo->query($sql_admins);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios y Administradores</title>
    <link rel="stylesheet" href="styledese.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if (isset($_SESSION['message'])): ?>
        <script>
            Swal.fire({
                icon: '<?php echo $_SESSION['message_type'] == "success" ? "success" : "error"; ?>',
                title: '<?php echo $_SESSION['message_type'] == "success" ? "¡Éxito!" : "¡Error!"; ?>',
                text: '<?php echo htmlspecialchars($_SESSION['message']); ?>',
                confirmButtonText: 'Aceptar'
            });
        </script>
        <?php
       
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>
    <h1>Gestionar Usuarios y Administradores</h1>
    <p>Bienvenido, <?php echo htmlspecialchars($admin_nombre); ?>. Aquí puedes agregar o eliminar usuarios y administradores.</p>

    <div class="container">
        
        <div class="section">
            <h2>Agregar Nuevo Usuario o Administrador</h2>
            <form action="gestionar_usuarios_ad.php" method="post">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Teléfono" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Contraseña" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo</label>
                    <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required>
                </div>
                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select id="rol" name="rol" required>
                        <option value="usuario">Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="add_user" class="button">Agregar Usuario/Administrador</button>
                </div>
            </form>
        </div>

        
        <div class="section">
            <h2>Usuarios Registrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result_usuarios->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['ID_Usuario']); ?></td>
                            <td><?php echo htmlspecialchars($user['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($user['Usuario']); ?></td>
                            <td><?php echo htmlspecialchars($user['Telefono']); ?></td>
                            <td><?php echo htmlspecialchars($user['Correo']); ?></td>
                            <td>
                                <a href="modi_usu_ad.php?edit_user_admin=<?php echo $user['ID_Usuario']; ?>&tabla=usuarios">Modificar</a> | 
                                <a href="gestionar_usuarios_ad.php?delete_user_admin=<?php echo $user['ID_Usuario']; ?>&tabla=usuarios" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

   
        <div class="section">
            <h2>Administradores Registrados</h2>
            <?php if ($result_admins->rowCount() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($admin = $result_admins->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['ID_Trabajador']); ?></td>
                            <td><?php echo htmlspecialchars($admin['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($admin['Telefono']); ?></td>
                            <td><?php echo htmlspecialchars($admin['Correo']); ?></td>
                            <td>
                                <a href="modi_usu_ad.php?edit_user_admin=<?php echo $admin['ID_Trabajador']; ?>&tabla=administradores">Modificar</a> | 
                                <a href="gestionar_usuarios_ad.php?delete_user_admin=<?php echo $admin['ID_Trabajador']; ?>&tabla=administradores" onclick="return confirm('¿Estás seguro de eliminar este administrador?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>No hay administradores registrados.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
