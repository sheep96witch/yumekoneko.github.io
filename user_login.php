<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="stylesuser.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="Nombre">Nombre:</label>
            <input type="text" id="Nombre" name="Nombre" required>
            <label for="pass">Contraseña:</label>
            <input type="password" id="pass" name="pass" required>
            <input type="submit" value="Iniciar Sesión">
        </form>

       
        <?php
       
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
         
            $name = trim($_POST['Nombre']);
            $pass = sha1(trim($_POST['pass'])); 

          
            $conexion = mysqli_connect('localhost', 'root', '', 'Biblioteca', '3306');

            if (!$conexion) {
                die("<p>Error de conexión: " . mysqli_connect_error() . "</p>");
            }

         
            $sqlUsuario = "SELECT * FROM Usuarios WHERE Nombre = ? AND Password = ?";
            $stmtUsuario = mysqli_prepare($conexion, $sqlUsuario);
            mysqli_stmt_bind_param($stmtUsuario, "ss", $name, $pass);
            mysqli_stmt_execute($stmtUsuario);
            $resultUsuario = mysqli_stmt_get_result($stmtUsuario);

            if (mysqli_num_rows($resultUsuario) == 1) {
              
                $_SESSION['user_id'] = $name; 
                $_SESSION['role'] = 'user'; 
                header("Location: contacto.php");
                exit();
            } else {
            
                $sqlAdmin = "SELECT * FROM Administradores WHERE Nombre = ? AND Contraseña = ?";
                $stmtAdmin = mysqli_prepare($conexion, $sqlAdmin);
                mysqli_stmt_bind_param($stmtAdmin, "ss", $name, $pass);
                mysqli_stmt_execute($stmtAdmin);
                $resultAdmin = mysqli_stmt_get_result($stmtAdmin);

                if (mysqli_num_rows($resultAdmin) == 1) {
                    
                    $_SESSION['user_id'] = $name; 
                    $_SESSION['role'] = 'admin'; 
                    header("Location: contacto.php");
                    exit();
                } else {
                    echo "<p style='color:red;'>Nombre de usuario o contraseña incorrectos.</p>";
                }
            }

           
            mysqli_close($conexion);
        }
        ?>
    </div>

    <div class="menu"><li><a href="index.html">Pagina principal</a></li></div>
</body>
</html>
