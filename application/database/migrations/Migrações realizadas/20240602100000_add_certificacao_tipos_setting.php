<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_certificacao_tipos_setting extends CI_Migration
{
    public function up()
    {
        $certificacoes = 'Scuba Diver,Open Water Diver,Advanced Open Water Diver,Rescue Diver,Master Scuba Diver,Divemaster,Enriched Air (Nitrox) Diver,Deep Diver,Wreck Diver,Night Diver,Peak Performance Buoyancy,Underwater Navigator,Dry Suit Diver,Search and Recovery Diver,Emergency First Response';
        $this->db->query("INSERT INTO configuracoes (config, valor) VALUES ('certificacao_tipos', '{$certificacoes}') ON DUPLICATE KEY UPDATE valor = VALUES(valor);");
    }

    public function down()
    {
        $this->db->query("DELETE FROM configuracoes WHERE config = 'certificacao_tipos'");
    }
}