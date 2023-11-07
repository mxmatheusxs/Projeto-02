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

$sql = "SELECT * FROM salas_conferencia";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visualização de Salas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Visualização de Salas</h1>
    
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Número da Sala</th>
                <th>Localização</th>
                <th>Capacidade Máxima</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["numero_sala"] . "</td>";
                    echo "<td>" . $row["localizacao"] . "</td>";
                    echo "<td>" . $row["capacidade"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Nenhuma sala disponível.</td></tr>";
            }
            ?>
        </table>
        
        <p><a href="agendar_sala.php">Reservar Sala </a></p>
        <p><a href="selecionar_equipamentos.php">Reservar Sala com Equipamentos</a></p>
        <p><a href="agendamentos.php"> Meus Agendamentos</a></p>
    
        <p><a href="dashboard.php">Voltar ao Painel</a></p>
        <p><a href="logout.php">Sair</a></p>
    </div>

</body>
</html>
