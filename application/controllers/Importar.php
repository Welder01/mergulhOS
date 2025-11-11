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
            $this->session->set_flashdata('error', 'Você não tem permissão para importar clientes.');
            redirect(base_url());
        }

        // Limpa erros da sessão anterior ao iniciar novo upload
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
            $this->session->set_flashdata('error', 'Erro no upload: ' . $this->upload->display_errors());
            redirect('importar/clientes');
        }

        $upload_data = $this->upload->data();
        $filePath = $upload_data['full_path'];

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $errors = [];
            $validData = [];

            $rowErrors = [];
            // 1. FASE DE VALIDAÇÃO
            foreach ($sheetData as $rowIndex => $row) {
                if ($rowIndex == 1) { // Pula o cabeçalho
                    continue;
                }

                // Validações essenciais
                $rowErrors = []; // Limpa os erros para a linha atual

                $nome = trim($row['A']);
                if (empty($nome)) {
                    $rowErrors[] = ['linha' => $rowIndex, 'coluna' => 'A (Nome)', 'valor' => $nome, 'erro' => 'O nome do cliente não pode estar vazio.'];
                }

                $email = trim($row['L']);
                if (empty($email)) {
                    $rowErrors[] = ['linha' => $rowIndex, 'coluna' => 'L (E-mail)', 'valor' => $email, 'erro' => 'O e-mail não pode estar vazio.'];
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $rowErrors[] = ['linha' => $rowIndex, 'coluna' => 'L (E-mail)', 'valor' => $email, 'erro' => 'Formato de e-mail inválido.'];
                } elseif ($this->clientes_model->emailExists($email)) {
                    $rowErrors[] = ['linha' => $rowIndex, 'coluna' => 'L (E-mail)', 'valor' => $email, 'erro' => 'Este e-mail já está cadastrado.'];
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
                    $rowErrors[] = ['linha' => $rowIndex, 'coluna' => 'Q/R (CPF/CNPJ)', 'valor' => $documento, 'erro' => 'CPF ou CNPJ é obrigatório.'];
                }

                // Se não houver erros nesta linha, prepara os dados para inserção
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
                        'senha' => password_hash(preg_replace('/[^\p{L}\p{N}\s]/', '', $documento), PASSWORD_DEFAULT)
                    ];
                }

                $errors = array_merge($errors, $rowErrors);
            }

            // 2. VERIFICAÇÃO E INSERÇÃO
            if (!empty($errors)) {
                // Se houver erros, não importa nada e exibe o relatório de erros
                unlink($filePath); // Remove o arquivo
                $this->session->set_userdata('import_error_main', 'A importação falhou. Foram encontrados erros na planilha.');
                $this->session->set_userdata('import_errors_list', $errors);
                log_info('Tentativa de importação de clientes falhou. Erros encontrados na planilha.');
                redirect('importar/clientes');
            }

            // Se não houver erros, prossegue com a importação
            $countSuccess = 0;
            $countError = 0;
            $insertionErrors = [];

            $this->db->trans_start();

            foreach ($validData as $data) {
                if ($this->clientes_model->add('clientes', $data)) {
                    $countSuccess++;
                } else {
                    $db_error = $this->db->error();
                    $error_message = "Erro de banco de dados ao inserir cliente '{$data['nomeCliente']}'. Detalhes: " . ($db_error['message'] ?? 'Não foi possível obter o erro.');
                    $countError++;
                    $insertionErrors[] = [
                        'linha' => 'N/A',
                        'coluna' => 'Banco de Dados',
                        'valor' => $data['nomeCliente'],
                        'erro' => $error_message
                    ];
                    log_info($error_message); // Log do erro específico do banco
                }
            }

            if ($countError > 0) {
                $this->db->trans_rollback();
                unlink($filePath);
                $this->session->set_userdata('import_error_main', 'Ocorreu um erro durante a inserção no banco de dados. Nenhuma alteração foi feita.');
                $this->session->set_userdata('import_errors_list', $insertionErrors);
                log_info('Tentativa de importação de clientes falhou durante a transação do banco de dados.');
                redirect('importar/clientes');
            }

            $this->db->trans_complete();

            unlink($filePath); // Remove o arquivo após o processamento

            $this->session->set_flashdata('success', "Importação concluída com sucesso! {$countSuccess} clientes foram adicionados.");
            log_info("Importação de clientes concluída. {$countSuccess} clientes adicionados.");
        } catch (Exception $e) {
            unlink($filePath);
            $this->session->set_flashdata('error', 'Ocorreu um erro ao processar o arquivo: ' . $e->getMessage());
            log_info('Exceção durante importação de clientes: ' . $e->getMessage());
        }

        redirect('importar/clientes');
    }

    public function limpar_erros()
    {
        $this->session->unset_userdata('import_error_main');
        $this->session->unset_userdata('import_errors_list');
        $this->session->set_flashdata('success', 'O relatório de erros foi limpo.');
        redirect('importar/clientes');
    }
}