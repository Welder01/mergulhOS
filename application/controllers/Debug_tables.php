<?php
class Debug_tables extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        $tables = $this->db->list_tables();
        foreach ($tables as $table) {
            echo $table . "<br>";
        }
    }
}
