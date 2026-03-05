<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Etiqueta Ativo - <?= $result->nome ?></title>
    <style>
        @media print {
            @page { margin: 0; size: auto; }
            body { margin: 0.5cm; }
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .etiqueta {
            border: 2px solid #000;
            padding: 10px;
            display: inline-block;
            border-radius: 10px;
            width: 300px;
        }
        h3 { margin: 5px 0; font-size: 16px; }
        p { margin: 5px 0; font-size: 14px; font-weight: bold; }
        #qrcode { margin: 10px auto; }
        #qrcode img { margin: 0 auto; }
    </style>
</head>
<body>
    <div class="etiqueta">
        <h3><?= $result->nome ?></h3>
        <p><?= $result->patrimonio ?></p>
        <div id="qrcode"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "<?= $result->codigo_qr ?>",
            width: 128,
            height: 128,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        }
    </script>
</body>
</html>