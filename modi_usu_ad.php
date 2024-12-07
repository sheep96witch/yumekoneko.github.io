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


if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_nombre = isset($_SESSION['admin_nombre']) ? $_SESSION['admin_nombre'] : 'Administrador';

if (isset($_GET['edit_user_admin'])) {
    $id = $_GET['edit_user_admin'];
    $tabla = $_GET['tabla'];

    try {
        if ($tabla == 'usuarios') {
            $sql = "SELECT * FROM Usuarios WHERE ID_Usuario = :id";
        } elseif ($tabla == 'administradores') {
            $sql = "SELECT * FROM Administradores WHERE ID_Trabajador = :id";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user_admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user_admin) {
            $_SESSION['message'] = "No se encontró el usuario o administrador.";
            $_SESSION['message_type'] = "error";
            header("Location: gestionar_usuarios_ad.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error en la base de datos: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: gestionar_usuarios_ad.php");
        exit();
    }
}

if (isset($_POST['update_user'])) {
    $id = $_POST['id']; 
    $nombre = trim($_POST['nombre']);
    $usuario = trim($_POST['usuario']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);
    $rol = trim($_POST['rol']);
    $password_hashed = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;


    if (empty($nombre) || empty($telefono) || empty($correo) || empty($rol)) {
        $_SESSION['message'] = "Todos los campos son obligatorios.";
        $_SESSION['message_type'] = "error";
        header("Location: modi_usu_ad.php?edit_user_admin=$id&tabla=$tabla");
        exit();
    }

    try {
        
        if ($rol == 'admin') {
           
            $sql = "UPDATE Administradores SET Nombre = :nombre, Telefono = :telefono, Correo = :correo";
            if ($password_hashed) {
                $sql .= ", Contraseña = :password";
            }
            $sql .= " WHERE ID_Trabajador = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            if ($password_hashed) {
                $stmt->bindParam(':password', $password_hashed);
            }
        } else {
           
            $sql = "UPDATE Usuarios SET Nombre = :nombre, Usuario = :usuario, Telefono = :telefono, Correo = :correo";
            if ($password_hashed) {
                $sql .= ", Password = :password";
            }
            $sql .= " WHERE ID_Usuario = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            if ($password_hashed) {
                $stmt->bindParam(':password', $password_hashed);
            }
        }

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Usuario/Administrador actualizado correctamente.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error al actualizar el usuario/administrador.";
            $_SESSION['message_type'] = "error";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error en la base de datos: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    header("Location: gestionar_usuarios_ad.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario/Administrador</title>
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

<h1>Modificar Usuario/Administrador</h1>
<p>Bienvenido, <?php echo htmlspecialchars($admin_nombre); ?>. Aquí puedes modificar los datos del usuario/administrador.</p>

<div class="container">
    <div class="section">
        <h2>Modificar Usuario/Administrador</h2>
        <form action="modi_usu_ad.php" method="post">
            <input type="hidden" name="id" value="<?php echo $user_admin['ID_Usuario'] ?? $user_admin['ID_Trabajador']; ?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user_admin['Nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($user_admin['Usuario'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user_admin['Telefono']); ?>" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($user_admin['Correo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="(Opcional)">
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" required>
                    <option value="usuario" <?php echo $user_admin['Rol'] == 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                    <option value="admin" <?php echo $user_admin['Rol'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="update_user" class="button">Actualizar Usuario/Administrador</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
