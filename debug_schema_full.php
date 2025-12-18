<?php
require 'index.php';

$ci = &get_instance();
$ci->load->database();

echo "<h1>Schema Check</h1>";

echo "<h2>Curso Instrutores</h2>";
$q1 = $ci->db->query("DESCRIBE curso_instrutores");
if ($q1) {
    echo "<pre>";
    print_r($q1->result());
    echo "</pre>";
} else {
    echo "Query failed for curso_instrutores: " . $ci->db->error()['message'];
}

echo "<h2>Viagem Instrutores</h2>";
$q2 = $ci->db->query("DESCRIBE viagem_instrutores");
if ($q2) {
    echo "<pre>";
    print_r($q2->result());
    echo "</pre>";
} else {
    echo "Query failed for viagem_instrutores: " . $ci->db->error()['message'];
}
