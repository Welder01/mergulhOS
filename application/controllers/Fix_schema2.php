<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fix_schema2 extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo "<h1>Fixing Schema 2: Viagem Instrutores</h1>";

        // Check and add columns to treinos_agendados
        $sql_treino1 = "ALTER TABLE `treinos_agendados` ADD COLUMN `valor_pagamento` DECIMAL(10,2) DEFAULT '0.00' AFTER `valor_cobrado`";
        echo "Running SQL Treino 1...<br>";
        if (!$this->db->query($sql_treino1)) {
            echo "Error Treino 1: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Success Treino 1.<br>";
        }

        $sql_treino2 = "ALTER TABLE `treinos_agendados` ADD COLUMN `tipo_pagamento` VARCHAR(20) DEFAULT 'fixo' AFTER `valor_pagamento`";
        echo "Running SQL Treino 2...<br>";
        if (!$this->db->query($sql_treino2)) {
            echo "Error Treino 2: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Success Treino 2.<br>";
        }

        $sql_treino3 = "ALTER TABLE `treinos_agendados` ADD COLUMN `status_pagamento` VARCHAR(50) DEFAULT 'pendente' AFTER `tipo_pagamento`";
        echo "Running SQL Treino 3...<br>";
        if (!$this->db->query($sql_treino3)) {
            echo "Error Treino 3: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Success Treino 3.<br>";
        }

        $sql_treino_aceite = "ALTER TABLE `treinos_agendados` ADD COLUMN `aceite` TINYINT(1) DEFAULT NULL COMMENT '1=Aceito, 0=Recusado, NULL=Pendente' AFTER `status_pagamento`";
        echo "Running SQL Treino Aceite...<br>";
        if (!$this->db->query($sql_treino_aceite)) {
            echo "Error Treino Aceite: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Success Treino Aceite.<br>";
        }

        // Verify
        $fields_treinos = $this->db->list_fields('treinos_agendados');
        echo "<h3>Current Columns in treinos_agendados:</h3>";
        echo "<pre>";
        print_r($fields_treinos);
        echo "</pre>";

        // Raw SQL Force
        $sql1 = "ALTER TABLE `viagem_instrutores` ADD COLUMN `valor_pagamento` DECIMAL(10,2) DEFAULT '0.00' AFTER `usuario_id`";
        echo "Running SQL 1...<br>";
        if (!$this->db->query($sql1)) {
            echo "Error 1: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Success 1.<br>";
        }

        $sql2 = "ALTER TABLE `viagem_instrutores` ADD COLUMN `tipo_pagamento` VARCHAR(20) DEFAULT 'fixo' AFTER `valor_pagamento`";
        echo "Running SQL 2...<br>";
        if (!$this->db->query($sql2)) {
            echo "Error 2: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Success 2.<br>";
        }

        $sql3 = "ALTER TABLE `viagem_instrutores` ADD COLUMN `status_pagamento` VARCHAR(50) DEFAULT 'pendente' AFTER `tipo_pagamento`";
        echo "Running SQL 3...<br>";
        if (!$this->db->query($sql3)) {
            echo "Error 3: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Success 3.<br>";
        }

        // Viagens
        $sql_viagem_aceite = "ALTER TABLE `viagem_instrutores` ADD COLUMN `aceite` TINYINT(1) DEFAULT NULL COMMENT '1=Aceito, 0=Recusado, NULL=Pendente' AFTER `status_pagamento`";
        echo "Running SQL Viagem Aceite...<br>";
        if (!$this->db->query($sql_viagem_aceite)) {
            echo "Error Viagem Aceite: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Success Viagem Aceite.<br>";
        }

        // Cursos
        $sql_curso_aceite = "ALTER TABLE `curso_instrutores` ADD COLUMN `aceite` TINYINT(1) DEFAULT NULL COMMENT '1=Aceito, 0=Recusado, NULL=Pendente' AFTER `status_pagamento`";
        echo "Running SQL Curso Aceite...<br>";
        if (!$this->db->query($sql_curso_aceite)) {
            echo "Error Curso Aceite: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Success Curso Aceite.<br>";
        }

        // Verify
        $fields = $this->db->list_fields('viagem_instrutores');
        echo "<h3>Current Columns in viagem_instrutores:</h3>";
        echo "<pre>";
        print_r($fields);
        echo "</pre>";

        echo "<h3>Done.</h3>";
    }
}
