<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Etiquetas de Ativos</title>
    <style>
        @media print {
            @page { margin: 0; size: auto; }
            body { margin: 0; }
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .page-container {
            padding-top: <?= $config['margem_topo'] ?>mm;
            padding-left: <?= $config['margem_esq'] ?>mm;
            width: 100%;
            box-sizing: border-box;
        }
        .etiqueta {
            float: left;
            width: <?= $config['largura'] ?>mm;
            height: <?= $config['altura'] ?>mm;
            margin-right: <?= $config['espacamento_h'] ?>mm;
            margin-bottom: <?= $config['espacamento_v'] ?>mm;
            border: 1px dashed #ccc; /* Borda pontilhada para guia de corte, pode ser removida se desejar */
            box-sizing: border-box;
            text-align: center;
            overflow: hidden;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 1mm;
        }
        /* Remove borda na impressão se for para aço/policarbonato direto na peça, mas se for folha adesiva, borda ajuda. 
           Para aço, geralmente não se imprime borda. Vamos deixar uma classe para ocultar borda na impressão se necessário. */
        @media print {
            .etiqueta { border: none; }
        }

        .etiqueta:nth-child(<?= $config['colunas'] ?>n + 1) {
            clear: left;
        }
        
        .qrcode-img {
            margin: 2px auto;
        }
        div[id^="qr_"] img {
            max-width: 100%;
            width: auto !important;
            height: auto !important;
            max-height: <?= ($config['altura'] - 8) ?>mm;
        }
        .texto {
            font-size: <?= $config['fonte_tamanho'] ?>px;
            font-weight: bold;
            margin: 1px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 95%;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <?php if (empty($results)) { ?>
            <p style="text-align: center; padding-top: 50px;">Nenhum ativo encontrado para impressão.</p>
        <?php } else { 
            foreach ($results as $r) { ?>
            <div class="etiqueta">
                <?php if($config['mostrar_nome']) { ?>
                    <div class="texto"><?= $r->nome ?></div>
                <?php } ?>
                
                <div id="qr_<?= $r->idAtivo ?>"></div>
                
                <?php if($config['mostrar_patrimonio']) { ?>
                    <div class="texto"><?= $r->patrimonio ?></div>
                <?php } ?>
            </div>
        <?php } 
        } ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        var configSize = <?= $config['tamanho_qr'] ?>;
        
        <?php if (!empty($results)) { 
            foreach ($results as $r) { 
                if (!empty($r->codigo_qr)) { ?>
            try {
                new QRCode(document.getElementById("qr_<?= $r->idAtivo ?>"), {
                    text: "<?= $r->codigo_qr ?>",
                    width: configSize,
                    height: configSize,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            } catch(e) { console.error('Erro QR Ativo <?= $r->idAtivo ?>', e); }
        <?php   }
            } 
        } ?>
        
        window.onload = function() {
            setTimeout(function() { window.print(); }, 1000);
        }
    </script>
</body>
</html>