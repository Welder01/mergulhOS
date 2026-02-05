SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';



-- -----------------------------------------------------
-- Table `ci_sessions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ci_sessions` (
        `id` varchar(128) NOT NULL,
        `ip_address` varchar(45) NOT NULL,
        `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
        `data` blob NOT NULL,
        KEY `ci_sessions_timestamp` (`timestamp`)
);


-- -----------------------------------------------------
-- Table `clientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `clientes` (
  `idClientes` INT(11) NOT NULL AUTO_INCREMENT,
  `asaas_id` VARCHAR(255) DEFAULT NULL,
  `nomeCliente` VARCHAR(255) NOT NULL,
  `sexo` VARCHAR(20) NULL,
  `data_nascimento` DATE NULL DEFAULT NULL,
  `altura` DECIMAL(5,2) NULL,
  `peso` DECIMAL(5,2) NULL,
  `pessoa_fisica` BOOLEAN NOT NULL DEFAULT 1,
  `documento` VARCHAR(20) NOT NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `celular` VARCHAR(20) NULL DEFAULT NULL,
  `email` VARCHAR(100) NOT NULL,
  `importacao_inconsistente` TINYINT(1) NOT NULL DEFAULT 0,
  `senha` VARCHAR(200) NOT NULL,
  `dataCadastro` DATE NULL DEFAULT NULL,
  `rua` VARCHAR(70) NULL DEFAULT NULL,
  `numero` VARCHAR(15) NULL DEFAULT NULL,
  `bairro` VARCHAR(45) NULL DEFAULT NULL,
  `cidade` VARCHAR(45) NULL DEFAULT NULL,
  `estado` VARCHAR(20) NULL DEFAULT NULL,
  `cep` VARCHAR(20) NULL DEFAULT NULL,
  `contato` varchar(45) DEFAULT NULL,
  `complemento` varchar(45) DEFAULT NULL,
  `fornecedor` BOOLEAN NOT NULL DEFAULT 0,
  `possui_regulador` BOOLEAN NOT NULL DEFAULT 0,
  `qtd_reguladores` INT NOT NULL DEFAULT 0,
  `qtd_lanterna` INT NOT NULL DEFAULT 0,
  `qtd_computador` INT NOT NULL DEFAULT 0,
  `tamanho_colete` VARCHAR(20) DEFAULT NULL,
  `peso_lastro` VARCHAR(20) DEFAULT NULL,
  `tamanho_neoprene` VARCHAR(20) DEFAULT NULL,
  `tamanho_nadadeira` VARCHAR(20) DEFAULT NULL,
  `contato_emergencia_nome` VARCHAR(255) DEFAULT NULL,
  `contato_emergencia_telefone` VARCHAR(20) DEFAULT NULL,
  `contato_emergencia_parentesco` VARCHAR(50) DEFAULT NULL,
  `atestado_medico_validade` DATE DEFAULT NULL,
  `nome_medico` VARCHAR(255) DEFAULT NULL,
  `crm_medico` VARCHAR(50) DEFAULT NULL,
  `codigo_validacao_atestado` VARCHAR(100) DEFAULT NULL,
  `atestado_medico_emissao` DATE DEFAULT NULL,
  `atestado_medico_arquivo` VARCHAR(255) DEFAULT NULL,
  `possui_colete` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_lastro` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_neoprene` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_nadadeira` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_lanterna` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_computador` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idClientes`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `resets_de_senha` ( 
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(200) NOT NULL , 
  `token` VARCHAR(255) NOT NULL , 
  `data_expiracao` DATETIME NOT NULL, 
  `token_utilizado` TINYINT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `categorias` (
  `idCategorias` INT NOT NULL AUTO_INCREMENT,
  `categoria` VARCHAR(80) NULL,
  `cadastro` DATE NULL,
  `status` TINYINT(1) NULL,
  `tipo` VARCHAR(15) NULL,
  PRIMARY KEY (`idCategorias`))
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `contas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contas` (
  `idContas` INT NOT NULL AUTO_INCREMENT,
  `conta` VARCHAR(45) NULL,
  `banco` VARCHAR(45) NULL,
  `numero` VARCHAR(45) NULL,
  `saldo` DECIMAL(10,2) NULL,
  `cadastro` DATE NULL,
  `status` TINYINT(1) NULL,
  `tipo` VARCHAR(80) NULL,
  PRIMARY KEY (`idContas`))
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `permissoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permissoes` (
  `idPermissao` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(80) NOT NULL,
  `permissoes` TEXT NULL,
  `situacao` TINYINT(1) NULL,
  `data` DATE NULL,
  PRIMARY KEY (`idPermissao`))
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
  `idUsuarios` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(80) NOT NULL,
  `sexo` VARCHAR(20) DEFAULT NULL,
  `altura` DECIMAL(5,2) DEFAULT NULL,
  `peso` DECIMAL(5,2) DEFAULT NULL,
  `rg` VARCHAR(20) NULL DEFAULT NULL,
  `cpf` VARCHAR(20) NOT NULL,
  `cep` VARCHAR(9) NOT NULL,
  `rua` VARCHAR(70) NULL DEFAULT NULL,
  `numero` VARCHAR(15) NULL DEFAULT NULL,
  `complemento` VARCHAR(45) DEFAULT NULL,
  `bairro` VARCHAR(45) NULL DEFAULT NULL,
  `cidade` VARCHAR(45) NULL DEFAULT NULL,
  `estado` VARCHAR(20) NULL DEFAULT NULL,
  `email` VARCHAR(80) NOT NULL,
  `senha` VARCHAR(200) NOT NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `celular` VARCHAR(20) NULL DEFAULT NULL,
  `contato` VARCHAR(45) DEFAULT NULL,
  `situacao` TINYINT(1) NOT NULL,
  `dataCadastro` DATE NOT NULL,
  `permissoes_id` INT NOT NULL,
  `dataExpiracao` date DEFAULT NULL,
  `url_image_user` VARCHAR(255) DEFAULT NULL,
  `tamanho_colete` VARCHAR(20) DEFAULT NULL,
  `peso_lastro` VARCHAR(20) DEFAULT NULL,
  `tamanho_neoprene` VARCHAR(20) DEFAULT NULL,
  `tamanho_nadadeira` VARCHAR(20) DEFAULT NULL,
  `qtd_reguladores` INT NOT NULL DEFAULT 0,
  `qtd_lanterna` INT NOT NULL DEFAULT 0,
  `qtd_computador` INT NOT NULL DEFAULT 0,
  `contato_emergencia_nome` VARCHAR(255) DEFAULT NULL,
  `contato_emergencia_telefone` VARCHAR(20) DEFAULT NULL,
  `contato_emergencia_parentesco` VARCHAR(50) DEFAULT NULL,
  `atestado_medico_validade` DATE DEFAULT NULL,
  `atestado_medico_arquivo` VARCHAR(255) DEFAULT NULL,
  `nome_medico` VARCHAR(255) DEFAULT NULL,
  `crm_medico` VARCHAR(50) DEFAULT NULL,
  `codigo_validacao_atestado` VARCHAR(100) DEFAULT NULL,
  `atestado_medico_emissao` DATE DEFAULT NULL,
  `possui_colete` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_lastro` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_neoprene` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_nadadeira` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_regulador` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_lanterna` TINYINT(1) NOT NULL DEFAULT 0,
  `possui_computador` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idUsuarios`),
  INDEX `fk_usuarios_permissoes1_idx` (`permissoes_id` ASC),
  CONSTRAINT `fk_usuarios_permissoes1`
    FOREIGN KEY (`permissoes_id`)
    REFERENCES `permissoes` (`idPermissao`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;



-- -----------------------------------------------------
-- Table `lancamentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lancamentos` (
  `idLancamentos` INT(11) NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(255) NULL DEFAULT NULL,
  `valor` DECIMAL(10, 2) NULL DEFAULT 0,
  `desconto` DECIMAL(10, 2) NULL DEFAULT 0,
  `valor_desconto` DECIMAL(10, 2) NULL DEFAULT 0,
  `tipo_desconto` varchar(8) NULL DEFAULT NULL,
  `data_vencimento` DATE NOT NULL,
  `data_pagamento` DATE NULL DEFAULT NULL,
  `baixado` TINYINT(1) NULL DEFAULT 0,
  `cliente_fornecedor` VARCHAR(255) NULL DEFAULT NULL,
  `forma_pgto` VARCHAR(100) NULL DEFAULT NULL,
  `tipo` VARCHAR(45) NULL DEFAULT NULL,
  `anexo` VARCHAR(250) NULL,
  `observacoes` TEXT NULL,
  `clientes_id` INT(11) NULL DEFAULT NULL,
  `pagar_usuario_id` INT DEFAULT NULL,
  `categorias_id` INT NULL,
  `contas_id` INT NULL,
  `vendas_id` INT NULL,
  `usuarios_id` INT NOT NULL,
  PRIMARY KEY (`idLancamentos`),
  INDEX `fk_lancamentos_clientes1` (`clientes_id` ASC),
  INDEX `fk_lancamentos_categorias1_idx` (`categorias_id` ASC),
  INDEX `fk_lancamentos_contas1_idx` (`contas_id` ASC),
  INDEX `fk_lancamentos_usuarios1` (`usuarios_id` ASC),
  CONSTRAINT `fk_lancamentos_clientes1`
    FOREIGN KEY (`clientes_id`)
    REFERENCES `clientes` (`idClientes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lancamentos_categorias1`
    FOREIGN KEY (`categorias_id`)
    REFERENCES `categorias` (`idCategorias`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lancamentos_contas1`
    FOREIGN KEY (`contas_id`)
    REFERENCES `contas` (`idContas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lancamentos_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `usuarios` (`idUsuarios`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `Garantia`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `garantias` (
  `idGarantias` INT NOT NULL AUTO_INCREMENT,
  `dataGarantia` DATE NULL,
  `refGarantia` VARCHAR(15) NULL,
  `textoGarantia` TEXT NULL,
  `usuarios_id` INT(11) NULL,
  PRIMARY KEY (`idGarantias`),
  INDEX `fk_garantias_usuarios1` (`usuarios_id` ASC),
  CONSTRAINT `fk_garantias_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `usuarios` (`idUsuarios`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `os`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `os` (
  `idOs` INT(11) NOT NULL AUTO_INCREMENT,
  `dataInicial` DATE NULL DEFAULT NULL,
  `dataFinal` DATE NULL DEFAULT NULL,
  `garantia` VARCHAR(45) NULL DEFAULT NULL,
  `descricaoProduto` TEXT NULL DEFAULT NULL,
  `defeito` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(45) NULL DEFAULT NULL,
  `observacoes` TEXT NULL DEFAULT NULL,
  `laudoTecnico` TEXT NULL DEFAULT NULL,
  `valorTotal` DECIMAL(10, 2) NULL DEFAULT 0,
  `desconto`DECIMAL(10, 2) NULL DEFAULT 0,
  `valor_desconto` DECIMAL(10, 2) NULL DEFAULT 0,
  `tipo_desconto` varchar(8) NULL DEFAULT NULL,
  `clientes_id` INT(11) NOT NULL,
  `usuarios_id` INT(11) NOT NULL,
  `lancamento` INT(11) NULL DEFAULT NULL,
  `faturado` TINYINT(1) NOT NULL,
  `garantias_id` int(11) NULL,
  PRIMARY KEY (`idOs`),
  INDEX `fk_os_clientes1` (`clientes_id` ASC),
  INDEX `fk_os_usuarios1` (`usuarios_id` ASC),
  INDEX `fk_os_lancamentos1` (`lancamento` ASC),
  INDEX `fk_os_garantias1` (`garantias_id` ASC),
  CONSTRAINT `fk_os_clientes1`
    FOREIGN KEY (`clientes_id`)
    REFERENCES `clientes` (`idClientes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_os_lancamentos1`
    FOREIGN KEY (`lancamento`)
    REFERENCES `lancamentos` (`idLancamentos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_os_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `usuarios` (`idUsuarios`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `produtos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `produtos` (
  `idProdutos` INT(11) NOT NULL AUTO_INCREMENT,
  `codDeBarra` VARCHAR(70) NOT NULL,
  `descricao` VARCHAR(80) NOT NULL,
  `unidade` VARCHAR(10) NULL DEFAULT NULL,
  `precoCompra` DECIMAL(10,2) NULL DEFAULT NULL,
  `precoVenda` DECIMAL(10,2) NOT NULL,
  `estoque` INT(11) NOT NULL,
  `estoqueMinimo` INT(11) NULL DEFAULT NULL,
  `saida`	TINYINT(1) NULL DEFAULT NULL,
  `entrada`	TINYINT(1) NULL DEFAULT NULL,
  PRIMARY KEY (`idProdutos`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `produtos_os`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `produtos_os` (
  `idProdutos_os` INT(11) NOT NULL AUTO_INCREMENT,
  `quantidade` INT(11) NOT NULL,
  `descricao` VARCHAR(80) NULL,
  `preco` DECIMAL(10,2) NULL DEFAULT 0,
  `os_id` INT(11) NOT NULL,
  `produtos_id` INT(11) NOT NULL,
  `subTotal` DECIMAL(10,2) NULL DEFAULT 0,
  PRIMARY KEY (`idProdutos_os`),
  INDEX `fk_produtos_os_os1` (`os_id` ASC),
  INDEX `fk_produtos_os_produtos1` (`produtos_id` ASC),
  CONSTRAINT `fk_produtos_os_os1`
    FOREIGN KEY (`os_id`)
    REFERENCES `os` (`idOs`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produtos_os_produtos1`
    FOREIGN KEY (`produtos_id`)
    REFERENCES `produtos` (`idProdutos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `servicos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `servicos` (
  `idServicos` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(45) NULL DEFAULT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`idServicos`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `servicos_os`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `servicos_os` (
  `idServicos_os` INT(11) NOT NULL AUTO_INCREMENT,
  `servico` VARCHAR(80) NULL,
  `quantidade` DOUBLE NULL,
  `preco` DECIMAL(10,2) NULL DEFAULT 0,
  `os_id` INT(11) NOT NULL,
  `servicos_id` INT(11) NOT NULL,
  `subTotal` DECIMAL(10,2) NULL DEFAULT 0,
  PRIMARY KEY (`idServicos_os`),
  INDEX `fk_servicos_os_os1` (`os_id` ASC),
  INDEX `fk_servicos_os_servicos1` (`servicos_id` ASC),
  CONSTRAINT `fk_servicos_os_os1`
    FOREIGN KEY (`os_id`)
    REFERENCES `os` (`idOs`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servicos_os_servicos1`
    FOREIGN KEY (`servicos_id`)
    REFERENCES `servicos` (`idServicos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `vendas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vendas` (
  `idVendas` INT NOT NULL AUTO_INCREMENT,
  `dataVenda` DATE NULL,
  `valorTotal` DECIMAL(10, 2) NULL DEFAULT 0,
  `desconto` DECIMAL(10, 2) NULL DEFAULT 0,
  `valor_desconto` DECIMAL(10, 2) NULL DEFAULT 0,
  `tipo_desconto` varchar(8) NULL DEFAULT NULL,
  `faturado` TINYINT(1) NULL,
  `observacoes` TEXT NULL,
  `observacoes_cliente` TEXT NULL,
  `clientes_id` INT(11) NOT NULL,
  `usuarios_id` INT(11) NULL,
  `lancamentos_id` INT(11) NULL,
  `status` VARCHAR(45) NULL,
  `garantia` INT(11) NULL,
  PRIMARY KEY (`idVendas`),
  INDEX `fk_vendas_clientes1` (`clientes_id` ASC),
  INDEX `fk_vendas_usuarios1` (`usuarios_id` ASC),
  INDEX `fk_vendas_lancamentos1` (`lancamentos_id` ASC),
  CONSTRAINT `fk_vendas_clientes1`
    FOREIGN KEY (`clientes_id`)
    REFERENCES `clientes` (`idClientes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vendas_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `usuarios` (`idUsuarios`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vendas_lancamentos1`
    FOREIGN KEY (`lancamentos_id`)
    REFERENCES `lancamentos` (`idLancamentos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


--
-- Estrutura da tabela `cobrancas`
--
CREATE TABLE IF NOT EXISTS `cobrancas` (
  `idCobranca` INT(11) NOT NULL AUTO_INCREMENT,
  `charge_id` varchar(255) DEFAULT NULL,
  `conditional_discount_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `custom_id` int(11) DEFAULT NULL,
  `expire_at` date NOT NULL,
  `message` varchar(255) NOT NULL,
  `payment_method` varchar(11) DEFAULT NULL,
  `payment_url` varchar(255) DEFAULT NULL,
  `request_delivery_address` varchar(64) DEFAULT NULL,
  `status` varchar(36) NOT NULL,
  `total` varchar(15) DEFAULT NULL,
  `barcode` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `payment_gateway` varchar(255) NULL DEFAULT NULL,
  `payment` varchar(64) NOT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `vendas_id` int(11) DEFAULT NULL,
  `os_id` int(11) DEFAULT NULL,
  `clientes_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCobranca`),
  INDEX `fk_cobrancas_os1` (`os_id` ASC),
  CONSTRAINT `fk_cobrancas_os1` FOREIGN KEY (`os_id`) REFERENCES `os` (`idOs`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  INDEX `fk_cobrancas_vendas1` (`vendas_id` ASC),
  CONSTRAINT `fk_cobrancas_vendas1` FOREIGN KEY (`vendas_id`) REFERENCES `vendas` (`idVendas`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  INDEX `fk_cobrancas_clientes1` (`clientes_id` ASC),
  CONSTRAINT `fk_cobrancas_clientes1` FOREIGN KEY (`clientes_id`) REFERENCES `clientes` (`idClientes`) ON DELETE NO ACTION ON UPDATE NO ACTION

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `itens_de_vendas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `itens_de_vendas` (
  `idItens` INT NOT NULL AUTO_INCREMENT,
  `subTotal` DECIMAL(10,2) NULL DEFAULT 0,
  `quantidade` INT(11) NULL,
  `preco` DECIMAL(10,2) NULL DEFAULT 0,
  `vendas_id` INT NOT NULL,
  `produtos_id` INT(11) NOT NULL,
  PRIMARY KEY (`idItens`),
  INDEX `fk_itens_de_vendas_vendas1` (`vendas_id` ASC),
  INDEX `fk_itens_de_vendas_produtos1` (`produtos_id` ASC),
  CONSTRAINT `fk_itens_de_vendas_vendas1`
    FOREIGN KEY (`vendas_id`)
    REFERENCES `vendas` (`idVendas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_itens_de_vendas_produtos1`
    FOREIGN KEY (`produtos_id`)
    REFERENCES `produtos` (`idProdutos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `anexos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `anexos` (
  `idAnexos` INT NOT NULL AUTO_INCREMENT,
  `anexo` VARCHAR(45) NULL,
  `thumb` VARCHAR(45) NULL,
  `url` VARCHAR(300) NULL,
  `path` VARCHAR(300) NULL,
  `os_id` INT(11) NOT NULL,
  PRIMARY KEY (`idAnexos`),
  INDEX `fk_anexos_os1` (`os_id` ASC),
  CONSTRAINT `fk_anexos_os1`
    FOREIGN KEY (`os_id`)
    REFERENCES `os` (`idOs`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `documentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `documentos` (
  `idDocumentos` INT NOT NULL AUTO_INCREMENT,
  `documento` VARCHAR(70) NULL,
  `descricao` TEXT NULL,
  `file` VARCHAR(100) NULL,
  `path` VARCHAR(300) NULL,
  `url` VARCHAR(300) NULL,
  `cadastro` DATE NULL,
  `categoria` VARCHAR(80) NULL,
  `tipo` VARCHAR(15) NULL,
  `tamanho` VARCHAR(45) NULL,
  PRIMARY KEY (`idDocumentos`))
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `marcas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `marcas` (
  `idMarcas` INT NOT NULL AUTO_INCREMENT,
  `marca` VARCHAR(100) NULL,
  `cadastro` DATE NULL,
  `situacao` TINYINT(1) NULL,
  PRIMARY KEY (`idMarcas`))
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `equipamentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `equipamentos` (
  `idEquipamentos` INT NOT NULL AUTO_INCREMENT,
  `equipamento` VARCHAR(150) NOT NULL,
  `num_serie` VARCHAR(80) NULL,
  `modelo` VARCHAR(80) NULL,
  `cor` VARCHAR(45) NULL,
  `descricao` VARCHAR(150) NULL,
  `tensao` VARCHAR(45) NULL,
  `potencia` VARCHAR(45) NULL,
  `voltagem` VARCHAR(45) NULL,
  `data_fabricacao` DATE NULL,
  `marcas_id` INT NULL,
  `clientes_id` INT(11) NULL,
  PRIMARY KEY (`idEquipamentos`),
  INDEX `fk_equipanentos_marcas1_idx` (`marcas_id` ASC),
  INDEX `fk_equipanentos_clientes1_idx` (`clientes_id` ASC),
  CONSTRAINT `fk_equipanentos_marcas1`
    FOREIGN KEY (`marcas_id`)
    REFERENCES `marcas` (`idMarcas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_equipanentos_clientes1`
    FOREIGN KEY (`clientes_id`)
    REFERENCES `clientes` (`idClientes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `equipamentos_os`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `equipamentos_os` (
  `idEquipamentos_os` INT NOT NULL AUTO_INCREMENT,
  `defeito_declarado` VARCHAR(200) NULL,
  `defeito_encontrado` VARCHAR(200) NULL,
  `solucao` VARCHAR(45) NULL,
  `equipamentos_id` INT NULL,
  `os_id` INT(11) NULL,
  PRIMARY KEY (`idEquipamentos_os`),
  INDEX `fk_equipamentos_os_equipanentos1_idx` (`equipamentos_id` ASC),
  INDEX `fk_equipamentos_os_os1_idx` (`os_id` ASC),
  CONSTRAINT `fk_equipamentos_os_equipanentos1`
    FOREIGN KEY (`equipamentos_id`)
    REFERENCES `equipamentos` (`idEquipamentos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_equipamentos_os_os1`
    FOREIGN KEY (`os_id`)
    REFERENCES `os` (`idOs`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- -----------------------------------------------------
-- Table `logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `logs` (
  `idLogs` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(80) NULL,
  `tarefa` VARCHAR(100) NULL,
  `data` DATE NULL,
  `hora` TIME NULL,
  `ip` VARCHAR(45) NULL,
  PRIMARY KEY (`idLogs`))
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `emitente`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `emitente` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(255) NULL ,
  `cnpj` VARCHAR(45) NULL ,
  `ie` VARCHAR(50) NULL ,
  `rua` VARCHAR(70) NULL ,
  `numero` VARCHAR(15) NULL ,
  `bairro` VARCHAR(45) NULL ,
  `cidade` VARCHAR(45) NULL ,
  `uf` VARCHAR(20) NULL ,
  `telefone` VARCHAR(20) NULL ,
  `email` VARCHAR(255) NULL ,
  `url_logo` VARCHAR(225) NULL ,
  `cep` VARCHAR(20) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `email_queue`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` varchar(255) NOT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('pending','sending','sent','failed') DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `headers` text,
  PRIMARY KEY (`id`)
)ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `anotacaoes_os`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `anotacoes_os` (
    `idAnotacoes` INT(11) NOT NULL AUTO_INCREMENT,
    `anotacao` VARCHAR(255) NOT NULL ,
    `data_hora` DATETIME NOT NULL ,
    `os_id` INT(11) NOT NULL ,
    PRIMARY KEY (`idAnotacoes`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `certificacoes_mergulhador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificacoes_mergulhador` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cliente_id` INT NOT NULL,
  `nome_certificacao` VARCHAR(255) NOT NULL,
  `orgao_emissor` VARCHAR(255) DEFAULT NULL,
  `numero_certificacao` VARCHAR(100) DEFAULT NULL,
  `data_emissao` DATE DEFAULT NULL,
  `data_validade` DATE DEFAULT NULL,
  `arquivo` VARCHAR(255) DEFAULT NULL,
  `observacoes` TEXT,
  `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_certificacao_cliente` (`cliente_id` ASC),
  CONSTRAINT `fk_certificacao_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`idClientes`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `certificacoes_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificacoes_usuario` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT NOT NULL,
  `nome_certificacao` VARCHAR(255) NOT NULL,
  `orgao_emissor` VARCHAR(255) DEFAULT NULL,
  `numero_certificacao` VARCHAR(100) DEFAULT NULL,
  `data_emissao` DATE DEFAULT NULL,
  `arquivo` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_certificacao_usuario` (`usuario_id` ASC),
  CONSTRAINT `fk_certificacao_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `cursos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cursos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome_curso` VARCHAR(255) NOT NULL,
  `descricao` TEXT,
  `data_inicio` DATE DEFAULT NULL,
  `data_fim` DATE DEFAULT NULL,
  `status` ENUM('ativo','inativo','em andamento','finalizado') NOT NULL DEFAULT 'ativo',
  `preco` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `vagas` INT NOT NULL DEFAULT '0',
  `vagas_total` INT NOT NULL DEFAULT '0',
  `data_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `cursos_os`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cursos_os` (
  `idCursos_os` INT NOT NULL AUTO_INCREMENT,
  `preco` DECIMAL(10,2) NOT NULL,
  `os_id` INT NOT NULL,
  `cursos_id` INT NOT NULL,
  `data_vinculo` DATE NOT NULL,
  `quantidade` INT NOT NULL DEFAULT '1',
  PRIMARY KEY (`idCursos_os`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `curso_alunos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `curso_alunos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `curso_id` INT UNSIGNED NOT NULL,
  `cliente_id` INT NOT NULL,
  `data_inscricao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_aluno` ENUM('inscrito','concluido','desistente') NOT NULL DEFAULT 'inscrito',
  PRIMARY KEY (`id`),
  INDEX `fk_curso_aluno_idx` (`curso_id` ASC),
  INDEX `fk_aluno_cliente_idx` (`cliente_id` ASC),
  CONSTRAINT `fk_aluno_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`idClientes`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_curso_aluno` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `curso_instrutores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `curso_instrutores` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `curso_id` INT UNSIGNED NOT NULL,
  `modulo_id` INT DEFAULT NULL,
  `usuario_cadastrou_id` INT DEFAULT NULL,
  `usuario_id` INT NOT NULL,
  `data_atribuicao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valor_pagamento` DECIMAL(10,2) DEFAULT '0.00',
  `tipo_pagamento` VARCHAR(20) DEFAULT 'aula',
  `status_pagamento` VARCHAR(50) DEFAULT 'pendente',
  `lancamento_id` INT UNSIGNED DEFAULT NULL,
  `aceite` TINYINT(1) DEFAULT NULL COMMENT '1=Aceito, 0=Recusado, NULL=Pendente',
  `data_aula` DATE DEFAULT NULL,
  `hora_inicio` TIME DEFAULT NULL,
  `hora_fim` TIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_curso_instrutor_idx` (`curso_id` ASC),
  INDEX `fk_instrutor_usuario_idx` (`usuario_id` ASC),
  CONSTRAINT `fk_curso_instrutor` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_instrutor_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `curso_modulos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `curso_modulos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `curso_id` INT UNSIGNED NOT NULL,
  `nome` VARCHAR(255) NOT NULL,
  `descricao` TEXT,
  `concluido` TINYINT(1) NOT NULL DEFAULT '0',
  `data_conclusao` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `curso_id` (`curso_id` ASC),
  CONSTRAINT `curso_modulos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `curso_modulo_instrutores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `curso_modulo_instrutores` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `curso_modulo_id` INT UNSIGNED NOT NULL,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `curso_modulo_id` (`curso_modulo_id` ASC),
  INDEX `usuario_id` (`usuario_id` ASC),
  CONSTRAINT `curso_modulo_instrutores_ibfk_1` FOREIGN KEY (`curso_modulo_id`) REFERENCES `curso_modulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `curso_modulo_instrutores_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `curso_requisitos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `curso_requisitos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `curso_id` INT UNSIGNED NOT NULL,
  `requisito` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `curso_id` (`curso_id` ASC),
  CONSTRAINT `curso_requisitos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `evolution_eventos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `evolution_eventos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `evento` VARCHAR(50) NOT NULL,
  `mensagem_id` INT UNSIGNED DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT '0',
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evento` (`evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `evolution_logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `evolution_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `timestamp` DATETIME NOT NULL,
  `endpoint` VARCHAR(255) DEFAULT NULL,
  `phone_number` VARCHAR(20) DEFAULT NULL,
  `request_payload` TEXT,
  `response_code` INT DEFAULT NULL,
  `response_body` TEXT,
  `curl_error` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `evolution_mensagens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `evolution_mensagens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NOT NULL,
  `mensagem` TEXT NOT NULL,
  `imagem_url` VARCHAR(1024) DEFAULT NULL,
  `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `evolution_queue`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `evolution_queue` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone_number` VARCHAR(20) NOT NULL,
  `message` TEXT NOT NULL,
  `options` TEXT,
  `status` ENUM('pending','sending','sent','failed') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `attempts` INT NOT NULL DEFAULT '0',
  `last_error` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `restricoes_alimentares`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restricoes_alimentares` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cliente_id` INT NOT NULL,
  `restricao` VARCHAR(255) NOT NULL,
  `observacoes` TEXT,
  `data_cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_restricao_cliente` (`cliente_id` ASC),
  CONSTRAINT `fk_restricao_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`idClientes`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `restricoes_alimentares_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restricoes_alimentares_usuario` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT NOT NULL,
  `restricao` VARCHAR(255) NOT NULL,
  `observacoes` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `treinos_agendados`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `treinos_agendados` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cliente_id` INT NOT NULL,
  `instrutor_id` INT DEFAULT NULL,
  `config_id` INT NOT NULL,
  `data_hora_inicio` DATETIME NOT NULL,
  `data_hora_fim` DATETIME NOT NULL,
  `valor_cobrado` DECIMAL(10,2) NOT NULL,
  `valor_pagamento` DECIMAL(10,2) DEFAULT '0.00',
  `tipo_pagamento` VARCHAR(20) DEFAULT 'fixo',
  `status_pagamento` VARCHAR(50) DEFAULT 'pendente',
  `lancamento_id` INT UNSIGNED DEFAULT NULL,
  `aceite` TINYINT(1) DEFAULT NULL COMMENT '1=Aceito, 0=Recusado, NULL=Pendente',
  `com_instrutor` TINYINT(1) NOT NULL DEFAULT '0',
  `status` VARCHAR(50) NOT NULL COMMENT 'Agendado, Concluido, Cancelado',
  `observacoes` TEXT,
  `os_id` INT DEFAULT NULL,
  `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `cliente_id` (`cliente_id` ASC),
  INDEX `instrutor_id` (`instrutor_id` ASC),
  INDEX `config_id` (`config_id` ASC),
  CONSTRAINT `treinos_agendados_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`idClientes`),
  CONSTRAINT `treinos_agendados_ibfk_2` FOREIGN KEY (`instrutor_id`) REFERENCES `usuarios` (`idUsuarios`),
  CONSTRAINT `treinos_agendados_ibfk_3` FOREIGN KEY (`config_id`) REFERENCES `treinos_config` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `treinos_config`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `treinos_config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `descricao` TEXT,
  `duracao_minutos` INT NOT NULL DEFAULT '60',
  `preco_sem_instrutor` DECIMAL(10,2) NOT NULL,
  `preco_com_instrutor` DECIMAL(10,2) NOT NULL,
  `dias_semana_disponiveis` VARCHAR(255) NOT NULL COMMENT 'Ex: 1,2,3,4,5 (Seg-Sex)',
  `horario_inicio` TIME NOT NULL,
  `horario_fim` TIME NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT '1',
  `instrutores_ids` TEXT,
  `limite_vagas` INT DEFAULT NULL COMMENT 'Limite máximo de vagas para o treino',
  `limite_alunos_instrutor` INT DEFAULT NULL COMMENT 'Limite de alunos por instrutor',
  `cancelamento_limite_dias` INT DEFAULT '0' COMMENT 'Limite de dias para cancelar',
  `cancelamento_limite_horas` INT DEFAULT '0' COMMENT 'Limite de horas para cancelar',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `viagens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viagens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome_viagem` VARCHAR(255) NOT NULL,
  `descricao` TEXT,
  `data_partida` DATETIME DEFAULT NULL,
  `data_retorno` DATETIME DEFAULT NULL,
  `vagas` INT NOT NULL DEFAULT '0',
  `vagas_total` INT DEFAULT NULL,
  `preco_pessoa` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `status` ENUM('Aberta','Concluida','Disponível','Indisponível','Adiada','Cancelada','Prorrogada','Prevista') NOT NULL DEFAULT 'Prevista',
  `curso_id` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_viagem_curso` (`curso_id` ASC),
  CONSTRAINT `fk_viagem_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `viagem_clientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viagem_clientes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `viagem_id` INT UNSIGNED NOT NULL,
  `cliente_id` INT NOT NULL,
  `status_pagamento` ENUM('Pendente','Pago','Parcial') DEFAULT 'Pendente',
  `precisa_embarque` TINYINT(1) DEFAULT '0',
  `precisa_hospedagem` TINYINT(1) DEFAULT '0',
  `detalhes_hospedagem` TEXT,
  `hospedagem_quarto_numero` VARCHAR(50) DEFAULT NULL,
  `hospedagem_tipo_quarto` ENUM('Solteiro','Casal','Família','Compartilhado') DEFAULT NULL,
  `hospedagem_numero_camas` INT DEFAULT NULL,
  `numero_bolsa` VARCHAR(50) DEFAULT NULL,
  `proposito` ENUM('Checkout','Acompanhante','Turismo','Batismo') DEFAULT NULL,
  `locar_nadadeira` TINYINT(1) DEFAULT '0',
  `locar_cilindro` TINYINT UNSIGNED DEFAULT '0',
  `locar_colete` TINYINT(1) DEFAULT '0',
  `locar_neoprene` TINYINT(1) DEFAULT '0',
  `locar_regulador` TINYINT UNSIGNED DEFAULT '0',
  `locar_lanterna` TINYINT(1) NOT NULL DEFAULT '0',
  `qtd_lanterna` INT NOT NULL DEFAULT '0',
  `locar_computador` TINYINT(1) NOT NULL DEFAULT '0',
  `qtd_computador` INT NOT NULL DEFAULT '0',
  `locar_lastro` TINYINT(1) DEFAULT '0',
  `data_inscricao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_viagem_cliente_viagem_idx` (`viagem_id` ASC),
  INDEX `fk_viagem_cliente_cliente_idx` (`cliente_id` ASC),
  CONSTRAINT `fk_viagem_cliente_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`idClientes`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_viagem_cliente_viagem` FOREIGN KEY (`viagem_id`) REFERENCES `viagens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `viagem_cursos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viagem_cursos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `viagem_id` INT UNSIGNED NOT NULL,
  `curso_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_viagem_cursos_viagem` (`viagem_id` ASC),
  INDEX `fk_viagem_cursos_curso` (`curso_id` ASC),
  CONSTRAINT `fk_viagem_cursos_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_viagem_cursos_viagem` FOREIGN KEY (`viagem_id`) REFERENCES `viagens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `viagem_custos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viagem_custos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `viagem_id` INT UNSIGNED NOT NULL,
  `descricao` VARCHAR(255) NOT NULL,
  `valor` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_viagem_custo_viagem` (`viagem_id` ASC),
  CONSTRAINT `fk_viagem_custo_viagem` FOREIGN KEY (`viagem_id`) REFERENCES `viagens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `viagem_instrutores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viagem_instrutores` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `viagem_id` INT UNSIGNED NOT NULL,
  `usuario_id` INT NOT NULL,
  `valor_pagamento` DECIMAL(10,2) DEFAULT '0.00',
  `tipo_pagamento` VARCHAR(20) DEFAULT 'fixo',
  `precisa_embarque` TINYINT(1) NOT NULL DEFAULT '0',
  `numero_bolsa` VARCHAR(50) DEFAULT NULL,
  `proposito` VARCHAR(50) DEFAULT NULL,
  `status_pagamento` VARCHAR(50) DEFAULT NULL,
  `lancamento_id` INT UNSIGNED DEFAULT NULL,
  `aceite` TINYINT(1) DEFAULT NULL COMMENT '1=Aceito, 0=Recusado, NULL=Pendente',
  `locar_nadadeira` TINYINT(1) DEFAULT '0',
  `locar_cilindro` INT NOT NULL DEFAULT '0',
  `locar_colete` TINYINT(1) DEFAULT '0',
  `locar_neoprene` TINYINT(1) DEFAULT '0',
  `locar_regulador` INT NOT NULL DEFAULT '0',
  `locar_lastro` TINYINT(1) DEFAULT '0',
  `precisa_hospedagem` TINYINT(1) DEFAULT '0',
  `detalhes_hospedagem` TEXT,
  `hospedagem_quarto_numero` VARCHAR(50) DEFAULT NULL,
  `hospedagem_tipo_quarto` ENUM('Solteiro','Casal','Família','Compartilhado') DEFAULT NULL,
  `hospedagem_numero_camas` INT DEFAULT NULL,
  `locar_lanterna` TINYINT(1) DEFAULT '0',
  `locar_computador` TINYINT(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `fk_viagem_instrutor_viagem` (`viagem_id` ASC),
  INDEX `fk_viagem_instrutor_usuario` (`usuario_id` ASC),
  CONSTRAINT `fk_viagem_instrutor_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_viagem_instrutor_viagem` FOREIGN KEY (`viagem_id`) REFERENCES `viagens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `viagens_os`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viagens_os` (
  `idViagens_os` INT NOT NULL AUTO_INCREMENT,
  `preco` DECIMAL(10,2) NOT NULL,
  `os_id` INT NOT NULL,
  `viagens_id` INT NOT NULL,
  `data_vinculo` DATE NOT NULL,
  `quantidade` INT NOT NULL DEFAULT '1',
  PRIMARY KEY (`idViagens_os`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `configuracoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `configuracoes` ( 
  `idConfig` INT NOT NULL AUTO_INCREMENT , `config` VARCHAR(20) NOT NULL UNIQUE, `valor` TEXT NULL , PRIMARY KEY (`idConfig`)
  ) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `migrations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrations` (
  `version` BIGINT(20) NOT NULL
);

INSERT IGNORE INTO `configuracoes` (`idConfig`, `config`, `valor`) VALUES
(2, 'app_name', 'Mergulho-OS'),
(3, 'app_theme', 'default'),
(4, 'per_page', '10'),
(5, 'os_notification', 'cliente'),
(6, 'control_estoque', '1'),
(7, 'notifica_whats', 'Prezado(a), {CLIENTE_NOME} a OS de nº {NUMERO_OS} teve o status alterado para: {STATUS_OS} segue a descrição {DESCRI_PRODUTOS} com valor total de {VALOR_OS}! Para mais informações entre em contato conosco. Atenciosamente, {EMITENTE} {TELEFONE_EMITENTE}.'),
(8, 'control_baixa', '0'),
(9, 'control_editos', '1'),
(10, 'control_datatable', '1'),
(11, 'pix_key', '47869259000137'),
(12, 'os_status_list', '[\"Aberto\",\"Or\\u00e7amento\",\"Negocia\\u00e7\\u00e3o\",\"Aprovado\",\"Em Andamento\",\"Finalizado\",\"Faturado\",\"Cancelado\"]'),
(13, 'control_edit_vendas', '1'),
(14, 'email_automatico', '1'),
(15, 'control_2vias', '0'),
(16, 'app_footer', '2025 © Welder\'s T.I. - Mergulho-OS'),
(17, 'certificacao_tipos', 'Scuba Diver,Open Water Diver,Advanced Open Water Diver,Rescue Diver,Master Scuba Diver,Divemaster,Nitrox,Deep Diver,Wreck Diver,'),
(18, 'certificadora_tipos', 'PADI,NAUI,SSI,CMAS,RAID,IANTD,GUE'),
(19, 'evolution_api_url', 'https://chatapi.paj.org.br'),
(20, 'evolution_api_key', '111D99F76C00-4D42-8BB9-09D1056784C5'),
(21, 'evolution_api_instance', 'isacbrasil'),
(22, 'evolution_presence', 'composing'),
(23, 'evolution_delay_fixo', '1200'),
(24, 'evolution_delay_min', '1200'),
(25, 'evolution_delay_max', '20000');

INSERT IGNORE INTO `permissoes` (`idPermissao`, `nome`, `permissoes`, `situacao`, `data`) VALUES
(1, 'Administrador', 'a:68:{s:8:\"aCliente\";s:1:\"1\";s:8:\"eCliente\";s:1:\"1\";s:8:\"dCliente\";s:1:\"1\";s:8:\"vCliente\";s:1:\"1\";s:8:\"aProduto\";s:1:\"1\";s:8:\"eProduto\";s:1:\"1\";s:8:\"dProduto\";s:1:\"1\";s:8:\"vProduto\";s:1:\"1\";s:8:\"aServico\";s:1:\"1\";s:8:\"eServico\";s:1:\"1\";s:8:\"dServico\";s:1:\"1\";s:8:\"vServico\";s:1:\"1\";s:3:\"aOs\";s:1:\"1\";s:3:\"eOs\";s:1:\"1\";s:3:\"dOs\";s:1:\"1\";s:3:\"vOs\";s:1:\"1\";s:6:\"aVenda\";s:1:\"1\";s:6:\"eVenda\";s:1:\"1\";s:6:\"dVenda\";s:1:\"1\";s:6:\"vVenda\";s:1:\"1\";s:9:\"aGarantia\";s:1:\"1\";s:9:\"eGarantia\";s:1:\"1\";s:9:\"dGarantia\";s:1:\"1\";s:9:\"vGarantia\";s:1:\"1\";s:9:\"aImportar\";s:1:\"1\";s:8:\"aArquivo\";s:1:\"1\";s:8:\"eArquivo\";s:1:\"1\";s:8:\"dArquivo\";s:1:\"1\";s:8:\"vArquivo\";s:1:\"1\";s:10:\"aPagamento\";N;s:10:\"ePagamento\";N;s:10:\"dPagamento\";N;s:10:\"vPagamento\";N;s:8:\"eEstorno\";s:1:\"1\";s:11:\"aLancamento\";s:1:\"1\";s:11:\"eLancamento\";s:1:\"1\";s:11:\"dLancamento\";s:1:\"1\";s:11:\"vLancamento\";s:1:\"1\";s:17:\"faturarAtribuicao\";s:1:\"1\";s:6:\"aCurso\";s:1:\"1\";s:6:\"eCurso\";s:1:\"1\";s:6:\"dCurso\";s:1:\"1\";s:6:\"vCurso\";s:1:\"1\";s:7:\"aViagem\";s:1:\"1\";s:7:\"eViagem\";s:1:\"1\";s:7:\"dViagem\";s:1:\"1\";s:7:\"vViagem\";s:1:\"1\";s:7:\"aTreino\";s:1:\"1\";s:7:\"eTreino\";s:1:\"1\";s:7:\"dTreino\";s:1:\"1\";s:7:\"vTreino\";s:1:\"1\";s:8:\"cUsuario\";s:1:\"1\";s:9:\"cEmitente\";s:1:\"1\";s:10:\"cPermissao\";s:1:\"1\";s:7:\"cBackup\";s:1:\"1\";s:10:\"cAuditoria\";s:1:\"1\";s:6:\"cEmail\";s:1:\"1\";s:8:\"cSistema\";s:1:\"1\";s:8:\"rCliente\";s:1:\"1\";s:8:\"rProduto\";s:1:\"1\";s:8:\"rServico\";s:1:\"1\";s:3:\"rOs\";s:1:\"1\";s:6:\"rVenda\";s:1:\"1\";s:11:\"rFinanceiro\";s:1:\"1\";s:9:\"aCobranca\";s:1:\"1\";s:9:\"eCobranca\";s:1:\"1\";s:9:\"dCobranca\";s:1:\"1\";s:9:\"vCobranca\";s:1:\"1\";}', 1, 'admin_created_at');

INSERT IGNORE INTO `usuarios` (`idUsuarios`, `nome`, `rg`, `cpf`, `cep`, `rua`, `numero`, `bairro`, `cidade`, `estado`, `email`, `senha`, `telefone`, `celular`, `situacao`, `dataCadastro`, `permissoes_id`,`dataExpiracao`) VALUES
(1, 'admin_name', 'MG-25.502.560', '600.021.520-87', '70005-115', 'Rua Acima', '12', 'Alvorada', 'Teste', 'MG', 'admin_email', 'admin_password', '000000-0000', '', 1, 'admin_created_at', 1, '3000-01-01');

INSERT IGNORE INTO `migrations`(`version`) VALUES ('20210125173741');

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
