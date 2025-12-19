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
                <div class="alert alert-info">
                    <strong>Integração Evolution API & Automação de Notificações</strong><br>
                    Desenvolvimento focado na expansão das capacidades de comunicação e automação do MergulhOS.
                </div>

                <h3>Versão Atual: Integração Completa Evolution API</h3>
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