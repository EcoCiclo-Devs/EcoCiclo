SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `cadastrofuncionario` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `numero_registro` varchar(20) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cadastrofuncionario` (`id`, `nome`, `rg`, `cpf`, `numero_registro`, `cargo`, `senha`) VALUES
(13, 'Julia Nicioli', '21830921', '29467923846', '281943698264', 'dev', '213124');

ALTER TABLE `cadastrofuncionario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_registro` (`numero_registro`);

ALTER TABLE `cadastrofuncionario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;
