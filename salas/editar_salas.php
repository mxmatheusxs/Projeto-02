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

$id = $numero_sala = $localizacao = $capacidade = '';
$edit_mode = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_sala = $_POST["numero_sala"];
    $localizacao = $_POST["localizacao"];
    $capacidade = $_POST["capacidade"];

    if (isset($_POST["incluir"])) {
        $sql = "INSERT INTO salas_conferencia (numero_sala, localizacao, capacidade) VALUES ('$numero_sala', '$localizacao', '$capacidade')";
    } elseif (isset($_POST["editar"])) {
        $id = $_POST["id"];
        $sql = "UPDATE salas_conferencia SET numero_sala = '$numero_sala', localizacao = '$localizacao', capacidade = '$capacidade' WHERE id = '$id'";
        $edit_mode = false;
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: editar_salas.php");
        exit;
    } else {
        echo "Erro na operação: " . $conn->error;
    }
}

if (isset($_GET["edit"])) {
    $id = $_GET["edit"];
    $edit_mode = true;
    $sql = "SELECT * FROM salas_conferencia WHERE id = '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $numero_sala = $row["numero_sala"];
        $localizacao = $row["localizacao"];
        $capacidade = $row["capacidade"];
    }
}

$sql = "SELECT * FROM salas_conferencia";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro/Edição de Salas</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="container">
        <h1>Registro/Edição de Salas</h1>

        <form method="POST" action="editar_salas.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            Número da Sala: <input type="text" name="numero_sala" value="<?php echo $numero_sala; ?>" required><br>
            Localização: <input type="text" name="localizacao" value="<?php echo $localizacao; ?>" required><br>
            Capacidade Máxima: <input type="number" name="capacidade" value="<?php echo $capacidade; ?>" required><br>
            <?php if ($edit_mode) : ?>
                <input type="submit" name="editar" value="Editar">
            <?php else : ?>
                <input type="submit" name="incluir" value="Incluir">
            <?php endif; ?>
        </form>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>Número da Sala</th>
                <th>Localização</th>
                <th>Capacidade Máxima</th>
                <th>Ação</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["numero_sala"] . "</td>";
                    echo "<td>" . $row["localizacao"] . "</td>";
                    echo "<td>" . $row["capacidade"] . "</td>";
                    echo "<td><a href='editar_salas.php?edit=" . $row["id"] . "'>Editar</a> | <a href='excluir_sala.php?id=" . $row["id"] . "'>Excluir</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nenhuma sala disponível.</td></tr>";
            }
            ?>
        </table>

        <p><a href="visualizar_salas.php">Visualizar Salas</a></p>
        <p><a href="agendamentos.php">Meus Agendamentos</a></p>
        <p><a href="dashboard.php">Voltar ao Painel</a></p>
        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>
