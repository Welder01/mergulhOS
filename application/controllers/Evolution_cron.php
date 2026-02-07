<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Evolution_cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('mapos_model');
        $this->load->library('evolution_queue');
    }

    /**
     * Process the Evolution Message Queue
     * Can be called via CLI: php index.php evolution_cron process
     * Or via URL: /evolution_cron/process
     */
    public function process($limit = 10)
    {
        // Marca como 'falhou' mensagens que excederam 3 tentativas para não serem processadas novamente
        $this->db->where('attempts >=', 3);
        $this->db->where_in('status', ['pending', 'error']);
        $this->db->update('evolution_queue', ['status' => 'falhou']);

        // Configurações da API
        $apiUrl = $this->mapos_model->get_ci_config('evolution_api_url');
        $apiKey = $this->mapos_model->get_ci_config('evolution_api_key');
        $instanceName = $this->mapos_model->get_ci_config('evolution_api_instance');

        if (empty($apiUrl) || empty($apiKey) || empty($instanceName)) {
            if ($this->input->is_cli_request()) echo "Configurações da API incompletas.\n";
            return;
        }

        // Busca itens da fila
        $this->db->where_in('status', ['pending', 'error']);
        $this->db->order_by('id', 'ASC');
        $this->db->limit($limit);
        $queue = $this->db->get('evolution_queue')->result();

        $processed = 0;
        $failed = 0;

        foreach ($queue as $item) {
            // Verifica se há mídia (na coluna ou nas opções)
            $mediaUrl = $item->media_url;
            if (empty($mediaUrl) && !empty($item->options)) {
                $opts = json_decode($item->options, true);
                if (isset($opts['media_url'])) {
                    $mediaUrl = $opts['media_url'];
                }
            }

            $cleanMessage = $this->cleanMessage($item->message);
            $body = [];
            $url = '';

            if (!empty($mediaUrl)) {
                $url = rtrim($apiUrl, '/') . "/message/sendMedia/{$instanceName}";
                
                // Extrai o nome do arquivo original
                $fileName = basename($mediaUrl);
                if (strpos($fileName, '?') !== false) {
                    $fileName = explode('?', $fileName)[0];
                }

                // Lógica para converter arquivos locais em Base64
                $localPath = '';
                
                // 1. Se não for URL (caminho absoluto ou relativo do sistema)
                if (strpos($mediaUrl, 'http') !== 0) {
                    $localPath = $mediaUrl;
                    $cleanPath = ltrim($mediaUrl, '/\\');
                    if (!file_exists($localPath) && file_exists(FCPATH . $cleanPath)) {
                        $localPath = FCPATH . $cleanPath;
                    }
                } 
                // 2. Tenta identificar se é local pela URL
                else {
                    $parsedUrl = parse_url($mediaUrl);
                    if (isset($parsedUrl['path'])) {
                        $relativePath = ltrim($parsedUrl['path'], '/');
                        
                        // Tenta encontrar o arquivo direto no FCPATH
                        if (file_exists(FCPATH . $relativePath)) {
                            $localPath = FCPATH . $relativePath;
                        } 
                        // Tenta remover o primeiro segmento (ex: subpasta do site)
                        else {
                            $pathParts = explode('/', $relativePath, 2);
                            if (count($pathParts) > 1 && file_exists(FCPATH . $pathParts[1])) {
                                $localPath = FCPATH . $pathParts[1];
                            }
                        }
                    }
                }

                if ($localPath) {
                    $localPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $localPath);
                    $localPath = explode('?', $localPath)[0]; 
                    
                    if (file_exists($localPath)) {
                        $fileData = file_get_contents($localPath);
                        $base64 = base64_encode($fileData);
                        $mime = mime_content_type($localPath);
                        if (!$mime) $mime = 'application/octet-stream';
                        $mediaUrl = $base64;
                    }
                }

                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $mediaType = 'document';
                $mimeType = 'application/octet-stream';

                $mimeTypes = [
                    'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp',
                    'mp4' => 'video/mp4', 'avi' => 'video/x-msvideo', 'mov' => 'video/quicktime', 'mkv' => 'video/x-matroska',
                    'mp3' => 'audio/mpeg', 'ogg' => 'audio/ogg', 'wav' => 'audio/wav',
                    'pdf' => 'application/pdf', 'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];

                if (isset($mimeTypes[$extension])) {
                    $mimeType = $mimeTypes[$extension];
                }
                
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $mediaType = 'image';
                } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'mkv'])) {
                    $mediaType = 'video';
                } elseif (in_array($extension, ['mp3', 'ogg', 'wav'])) {
                    $mediaType = 'audio';
                }

                $body = [
                    "number" => $item->phone_number,
                    "mediatype" => $mediaType,
                    "mimetype" => $mimeType,
                    "caption" => $cleanMessage,
                    "media" => $mediaUrl,
                    "fileName" => $fileName,
                    "delay" => 1200
                ];
            } else {
                $url = rtrim($apiUrl, '/') . "/message/sendText/{$instanceName}";
                
                $body = [
                    "number" => $item->phone_number,
                    "options" => [
                        "delay" => 1200,
                        "presence" => "composing",
                        "linkPreview" => false
                    ],
                    "text" => $cleanMessage
                ];
            }

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($body, JSON_UNESCAPED_SLASHES),
                CURLOPT_HTTPHEADER => [
                    "apikey: {$apiKey}",
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            // Log
            $this->db->insert('evolution_logs', [
                'timestamp' => date('Y-m-d H:i:s'),
                'phone_number' => $item->phone_number,
                'request_payload' => json_encode($body),
                'response_body' => $response,
                'response_code' => $httpCode,
                'curl_error' => $err
            ]);

            if ($err || ($httpCode < 200 || $httpCode >= 300)) {
                $failed++;
                $this->db->set('attempts', 'attempts+1', false);
                $this->db->set('last_error', $err ? "cURL: $err" : "API: $response");
                $this->db->where('id', $item->id);
                $this->db->update('evolution_queue');
            } else {
                $processed++;
                $this->db->delete('evolution_queue', ['id' => $item->id]);
            }
        }

        if ($this->input->is_cli_request()) {
            echo "Processed: " . $processed . "\n";
            echo "Failed: " . $failed . "\n";
        } else {
            header('Content-Type: application/json');
            echo json_encode(['processed' => $processed, 'failed' => $failed]);
        }
    }

    private function cleanMessage($message)
    {
        $message = html_entity_decode($message);
        $message = str_ireplace(['<br />', '<br>', '<br/>'], "\n", $message);
        $message = str_ireplace(['<p>', '</p>'], ['', "\n"], $message);
        $message = str_ireplace(['<b>', '</b>', '<strong>', '</strong>'], '*', $message);
        $message = str_ireplace(['<i>', '</i>', '<em>', '</em>'], '_', $message);
        $message = str_ireplace(['<s>', '</s>', '<strike>', '</strike>', '<del>', '</del>'], '~', $message);
        $message = strip_tags($message);
        return trim($message);
    }

    /**
     * Run Daily Checks (Cron: 0 9 * * *)
     * Checks for birthdays, due payments, and reminders.
     */
    public function daily_checks()
    {
        echo "Running Daily Checks...\n";
        $this->checkBirthdays();
        $this->checkDuePayments();
        $this->checkAppointments();
        echo "Daily Checks Completed.\n";
    }

    private function checkBirthdays()
    {
        $this->load->model('evolution_model');
        $trigger = $this->evolution_model->getEventTrigger('aniversario_cliente');

        if ($trigger && $trigger->status == 1) {
            // Get clients with birthday today
            // Assuming dataNascimento is stored as DATE (Y-m-d)
            $this->db->where("DATE_FORMAT(dataNascimento, '%m-%d') =", date('m-d'));
            $aniversariantes = $this->db->get('clientes')->result();

            if (!empty($aniversariantes)) {
                $this->load->library('evolution_queue');
                foreach ($aniversariantes as $cliente) {
                    $mensagem = $this->evolution_model->getById($trigger->mensagem_id);
                    $mediaUrl = $mensagem->imagem_url ?? null;
                    $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, ['cliente' => $cliente]);
                    $phone = $cliente->celular ?: $cliente->telefone;

                    if ($phone) {
                        // Avoid duplicates if necessary, but for daily cron running once it's fine.
                        // Optimization: Check if msg already sent today?
                        // For now, assume cron runs once a day.
                        $this->evolution_queue->add($phone, $msg_parsed, ['media_url' => $mediaUrl]);
                        echo "Queued birthday msg for: {$cliente->nomeCliente}\n";
                    }
                }
            }
        }
    }

    private function checkDuePayments()
    {
        $this->load->model('evolution_model');
        $trigger = $this->evolution_model->getEventTrigger('cobranca_vencimento');

        if ($trigger && $trigger->status == 1) {
            // Get unpaid payments due today
            $this->db->where('status !=', 'Pago');
            $this->db->where('status !=', 'Cancelado');
            $this->db->where('expire_at', date('Y-m-d')); // Only today
            $cobrancas = $this->db->get('cobrancas')->result();

            if (!empty($cobrancas)) {
                $this->load->library('evolution_queue');
                $this->load->model('clientes_model');

                foreach ($cobrancas as $cob) {
                    $cliente = $this->clientes_model->getById($cob->clientes_id);
                    if ($cliente) {
                        $mensagem = $this->evolution_model->getById($trigger->mensagem_id);
                        $mediaUrl = $mensagem->imagem_url ?? null;
                        $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, [
                            'cliente' => $cliente,
                            'cobranca' => $cob
                        ]);
                        $phone = $cliente->celular ?: $cliente->telefone;
                        if ($phone) {
                            $this->evolution_queue->add($phone, $msg_parsed, ['media_url' => $mediaUrl]);
                            echo "Queued payment due msg for ID: {$cob->idCobranca}\n";
                        }
                    }
                }
            }
        }
    }

    /**
     * Check Appointments for generic reminders (e.g. 1 day before)
     * You might want to run this hourly if the reminder time is granular (e.g. 1 hour before)
     * But for "lembrete_agendamento", let's assume 24h before or checking specifically configured time.
     * Currently implemented as "Day Before" check.
     */
    private function checkAppointments()
    {
        $this->load->model('evolution_model');
        $trigger = $this->evolution_model->getEventTrigger('lembrete_agendamento');

        if ($trigger && $trigger->status == 1) {
            // Assuming reminder is set for 'Tomorrow' appointments if checked daily
            // OR check specific logic.
            // Let's implement logic for: Appointments starting TOMORROW (24h lookahead approx)
            // or logic based on "data_hora_inicio"

            $tomorrow = date('Y-m-d', strtotime('+1 day'));

            $this->db->select('ta.*, tc.nome as nome_treino');
            $this->db->from('treinos_agendados ta');
            $this->db->join('treinos_config tc', 'tc.id = ta.config_id');
            $this->db->where('ta.status', 'Agendado'); // Only active ones
            $this->db->where("DATE(ta.data_hora_inicio) = '{$tomorrow}'");

            $agendamentos = $this->db->get()->result();

            if (!empty($agendamentos)) {
                $this->load->library('evolution_queue');
                $this->load->model('clientes_model');

                foreach ($agendamentos as $ag) {
                    $cliente = $this->clientes_model->getById($ag->cliente_id);
                    if ($cliente) {
                        $mensagem = $this->evolution_model->getById($trigger->mensagem_id);
                        $mediaUrl = $mensagem->imagem_url ?? null;
                        $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, [
                            'cliente' => $cliente,
                            'treino' => $ag
                        ]);
                        $phone = $cliente->celular ?: $cliente->telefone;
                        if ($phone) {
                            $this->evolution_queue->add($phone, $msg_parsed, ['media_url' => $mediaUrl]);
                            echo "Queued reminder for training ID: {$ag->id}\n";
                        }
                    }
                }
            }
        }
    }
}
