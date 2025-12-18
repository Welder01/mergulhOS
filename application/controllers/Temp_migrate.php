<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Temp_migrate extends CI_Controller
{
    public function index()
    {
        echo "<pre>";
        echo "DIAGNOSTIC START\n";

        $this->load->database();
        if ($this->db->table_exists('migrations')) {
            $row = $this->db->get('migrations')->row();
            echo "Current DB Version: " . ($row ? $row->version : 'None') . "\n";
        } else {
            echo "Table 'migrations' does not exist.\n";
        }

        $path = APPPATH . 'database/migrations/';
        echo "Migration Path: $path\n";
        $files = scandir($path);
        echo "Files:\n";
        foreach ($files as $f) {
            if ($f != '.' && $f != '..')
                echo " - $f\n";
        }

        $this->load->library('migration');
        echo "Running latest()...\n";
        if ($this->migration->latest() === FALSE) {
            echo "ERROR: " . $this->migration->error_string() . "\n";
        } else {
            echo "SUCCESS: Migrated to latest.\n";
        }
        echo "DIAGNOSTIC END";
    }
}
