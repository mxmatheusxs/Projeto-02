<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php"); 
    exit;
}


$nomeUsuario = $_SESSION["nome"];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Painel de Controle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?php echo $nomeUsuario; ?>!</h1>
    
        <p><a href="visualizar_salas.php">Salas Disponíveis</a></p>
        <p><a href="agendar_sala.php">Reservar Sala </a></p>
        <p><a href="selecionar_equipamentos.php">Reservar Sala com Equipamentos</a></p>
        <p><a href="agendamentos.php">Meus Agendamentos</a></p>
        <p><a href="convidados.php">Meus Convidados</a></p>

        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>
