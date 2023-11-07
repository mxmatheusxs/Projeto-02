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
    $agendamento_id = $_POST["agendamento_id"];
    $nome_convidado = $_POST["nome_convidado"];
    $email_convidado = $_POST["email_convidado"];

    $sql_insert = "INSERT INTO convidados (agendamento_id, nome, email) VALUES ('$agendamento_id', '$nome_convidado', '$email_convidado')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "Convidado cadastrado com sucesso.";
    } else {
        echo "Erro no cadastro do convidado: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Convidados</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Convidados</h1>
        <form method="POST" action="cadastrar_convidados.php">
            <label for="agendamento_id">ID do Agendamento:</label>
            <input type="text" name="agendamento_id" id="agendamento_id" required><br>
    
            <label for="nome_convidado">Nome do Convidado:</label>
            <input type="text" name="nome_convidado" id="nome_convidado" required><br>
    
            <label for="email_convidado">Email do Convidado:</label>
            <input type="email" name="email_convidado" id="email_convidado" required><br>
    
            <input type="submit" name="cadastrar" value="Cadastrar Convidado">
        </form>
    
        <p><a href="agendamentos.php">Meus agendamentos</a></p>
        <p><a href="dashboard.php">Voltar ao Painel</a></p>
        <p><a href="logout.php">Sair</a></p>
    </div>

</body>
</html>
