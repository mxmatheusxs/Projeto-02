<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $agendamento_id = $_GET["id"];
} else {
    header("Location: agendar_sala.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "sala");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancelar"])) {
    $sql_delete = "DELETE FROM agendamentos WHERE id = '$agendamento_id'";
    if ($conn->query($sql_delete) === TRUE) {
        echo "Agendamento cancelado com sucesso.";
    } else {
        echo "Erro no cancelamento do agendamento: " . $conn->error;
    }
}

$usuario_id = $_SESSION["id"];
$sql_agendamento = "SELECT salas_conferencia.numero_sala, agendamentos.data_agendamento, agendamentos.hora_agendamento
                    FROM agendamentos
                    INNER JOIN salas_conferencia ON agendamentos.sala_id = salas_conferencia.id
                    WHERE agendamentos.id = '$agendamento_id' AND agendamentos.usuario_id = '$usuario_id'";
$result_agendamento = $conn->query($sql_agendamento);

if ($result_agendamento->num_rows > 0) {
    $agendamento = $result_agendamento->fetch_assoc();
} else {
    header("Location: agendar_sala.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cancelar Agendamento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Cancelar Agendamento</h1>

        <p>Tem certeza de que deseja cancelar o seguinte agendamento?</p>

        <table border="1">
            <tr>
                <th>Sala</th>
                <th>Data do Agendamento</th>
                <th>Hora do Agendamento</th>
            </tr>
            <tr>
                <td><?php echo $agendamento["numero_sala"]; ?></td>
                <td><?php echo $agendamento["data_agendamento"]; ?></td>
                <td><?php echo $agendamento["hora_agendamento"]; ?></td>
            </tr>
        </table>

        <form method="POST" action="cancelar_agendamento.php?id=<?php echo $agendamento_id; ?>">
            <input type="submit" name="cancelar" value="Cancelar Agendamento">
        </form>

        <p><a href="agendar_sala.php">Voltar ao Agendamento</a></p>
        <p><a href="dashboard.php">Voltar ao painel</a></p>
        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>
