<?php

class Debug_events extends CI_Controller
{
    public function index()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        echo "<h1>Debug Evolution Events - " . date('Y-m-d H:i:s') . "</h1>";

        try {
            $this->load->database();
            $this->load->library('migration');

            echo "<h2>Forcing Migration...</h2>";

            if ($this->migration->latest()) {
                echo "<p style='color:green'>Migration SUCCESS. Current Version: " . $this->migration->current() . "</p>";
            } else {
                echo "<p style='color:red'>Migration FAILED: " . $this->migration->error_string() . "</p>";
            }

            // Check Table State AFTER migration
            if ($this->db->table_exists('evolution_eventos')) {
                echo "<p style='color:green'>Table now exists.</p>";
                $count = $this->db->count_all('evolution_eventos');
                echo "<p>Rows: $count</p>";

                $query = $this->db->get('evolution_eventos');
                echo "<table border='1'><tr><th>Evento</th></tr>";
                foreach ($query->result() as $row) {
                    echo "<tr><td>{$row->evento}</td></tr>";
                }
                echo "</table>";

            } else {
                echo "<p style='color:red'>Table STILL misses after migration attempt.</p>";
            }


        } catch (Throwable $e) {
            echo "<h2>EXCEPTION:</h2>";
            echo $e->getMessage();
        }
    }
}
