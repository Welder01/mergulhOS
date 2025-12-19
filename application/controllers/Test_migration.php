<?php
class Test_migration extends CI_Controller
{
    public function index()
    {
        echo "Running Test_migration " . date('H:i:s');
        $this->load->library('migration');
        if ($this->migration->latest()) {
            echo "SUCCESS";
        } else {
            echo "FAIL: " . $this->migration->error_string();
        }
    }
}
