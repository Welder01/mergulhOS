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
                    <strong>v4.54.1 - Melhorias no Módulo de Ativos</strong><br>
                    Novas funcionalidades para gestão de ativos, incluindo detalhes de locação, impressão de etiquetas e interface modernizada.
                </div>

                <h3>Novidades e Melhorias</h3>
                <hr>
                <h4>1. Gestão de Ativos</h4>
                <ul>
                    <li><strong>Novos Campos:</strong> Adicionado suporte para Cor, Tamanho e Preço de Locação no cadastro de ativos.</li>
                    <li><strong>Upload de Fotos:</strong> Implementado sistema de Drag-and-Drop para upload de imagens com redimensionamento automático.</li>
                    <li><strong>Consulta Rápida:</strong> Nova interface de busca com autocomplete, exibição de foto, status e histórico recente do ativo.</li>
                    <li><strong>Impressão de Etiquetas:</strong> Ferramenta de geração de etiquetas QR Code em massa com configurações personalizadas de layout (dimensões, margens, colunas).</li>
                    <li><strong>Dashboard:</strong> Painel renovado com cards de resumo e gráficos interativos para melhor visualização do status e distribuição dos ativos.</li>
                </ul>
                <br>

                <div class="alert alert-success">
                    <strong>v4.54.0 - Módulo de Ativos e Melhorias em Viagens</strong><br>
                    Novo módulo de Gestão de Ativos e Logística, conferência de bolsas e melhorias na interface de viagens.
                </div>

                <h3>Novidades e Melhorias</h3>
                <hr>
                <h4>1. Módulo de Ativos e Logística</h4>
                <ul>
                    <li><strong>Gestão de Bolsas/Caixas:</strong> Criação e gerenciamento de recipientes para agrupar ativos.</li>
                    <li><strong>Movimentação (Check-in/Check-out):</strong> Controle de saída e retorno de ativos e bolsas, com registro de responsável e data.</li>
                    <li><strong>Conferência de Itens:</strong> Nova interface para conferência item a item do conteúdo de uma bolsa durante a movimentação, suportando leitor de código de barras/QR Code.</li>
                    <li><strong>Dashboard de Ativos:</strong> Novos gráficos de status global e por responsável, além de lista de movimentações pendentes.</li>
                    <li><strong>QR Code:</strong> Geração automática de QR Code para Ativos e Bolsas para facilitar a identificação.</li>
                    <li><strong>Permissões Granulares:</strong> Novas permissões específicas para criar, editar, excluir bolsas, movimentar itens e realizar conferências.</li>
                    <li><strong>Auditoria de Ativos:</strong> Registro histórico de ações (criação, edição, movimentação) com filtros de pesquisa e permissão especial para exclusão de logs.</li>
                    <li><strong>Categorias de Ativos:</strong> Gerenciamento completo de categorias para classificação de ativos.</li>
                </ul>

                <h4>2. Módulo de Viagens</h4>
                <ul>
                    <li><strong>Associação de Bolsas:</strong> Novo campo com busca automática (autocomplete) para vincular uma bolsa a um Cliente ou Instrutor dentro da viagem.</li>
                    <li><strong>Interface Aprimorada:</strong> Reorganização das abas de Clientes e Instrutores para melhor aproveitamento de espaço e responsividade em dispositivos móveis e tablets.</li>
                    <li><strong>Ficha de Operação:</strong> Novo layout de impressão detalhando mergulhadores, instrutores e resumo de equipamentos necessários.</li>
                </ul>

                <h4>3. Sistema</h4>
                <p>Ajustes gerais em modais e formulários para melhor visualização em modo paisagem (landscape) em tablets e celulares.</p>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.12 - Correção de Permissões</strong><br>
                    Ajuste na verificação de permissão para cancelamento de faturamento.
                </div>

                <h3>Correções</h3>
                <hr>
                <h4>1. Ordem de Serviço</h4>
                <p>Corrigido o bloqueio indevido ao tentar cancelar o faturamento de uma OS. O sistema agora verifica corretamente as permissões de lançamentos financeiros como alternativa caso a permissão específica de faturamento não esteja atribuída.</p>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.11 - Melhorias em Impressão e Pagamentos</strong><br>
                    QR Code PIX na térmica, correções em gateways e ajustes visuais na OS.
                </div>

                <h3>Novidades e Correções</h3>
                <hr>
                <h4>1. Impressão Térmica</h4>
                <p>Adicionado o <strong>QR Code PIX</strong> na impressão de cupom (80mm) da Ordem de Serviço, facilitando o recebimento no balcão.</p>

                <h4>2. Correções em Pagamentos</h4>
                <ul>
                    <li><strong>Validação de Contato:</strong> Gateways de pagamento agora aceitam o número de celular caso o telefone fixo não esteja preenchido.</li>
                    <li><strong>Cálculo de Total:</strong> Corrigido erro que impedia gerar cobrança para OS contendo apenas Cursos ou Viagens.</li>
                </ul>

                <h4>3. Ordem de Serviço</h4>
                <ul>
                    <li><strong>Propósito da Viagem:</strong> Corrigido problema onde o propósito da viagem não era salvo ou exibido corretamente.</li>
                    <li><strong>Visualização:</strong> Alinhamento padronizado das colunas de valores (Quantidade, Unitário, Subtotal) em todas as tabelas da OS.</li>
                </ul>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.10 - Melhorias Financeiras e Correções em OS</strong><br>
                    Faturamento parcelado, cancelamento de faturamento e ajustes de cálculo.
                </div>

                <h3>Novidades e Correções</h3>
                <hr>
                <h4>1. Financeiro em OS</h4>
                <ul>
                    <li><strong>Faturamento Parcelado:</strong> Agora é possível informar entrada e número de parcelas ao faturar uma OS. O sistema gera os lançamentos financeiros automaticamente.</li>
                    <li><strong>Cancelar Faturamento:</strong> Nova opção para reverter o faturamento de uma OS, excluindo os lançamentos gerados e voltando o status para "Em Andamento".</li>
                </ul>
                <h4>2. Cálculos e Quantidades</h4>
                <p>Corrigido o cálculo de valores totais para Cursos e Viagens inseridos na OS, que agora respeitam a quantidade informada. As telas de edição e impressão foram atualizadas para exibir a coluna de quantidade.</p>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.9 - Correções de Lógica e Validação</strong><br>
                    Ajustes no consumo de vagas em viagens, validação de clientes, requisitos de cursos e layout de impressão.
                </div>

                <h3>Correções e Melhorias</h3>
                <hr>
                <h4>1. Viagens</h4>
                <p>Corrigido o comportamento ao adicionar/remover instrutores: agora eles consomem vagas na viagem, garantindo que o limite total de participantes (clientes + instrutores) seja respeitado.</p>
                <p>Otimização do layout da <strong>Ficha de Operação</strong>: Redução do tamanho da fonte e espaçamentos para permitir que mais registros (Mergulhadores e Instrutores) caibam em uma única página.</p>

                <h4>2. Clientes</h4>
                <p>Corrigida a validação de e-mail na edição de clientes, que impedia salvar o cadastro mesmo mantendo o e-mail original.</p>

                <h4>3. Cursos</h4>
                <p>Adicionada a opção "Instrutor" na lista de requisitos e corrigida a exibição de linhas vazias no dropdown.</p>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.8 - Correções de Interface e Parâmetros</strong><br>
                    Padronização de cabeçalhos, ajustes no menu lateral e correção na edição de cursos.
                </div>

                <h3>Melhorias e Correções</h3>
                <hr>
                <h4>1. Menu Lateral (Sidebar)</h4>
                <p>Ajuste na rolagem do menu lateral para garantir que todos os itens (como "Sair") sejam acessíveis em diferentes resoluções e níveis de zoom, mantendo a estética sem barra de rolagem visível.</p>

                <h4>2. Padronização de Telas</h4>
                <p>Atualização do cabeçalho das telas "Dashboard" e "Clientes" para seguir o padrão visual do restante do sistema.</p>

                <h4>3. Correções de Bugs</h4>
                <p>Correção no formulário de edição de cursos que impedia o salvamento devido a um erro de parâmetro inválido.</p>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.7 - Melhorias de Interface e Usabilidade</strong><br>
                    Ajustes no menu lateral e alertas na área do cliente.
                </div>

                <h3>Melhorias de Interface</h3>
                <hr>
                <h4>1. Menu Lateral (Sidebar)</h4>
                <p>Correção na rolagem do menu lateral em todo o sistema (Painel Administrativo e Área do Cliente). O menu agora possui rolagem independente, permitindo visualizar todos os itens mesmo em telas menores sem necessidade de ajustar o zoom.</p>

                <h4>2. Área do Cliente</h4>
                <ul>
                    <li><strong>Alerta de Perfil:</strong> Novo aviso visual destacado (piscante) quando o perfil está incompleto, listando pendências específicas (Dados Pessoais, Saúde, Atestado) com links diretos para correção.</li>
                    <li><strong>Ícones:</strong> Padronização do tamanho dos ícones no cabeçalho.</li>
                </ul>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.6 - Correção no Envio de Mídia via Cron e Gatilhos</strong><br>
                    Correções críticas para garantir o envio de imagens e arquivos nas notificações automáticas.
                </div>

                <h3>Correções e Melhorias</h3>
                <hr>
                <h4>1. Envio de Mídia via Cron Job</h4>
                <p>Corrigido o processamento da fila de mensagens via tarefa agendada (Cron). O sistema agora identifica corretamente arquivos locais e URLs de mídia mesmo quando executado via linha de comando, garantindo que anexos sejam enviados.</p>

                <h4>2. Gatilhos de Eventos Automáticos</h4>
                <p>Todos os gatilhos de notificação (Clientes, OS, Financeiro, Treinos, Viagens, Cursos, etc.) foram atualizados para carregar corretamente a URL da imagem/arquivo configurada no modelo de mensagem, resolvendo o problema onde apenas o texto era enviado.</p>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.5 - Segurança na Exclusão de Clientes</strong><br>
                    Melhoria no alerta de exclusão para detalhar impacto da remoção de clientes.
                </div>

                <h3>Melhorias de Interface</h3>
                <hr>
                <h4>1. Alerta de Exclusão Detalhado</h4>
                <p>O sistema agora exibe uma lista completa de todos os registros (Financeiro, OS, Vendas, Treinos, etc.) que serão excluídos permanentemente ao remover um cliente, garantindo que o usuário esteja ciente da irreversibilidade da ação.</p>
                <br>

                <div class="alert alert-success">
                    <strong>v4.53.4 - Correção no Cron Job da Evolution API</strong><br>
                    Ajuste no comando do Cron Job para processamento da fila de mensagens.
                </div>

                <h3>Correções e Melhorias</h3>
                <hr>
                <h4>1. Automação (Cron Job)</h4>
                <ul>
                    <li><strong>Comando Atualizado:</strong> O comando para execução via Cron Job foi atualizado para utilizar o controlador <code>evolution_cron</code> via CLI, garantindo estabilidade e evitando problemas de autenticação/sessão.</li>
                    <li><strong>Instruções:</strong> As instruções de configuração foram atualizadas na tela de gerenciamento da Evolution API.</li>
                </ul>
                <br>

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