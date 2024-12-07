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


$sql_libros = "SELECT * FROM libros";
$result_libros = $pdo->query($sql_libros);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Manga Acogedora</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Quicksand:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="stylesmanga.css">
</head>
<body>
<header class="header">
    <div class="container">
        <h1 class="logo">Manga neko</h1>
        <nav>
            <ul>
                <li><a href="index.html">Inicio</a></li>
                <li><a href="librosp.php">Tienda</a></li>
                <li><a href="juegos.html">Games</a></li>
                <li><a href="menu.html">Comidita</a></li>
                <li><a href="cafes.html">Cafecito</a></li>
            </ul>
        </nav>
    </div>
</header>

<section id="shop" class="shop">
    <div class="container">
        <h2>Productos Destacados</h2>
        <div class="product-list">
            <?php while ($libro = $result_libros->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($libro['imagen']); ?>" alt="<?php echo htmlspecialchars($libro['titulo']); ?>">
                    <h3><?php echo htmlspecialchars($libro['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($libro['descripcion']); ?></p>
                    <a href="#" class="btn">Comprar</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<div id="contactModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Ponte en contacto con nosotros</h2>
        <p>Para poder atenderte, por favor contáctanos para más detalles sobre la compra.</p>
        <a href="contacto.php" class="btn">Ir a la página de contacto</a>
    </div>
</div>

<section id="about" class="about">
    <div class="container">
        <h2>Sobre Nosotros</h2>
        <p>En Manga Cozy nos dedicamos a ofrecerte los mangas más adorables y conmovedores. Creemos que la lectura de mangas debe ser una experiencia cálida y accesible para todos. ¡Esperamos que disfrutes tu visita!</p>
    </div>
</section>

<script>
    var modal = document.getElementById("contactModal");
    var span = document.getElementsByClassName("close")[0];
    var comprarBtns = document.querySelectorAll('.btn');
    
    comprarBtns.forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault(); 
            modal.style.display = "block"; 
        });
    });

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<footer id="contact" class="footer">
    <div class="container">
        <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
        <p>&copy; 2024 Manga Cozy | Todos los derechos reservados</p>
        <form class="newsletter">
            <input type="email" placeholder="Suscríbete a nuestro boletín" required>
            <button type="submit">Suscribir</button>
        </form>
    </div>
</footer>

</body>
</html>
