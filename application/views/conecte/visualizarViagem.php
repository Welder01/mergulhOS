<div class="widget-box">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon">
            <i class="fas fa-plane"></i>
        </span>
        <h5>Detalhes da Viagem</h5>
    </div>
    <div class="widget-content">
        <div class="row-fluid">
            <div class="span12">
                <h4><strong>Viagem:</strong> <?php echo htmlspecialchars($result->nome_viagem); ?></h4>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($result->status); ?></p>
                <p><strong>Data de Partida:</strong> <?php echo date('d/m/Y', strtotime($result->data_partida)); ?></p>
                <?php if ($result->data_retorno) : ?>
                    <p><strong>Data de Retorno:</strong> <?php echo date('d/m/Y', strtotime($result->data_retorno)); ?></p>
                <?php endif; ?>
                <p><strong>Preço por Pessoa:</strong> R$ <?php echo number_format($result->preco_pessoa, 2, ',', '.'); ?></p>
                <p><strong>Vagas Restantes:</strong> <?php echo $result->vagas; ?></p>
                <div>
                    <strong>Descrição:</strong>
                    <p><?php echo nl2br(htmlspecialchars($result->descricao)); ?></p>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <a href="<?php echo base_url() ?>index.php/mine/minhasViagens" class="btn">Voltar</a>
        </div>
    </div>
</div>