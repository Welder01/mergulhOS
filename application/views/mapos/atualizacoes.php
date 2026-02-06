<div class="row-fluid" style="margin-top: 0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-history"></i>
                </span>
                <h5>Notas de Atualização e Evolução do Sistema</h5>
            </div>
            <div class="widget-content">
                <div class="alert alert-success">
                    <strong>v4.53.3 - Melhorias na Integração Evolution API</strong><br>
                    Novas funcionalidades para envio de mídia, controle da fila e tratamento de mensagens.
                </div>

                <h3>Novidades e Melhorias</h3>
                <hr>
                <h4>1. Envio de Mídia e Arquivos</h4>
                <ul>
                    <li><strong>Upload de Mídia:</strong> Agora é possível anexar imagens, vídeos, áudios e documentos (PDF, DOCX, etc.) diretamente no cadastro de mensagens.</li>
                    <li><strong>Conversão Automática:</strong> O sistema converte automaticamente arquivos locais para Base64, garantindo o envio mesmo em ambiente local (localhost).</li>
                </ul>

                <h4>2. Gerenciamento da Fila de Envios</h4>
                <ul>
                    <li><strong>Envio Manual:</strong> Adicionado botão "Forçar Envio (Cron)" para processar a fila imediatamente sem aguardar o agendamento.</li>
                    <li><strong>Envio Individual:</strong> Novo botão na listagem da fila para forçar o envio de uma mensagem específica.</li>
                    <li><strong>Atualização em Tempo Real:</strong> A lista da fila agora possui atualização automática (AJAX), permitindo acompanhar o status dos envios sem recarregar a página.</li>
                </ul>

                <h4>3. Tratamento de Mensagens</h4>
                <ul>
                    <li><strong>Limpeza de HTML:</strong> Implementada função para remover tags HTML do editor de texto e converter formatação (negrito, itálico) para o padrão do WhatsApp.</li>
                    <li><strong>Logs Detalhados:</strong> Melhoria no registro de logs de envio, incluindo tentativas falhas e respostas da API.</li>
                </ul>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.2 - Faturamento de Treinos e Pagamento de Instrutores</strong><br>
                    Novas funcionalidades para gestão financeira de treinos e correções de interface.
                </div>

                <h3>Novidades e Melhorias</h3>
                <hr>
                <h4>1. Financeiro de Treinos</h4>
                <ul>
                    <li><strong>Faturamento de Treinos:</strong> Adicionado botão "Faturar" na listagem de treinos agendados. Agora é possível lançar a receita diretamente no financeiro e marcar o treino como faturado.</li>
                    <li><strong>Pagamento de Instrutores:</strong> O modal de pagamento de instrutores agora permite selecionar a <strong>Data do Pagamento</strong> e a <strong>Forma de Pagamento</strong>. Ao confirmar, uma despesa é lançada automaticamente no financeiro.</li>
                </ul>

                <h4>2. Interface e Correções</h4>
                <ul>
                    <li><strong>Calendários em Modais:</strong> Corrigido o problema onde os seletores de data (datepickers) não apareciam corretamente dentro dos modais de faturamento e pagamento.</li>
                    <li><strong>Validação de Formulários:</strong> Corrigidos erros de script que impediam a validação correta dos formulários de treino.</li>
                </ul>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.1 - Correções no Agendamento e Financeiro de Treinos</strong><br>
                    Ajustes críticos na disponibilidade de datas, seleção de instrutores e cálculo de comissões.
                </div>

                <h3>Correções e Melhorias</h3>
                <hr>
                <h4>1. Agendamento e Reagendamento (Admin)</h4>
                <ul>
                    <li><strong>Disponibilidade de Datas:</strong> O calendário agora respeita corretamente os dias da semana e horários configurados para cada tipo de treino ao agendar pelo painel administrativo.</li>
                    <li><strong>Seleção de Instrutor:</strong> Corrigido o comportamento do campo de instrutor que não aparecia ao marcar a opção "Com Instrutor".</li>
                    <li><strong>Mensagens de Alerta:</strong> Restaurada a exibição de mensagens quando não há instrutores disponíveis no modal de reagendamento.</li>
                </ul>

                <h4>2. Financeiro de Instrutores</h4>
                <ul>
                    <li><strong>Cálculo Automático:</strong> Corrigido o erro que registrava o valor de pagamento como R$ 0,00. O sistema agora calcula automaticamente (Preço com Instrutor - Preço sem Instrutor) tanto no agendamento pelo cliente quanto pelo administrador.</li>
                    <li><strong>Atualização no Reagendamento:</strong> O valor a ser pago ao instrutor é recalculado automaticamente ao reagendar um treino, considerando as alterações de "Com/Sem Instrutor".</li>
                </ul>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.0 - Gestão Financeira de Treinos & Otimização de Importação</strong><br>
                    Novas ferramentas para controle de pagamento de instrutores e melhorias na importação de dados.
                </div>

                <h3>Novidades no Módulo de Treinos</h3>
                <hr>
                <h4>1. Pagamento de Instrutores</h4>
                <p>Implementado fluxo financeiro para instrutores dentro dos agendamentos:</p>
                <ul>
                    <li><strong>Visualização de Custos:</strong> Coluna dedicada para exibir o valor a ser pago ao instrutor na listagem de treinos.</li>
                    <li><strong>Ação de Pagamento:</strong> Botão rápido para efetivar o pagamento, com modal que permite confirmar ou ajustar o valor antes de salvar.</li>
                </ul>

                <h4>2. Capacidades do Sistema de Treinos</h4>
                <p>O módulo de treinos do Mergulho-OS agora oferece um ciclo completo de gestão:</p>
                <ul>
                    <li><strong>Configuração Flexível:</strong> Definição de tipos de treino com duração, preços variáveis (com/sem instrutor), limites de vagas e regras de cancelamento.</li>
                    <li><strong>Agendamento Inteligente:</strong> Controle de disponibilidade de instrutores e horários, com cálculo automático de valores.</li>
                    <li><strong>Notificações Automáticas:</strong> Integração com WhatsApp para avisar alunos e instrutores sobre agendamentos, cancelamentos e alterações de status.</li>
                </ul>

                <h4>3. Melhorias na Importação de Clientes</h4>
                <ul>
                    <li><strong>Inteligência de Dados:</strong> O sistema agora ignora automaticamente e-mails duplicados e gera e-mails provisórios para registros inválidos, garantindo que a importação de grandes listas não pare por erros simples.</li>
                </ul>
                <br>

                <div class="alert alert-info">
                    <strong>Integração Evolution API & Automação de Notificações</strong><br>
                    Desenvolvimento focado na expansão das capacidades de comunicação e automação do MergulhOS.
                </div>

                <h3>Versão Anterior: Integração Completa Evolution API</h3>
                <hr>

                <h4>1. Sistema de Notificação Dupla (Cliente & Instrutor)</h4>
                <p>Implementada a separação de mensagens automáticas para Clientes e Equipantes (Instrutores). Agora é
                    possível configurar mensagens distintas para:</p>
                <ul>
                    <li><strong>Viagens:</strong> Cadastro, Edição, Exclusão de Viagens; Adição/Remoção de Clientes;
                        Adição/Remoção de Instrutores.</li>
                    <li><strong>Cursos:</strong> Adição/Remoção de Alunos; Adição/Remoção de Instrutores.</li>
                    <li><strong>Treinos:</strong> Alteração de Status (Confirmado/Cancelado) e Exclusão de Agendamentos.
                    </li>
                    <li><strong>Ordens de Serviço:</strong> Alteração de Status.</li>
                </ul>

                <h4>2. Notificações CRUD (Ciclo de Vida de Registros)</h4>
                <p>Cobertura completa de notificações para criação, edição e exclusão de registros principais:</p>
                <ul>
                    <li><strong>Clientes:</strong> Criado, Editado, Excluído, Aniversário.</li>
                    <li><strong>Financeiro:</strong> Cobrança Criada, Cancelada, Excluída, Pagamento Confirmado,
                        Vencimento.</li>
                    <li><strong>Produtos & Serviços:</strong> Criação, Edição e Exclusão.</li>
                </ul>

                <h4>3. Automação Diária (Cron Jobs)</h4>
                <p>Implementação de rotinas automáticas executadas diariamente:</p>
                <ul>
                    <li><strong>Aniversariantes:</strong> Envio automático de mensagens de parabéns.</li>
                    <li><strong>Vencimentos:</strong> Lembrete de cobranças vencendo no dia.</li>
                    <li><strong>Agenda de Treinos:</strong> Lembrete para treinos agendados para o dia seguinte.</li>
                </ul>

                <h4>4. Melhorias Financeiras e Operacionais</h4>
                <ul>
                    <li><strong>Pagamento de Instrutores:</strong> Nova funcionalidade em <em>Financeiro >
                            Lançamentos</em> que permite registrar e efetivar o pagamento de comissões/diárias para
                        instrutores diretamente pelo sistema.</li>
                    <li><strong>Aceite de Tarefas:</strong> No painel do usuário (Área do Instrutor), agora é possível
                        <strong>Aceitar</strong> ou <strong>Recusar</strong> atribuições em Viagens e Cursos,
                        facilitando a confirmação de presença na escala.</li>
                </ul>

                <hr>
                <p class="text-info"><em>Todas as configurações de mensagem podem ser gerenciadas em
                        <strong>Configurações > Evolution API > Gerenciar Mensagens</strong>.</em></p>

            </div>
        </div>
    </div>
</div>