-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/04/2026 às 02:27
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
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `data_coleta` date NOT NULL,
  `hora_coleta` time NOT NULL,
  `materiais` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `uf` char(2) DEFAULT NULL,
  `endereco` varchar(255) NOT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `tipo_residuo` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `nivel_lixo` int(11) NOT NULL DEFAULT 0,
  `dispositivo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ecopontos`
--

INSERT INTO `ecopontos` (`id`, `nome`, `cidade`, `uf`, `endereco`, `cep`, `numero`, `complemento`, `bairro`, `latitude`, `longitude`, `tipo_residuo`, `created_at`, `nivel_lixo`, `dispositivo_id`) VALUES
(1, 'Ecoponto Centro SP', 'São Paulo', NULL, 'Rua A, 123, São Paulo - SP', NULL, NULL, NULL, NULL, -23.55052000, -46.63330800, 'Plástico, Papel', '2026-03-09 00:52:11', 0, NULL),
(2, 'Ecoponto Copacabana', 'Rio de Janeiro', NULL, 'Av. B, 456, Rio de Janeiro - RJ', NULL, NULL, NULL, NULL, -22.90680000, -43.17290000, 'Vidro, Metal', '2026-03-09 00:52:11', 0, NULL),
(3, 'Ecoponto Savassi', 'Belo Horizonte', NULL, 'Praça C, 789, Belo Horizonte - MG', NULL, NULL, NULL, NULL, -19.91670000, -43.93450000, 'Óleo, Papel', '2026-03-09 00:52:11', 0, NULL),
(4, 'Lixeira 4', 'Itapira', NULL, 'Campinas', NULL, NULL, NULL, NULL, -22.94668300, -47.05972514, 'Vidro', '2026-03-09 01:11:21', 0, NULL),
(5, 'Ecoponto Centro', 'São Paulo', NULL, 'Rua A, 123, São Paulo - SP', NULL, NULL, NULL, NULL, -23.55052000, -46.63330800, 'Plástico, Papel', '2026-03-09 01:15:24', 68, NULL),
(6, 'Ecoponto Praia', 'Rio de Janeiro', NULL, 'Av. B, 456, Rio de Janeiro - RJ', NULL, NULL, NULL, NULL, -22.90680000, -43.17290000, 'Vidro, Metal', '2026-03-09 01:15:24', 42, NULL),
(7, 'Ecoponto Savassi', 'Belo Horizonte', NULL, 'Praça C, 789, Belo Horizonte - MG', NULL, NULL, NULL, NULL, -19.91670000, -43.93450000, 'Óleo, Papel', '2026-03-09 01:15:24', 87, NULL),
(14, 'Testando', 'Itapira', NULL, 'Rua Anita Garibaldi, 43 - 43 - Boa Vista - Itapira/SP - CEP: 13974-502', NULL, NULL, NULL, NULL, -22.43611100, -46.82166700, 'Papel', '2026-04-22 00:25:10', 0, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `esp32_dispositivos`
--

CREATE TABLE `esp32_dispositivos` (
  `id` int(11) NOT NULL,
  `nome_dispositivo` varchar(100) DEFAULT NULL,
  `device_token` varchar(100) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `esp32_dispositivos`
--

INSERT INTO `esp32_dispositivos` (`id`, `nome_dispositivo`, `device_token`, `ativo`) VALUES
(2, 'ESP32 Joao', 'TOKEN123', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_lixeiras`
--

CREATE TABLE `historico_lixeiras` (
  `id` int(11) NOT NULL,
  `ecoponto_id` int(11) NOT NULL,
  `distancia_cm` decimal(6,2) NOT NULL,
  `nivel_percentual` tinyint(3) UNSIGNED NOT NULL,
  `sinal_valido` tinyint(1) NOT NULL DEFAULT 1,
  `registrado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `status_lixeiras`
--

CREATE TABLE `status_lixeiras` (
  `id` int(11) NOT NULL,
  `ecoponto_id` int(11) DEFAULT NULL,
  `dispositivo_id` int(11) DEFAULT NULL,
  `distancia_cm` decimal(6,2) DEFAULT NULL,
  `nivel_percentual` int(11) DEFAULT NULL,
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `status_lixeiras`
--

INSERT INTO `status_lixeiras` (`id`, `ecoponto_id`, `dispositivo_id`, `distancia_cm`, `nivel_percentual`, `atualizado_em`) VALUES
(1, 10, 2, 10.00, 80, '2026-04-21 21:06:48'),
(2, 13, 2, 20.00, 57, '2026-04-21 21:24:54'),
(29, 14, 2, 19.00, 60, '2026-04-21 21:27:44');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`);

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
-- Índices de tabela `esp32_dispositivos`
--
ALTER TABLE `esp32_dispositivos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_token` (`device_token`);

--
-- Índices de tabela `historico_lixeiras`
--
ALTER TABLE `historico_lixeiras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ecoponto_data` (`ecoponto_id`,`registrado_em`);

--
-- Índices de tabela `status_lixeiras`
--
ALTER TABLE `status_lixeiras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ecoponto_id` (`ecoponto_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cadastrofuncionario`
--
ALTER TABLE `cadastrofuncionario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `ecopontos`
--
ALTER TABLE `ecopontos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `esp32_dispositivos`
--
ALTER TABLE `esp32_dispositivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `historico_lixeiras`
--
ALTER TABLE `historico_lixeiras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `status_lixeiras`
--
ALTER TABLE `status_lixeiras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `historico_lixeiras`
--
ALTER TABLE `historico_lixeiras`
  ADD CONSTRAINT `fk_historico_ecoponto` FOREIGN KEY (`ecoponto_id`) REFERENCES `ecopontos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
