<?php

class Migration_add_desconto_lancamentos_os_vendas extends CI_Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE `lancamentos` CHANGE `valor` `valor` DECIMAL(10,2) NOT NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `lancamentos` ADD `desconto` DECIMAL(10, 2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `lancamentos` ADD `valor_desconto` DECIMAL(10, 2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `os` CHANGE `valorTotal` `valorTotal` DECIMAL(10,2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `os` ADD `desconto` DECIMAL(10, 2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `os` ADD `valor_desconto` DECIMAL(10, 2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `vendas` CHANGE `valorTotal` `valorTotal` DECIMAL(10,2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `vendas` CHANGE `desconto` `desconto` DECIMAL(10,2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `vendas` ADD `valor_desconto` DECIMAL(10, 2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `cobrancas` CHANGE `total` `total` DECIMAL(10,2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `produtos_os` CHANGE `preco` `preco` DECIMAL(10,2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `produtos_os` CHANGE `subTotal` `subTotal` DECIMAL(10,2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `servicos_os` CHANGE `preco` `preco` DECIMAL(10,2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `servicos_os` CHANGE `subTotal` `subTotal` DECIMAL(10,2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `itens_de_vendas` CHANGE `subTotal` `subTotal` DECIMAL(10,2) NULL DEFAULT 0');
        $this->db->query('ALTER TABLE `itens_de_vendas` CHANGE `preco` `preco` DECIMAL(10,2) NULL DEFAULT 0');
        $this->db->query("INSERT INTO `configuracoes` (`idConfig`, `config`, `valor`) VALUES (14, 'email_automatico', 1) ON DUPLICATE KEY UPDATE valor = 1");
        $this->db->query("UPDATE permissoes SET permissoes = 'a:57:{s:8:\"aCliente\";s:1:\"1\";s:8:\"eCliente\";s:1:\"1\";s:8:\"dCliente\";s:1:\"1\";s:8:\"vCliente\";s:1:\"1\";s:8:\"aProduto\";s:1:\"1\";s:8:\"eProduto\";s:1:\"1\";s:8:\"dProduto\";s:1:\"1\";s:8:\"vProduto\";s:1:\"1\";s:8:\"aServico\";s:1:\"1\";s:8:\"eServico\";s:1:\"1\";s:8:\"dServico\";s:1:\"1\";s:8:\"vServico\";s:1:\"1\";s:3:\"aOs\";s:1:\"1\";s:3:\"eOs\";s:1:\"1\";s:3:\"dOs\";s:1:\"1\";s:3:\"vOs\";s:1:\"1\";s:6:\"aVenda\";s:1:\"1\";s:6:\"eVenda\";s:1:\"1\";s:6:\"dVenda\";s:1:\"1\";s:6:\"vVenda\";s:1:\"1\";s:9:\"aGarantia\";s:1:\"1\";s:9:\"eGarantia\";s:1:\"1\";s:9:\"dGarantia\";s:1:\"1\";s:9:\"vGarantia\";s:1:\"1\";s:8:\"aArquivo\";s:1:\"1\";s:8:\"eArquivo\";s:1:\"1\";s:8:\"dArquivo\";s:1:\"1\";s:8:\"vArquivo\";s:1:\"1\";s:10:\"aPagamento\";N;s:10:\"ePagamento\";N;s:10:\"dPagamento\";N;s:10:\"vPagamento\";N;s:11:\"aLancamento\";s:1:\"1\";s:11:\"eLancamento\";s:1:\"1\";s:11:\"dLancamento\";s:1:\"1\";s:11:\"vLancamento\";s:1:\"1\";s:6:\"aCurso\";s:1:\"1\";s:6:\"eCurso\";s:1:\"1\";s:6:\"dCurso\";s:1:\"1\";s:6:\"vCurso\";s:1:\"1\";s:8:\"cUsuario\";s:1:\"1\";s:9:\"cEmitente\";s:1:\"1\";s:10:\"cPermissao\";s:1:\"1\";s:7:\"cBackup\";s:1:\"1\";s:10:\"cAuditoria\";s:1:\"1\";s:6:\"cEmail\";s:1:\"1\";s:8:\"cSistema\";s:1:\"1\";s:8:\"rCliente\";s:1:\"1\";s:8:\"rProduto\";s:1:\"1\";s:8:\"rServico\";s:1:\"1\";s:3:\"rOs\";s:1:\"1\";s:6:\"rVenda\";s:1:\"1\";s:11:\"rFinanceiro\";s:1:\"1\";s:9:\"aCobranca\";s:1:\"1\";s:9:\"eCobranca\";s:1:\"1\";s:9:\"dCobranca\";s:1:\"1\";s:9:\"vCobranca\";s:1:\"1\";}' WHERE idPermissao = 1;");
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `lancamentos` DROP `desconto`');
        $this->db->query('ALTER TABLE `lancamentos` DROP `valor_desconto`');
        $this->db->query('ALTER TABLE `os` DROP `desconto`');
        $this->db->query('ALTER TABLE `os` DROP `valor_desconto`');
        $this->db->query('ALTER TABLE `vendas` DROP `desconto`');
        $this->db->query('ALTER TABLE `vendas` DROP `valor_desconto`');
        $this->db->query('DELETE FROM `configuracoes` WHERE `configuracoes`.`idConfig` = 14');
    }
}
