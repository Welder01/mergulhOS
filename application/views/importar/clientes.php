<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-upload"></i>
                </span>
                <h5>Importar Clientes</h5>
            </div>
            <div class="widget-content nopadding">

                <?php if ($this->session->flashdata('success') != null) : ?>
                    <div class="alert alert-success" style="margin: 10px;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($import_error_main) && $import_error_main != null) : ?>
                    <div class="alert alert-danger" style="margin: 10px;">
                        <?= $import_error_main; ?>
                    </div>
                <?php endif; ?>

                <?php
                if (isset($import_errors_list) && !empty($import_errors_list)) :
                ?>
                    <div class="alert alert-warning" style="margin: 10px;">
                        <strong>Por favor, corrija os seguintes erros na sua planilha e tente novamente:</strong>
                        <a href="<?= site_url('importar/limpar_erros'); ?>" class="btn btn-mini btn-danger" style="float: right;">Limpar Relatório de Erros</a>
                        <table class="table table-bordered table-striped" style="margin-top: 10px;">
                            <thead>
                                <tr>
                                    <th>Linha</th>
                                    <th>Coluna</th>
                                    <th>Valor com Problema</th>
                                    <th>Erro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($import_errors_list as $error) : ?>
                                    <tr>
                                        <td><?= isset($error['linha']) ? html_escape($error['linha']) : 'N/A'; ?></td>
                                        <td><?= isset($error['coluna']) ? html_escape($error['coluna']) : 'N/A'; ?></td>
                                        <td><?= isset($error['valor']) ? html_escape($error['valor']) : ''; ?></td>
                                        <td><?= isset($error['erro']) ? html_escape($error['erro']) : html_escape($error); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('importar/upload_clientes'); ?>" id="formImportar" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <div class="control-group">
                        <label for="file" class="control-label">Arquivo (XLS, XLSX)<span class="required">*</span></label>
                        <div class="controls">
                            <input id="file" type="file" name="file" required />
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content: center">
                                <button type="submit" class="button btn btn-success">
                                  <span class="button__icon"><i class="fas fa-upload"></i></span><span class="button__text2">Importar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>