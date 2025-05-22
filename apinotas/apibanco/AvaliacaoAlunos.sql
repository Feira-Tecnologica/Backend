CREATE DATABASE IF NOT EXISTS AvaliacaoAlunos;

USE AvaliacaoAlunos;

CREATE TABLE
    IF NOT EXISTS avaliacoes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome_aluno VARCHAR(100) NOT NULL,
        nota1 FLOAT NOT NULL,
        nota2 FLOAT NOT NULL,
        nota3 FLOAT NOT NULL,
        media FLOAT,
        mencao VARCHAR(2),
        aprovado BOOLEAN DEFAULT 0
    );