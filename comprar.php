<?php
$conn = new mysqli("localhost", "root", "", "mi_base_datos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente = $_POST['cliente'];
    $producto = $_POST['producto'];
    $precio = (float)$_POST['precio'];
    $cantidad = (int)$_POST['cantidad'];
    $total = $precio * $cantidad;

    $stmt = $conn->prepare("INSERT INTO pedidos (cliente, producto_nombre, cantidad, total) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssid", $cliente, $producto, $cantidad, $total);

    if ($stmt->execute()) {
        echo "<h2> ¡Compra registrada!</h2>";
        echo "<p>Gracias <b>$cliente</b>, compraste $cantidad x $producto por un total de <b>$$total</b>.</p>";
        echo "<a href='tienda.php'>Volver a la tienda</a>";
    } else {
        echo "Error al registrar compra: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>