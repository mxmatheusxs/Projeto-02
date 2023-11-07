CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE salas_conferencia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_sala VARCHAR(10) NOT NULL,
    localizacao VARCHAR(50) NOT NULL,
    capacidade INT NOT NULL
);

CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sala_id INT,
    usuario_id INT,
    data_agendamento DATE,
    hora_agendamento TIME,
    FOREIGN KEY (sala_id) REFERENCES salas_conferencia(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);


CREATE TABLE agendamento_equipamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agendamento_id INT NOT NULL,
    equipamento_id INT NOT NULL,
    FOREIGN KEY (agendamento_id) REFERENCES agendamentos(id),
    FOREIGN KEY (equipamento_id) REFERENCES equipamentos(id)
);

CREATE TABLE equipamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT 
);

CREATE TABLE convidados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agendamento_id INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);