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
        $this->load->library('evolution_queue');
        $result = $this->evolution_queue->process($limit);

        if ($this->input->is_cli_request()) {
            echo "Processed: " . $result['processed'] . "\n";
            echo "Failed: " . $result['failed'] . "\n";
        } else {
            header('Content-Type: application/json');
            echo json_encode($result);
        }
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
                    $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, ['cliente' => $cliente]);
                    $phone = $cliente->celular ?: $cliente->telefone;

                    if ($phone) {
                        // Avoid duplicates if necessary, but for daily cron running once it's fine.
                        // Optimization: Check if msg already sent today?
                        // For now, assume cron runs once a day.
                        $this->evolution_queue->add($phone, $msg_parsed);
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
                        $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, [
                            'cliente' => $cliente,
                            'cobranca' => $cob
                        ]);
                        $phone = $cliente->celular ?: $cliente->telefone;
                        if ($phone) {
                            $this->evolution_queue->add($phone, $msg_parsed);
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
                        $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, [
                            'cliente' => $cliente,
                            'treino' => $ag
                        ]);
                        $phone = $cliente->celular ?: $cliente->telefone;
                        if ($phone) {
                            $this->evolution_queue->add($phone, $msg_parsed);
                            echo "Queued reminder for training ID: {$ag->id}\n";
                        }
                    }
                }
            }
        }
    }
}
