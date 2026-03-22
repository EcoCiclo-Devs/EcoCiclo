-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/03/2026 às 17:09
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ecociclodb`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cadastrocidadao`
--

CREATE TABLE `cadastrocidadao` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'SP',
  `cidade` varchar(50) DEFAULT 'Itapira',
  `bairro` varchar(100) NOT NULL,
  `rua` varchar(100) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cadastrocidadao`
--

INSERT INTO `cadastrocidadao` (`id`, `nome`, `cep`, `estado`, `cidade`, `bairro`, `rua`, `cpf`, `email`, `senha`, `data_cadastro`, `is_admin`) VALUES
(0, 'Joao', '13974502', 'SP', 'Itapira', 'Boa vista', 'A', '123456789', 'joao.altafini05@gmail.com', '123456', '2026-03-08 22:42:29', 1),
(0, 'João Lucas Altafini Batista', '13974-502', 'SP', 'Itapira', 'Boa Vista', 'Rua Anita Garibaldi', '50722628803', 'jesusaltafini@gmail.com', '$2y$10$DFCkZIZB21jiP66uycsiZ.KyrF3UNzdqtwR6M2L/S5c1F4qVfcToi', '2026-03-08 23:06:02', 0),
(0, 'João Lucas Altafini Batista', '13974-502', 'SP', 'Itapira', 'Boa Vista', 'Rua Anita Garibaldi', '23', 'diretor@fatec.com.br', '$2y$10$S/5yihC1B3nfVQxqHHgrq.rtSiJy75yyqyhxfU4uFL7Qzx216dieS', '2026-03-08 23:26:58', 0),
(0, 'Vai Corinthians', '13974-502', 'SP', 'Itapira', 'Boa Vista', 'Rua Anita Garibaldi', '13649422', 'durinhodematar@gmail.com', '123', '2026-03-08 23:30:43', 0),
(0, 'Priscila Lins', '13674000', 'SP', 'Santa Rita do Passa Quatro', 'Vila Kennedy', 'Avenida Otávio Pavani', '123', 'priscila@gmail.com', '123', '2026-03-08 23:43:20', 0),
(0, 'Chupa Porco', '13974502', 'SP', 'Itapira', 'Boa Vista', 'Rua Anita Garibaldi', '123', 'durodematar@gmail.com', '123', '2026-03-08 23:47:19', 0),
(0, 'Chupa Palmeiras kkkkkk', '13974000', 'SP', 'Itapira', 'Santa Cruz', 'Rua Saldanha Marinho', '123', 'teste@gmail.com', '123', '2026-03-08 23:49:30', 0),
(0, 'João Lucas Altafini Batista', '13974-502', 'SP', 'Itapira', 'Boa Vista', 'Rua Anita Garibaldi', '123', 'jesusaltafini@gmail.com', '123', '2026-03-08 23:52:57', 0),
(0, 'João Lucas Altafini Batista', '13974-502', 'SP', 'Itapira', 'Boa Vista', 'Rua Anita Garibaldi', '12345679', 'joao.batista@gmail.com', '1234', '2026-03-12 22:24:53', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cadastrofuncionario`
--

CREATE TABLE `cadastrofuncionario` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `numero_registro` varchar(20) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cadastrofuncionario`
--

INSERT INTO `cadastrofuncionario` (`id`, `nome`, `rg`, `cpf`, `numero_registro`, `cargo`, `senha`) VALUES
(13, 'Julia Nicioli', '21830921', '29467923846', '281943698264', 'dev', '213124');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ecopontos`
--

CREATE TABLE `ecopontos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `tipo_residuo` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `nivel_lixo` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ecopontos`
--

INSERT INTO `ecopontos` (`id`, `nome`, `cidade`, `endereco`, `latitude`, `longitude`, `tipo_residuo`, `created_at`, `nivel_lixo`) VALUES
(1, 'Ecoponto Centro SP', 'São Paulo', 'Rua A, 123, São Paulo - SP', -23.55052000, -46.63330800, 'Plástico, Papel', '2026-03-09 00:52:11', 0),
(2, 'Ecoponto Copacabana', 'Rio de Janeiro', 'Av. B, 456, Rio de Janeiro - RJ', -22.90680000, -43.17290000, 'Vidro, Metal', '2026-03-09 00:52:11', 0),
(3, 'Ecoponto Savassi', 'Belo Horizonte', 'Praça C, 789, Belo Horizonte - MG', -19.91670000, -43.93450000, 'Óleo, Papel', '2026-03-09 00:52:11', 0),
(4, 'Lixeira 4', 'Itapira', 'Campinas', -22.94668300, -47.05972514, 'Vidro', '2026-03-09 01:11:21', 0),
(5, 'Ecoponto Centro', 'São Paulo', 'Rua A, 123, São Paulo - SP', -23.55052000, -46.63330800, 'Plástico, Papel', '2026-03-09 01:15:24', 68),
(6, 'Ecoponto Praia', 'Rio de Janeiro', 'Av. B, 456, Rio de Janeiro - RJ', -22.90680000, -43.17290000, 'Vidro, Metal', '2026-03-09 01:15:24', 42),
(7, 'Ecoponto Savassi', 'Belo Horizonte', 'Praça C, 789, Belo Horizonte - MG', -19.91670000, -43.93450000, 'Óleo, Papel', '2026-03-09 01:15:24', 87),
(8, 'Platisco', 'Itapira', 'Rua José Soares de Campos, Recreio Santa Fé, Itapira, Região Imediata de Mogi Guaçu, Região Geográfica Intermediária de Campinas, São Paulo, 13977-175, Brasil', -22.43755108, -46.84208942, 'Vidro', '2026-03-09 01:28:24', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cadastrofuncionario`
--
ALTER TABLE `cadastrofuncionario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_registro` (`numero_registro`);

--
-- Índices de tabela `ecopontos`
--
ALTER TABLE `ecopontos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cadastrofuncionario`
--
ALTER TABLE `cadastrofuncionario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `ecopontos`
--
ALTER TABLE `ecopontos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;