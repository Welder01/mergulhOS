<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_user_permissions extends CI_Migration
{
    public function up()
    {
        // Pega as permissões atuais do Administrador
        $this->db->where('idPermissao', 1);
        $query = $this->db->get('permissoes', 1);
        $admin_permission = $query->row();

        if ($admin_permission) {
            $permissions = unserialize($admin_permission->permissoes);

            // Adiciona as novas permissões se não existirem
            $new_permissions = [
                'vUsuario' => '1',
                'aUsuario' => '1',
                'eUsuario' => '1',
                'dUsuario' => '1',
            ];

            foreach ($new_permissions as $key => $value) {
                if (!isset($permissions[$key])) {
                    $permissions[$key] = $value;
                }
            }

            $this->db->where('idPermissao', 1);
            $this->db->update('permissoes', ['permissoes' => serialize($permissions)]);
        }
    }

    public function down()
    {
        // Lógica para reverter, se necessário
    }
}