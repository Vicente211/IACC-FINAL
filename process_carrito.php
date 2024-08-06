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

$conn->close();
?>
