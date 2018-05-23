--
-- MySQL 5.6.17
-- Tue, 17 May 2016 22:29:49 +0000
--

CREATE TABLE `bandeiras` (
   `id` int(11) not null auto_increment,
   `tipo` varchar(25) not null,
   `acrescimo` float not null,
   PRIMARY KEY (`id`),
   UNIQUE KEY (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

INSERT INTO `bandeiras` (`id`, `tipo`, `acrescimo`) VALUES 
('1', 'verde', '0'),
('2', 'amarelo', '0.015'),
('3', 'vermelho pat. 1', '0.03'),
('4', 'vermelho pat. 2', '0.04');

CREATE TABLE `clientes` (
   `id` int(11) not null auto_increment,
   `nome` varchar(50) not null,
   `email` varchar(40) not null,
   `senha` varchar(100) not null,
   `pergunta` varchar(50),
   `resposta` varchar(50),
   PRIMARY KEY (`id`),
   KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

INSERT INTO `clientes` (`id`, `nome`, `email`, `senha`, `pergunta`, `resposta`) VALUES 
('1', 'admin', 'admin@ecoeletri.com', '1902e47813428cdfc33fd97ea09f9001', 'dHJhYmFsaG8=', 'cGk=');

CREATE TABLE `consumo` (
   `id` int(11) not null auto_increment,
   `ano` int(11) not null,
   `mes` int(11) not null,
   `cliente` int(11) not null,
   `consumo` int(11) not null,
   `bandeira` int(11) not null,
   `acrescimo` decimal(11,2) not null,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- [A tabela 'consumo' está vazia]

CREATE TABLE `mensagens` (
   `id` int(11) not null auto_increment,
   `nome` varchar(50) not null,
   `email` varchar(40) not null,
   `mensagem` text not null,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- [A tabela 'mensagens' está vazia]

CREATE TABLE `paginas` (
   `id` int(11) not null auto_increment,
   `sigla` char(10) not null,
   `pagina` varchar(35) not null,
   `caminho` varchar(35) not null,
   PRIMARY KEY (`id`),
   UNIQUE KEY (`sigla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=8;

INSERT INTO `paginas` (`id`, `sigla`, `pagina`, `caminho`) VALUES 
('1', 'C', 'Calculadora', 'calculadora.php'),
('2', 'SC', 'Sua Conta', 'conta.php'),
('3', 'CT', 'Contato', 'contato.php'),
('4', 'N', 'Notícias', 'noticias.php'),
('5', 'CD', 'Cadastramento', 'cadastramento.php'),
('6', 'E', 'Recuperar Senha', 'rec_senha.php'),
('7', 'Q', 'Quem Somos Nós?', 'quem_somos.php');