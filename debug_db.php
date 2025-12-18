<?php
require 'index.php';

$ci = &get_instance();
$ci->load->database();

echo "<h1>Debug: Curso Instrutores</h1>";

// 1. List all assignments
$query = $ci->db->get('curso_instrutores');
echo "<h2>All Assignments in curso_instrutores:</h2>";
echo "<pre>";
print_r($query->result());
echo "</pre>";

// 2. List all users to check IDs
$queryUser = $ci->db->select('idUsuarios, nome, email')->get('usuarios');
echo "<h2>All Users:</h2>";
echo "<pre>";
print_r($queryUser->result());
echo "</pre>";
