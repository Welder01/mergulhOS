<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class Importar extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('clientes_model');
        $this->data['menuImportar'] = 'importar';
    }

    public function clientes()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aImportar')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para importar clientes.');
            redirect(base_url());
        }

        $this->data['import_error_main'] = $this->session->userdata('import_error_main');
        $this->data['import_errors_list'] = $this->session->userdata('import_errors_list');
        $this->data['view'] = 'importar/clientes';
        return $this->layout();
    }

    public function upload_clientes()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aImportar')) {
            echo json_encode(['error' => 'Você não tem permissão para importar clientes.']);
            return;
        }

        $importId = $this->input->post('importId');
        if (!$importId) {
            // Fallback se não enviado (mas deve ser enviado pelo front)
            $importId = 'import_' . time() . '_' . rand(1000, 9999);
        }

        // Validação de segurança do ID
        if (!preg_match('/^import_\d+_\d+$/', $importId)) {
            echo json_encode(['error' => 'ID de importação inválido.']);
            return;
        }

        $progressFile = './assets/uploads/' . $importId . '.json';
        file_put_contents($progressFile, json_encode(['status' => 'uploading', 'progress' => 0, 'message' => 'Carregando arquivo...']));

        // Limpa erros da sessão (opcional, já que vamos retornar JSON)
        $this->session->unset_userdata('import_error_main');
        $this->session->unset_userdata('import_errors_list');

        $config['upload_path'] = './assets/uploads/importacoes/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = '5120'; // 5MB
        $config['encrypt_name'] = true;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            echo json_encode(['error' => 'Erro no upload: ' . $this->upload->display_errors()]);
            return;
        }

        $upload_data = $this->upload->data();
        $filePath = $upload_data['full_path'];

        // Aumenta limites de execução e memória para uploads grandes
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        ignore_user_abort(true); // Continua executando mesmo se o cliente desconectar

        // Libera a sessão para permitir polling
        session_write_close();

        try {
            file_put_contents($progressFile, json_encode(['status' => 'processing', 'progress' => 5, 'message' => 'Lendo arquivo...']));

            // Verifica se o arquivo existe
            if (!file_exists($filePath)) {
                throw new Exception('Arquivo não encontrado após upload.');
            }

            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $totalRows = count($sheetData) - 1; // Desconsidera cabeçalho

            if ($totalRows <= 0) {
                unlink($filePath);
                unlink($progressFile);
                echo json_encode(['error' => 'O arquivo está vazio.']);
                return;
            }

            $errors = [];
            $validData = [];
            $rowErrors = [];

            // 1. FASE DE VALIDAÇÃO
            $processedRows = 0;
            foreach ($sheetData as $rowIndex => $row) {
                if ($rowIndex == 1) {
                    continue;
                } // Pula cabeçalho

                // Atualiza progresso a cada 10 linhas ou se for a última
                $processedRows++;
                if ($processedRows % 10 == 0 || $processedRows == $totalRows) {
                    $percent = 5 + round(($processedRows / $totalRows) * 45); // 5% a 50%
                    file_put_contents($progressFile, json_encode([
                        'status' => 'validating',
                        'progress' => $percent,
                        'message' => "Validando linha {$processedRows} de {$totalRows}..."
                    ]));

                    // Force garbage collection in heavy loops
                    if ($processedRows % 100 == 0)
                        gc_collect_cycles();
                }

                $rowErrors = [];
                $inconsistente = 0;
                $observacoes = '';

                $nome = trim($row['A']);
                if (empty($nome)) {
                    $rowErrors[] = ['linha' => $rowIndex, 'coluna' => 'A (Nome)', 'valor' => $nome, 'erro' => 'O nome do cliente não pode estar vazio.'];
                }

                $email = trim($row['L']);
                if (!empty($email) && $this->clientes_model->emailExists($email)) {
                    continue;
                }

                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $inconsistente = 1;
                    $email = 'inconsistente_' . time() . '_' . $rowIndex . '@mergulhos.com';
                    $observacoes .= "Email gerado automaticamente. ";
                }

                $dataNascimento = null;
                if (!empty(trim($row['M']))) {
                    $date = DateTime::createFromFormat('d/m/Y', trim($row['M']));
                    if ($date && $date->format('d/m/Y') === trim($row['M'])) {
                        $dataNascimento = $date->format('Y-m-d');
                    } else {
                        $rowErrors[] = ['linha' => $rowIndex, 'coluna' => 'M (Data Nascimento)', 'valor' => $row['M'], 'erro' => 'Formato de data inválido. Use dd/mm/aaaa.'];
                    }
                }

                $documento = !empty(trim($row['Q'])) ? trim($row['Q']) : trim($row['R']);
                if (empty($documento)) {
                    $inconsistente = 1;
                    $documento = 'NA_' . time() . '_' . $rowIndex;
                    $observacoes .= "Documento gerado automaticamente. ";
                }

                if (empty($rowErrors)) {
                    $validData[] = [
                        'nomeCliente' => $row['A'],
                        'rua' => $row['B'],
                        'numero' => $row['C'],
                        'complemento' => $row['D'],
                        'bairro' => $row['E'],
                        'cidade' => $row['F'],
                        'estado' => $row['G'],
                        'cep' => $row['H'],
                        'telefone' => !empty(trim($row['J'])) ? trim($row['J']) : trim($row['K']),
                        'celular' => trim($row['K']),
                        'email' => $email,
                        'data_nascimento' => $dataNascimento,
                        'sexo' => ($row['N'] == 'M' ? 'Masculino' : ($row['N'] == 'F' ? 'Feminino' : null)),
                        'documento' => $documento,
                        'altura' => str_replace(',', '.', $row['S']),
                        'peso' => str_replace(',', '.', $row['T']),
                        'tamanho_colete' => $row['U'],
                        'possui_colete' => (strtoupper($row['V']) == 'S' ? 1 : 0),
                        'tamanho_neoprene' => $row['W'],
                        'possui_neoprene' => (strtoupper($row['X']) == 'S' ? 1 : 0),
                        'peso_lastro' => $row['Y'],
                        'tamanho_nadadeira' => $row['Z'],
                        'possui_nadadeira' => (strtoupper($row['AA']) == 'S' ? 1 : 0),
                        'possui_regulador' => (strtoupper($row['AB']) == 'S' ? 1 : 0),
                        'contato_emergencia_nome' => $row['AC'],
                        'contato_emergencia_parentesco' => $row['AD'],
                        'contato_emergencia_telefone' => $row['AE'],
                        'dataCadastro' => date('Y-m-d'),
                        'senha' => password_hash(preg_replace('/[^\p{L}\p{N}\s]/', '', $documento), PASSWORD_DEFAULT),
                        'importacao_inconsistente' => $inconsistente
                    ];
                }
                $errors = array_merge($errors, $rowErrors);
            }

            if (!empty($errors)) {
                unlink($filePath);
                unlink($progressFile);
                // CRITICAL: Re-enable session to save errors before returning JSON
                session_start();
                // CRITICAL FIX: Limit session error storage
                $totalErrors = count($errors);
                $displayedErrors = array_slice($errors, 0, 50);

                $message = 'A importação falhou. Foram encontrados ' . $totalErrors . ' erros na planilha.';
                if ($totalErrors > 50) {
                    $message .= ' (Exibindo apenas os primeiros 50 erros).';
                }

                $this->session->set_userdata('import_error_main', $message);
                $this->session->set_userdata('import_errors_list', $displayedErrors);
                session_write_close();

                echo json_encode(['error' => 'validation_errors', 'errors' => $displayedErrors, 'total_errors' => $totalErrors]);
                return;
            }

            // 2. INSERÇÃO
            $countSuccess = 0;
            $countError = 0;
            $insertionErrors = [];
            $totalValid = count($validData);

            // Reabre conexão se necessário
            $this->db->reconnect();
            $this->db->trans_start();

            $insertedCount = 0;
            foreach ($validData as $data) {
                $insertedCount++;
                if ($insertedCount % 5 == 0 || $insertedCount == $totalValid) {
                    $percent = 50 + round(($insertedCount / $totalValid) * 50); // 50% a 100%
                    file_put_contents($progressFile, json_encode([
                        'status' => 'inserting',
                        'progress' => $percent,
                        'message' => "Importando registro {$insertedCount} de {$totalValid}..."
                    ]));
                }

                if ($this->clientes_model->add('clientes', $data)) {
                    $countSuccess++;
                } else {
                    $db_error = $this->db->error();
                    $insertionErrors[] = [
                        'linha' => 'N/A',
                        'coluna' => 'DB',
                        'valor' => $data['nomeCliente'],
                        'erro' => "Erro ao inserir: " . ($db_error['message'] ?? 'Desconhecido')
                    ];
                    $countError++;
                }
            }

            if ($countError > 0) {
                $this->db->trans_rollback();
                unlink($filePath);
                unlink($progressFile);

                session_start();
                $this->session->set_userdata('import_error_main', 'Ocorreu um erro durante a inserção no banco de dados. Nenhuma alteração foi feita.');
                $this->session->set_userdata('import_errors_list', $insertionErrors);
                session_write_close();

                echo json_encode(['error' => 'db_errors', 'errors' => $insertionErrors]);
                return;
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                unlink($filePath);
                unlink($progressFile);
                echo json_encode(['error' => 'Erro crítico: A transação do banco de dados falhou. Nenhuma alteração foi salva.']);
                return;
            }

            unlink($filePath);

            // Finaliza com 100%
            file_put_contents($progressFile, json_encode(['status' => 'complete', 'progress' => 100, 'message' => 'Concluído!']));

            // Aguarda um pouco para o front pegar o 100% antes de limpar
            sleep(1);
            unlink($progressFile);

            echo json_encode(['success' => true, 'count' => $countSuccess]);

        } catch (Throwable $e) {
            unlink($filePath);
            if (file_exists($progressFile)) {
                file_put_contents($progressFile, json_encode(['status' => 'error', 'message' => 'Erro fatal: ' . $e->getMessage()]));
            }
            echo json_encode(['error' => 'Exceção: ' . $e->getMessage()]);
        }
    }

    public function get_progress($importId)
    {
        // Validação básica do ID
        if (!preg_match('/^import_\d+_\d+$/', $importId)) {
            echo json_encode(['progress' => 0, 'message' => 'ID inválido']);
            return;
        }

        $file = './assets/uploads/' . $importId . '.json';
        if (file_exists($file)) {
            echo file_get_contents($file);
        } else {
            echo json_encode(['progress' => 0, 'message' => 'Aguardando início...']); // Ou talvez erro se já deveria existir
        }
    }

    public function limpar_erros()
    {
        $this->session->unset_userdata('import_error_main');
        $this->session->unset_userdata('import_errors_list');
        $this->session->set_flashdata('success', 'O relatório de erros foi limpo.');
        redirect('importar/clientes');
    }
}