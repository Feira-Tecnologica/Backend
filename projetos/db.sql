CREATE DATABASE projetos;

CREATE TABLE projetos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    objetivo TEXT,
    justificativa TEXT
);

INSERT INTO projetos (nome, descricao, objetivo, justificativa) VALUES
('Projeto A', 'Descrição do Projeto A', 'Objetivo do Projeto A', 'Justificativa do Projeto A'),
('Projeto B', 'Descrição do Projeto B', 'Objetivo do Projeto B', 'Justificativa do Projeto B'),
('Projeto C', 'Descrição do Projeto C', 'Objetivo do Projeto C', 'Justificativa do Projeto C');