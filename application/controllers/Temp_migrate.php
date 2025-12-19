<?php
class Temp_migrate extends CI_Controller
{
    public function index()
    {
        $this->load->library('migration');
        if ($this->migration->version(20251219200000) === FALSE) {
            echo "Migration success. Current version: " . $this->migration->current() . "<br>";
            $files = $this->migration->find_migrations();
            echo "Available migrations:<br>";
            foreach ($files as $version => $file) {
                echo $version . ": " . basename($file) . "<br>";
            }
        } else {
            echo "Migration failed: " . $this->migration->error_string();
        }
    }
}
