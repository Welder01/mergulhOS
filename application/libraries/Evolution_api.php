<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Evolution_api
{
    protected $CI;
    protected $apiUrl;
    protected $apiKey;
    protected $instanceName;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('mapos_model');
        $this->CI->load->model('evolution_model');

        $this->apiUrl = $this->CI->mapos_model->get_ci_config('evolution_api_url');
        $this->apiKey = $this->CI->mapos_model->get_ci_config('evolution_api_key');
        $this->instanceName = $this->CI->mapos_model->get_ci_config('evolution_api_instance');
    }

    /**
     * Send text message via Evolution API
     *
     * @param string $number Phone number (only digits)
     * @param string $message Text message
     * @param int $delay Delay in milliseconds
     * @param string $presence Presence status (composing, recording, etc)
     * @return array Response from API or Error
     */
    public function sendText($number, $message, $delay = 1200, $presence = 'composing')
    {
        if (empty($this->apiUrl) || empty($this->apiKey) || empty($this->instanceName)) {
            return ['success' => false, 'message' => 'Configurações da API Evolution incompletas.'];
        }

        $url = rtrim($this->apiUrl, '/') . "/message/sendText/{$this->instanceName}";

        // Formatting message logic copied/adapted from Controller
        $text = $message;

        // 1. HTML Entity Decode
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace(["\xc2\xa0", "&nbsp;"], ' ', $text);

        // 2. Formatting Paragraphs
        $text = str_replace('</p>', "\n\n", $text);
        $text = preg_replace('/<br\s?\/?>/i', "\n", $text);
        $text = str_replace('<p>', '', $text);

        // 3. Remove Empty Tags
        $text = preg_replace('/<(b|strong|i|em|s|strike|del)[^>]*>\s*<\/\1>/iu', '', $text);

        // 4. Convert HTML tags to WhatsApp Markdown
        $text = preg_replace(['/<b>\s*/iu', '/\s*<\/b>/iu', '/<strong>\s*/iu', '/\s*<\/strong>/iu'], '*', $text);
        $text = preg_replace(['/<i>\s*/iu', '/\s*<\/i>/iu', '/<em>\s*/iu', '/\s*<\/em>/iu'], '_', $text);
        $text = preg_replace(['/<s>\s*/iu', '/\s*<\/s>/iu', '/<strike>\s*/iu', '/\s*<\/strike>/iu', '/<del>\s*/iu', '/\s*<\/del>/iu'], '~', $text);

        // 5. Strip remaining tags
        $plainTextMessage = trim(strip_tags($text));
        $plainTextMessage = preg_replace("/\n{3,}/", "\n\n", $plainTextMessage);
        $plainTextMessage = preg_replace('/[ \t]+/', ' ', $plainTextMessage);

        $payload = [
            'number' => $number,
            'options' => [
                'delay' => (int) $delay,
                'presence' => $presence,
                'linkPreview' => false
            ],
            'text' => $plainTextMessage
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => ["Content-Type: application/json", "apikey: {$this->apiKey}"],
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);

        // Log result
        $this->CI->evolution_model->add('evolution_logs', [
            'timestamp' => date('Y-m-d H:i:s'),
            'endpoint' => $url,
            'phone_number' => $number,
            'request_payload' => json_encode($payload),
            'response_code' => $httpcode,
            'response_body' => $response,
            'curl_error' => $err,
        ]);

        if ($httpcode >= 200 && $httpcode < 300 && !$err) {
            return ['success' => true, 'response' => $response];
        } else {
            return ['success' => false, 'error' => $err, 'http_code' => $httpcode, 'response' => $response];
        }
    }
}
