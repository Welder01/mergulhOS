<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Update_certificacoes extends CI_Migration
{
    public function up()
    {
        // Adiciona o campo numero_certificacao
        if (!$this->db->field_exists('numero_certificacao', 'certificacoes_mergulhador')) {
            $this->dbforge->add_column('certificacoes_mergulhador', [
                'numero_certificacao' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true, 'after' => 'orgao_emissor'],
            ]);
        }

        // Adiciona a configuração para tipos de certificadoras
        $certificadoras = 'PADI,NAUI,SSI,CMAS,RAID,IANTD,GUE';
        $this->db->query("INSERT INTO configuracoes (config, valor) VALUES ('certificadora_tipos', '{$certificadoras}') ON DUPLICATE KEY UPDATE valor = VALUES(valor);");
    }

    public function down()
    {
        if ($this->db->field_exists('numero_certificacao', 'certificacoes_mergulhador')) {
            $this->dbforge->drop_column('certificacoes_mergulhador', 'numero_certificacao');
        }
        $this->db->query("DELETE FROM configuracoes WHERE config = 'certificadora_tipos'");
    }
}