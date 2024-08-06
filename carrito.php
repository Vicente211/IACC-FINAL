<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libreria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $libro_id = $_POST['libro_id'];
    $cantidad = $_POST['cantidad'];

    $sql = "INSERT INTO carrito (usuario_id, libro_id, cantidad)
            VALUES (" . $_SESSION['usuario_id'] . ", $libro_id, $cantidad)";

    if ($conn->query($sql) === TRUE) {
        echo "Libro añadido al carrito.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT libros.id, libros.título, libros.autor, libros.precio, carrito.cantidad
        FROM carrito
        INNER JOIN libros ON carrito.libro_id = libros.id
        WHERE carrito.usuario_id = " . $_SESSION['usuario_id'];
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container mt-5">
        <h2>Carrito de Compras</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $subtotal = $row['precio'] * $row['cantidad'];
                        $total += $subtotal;
                        echo "<tr>
                                <td>{$row['título']}</td>
                                <td>{$row['autor']}</td>
                                <td>{$row['precio']}</td>
                                <td>{$row['cantidad']}</td>
                                <td>$subtotal</td>
                            </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <p>Total: <?php echo $total; ?></p>
    </main>
</body>
</html>

<?php $conn->close(); ?>
