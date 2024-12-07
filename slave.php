<!DOCTYPE html>
<html>
<head>
    <title>Mostrar Datos de Tablas</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
    </style>
</head>
<body>
<h2>Agregar Administrador</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="nombre_admin">Nombre:</label>
    <input type="text" id="nombre_admin" name="nombre_admin" required>
    <label for="Apellidos">Apellidos:</label>
    <input type="text" id="Apellidos" name="Apellidos" required>
    <label for="telefonoA">Telefono:</label>
    <input type="text" id="telefonoA" name="telefonoA" required>
    <label for="salario">Salario:</label>
    <input type="text" id="salario" name="salario" required>
    <label for="contraseña">Contraseña:</label>
    <input type="text" id="contraseña" name="contraseña" required>
    <label for="correo">Correo:</label>
    <input type="text" id="correo" name="correo" required>
    <input type="submit" value="Agregar">
</form>

<h2>Agregar Usuario</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="usuario">Usuario:</label>
    <input type="text" id="usuario" name="usuario" required>
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>
    <label for="telefono">Telefono:</label>
    <input type="text" id="telefono" name="telefono" required>
    <label for="password">Contraseña:</label>
    <input type="text" id="password" name="password" required>
    <label for="correo">Correo:</label>
    <input type="text" id="correo" name="correo" required>
    <input type="submit" value="Agregar">
</form>
<h2>Agregar Libro</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" required>

    <label for="autor">Autor:</label>
    <input type="text" id="autor" name="autor" required>

    <label   
 for="genero">Género:</label>
    <input type="text" id="genero" name="genero" required>

    <label for="es_manga">Es Manga:</label>
    <input type="checkbox" id="es_manga" name="es_manga">

    <label for="precio">Precio:</label>
    <input type="text" id="precio" name="precio" required>

    <label for="cantidad">Cantidad:</label>
    <input type="number" id="cantidad" name="cantidad" required>

    <input type="submit" value="Agregar   
 Libro">
</form>
<?php
$conexion = mysqli_connect('localhost', 'root', '', 'Biblioteca', '3306');

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $genero = $_POST['genero'];
    $es_manga = isset($_POST['es_manga']) ? 1 : 0;
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    
    $sql = "INSERT INTO libros (titulo, autor, genero, es_manga, precio, cantidad_existencia) 
            VALUES ('$titulo', '$autor', '$genero', '$es_manga', '$precio', '$cantidad')";

    if (mysqli_query($conexion, $sql)) {
        echo "Libro agregado exitosamente.";
    } else {
        echo "Error al agregar libro: " . mysqli_error($conexion);
    }
}
?>
<?php
$conexion = mysqli_connect('localhost', 'root', '', 'Biblioteca', '3306');

            if (!$conexion) {
                die("Error de conexión: " . mysqli_connect_error());
            }

            function modify() {
              
                $conn = mysqli_connect('localhost', 'root', '', "Biblioteca", '3306');
            
               
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }
            
               
                $id = $_POST['id']; 
                $nombre_admin = $_POST['nombre_admin'];
                $Apellidos = $_POST['Apellidos'];
                $telefonoA = $_POST['telefonoA'];
                $salario = $_POST['salario'];
                $contraseña = $_POST['contraseña'];
                $correo = $_POST['correo'];
            

                $sql = "UPDATE your_table SET nombre_admin='$nombre_admin', Apellidos='$Apellidos', telefonoA='$telefonoA', salario='$salario', contraseña='$contraseña', correo='$correo' WHERE id='$id'";
            
                if (mysqli_query($conn, $sql)) {
                  
                    header("Location: success.php");
                } else {
                    
                    echo "Error updating record: " . mysqli_error($conn);
                }
            
                mysqli_close($conn);
            }
            




$sql_administradores = "SELECT * FROM Administradores";
$result_administradores = $conexion->query($sql_administradores);

$sql_usuarios = "SELECT * FROM Usuarios";
$result_usuarios = $conexion->query($sql_usuarios);

?>

<h2>Administradores</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Teléfono</th>
        <th>Salario</th>
        <th>Contraseña</th>
        <th>Correo</th>
    </tr>
    <?php
    if ($result_administradores->num_rows > 0) {
        while($row = $result_administradores->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["ID_Trabajador"] . "</td>";
            echo "<td>" . $row["Nombre"] . "</td>";
            echo "<td>" . $row["Apellidos"] . "</td>";
            echo "<td>" . $row["telefonoA"] . "</td>";
            echo "<td>" . $row["Salario"] . "</td>";
            echo "<td>" . $row["Contraseña"] . "</td>"; 
            echo "<td>" . $row["Correo"] . "</td>";
            echo "<td><button onclick='eliminarAdministrador(" . $row["ID_Trabajador"] . ")'>Eliminar</button></td>";
            echo "</tr>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No se encontraron administradores.</td></tr>";
    }
    
    ?>
</table>

<script>
function eliminarAdministrador(id) {
    if (confirm("¿Estás seguro de eliminar al administrador con ID " + id + "?")) {
      
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "eliminar.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload   
 = function() {
            if (xhr.status == 200) {
                alert(xhr.responseText);   
 
                location.reload();
            } else {
                alert("Error al eliminar el administrador.");
            }
        };
        xhr.send("id=" + id);
    }
}

</script>
<h2>Usuarios</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Nombre</th>
        <th>Teléfono</th>
        <th>Contraseña</th>
        <th>Email</th>
    </tr>
    <?php
    if ($result_usuarios->num_rows > 0) {
        while($row = $result_usuarios->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["ID_Usuario"] . "</td>";
            echo "<td>" . $row["Usuario"] . "</td>";
            echo "<td>" . $row["Nombre"] . "</td>";
            echo "<td>" . $row["Telefono"] . "</td>";
            echo "<td>" . $row["Password"] . "</td>";
            echo "<td>" . $row["Correo"] . "</td>";
            echo "<td><button onclick='eliminarUsuario(" . $row["ID_Usuario"] . ")'>Eliminar</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No se encontraron usuarios.</td></tr>";
    }
    ?>
</table>
<script>
function eliminarUsuario(id) {
    if (confirm("¿Estás seguro de eliminar al usuario con ID " + id + "?")) {
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "eliminar_usuario.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload 
 = function() {
            if (xhr.status == 200) {
                alert(xhr.responseText);

                
                location.reload();
            } else {
                alert("Error al eliminar el usuario.");
            }
        };
        xhr.send("id=" + id);
    }
}
</script>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['nombre_admin'])) {
        
        $nombre_admin = $_POST['nombre_admin'];
        $apellidos = $_POST['apellidos']; 
        $telefono = $_POST['telefono'];

        $salario = $_POST['salario'];
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); 
        $correo = $_POST['correo'];

        $stmt = $conexion->prepare("INSERT INTO Administradores (Nombre, Apellidos, telefono, Salario, Contraseña, Correo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiis", $nombre_admin, $apellidos, $telefono, $salario, $contraseña, $correo);

        if ($stmt->execute()) {
            echo "Administrador agregado correctamente.";
        } else {
            echo "Error al agregar administrador: " . $stmt->error;
        }

        $stmt->close();
    } elseif (isset($_POST['usuario'])) {
  
        $usuario = $_POST['usuario'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

        $stmt = $conexion->prepare("INSERT INTO Usuarios (Usuario, Nombre, telefono, Password, Correo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssss", $usuario, $nombre, $telefono, $password, $correo);

        if ($stmt->execute()) {
            echo "Usuario agregado correctamente.";
        } else {
            echo "Error al agregar usuario: " . $stmt->error;
        }

        $stmt->close();
    }
}
$sql_libros = "SELECT * FROM libros";
$result_libros = $conexion->query($sql_libros);

?>

<h2>Libros</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Autor</th>
        <th>Género</th>
        <th>¿Es Manga?</th>
        <th>Precio</th>
        <th>Cantidad</th>
    </tr>
    <?php
    if ($result_libros->num_rows > 0) {
        while($row = $result_libros->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["titulo"] . "</td>";
            echo "<td>" . $row["autor"] . "</td>";
            echo "<td>" . $row["genero"] . "</td>";
            echo "<td>" . ($row["es_manga"] ? 'Sí' : 'No') . "</td>";
            echo "<td>" . $row["precio"] . "</td>";
            echo "<td>" . $row["cantidad_existencia"] . "</td>";
            echo "<td><button onclick='eliminarLibro(" . $row["id"] . ")'>Eliminar</button></td>";
           
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No se encontraron libros.</td></tr>";
    }
    ?>
</table>
<script>
function eliminarLibro(id) {
    if (confirm("¿Estás seguro de eliminar el libro? " + id + "?")) {
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "eliminar_libro.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload 
 = function() {
            if (xhr.status == 200) {
                alert(xhr.responseText);
 
                location.reload();
            } else {
                alert("Error al eliminar el libro.");
            }
        };
        xhr.send("id=" + id);
    }
}
</script>
<?php

$conexion->close();
?>

<li><a href="Index.html">Regresar al inicio</a></li>
</body>
</html>