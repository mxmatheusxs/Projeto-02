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
            $agendamento_id = $conn->insert_id;

            if (isset($_POST["equipamentos"])) {
                $equipamentos_selecionados = $_POST["equipamentos"];
                foreach ($equipamentos_selecionados as $equipamento_id) {
                    $sql_insert_equip = "INSERT INTO agendamento_equipamentos (agendamento_id, equipamento_id) VALUES ('$agendamento_id', '$equipamento_id')";
                    $conn->query($sql_insert_equip);
                }
            }

            echo "Sala agendada com sucesso.";
        } else {
            echo "Erro no agendamento: " . $conn->error;
        }
    }
}

$sql_salas = "SELECT * FROM salas_conferencia";
$result_salas = $conn->query($sql_salas);

$sql_equipamentos = "SELECT * FROM equipamentos";
$result_equipamentos = $conn->query($sql_equipamentos);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agendamento de Salas com Equipamentos</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="container">

        <h1>Agendamento de Salas com Equipamentos</h1>
    
        <form method="POST" action="selecionar_equipamentos.php">
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
    
            <label for="equipamentos">Selecione os equipamentos desejados:</label>
            <select name="equipamentos[]" id="equipamentos" multiple>
                <?php
                if ($result_equipamentos->num_rows > 0) {
                    while ($row = $result_equipamentos->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["nome"] . "</option>";
                    }
                } else {
                    echo "<option disabled>Nenhum equipamento disponível.</option>";
                }
                ?>
            </select><br>
    
            <input type="submit" name="agendar" value="Agendar Sala">
        </form>
    
        <p><a href="agendar_sala.php">Agendamento de Salas sem Equipamentos</a></p>
        
        <p><a href="cadastrar_convidados.php">Cadastrar Meus Convidados</a></p>
        <p><a href="agendamentos.php"> Meus Agendamentos</a></p>
    
        <p><a href="dashboard.php">Voltar ao painel</a></p>
    
        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>
