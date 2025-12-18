<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Evolution_queue
{
    protected $CI;
    protected $table = 'evolution_queue';

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->CI->load->library('evolution_api');
    }

    /**
     * Add a message to the queue
     *
     * @param string $number Phone number
     * @param string $message Message Text
     * @param array $options Additional options (delay, presence, etc)
     * @return int|bool Insert ID or false
     */
    public function add($number, $message, $options = [])
    {
        $data = [
            'phone_number' => $number,
            'message' => $message,
            'options' => json_encode($options),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'attempts' => 0
        ];

        if ($this->CI->db->insert($this->table, $data)) {
            return $this->CI->db->insert_id();
        }
        return false;
    }

    /**
     * Process pending messages in the queue
     *
     * @param int $limit Max messages to process per run
     * @return array Result summary
     */
    public function process($limit = 5)
    {
        // 1. Fetch pending messages
        // Also retry failed ones if they haven't exceeded max attempts (e.g., 3)
        $this->CI->db->group_start();
        $this->CI->db->where('status', 'pending');
        $this->CI->db->or_group_start();
        $this->CI->db->where('status', 'failed');
        $this->CI->db->where('attempts <', 3);
        $this->CI->db->group_end();
        $this->CI->db->group_end();

        $this->CI->db->order_by('created_at', 'ASC'); // FIFO
        $this->CI->db->limit($limit);
        $messages = $this->CI->db->get($this->table)->result();

        $processed = 0;
        $success = 0;
        $failed = 0;

        foreach ($messages as $msg) {
            // Mark as sending to prevent double processing if Cron overlaps?
            // Ideally we should lock, but simple update is okay for now.
            $this->CI->db->where('id', $msg->id);
            $this->CI->db->update($this->table, ['status' => 'sending', 'updated_at' => date('Y-m-d H:i:s')]);

            $options = json_decode($msg->options, true) ?? [];
            $delay = $options['delay'] ?? 1200;
            $presence = $options['presence'] ?? 'composing';

            $result = $this->CI->evolution_api->sendText($msg->phone_number, $msg->message, $delay, $presence);

            $updateData = [
                'updated_at' => date('Y-m-d H:i:s'),
                'attempts' => $msg->attempts + 1
            ];

            if ($result['success']) {
                $updateData['status'] = 'sent';
                $updateData['last_error'] = null;
                $success++;
            } else {
                $updateData['status'] = 'failed';
                $updateData['last_error'] = isset($result['error']) ? $result['error'] : 'Unknown Error';
                $failed++;
            }

            $this->CI->db->where('id', $msg->id);
            $this->CI->db->update($this->table, $updateData);
            $processed++;

            // Optional: Sleep to respect rate limits if huge batch?
            // The API class handles connection timeout, but delay is passed to the API itself.
        }

        return [
            'processed' => $processed,
            'success' => $success,
            'failed' => $failed
        ];
    }

    /**
     * Count pending messages
     */
    public function count_pending()
    {
        return $this->CI->db->where('status', 'pending')->count_all_results($this->table);
    }
}
