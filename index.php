<?php
session_start();
if (isset($_SESSION['usuario_nombre'])) {
    header("Location: tienda.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "mi_base_datos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$mensaje = "";

if (isset($_POST['accion']) && $_POST['accion'] == 'registro') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $correo, $password);

    if ($stmt->execute()) {
        $mensaje = "<p class='exito'> ¡Cuenta creada con éxito! Ahora inicia sesión.</p>";
    } else {
        $mensaje = "<p class='error'> Error: Ese correo ya está registrado.</p>";
    }
    $stmt->close();
}

if (isset($_POST['accion']) && $_POST['accion'] == 'login') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($usuario = $resultado->fetch_assoc()) {
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            header("Location: tienda.php");
            exit();
        } else {
            $mensaje = "<p class='error'> Contraseña incorrecta.</p>";
        }
    } else {
        $mensaje = "<p class='error'> No existe una cuenta con ese correo.</p>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a mi tienda de bebidas</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #eef2f7; margin: 0; padding: 40px; text-align: center; }
        .bienvenida { max-width: 700px; margin: 0 auto 30px auto; }
        .bienvenida h1 { color: #2c3e50; margin-bottom: 10px; }
        .bienvenida p { color: #555; font-size: 1.1em; }
        .contenedor-cajas { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; }
        .caja { background: white; padding: 25px; border-radius: 10px; width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: left; }
        .caja h2 { margin-top: 0; color: #333; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; }
        input { width: 100%; padding: 10px; margin: 8px 0 15px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; padding: 12px; border: none; border-radius: 5px; color: white; font-size: 1em; cursor: pointer; }
        .btn-login { background-color: #007bff; }
        .btn-login:hover { background-color: #0056b3; }
        .btn-registro { background-color: #28a745; }
        .btn-registro:hover { background-color: #218838; }
        .exito { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; max-width: 650px; margin: 0 auto 20px auto; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; max-width: 650px; margin: 0 auto 20px auto; }
    </style>
</head>
<body>

    <div class="bienvenida">
        <h1> ¡Bienvenido a Nuestra Tienda de bebidas!</h1>
        <p>Inicia sesión con tu cuenta o regístrate gratis para ver el catálogo y realizar tus compras.</p>
    </div>

    <?php echo $mensaje; ?>

    <div class="contenedor-cajas">
        <div class="caja">
            <h2>Iniciar Sesión</h2>
            <form method="POST" action="index.php">
                <input type="hidden" name="accion" value="login">
                <label>Correo electrónico:</label>
                <input type="email" name="correo" required placeholder="ejemplo@correo.com">
                
                <label>Contraseña:</label>
                <input type="password" name="password" required placeholder="********">
                
                <button type="submit" class="btn-login">Entrar a la Tienda</button>
            </form>
        </div>

        <div class="caja">
            <h2>Crear Cuenta Nueva</h2>
            <form method="POST" action="index.php">
                <input type="hidden" name="accion" value="registro">
                <label>Tu Nombre:</label>
                <input type="text" name="nombre" required placeholder="Ej. Juan Pérez">

                <label>Correo electrónico:</label>
                <input type="email" name="correo" required placeholder="ejemplo@correo.com">
                
                <label>Crea una Contraseña:</label>
                <input type="password" name="password" required placeholder="********">
                
                <button type="submit" class="btn-registro">Registrarme</button>
            </form>
        </div>
    </div>

<fotter> Rivera Sánchez Victor Didier      5ºI     0648     programación </fotter>
    
</body>
</html>
