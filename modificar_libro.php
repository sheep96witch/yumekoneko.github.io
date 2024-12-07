<?php
session_start();

$host = 'localhost';
$db = 'Biblioteca';
$usuario = 'root';
$pasar = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $usuario, $pasar);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM libros WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$libro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$libro) {
    $_SESSION['mensaje'] = "El libro no existe.";
    $_SESSION['mensaje_tipo'] = "error";
    header("Location: gestionar_libros.php");
    exit();
}

if (isset($_POST['modificar_libro'])) {
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $descripcion = trim($_POST['descripcion']);
    $imagen = trim($_POST['imagen']);
    $es_manga = isset($_POST['es_manga']) ? 1 : 0;

    if (empty($titulo) || empty($autor) || empty($descripcion) || empty($imagen)) {
        $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
        $_SESSION['mensaje_tipo'] = "error";
    } else {
        if (!filter_var($imagen, FILTER_VALIDATE_URL)) {
            $_SESSION['mensaje'] = "La URL de la imagen no es válida.";
            $_SESSION['mensaje_tipo'] = "error";
        } else {
            try {
               
                $sql_update = "UPDATE libros SET titulo = :titulo, autor = :autor, descripcion = :descripcion, imagen = :imagen, es_manga = :es_manga WHERE id = :id";
                $stmt_update = $pdo->prepare($sql_update);
                $stmt_update->bindParam(':titulo', $titulo);
                $stmt_update->bindParam(':autor', $autor);
                $stmt_update->bindParam(':descripcion', $descripcion);
                $stmt_update->bindParam(':imagen', $imagen);
                $stmt_update->bindParam(':es_manga', $es_manga);
                $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);

                if ($stmt_update->execute()) {
                    $_SESSION['mensaje'] = "Libro actualizado correctamente.";
                    $_SESSION['mensaje_tipo'] = "success";  
                    header("Location: gestionar_libros.php");
                    exit();
                } else {
                    $_SESSION['mensaje'] = "Error al actualizar el libro.";
                    $_SESSION['mensaje_tipo'] = "error";  
                }
            } catch (PDOException $e) {
                $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
                $_SESSION['mensaje_tipo'] = "error";  
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
    <title>Modificar Libro</title>
    <link rel="stylesheet" href="styledese.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
</head>
<body>
    <h1>Modificar Libro</h1>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <script>
            Swal.fire({
                icon: '<?php echo $_SESSION['mensaje_tipo'] == "success" ? "success" : "error"; ?>',
                title: '<?php echo $_SESSION['mensaje_tipo'] == "success" ? "¡Éxito!" : "¡Error!"; ?>',
                text: '<?php echo htmlspecialchars($_SESSION['mensaje']); ?>',
                confirmButtonText: 'Aceptar'
            });
        </script>
    <?php 
        unset($_SESSION['mensaje']); 
        unset($_SESSION['mensaje_tipo']); 
    endif; ?>

    <form action="modificar_libro.php?id=<?php echo $libro['id']; ?>" method="post">
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>
        </div>
        <div class="form-group">
            <label for="autor">Autor</label>
            <input type="text" id="autor" name="autor" value="<?php echo htmlspecialchars($libro['autor']); ?>" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($libro['descripcion']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="imagen">Imagen</label>
            <input type="url" id="imagen" name="imagen" value="<?php echo htmlspecialchars($libro['imagen']); ?>" required>
        </div>
        <div class="form-group">
            <label for="es_manga">
                <input type="checkbox" id="es_manga" name="es_manga" <?php echo $libro['es_manga'] ? 'checked' : ''; ?>>
                ¿Es Manga?
            </label>
        </div>
        <div class="form-group">
            <button type="submit" name="modificar_libro" class="button">Actualizar Libro</button>
        </div>
    </form>
</body>
</html>
