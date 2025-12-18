<?php
// Script to check columns of curso_instrutores
include 'index.php';

$CI =& get_instance();
$fields = $CI->db->list_fields('curso_instrutores');
echo "Columns in curso_instrutores:\n";
foreach ($fields as $field) {
    echo $field . "\n";
}
?>