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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agendar"])) {
    $sala_id = $_POST["sala_id"];
    $data_agendamento = $_POST["data_agendamento"];
    $hora_agendamento = $_POST["hora_agendamento"];
    $usuario_id = $_SESSION["id"];

    $sql_check = "SELECT * FROM agendamentos WHERE sala_id = '$sala_id' AND data_agendamento = '$data_agendamento' AND hora_agendamento = '$hora_agendamento'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "Sala já agendada para essa data e hora.";
    } else {
        $sql_insert = "INSERT INTO agendamentos (sala_id, usuario_id, data_agendamento, hora_agendamento) VALUES ('$sala_id', '$usuario_id', '$data_agendamento', '$hora_agendamento')";
        if ($conn->query($sql_insert) === TRUE) {
            echo "Sala agendada com sucesso.";
        } else {
            echo "Erro no agendamento: " . $conn->error;
        }
    }
}

$sql_salas = "SELECT * FROM salas_conferencia";
$result_salas = $conn->query($sql_salas);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agendamento de Salas</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="container">
        <h1>Agendamento de Salas</h1>
        <form method="POST" action="agendar_sala.php">
            <label for="sala_id">Selecione uma sala:</label>
            <select name="sala_id" id="sala_id" required>
                <option value="" disabled selected>Escolha uma sala</option>
                <?php
                if ($result_salas->num_rows > 0) {
                    while ($row = $result_salas->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["numero_sala"] . " - " . $row["localizacao"] . " (Capacidade: " . $row["capacidade"] . ")</option>";
                    }
                } else {
                    echo "<option disabled>Nenhuma sala disponível.</option>";
                }
                ?>
            </select><br>

            <label for="data_agendamento">Data do Agendamento:</label>
            <input type="date" name="data_agendamento" id="data_agendamento" required><br>

            <label for="hora_agendamento">Hora do Agendamento:</label>
            <input type="time" name="hora_agendamento" id="hora_agendamento" required><br>

            <input type="submit" name="agendar" value="Agendar Sala">
        </form>
            <p><a href="agendamentos.php">Meus agendamentos</a></p>       
            <p><a href="selecionar_equipamentos.php">Agendamento de Sala com Equipamentos</a></p>
            <p><a href="cadastrar_convidados.php">Cadastrar Meus Convidados</a></p>
            <p><a href="dashboard.php">Voltar ao Painel</a></p>
            <p><a href="logout.php">Sair</a></p>    
    </div>
</body>
</html>

    
    