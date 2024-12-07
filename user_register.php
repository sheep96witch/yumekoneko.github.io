<!DOCTYPE html>
<html>
<head>
    <title>Registro de Administrador</title>
</head>
<body>
    <h2>Registro de Administrador</h2>
    <form method="post" action="procesar_registro.php">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required> 

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required>

        <label for="telefonoA">Teléfono:</label>
        <input type="tel" id="telefonoA" name="telefonoA">

        <label for="salario">Salario:</label>
        <input type="number" id="salario" name="salario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" required>

        <input type="submit" value="Registrar">
    </form> 

    <?php

$conn = mysqli_connect('localhost', 'root', '', 'Biblioteca', '3306');

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $nombre = $_POST['nombre'];

    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefonoA'];
    $salario = $_POST['salario'];
    $contrasena = ($_POST['contrasena']); 
    $correo = $_POST['correo'];

  
    $sql = "INSERT INTO Administradores (Nombre, Apellidos, telefonoA, Salario, Contraseña, Correo)
            VALUES ('$nombre', '$apellidos', '$telefono', '$salario', '$contrasena', '$correo')";

 
    if (mysqli_query($conn, $sql)) {
        echo "Nuevo administrador registrado correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
<li><a href="Index.html">Regresar al inicio</a></li>
</body>
</html>