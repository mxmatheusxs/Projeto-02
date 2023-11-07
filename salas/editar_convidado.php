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
    $convidado_id = $_POST["convidado_id"];
    $novo_nome = $_POST["novo_nome"];
    $novo_email = $_POST["novo_email"];

    $sql_update = "UPDATE convidados SET nome = '$novo_nome', email = '$novo_email' WHERE id = '$convidado_id'";
    if ($conn->query($sql_update) === TRUE) {
        echo "Informações do convidado atualizadas com sucesso.";
    } else {
        echo "Erro na atualização das informações do convidado: " . $conn->error;
    }
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $convidado_id = $_GET["id"];

    $sql_convidado = "SELECT * FROM convidados WHERE id = '$convidado_id'";
    $result_convidado = $conn->query($sql_convidado);

    if ($result_convidado->num_rows > 0) {
        $convidado = $result_convidado->fetch_assoc();
    } else {
        echo "Convidado não encontrado.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Convidado</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="container">
        <h1>Editar Convidado</h1>
    
        <form method="POST" action="editar_convidado.php">
            <input type="hidden" name="convidado_id" value="<?php echo $convidado_id; ?>">
    
            <label for="novo_nome">Novo Nome do Convidado:</label>
            <input type="text" name="novo_nome" id="novo_nome" value="<?php echo $convidado["nome"]; ?>" required><br>
    
            <label for="novo_email">Novo Email do Convidado:</label>
            <input type="email" name="novo_email" id="novo_email" value="<?php echo $convidado["email"]; ?>" required><br>
    
            <input type="submit" name="atualizar" value="Atualizar Informações">
        </form>
    
        <p><a href="convidados.php">Voltar à Lista de Convidados</a></p>
        <p><a href="dashboard.php">Voltar ao painel</a></p>
        <p><a href="logout.php">Sair</a></p>
    </div>

</body>
</html>
