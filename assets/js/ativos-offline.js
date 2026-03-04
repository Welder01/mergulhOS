// assets/js/ativos-offline.js

const DB_NAME = 'mergulhos_ativos_db';
const DB_VERSION = 1;
let db;

// Inicializa o IndexedDB
function initDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onerror = (event) => {
            console.error("Erro ao abrir IndexedDB:", event.target.error);
            reject(event.target.error);
        };

        request.onsuccess = (event) => {
            db = event.target.result;
            console.log("IndexedDB aberto com sucesso.");
            resolve(db);
            // Tenta sincronizar ao iniciar se estiver online
            if (navigator.onLine) {
                syncUp();
                syncDown();
            }
        };

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            // Store para Ativos (cópia local para consulta rápida e offline)
            if (!db.objectStoreNames.contains('ativos')) {
                db.createObjectStore('ativos', { keyPath: 'idAtivo' });
            }
            // Store para Logs Offline (fila de sincronização para envio)
            if (!db.objectStoreNames.contains('logs_offline')) {
                db.createObjectStore('logs_offline', { autoIncrement: true });
            }
        };
    });
}

// Baixa dados do servidor para o IndexedDB (Sync Down)
async function syncDown() {
    if (!navigator.onLine) return;

    try {
        // Fallback para o controller criado anteriormente se a rota da API não estiver configurada
        const url = (typeof base_url !== 'undefined' ? base_url : '') + 'index.php/ativos_api/sync_down';
        
        const fetchResponse = await fetch(url);
        const responseText = await fetchResponse.text();
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error("Erro no syncDown: A resposta do servidor não é um JSON válido.", responseText.substring(0, 200));
            return;
        }

        if (data.ativos) {
            const transaction = db.transaction(['ativos'], 'readwrite');
            const store = transaction.objectStore('ativos');
            
            // Limpa dados antigos para garantir consistência
            store.clear();

            data.ativos.forEach(ativo => {
                // Garante que tipos numéricos sejam preservados se necessário
                ativo.idAtivo = parseInt(ativo.idAtivo);
                store.put(ativo);
            });

            console.log("Dados de ativos sincronizados localmente:", data.ativos.length);
            // Dispara evento customizado para atualizar a UI (tabelas, dashboards)
            window.dispatchEvent(new CustomEvent('ativos-synced'));
        }
    } catch (error) {
        console.error("Erro no syncDown:", error);
    }
}

// Envia logs offline para o servidor (Sync Up)
async function syncUp() {
    if (!navigator.onLine) return;

    const transaction = db.transaction(['logs_offline'], 'readonly');
    const store = transaction.objectStore('logs_offline');
    const request = store.getAll();

    request.onsuccess = async () => {
        const logs = request.result;
        if (logs.length === 0) return;

        try {
            const url = (typeof base_url !== 'undefined' ? base_url : '') + 'index.php/ativos_api/sync_up';

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ logs: logs })
            });

            const result = await response.json();

            if (result.status === 'success') {
                console.log("Logs sincronizados com sucesso:", result.processed);
                
                // Limpa os logs processados localmente
                const clearTx = db.transaction(['logs_offline'], 'readwrite');
                clearTx.objectStore('logs_offline').clear();
                
                // Atualiza dados locais novamente para refletir confirmação do servidor
                syncDown();
                
                // Notifica usuário
                if(typeof Swal !== 'undefined'){
                    Swal.fire('Sincronizado', 'Dados offline enviados com sucesso.', 'success');
                }
            } else {
                console.error("Erro no syncUp (servidor):", result);
            }
        } catch (error) {
            console.error("Erro no syncUp (rede):", error);
        }
    };
}

// Função pública para registrar ações (Check-in/Check-out/Movimentação)
function registrarAcaoOffline(ativoId, novoStatus, acao, detalhes) {
    const log = {
        ativo_id: parseInt(ativoId),
        novo_status: novoStatus,
        acao: acao,
        detalhes: detalhes,
        data_acao: new Date().toISOString().slice(0, 19).replace('T', ' ') // Formato MySQL DATETIME
    };

    const transaction = db.transaction(['logs_offline'], 'readwrite');
    const store = transaction.objectStore('logs_offline');
    store.add(log);

    // Atualiza o status localmente imediatamente para feedback visual instantâneo (Optimistic UI)
    const ativoTx = db.transaction(['ativos'], 'readwrite');
    const ativoStore = ativoTx.objectStore('ativos');
    const getReq = ativoStore.get(parseInt(ativoId));

    getReq.onsuccess = () => {
        const ativo = getReq.result;
        if (ativo) {
            ativo.status = novoStatus;
            ativoStore.put(ativo);
            // Dispara evento para a UI saber que um item mudou
            window.dispatchEvent(new CustomEvent('ativo-updated', { detail: ativo }));
        }
    };

    console.log("Ação registrada offline:", log);

    // Tenta sincronizar imediatamente se estiver online
    if (navigator.onLine) {
        syncUp();
    }
}

// Listeners de Rede para automação
window.addEventListener('online', () => {
    console.log("Online detectado. Iniciando sincronização...");
    if(document.body) document.body.classList.remove('offline-mode');
    syncUp();
});

window.addEventListener('offline', () => {
    console.log("Offline detectado.");
    if(document.body) document.body.classList.add('offline-mode');
});

// Inicializa ao carregar a página
document.addEventListener('DOMContentLoaded', () => {
    initDB();
});