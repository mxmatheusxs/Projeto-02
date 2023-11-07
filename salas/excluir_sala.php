<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $conn = new mysqli("localhost", "root", "", "sala");

    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $sql = "DELETE FROM salas_conferencia WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: editar_salas.php");
        exit;
    } else {
        echo "Erro na exclusão: " . $conn->error;
    }
}
?>
