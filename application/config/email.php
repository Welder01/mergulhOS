<?php

// Carrega configurações do arquivo .env manualmente para garantir que sejam lidas
$env_path = FCPATH . '.env';
if (!file_exists($env_path)) {
    // Fallback para pasta application se não achar na raiz
    $env_path = APPPATH . '.env';
}

if (file_exists($env_path)) {
    $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (preg_match('/^"(.*)"$/', $value, $m)) $value = $m[1];
            elseif (preg_match("/^'(.*)'$/", $value, $m)) $value = $m[1];
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}

$config['protocol'] = getenv('EMAIL_PROTOCOL') ?: ($_ENV['EMAIL_PROTOCOL'] ?? 'smtp');
$config['smtp_host'] = getenv('EMAIL_SMTP_HOST') ?: ($_ENV['EMAIL_SMTP_HOST'] ?? 'mail.bhdivers.com.br');
$config['smtp_crypto'] = getenv('EMAIL_SMTP_CRYPTO') ?: ($_ENV['EMAIL_SMTP_CRYPTO'] ?? 'ssl'); // tls or ssl
$config['smtp_port'] = getenv('EMAIL_SMTP_PORT') ?: ($_ENV['EMAIL_SMTP_PORT'] ?? 465);
$config['smtp_user'] = getenv('EMAIL_SMTP_USER') ?: ($_ENV['EMAIL_SMTP_USER'] ?? 'naoresponda@bhdivers.com.br');
$config['smtp_pass'] = getenv('EMAIL_SMTP_PASS') ?: ($_ENV['EMAIL_SMTP_PASS'] ?? 'Noreply$#@!4321');
$config['validate'] = filter_var(getenv('EMAIL_VALIDATE') ?: ($_ENV['EMAIL_VALIDATE'] ?? true), FILTER_VALIDATE_BOOLEAN); // validar email
$config['mailtype'] = getenv('EMAIL_MAILTYPE') ?: ($_ENV['EMAIL_MAILTYPE'] ?? 'html'); // text ou html
$config['charset'] = getenv('EMAIL_CHARSET') ?: ($_ENV['EMAIL_CHARSET'] ?? 'utf-8');
$config['newline'] = getenv('EMAIL_NEWLINE') ?: ($_ENV['EMAIL_NEWLINE'] ?? "\r\n");
$config['bcc_batch_mode'] = filter_var(getenv('EMAIL_BCC_BATCH_MODE') ?: ($_ENV['EMAIL_BCC_BATCH_MODE'] ?? false), FILTER_VALIDATE_BOOLEAN);
$config['wordwrap'] = filter_var(getenv('EMAIL_WORDWRAP') ?: ($_ENV['EMAIL_WORDWRAP'] ?? false), FILTER_VALIDATE_BOOLEAN);
$config['priority'] = getenv('EMAIL_PRIORITY') ?: ($_ENV['EMAIL_PRIORITY'] ?? 3); // 1, 2, 3, 4, 5 | Email Priority. 1 = highest. 5 = lowest. 3 = normal.
