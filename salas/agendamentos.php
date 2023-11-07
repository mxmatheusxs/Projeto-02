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
    if (isset($_POST["cancelar"])) {
        $agendamento_id = $_POST["agendamento_id"];
        
        $sql_delete = "DELETE FROM agendamentos WHERE id = '$agendamento_id'";
        if ($conn->query($sql_delete) === TRUE) {
            echo "Agendamento cancelado com sucesso.";
        } else {
            echo "Erro no cancelamento do agendamento: " . $conn->error;
        }
    }
}

$usuario_id = $_SESSION["id"];

$sql_agendamentos = "SELECT agendamentos.id, salas_conferencia.numero_sala, agendamentos.data_agendamento, agendamentos.hora_agendamento
                    FROM agendamentos
                    INNER JOIN salas_conferencia ON agendamentos.sala_id = salas_conferencia.id
                    WHERE agendamentos.usuario_id = '$usuario_id'";
$result_agendamentos = $conn->query($sql_agendamentos);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agendamentos</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="container">
        <h1>Agendamentos</h1>

        <h2>Seus Agendamentos</h2>
        <table border="1">
            <tr>
                <th>ID do Agendamento</th>
                <th>Sala</th>
                <th>Data do Agendamento</th>
                <th>Hora do Agendamento</th>
                <th>Cancelar Agendamento</th>
                <th>Alterar Agendamento</th>
            </tr>
            <?php
            if ($result_agendamentos->num_rows > 0) {
                while ($row = $result_agendamentos->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["numero_sala"] . "</td>";
                    echo "<td>" . $row["data_agendamento"] . "</td>";
                    echo "<td>" . $row["hora_agendamento"] . "</td>";
                    echo "<td>
                            <form method='POST' action='agendamentos.php'>
                                <input type='hidden' name='agendamento_id' value='" . $row["id"] . "'>
                                <input type='submit' name='cancelar' value='Cancelar'>
                            </form>
                        </td>";
                    echo "<td><a href='editar_agendamento.php?id=" . $row["id"] . "'>Alterar</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Você não possui agendamentos.</td></tr>";
            }
            ?>
        </table>

        <p><a href="dashboard.php">Voltar ao Painel</a></p>
        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>

       