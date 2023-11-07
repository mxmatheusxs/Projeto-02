<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    $conexao = new mysqli("localhost", "root", "", "sala");

    if ($conexao->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
    }

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
    if ($conexao->query($sql) === TRUE) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro no cadastro: " . $conexao->error;
    }

    $conexao->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro</h1>
        <form method="POST" action="cadastro.php">
            Nome: <input type="text" name="nome" required><br>
            E-mail: <input type="email" name="email" required><br>
            Senha: <input type="password" name="senha" required><br>
            <input type="submit" value="Cadastrar">
        </form>

        <p>Já é cadastrado? Faça o <a href="login.php">login</a></p>
    </div>
</body>
</html>
