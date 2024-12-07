<?php

$conn = mysqli_connect('localhost', 'root', '', 'Biblioteca', '3306');

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']); 
    $salario = mysqli_real_escape_string($conn, $_POST['salario']);
    $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);

   
    $sql = "INSERT INTO Administradores (Nombre, Apellidos, telefono, Salario, Contraseña, Correo)
            VALUES (?, ?, ?, ?, ?, ?)";

    
    $stmt = mysqli_prepare($conn, $sql);

    
    mysqli_stmt_bind_param($stmt, "sssiis", $nombre, $apellidos, $telefono, $salario, $contrasena, $correo);

    
    if (mysqli_stmt_execute($stmt)) {
        echo "Nuevo administrador registrado correctamente";
        exit();
    } else {
        echo "Error al registrar: " . mysqli_error($conn);
    }

   
    mysqli_stmt_close($stmt);
} else {
    
    echo "Formulario no enviado";
}

mysqli_close($conn);
?>
<li><a href="Index.html">Regresar al inicio</a></li>