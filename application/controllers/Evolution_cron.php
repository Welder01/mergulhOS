<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Evolution_cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('evolution_queue');
    }

    /**
     * Process the Evolution Message Queue
     * Can be called via CLI: php index.php evolution_cron process
     * Or via URL: /evolution_cron/process
     */
    public function process($limit = 10)
    {
        // Optional: Check for CLI or specific permission if accessed via URL
        // if (!$this->input->is_cli_request()) { ... }

        echo "Processing Evolution Queue...\n";

        $result = $this->evolution_queue->process($limit);

        echo "Processed: " . $result['processed'] . "\n";
        echo "Success: " . $result['success'] . "\n";
        echo "Failed: " . $result['failed'] . "\n";
    }
}
