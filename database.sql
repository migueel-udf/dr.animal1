-- =====================================================
-- BANCO DE DADOS: DR. ANIMAL PET SHOP
-- =====================================================
-- Script SQL para criar o banco de dados e tabelas
-- Compatível com MySQL 5.7+

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS dr_animal_petshop;
USE dr_animal_petshop;

-- =====================================================
-- TABELA: CLIENTES
-- =====================================================
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    telefone VARCHAR(20),
    endereco VARCHAR(255),
    cidade VARCHAR(50),
    estado VARCHAR(2),
    cep VARCHAR(10),
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
);

-- =====================================================
-- TABELA: PETS
-- =====================================================
CREATE TABLE IF NOT EXISTS pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    especie VARCHAR(50) NOT NULL,
    raca VARCHAR(100),
    data_nascimento DATE,
    peso DECIMAL(5, 2),
    cor VARCHAR(100),
    observacoes TEXT,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- =====================================================
-- TABELA: SERVICOS
-- =====================================================
CREATE TABLE IF NOT EXISTS servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    duracao_minutos INT,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
);

-- =====================================================
-- DADOS DE EXEMPLO
-- =====================================================

-- Inserir clientes de exemplo
INSERT INTO clientes (nome, email, telefone, endereco, cidade, estado, cep) VALUES
('João Silva', 'joao@email.com', '(11) 98765-4321', 'Rua A, 123', 'São Paulo', 'SP', '01234-567'),
('Maria Santos', 'maria@email.com', '(11) 99876-5432', 'Rua B, 456', 'São Paulo', 'SP', '01234-568'),
('Pedro Oliveira', 'pedro@email.com', '(21) 97654-3210', 'Avenida C, 789', 'Rio de Janeiro', 'RJ', '20000-000'),
('Ana Costa', 'ana@email.com', '(31) 98765-4321', 'Rua D, 321', 'Belo Horizonte', 'MG', '30100-000');

-- Inserir pets de exemplo
INSERT INTO pets (cliente_id, nome, especie, raca, data_nascimento, peso, cor) VALUES
(1, 'Max', 'Cão', 'Labrador', '2020-05-15', 32.50, 'Amarelo'),
(1, 'Luna', 'Gato', 'Siamês', '2021-03-20', 3.50, 'Branco e Marrom'),
(2, 'Rex', 'Cão', 'Pastor Alemão', '2019-08-10', 28.00, 'Preto e Marrom'),
(3, 'Bella', 'Gato', 'Persa', '2022-01-12', 4.20, 'Branco'),
(4, 'Charlie', 'Cão', 'Poodle', '2021-11-05', 5.50, 'Branco');

-- Inserir serviços de exemplo
INSERT INTO servicos (nome, descricao, preco, duracao_minutos) VALUES
('Banho', 'Banho completo com xampú e condicionador', 50.00, 30),
('Tosa', 'Tosa higiênica ou tosa completa', 80.00, 45),
('Banho + Tosa', 'Banho e tosa completa', 120.00, 75),
('Limpeza de Ouvidos', 'Limpeza e higiene dos ouvidos', 30.00, 15),
('Corte de Unhas', 'Corte e limpeza das unhas', 25.00, 15),
('Escovação de Dentes', 'Limpeza e escovação dos dentes', 35.00, 20),
('Consulta Veterinária', 'Consulta com veterinário', 100.00, 30);
