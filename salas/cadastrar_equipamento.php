<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "sala");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];

    $sql_insert = "INSERT INTO equipamentos (nome, descricao) VALUES ('$nome', '$descricao')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "Equipamento cadastrado com sucesso.";
    } else {
        echo "Erro no cadastro do equipamento: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Equipamentos</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Equipamentos</h1>
        <form method="POST" action="cadastrar_equipamento.php">
            <label for="nome">Nome do Equipamento:</label>
            <input type="text" name="nome" id="nome" required><br>
    
            <label for="descricao">Descrição do Equipamento:</label>
            <textarea name="descricao" id="descricao" rows="4" required></textarea><br>
    
            <input type="submit" name="cadastrar" value="Cadastrar Equipamento">
        </form>
    
        <p><a href="agendamentos.php">Meus agendamentos</a></p>
        <p><a href="dashboard.php">Voltar ao Painel</a></p>
        <p><a href="agendamentos.php">Sair</a></p>
    </div>

</body>
</html>
