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


if (isset($_POST['add_book'])) {
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $descripcion = trim($_POST['descripcion']);
    $imagen = trim($_POST['imagen']);
    $es_manga = isset($_POST['es_manga']) ? 1 : 0;

    
    if (empty($titulo) || empty($autor) || empty($descripcion) || empty($imagen)) {
        $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
        $_SESSION['mensaje_tipo'] = "error";
        header("Location: gestionar_libros.php");
        exit();
    }

    try {
        $sql = "INSERT INTO libros (titulo, autor, descripcion, imagen, es_manga) 
                VALUES (:titulo, :autor, :descripcion, :imagen, :es_manga)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':es_manga', $es_manga);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Libro agregado correctamente.";
            $_SESSION['mensaje_tipo'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al agregar el libro.";
            $_SESSION['mensaje_tipo'] = "error";
        }
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
        $_SESSION['mensaje_tipo'] = "error";
    }

    header("Location: gestionar_libros.php");
    exit();
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    try {
        $sql = "DELETE FROM libros WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Libro eliminado correctamente.";
            $_SESSION['mensaje_tipo'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el libro.";
            $_SESSION['mensaje_tipo'] = "error";
        }
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
        $_SESSION['mensaje_tipo'] = "error";
    }

    
    header("Location: gestionar_libros.php");
    exit();
}

$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';


if ($busqueda) {
    
    $sql_libros = "SELECT * FROM libros WHERE titulo LIKE :busqueda OR autor LIKE :busqueda";
    $stmt = $pdo->prepare($sql_libros);
    $stmt->bindValue(':busqueda', '%' . $busqueda . '%');
    $stmt->execute();
} else {
   
    $sql_libros = "SELECT * FROM libros";
    $stmt = $pdo->query($sql_libros);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Libros</title>
    <link rel="stylesheet" href="styledese.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h1>Gestión de Libros</h1>
    <p>Bienvenido, <?php echo htmlspecialchars($admin_nombre); ?>. Aquí puedes agregar o eliminar libros.</p>

    <div class="container">

        
        <div class="section">
            <h2>Buscar Libro</h2>
            <form action="gestionar_libros.php" method="get">
                <div class="form-group">
                    <input type="text" id="busqueda" name="busqueda" placeholder="Buscar por título o autor" value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">
                    <button type="submit" class="button">Buscar</button>
                </div>
            </form>
        </div>

    
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
            ?>
        <?php endif; ?>

    
        <div class="section">
            <h2>Agregar Nuevo Libro</h2>
            <form action="gestionar_libros.php" method="post">
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" placeholder="Título del libro" required>
                </div>
                <div class="form-group">
                    <label for="autor">Autor</label>
                    <input type="text" id="autor" name="autor" placeholder="Autor del libro" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Descripción del libro" required></textarea>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen</label>
                    <input type="url" id="imagen" name="imagen" placeholder="URL de la imagen" required>
                </div>
                <div class="form-group">
                    <label for="es_manga">
                        <input type="checkbox" id="es_manga" name="es_manga">
                        ¿Es Manga?
                    </label>
                </div>
                <div class="form-group">
                    <button type="submit" name="add_book" class="button">Agregar Libro</button>
                </div>
            </form>
        </div>

        
        <div class="section">
            <h2>Libros Registrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Descripción</th>
                        <th>¿Es Manga?</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($libro = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($libro['id']); ?></td>
                            <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                            <td><?php echo htmlspecialchars($libro['descripcion']); ?></td>
                            <td><?php echo $libro['es_manga'] ? 'Sí' : 'No'; ?></td>
                            <td>
                                <a href="modificar_libro.php?id=<?php echo $libro['id']; ?>" onclick="return confirm('¿Modificar este libro?');">Modificar</a>
                                <a href="gestionar_libros.php?delete=<?php echo $libro['id']; ?>" onclick="return confirm('¿Eliminar este libro?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
