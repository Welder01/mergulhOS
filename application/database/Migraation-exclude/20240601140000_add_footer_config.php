<?php

class Migration_add_footer_config extends CI_Migration
{
    public function up()
    {
        $this->db->query("INSERT INTO `configuracoes` (`config`, `valor`) VALUES ('app_footer', '2025 © Ramon Silva - Map-OS') ON DUPLICATE KEY UPDATE `config` = 'app_footer';");
    }

    public function down()
    {
        $this->db->query("DELETE FROM `configuracoes` WHERE `config` = 'app_footer'");
    }
}