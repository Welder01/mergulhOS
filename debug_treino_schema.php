<?php
require 'index.php';

$ci = &get_instance();
$ci->load->database();

echo "<h1>Schema Check: Treinos</h1>";

echo "<h2>Tables matching 'treino'</h2>";
$tables = $ci->db->list_tables();
foreach ($tables as $table) {
    if (strpos($table, 'treino') !== false) {
        echo "<h3>$table</h3>";
        $q = $ci->db->query("DESCRIBE $table");
        if ($q) {
            echo "<pre>";
            print_r($q->result());
            echo "</pre>";
        }
    }
}
