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

if (isset($_GET["excluir"]) && is_numeric($_GET["excluir"])) {
    $convidado_id = $_GET["excluir"];

    $sql_delete = "DELETE FROM convidados WHERE id = '$convidado_id'";
    if ($conn->query($sql_delete) === TRUE) {
        echo "Convidado excluído com sucesso.";
    } else {
        echo "Erro na exclusão do convidado: " . $conn->error;
    }
}

$sql_convidados = "SELECT convidados.id, convidados.nome, convidados.email, agendamentos.data_agendamento, agendamentos.hora_agendamento
                  FROM convidados
                  INNER JOIN agendamentos ON convidados.agendamento_id = agendamentos.id";
$result_convidados = $conn->query($sql_convidados);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Convidados Cadastrados</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="container">

        <h1>Convidados Cadastrados</h1>
    
        <table border="1">
            <tr>
                <th>Nome do Convidado</th>
                <th>Email do Convidado</th>
                <th>Data do Agendamento</th>
                <th>Hora do Agendamento</th>
                <th>Ações</th>
            </tr>
            <?php
            if ($result_convidados->num_rows > 0) {
                while ($row = $result_convidados->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["nome"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["data_agendamento"] . "</td>";
                    echo "<td>" . $row["hora_agendamento"] . "</td>";
                    echo "<td>";
                    echo "<a href='editar_convidado.php?id=" . $row["id"] . "'>Editar</a> | ";
                    echo "<a href='convidados.php?excluir=" . $row["id"] . "'>Excluir</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nenhum convidado cadastrado.</td></tr>";
            }
            ?>
        </table>
    
        <p><a href="cadastrar_convidados.php">Cadastrar Novo Convidados</a></p>
        <p><a href="agendamentos.php">Meus agendamentos</a></p>
    
    
        <p><a href="dashboard.php">Voltar ao Painel</a></p>
    
        <p><a href="logout.php">Sair</a></p>
    </div>

</body>
</html>
