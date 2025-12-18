<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Show_tables extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo "<h1>Schema Check: Treinos</h1>";

        $tables = $this->db->list_tables();
        foreach ($tables as $table) {
            if (strpos($table, 'treino') !== false) {
                echo "<h3>Table: $table</h3>";
                $fields = $this->db->list_fields($table);
                echo "<ul>";
                foreach ($fields as $field) {
                    echo "<li>$field</li>";
                }
                echo "</ul>";
            }
        }
    }
}
