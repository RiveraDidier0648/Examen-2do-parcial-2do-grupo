<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: index.php");
    exit();
}

$nombre_usuario = $_SESSION['usuario_nombre'];

$conn = new mysqli("localhost", "root", "", "mi_base_datos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$resultado = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Tienda Sencilla</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f4f9; }
        .barra-superior { background-color: #2c3e50; color: white; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        .barra-superior a { color: #ff6b6b; text-decoration: none; font-weight: bold; }
        h1 { text-align: center; color: #333; margin-top: 30px; }
        .contenedor { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; padding: 20px; }
        .tarjeta { background: white; padding: 20px; border-radius: 8px; width: 250px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .precio { font-size: 1.2em; color: #28a745; font-weight: bold; }
        input { width: 100%; padding: 6px; margin: 6px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>

    <div class="barra-superior">
        <span> Hola, <b><?php echo htmlspecialchars($nombre_usuario); ?></b></span>
        <a href="cerrar_sesion.php">Cerrar Sesión</a>
    </div>

    <h1> Catálogo de Productos</h1>

    <div class="contenedor">
        <?php while($prod = $resultado->fetch_assoc()): ?>
            <div class="tarjeta">
                <h3><?php echo $prod['nombre']; ?></h3>
                <p><?php echo $prod['descripcion']; ?></p>
                <p class="precio">$<?php echo $prod['precio']; ?></p>

                <form action="comprar.php" method="POST">
                    <input type="hidden" name="producto" value="<?php echo $prod['nombre']; ?>">
                    <input type="hidden" name="precio" value="<?php echo $prod['precio']; ?>">
                    <input type="hidden" name="cliente" value="<?php echo htmlspecialchars($nombre_usuario); ?>">
                    
                    <label>Cantidad:</label>
                    <input type="number" name="cantidad" value="1" min="1" required>
                    
                    <button type="submit">Comprar ahora</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

<fotter>  Victor Didier Rivera Sánchez     5ºI    0648    Programación </fotter>
    
</body>
</html>
<?php $conn->close(); ?>
