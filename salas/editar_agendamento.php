<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $agendamento_id = $_GET["id"];
} else {
    header("Location: agendamentos.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "sala");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["alterar"])) {
        $nova_data = $_POST["nova_data"];
        $nova_hora = $_POST["nova_hora"];
        
        // Atualizar as informações do agendamento no banco de dados
        $sql_update = "UPDATE agendamentos SET data_agendamento = '$nova_data', hora_agendamento = '$nova_hora' WHERE id = '$agendamento_id'";
        if ($conn->query($sql_update) === TRUE) {
            echo "Agendamento atualizado com sucesso.";
        } else {
            echo "Erro na atualização do agendamento: " . $conn->error;
        }
    }
}

$usuario_id = $_SESSION["id"];

// Consulta para buscar os detalhes do agendamento
$sql_agendamento = "SELECT agendamentos.id, salas_conferencia.numero_sala, agendamentos.data_agendamento, agendamentos.hora_agendamento
                    FROM agendamentos
                    INNER JOIN salas_conferencia ON agendamentos.sala_id = salas_conferencia.id
                    WHERE agendamentos.id = '$agendamento_id' AND agendamentos.usuario_id = '$usuario_id'";
$result_agendamento = $conn->query($sql_agendamento);

if ($result_agendamento->num_rows > 0) {
    $agendamento = $result_agendamento->fetch_assoc();
} else {
    header("Location: agendamentos.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Agendamento</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
  <div class="container">
    <h1>Editar Agendamento</h1>
    <form method="POST" action="editar_agendamento.php?id=<?php echo $agendamento_id; ?>">
        <label for="nova_data">Nova Data do Agendamento:</label>
        <input type="date" name="nova_data" id="nova_data" value="<?php echo $agendamento["data_agendamento"]; ?>" required><br>

        <label for="nova_hora">Nova Hora do Agendamento:</label>
        <input type="time" name="nova_hora" id="nova_hora" value="<?php echo $agendamento["hora_agendamento"]; ?>" required><br>

        <input type="submit" name="alterar" value="Alterar Agendamento">
    </form>

    <p><a href="dashboard.php">Voltar ao Painel</a></p>
    <p><a href="logout.php">Sair</a></p></body>
  </div>
</html>
