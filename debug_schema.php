<?php
require 'index.php';

$ci = &get_instance();
$ci->load->database();

echo "<h1>Schema: Curso Instrutores</h1>";
$query = $ci->db->query("DESCRIBE curso_instrutores");
echo "<pre>";
print_r($query->result());
echo "</pre>";
