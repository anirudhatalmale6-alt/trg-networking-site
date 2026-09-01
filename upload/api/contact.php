<?php
/**
 * TRG Networking — contact form handler.
 *
 * The previous build wrote enquiries into a hidden Horizons database table and
 * emailed nobody. This handler mails them to a real inbox and sets Reply-To to
 * the enquirer so a reply goes straight back to them.
 *
 * It also appends every enquiry to a local CSV as a backup, so a mail outage
 * never loses a lead.
 *
 * ---------------------------------------------------------------------------
 * SETTINGS — change these two lines and nothing else.
 * ---------------------------------------------------------------------------
 */

// Who receives the enquiries. Separate multiple addresses with a comma.
$NOTIFY_TO = 'info@trgnetworking.com';

// The From address. This MUST be a mailbox on the domain this site is hosted
// on, otherwise most mail providers will reject or spam-folder the message.
$FROM_ADDRESS = 'website@trgnetworking.com';

// ---------------------------------------------------------------------------

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function fail($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $msg]);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    fail('Method not allowed', 405);
}

$raw = file_get_contents('php://input');
$in  = json_decode($raw, true);
if (!is_array($in)) {
    // Also accept a normal form POST, so the form still works if JavaScript
    // is blocked and the browser submits natively.
    $in = $_POST;
}
if (!is_array($in)) fail('Malformed request');

$get = function ($key) use ($in) {
    $v = isset($in[$key]) ? $in[$key] : '';
    if (!is_string($v)) return '';
    // Strip CR/LF so nothing can inject extra mail headers.
    return trim(str_replace(["\r", "\n"], ' ', $v));
};

// Honeypot: a real visitor never sees this field, so anything in it is a bot.
// Answer 200 so the bot believes it succeeded and does not retry.
if ($get('website') !== '') {
    echo json_encode(['ok' => true]);
    exit;
}

$name    = $get('name');
$email   = $get('email');
$companyName = $get('company');
$phone   = $get('phone');
$service = $get('service');
$type    = $get('type') === 'assessment' ? 'Free IT Assessment' : 'Consultation';
$message = isset($in['message']) && is_string($in['message']) ? trim($in['message']) : '';

if ($name === '')  fail('Please enter your name.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) fail('Please enter a valid email address.');
if (mb_strlen($name) > 120 || mb_strlen($message) > 5000) fail('That message is too long.');

// --- Basic rate limit: max 5 submissions per IP per 10 minutes -------------
$ip      = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$bucket  = sys_get_temp_dir() . '/trg_contact_' . md5($ip);
$now     = time();
$hits    = file_exists($bucket) ? array_filter(explode(',', (string)file_get_contents($bucket)), 'strlen') : [];
$hits    = array_values(array_filter($hits, function ($t) use ($now) { return ($now - (int)$t) < 600; }));
if (count($hits) >= 5) fail('Too many submissions. Please try again shortly, or call us.', 429);
$hits[] = $now;
@file_put_contents($bucket, implode(',', $hits), LOCK_EX);

// --- Compose ---------------------------------------------------------------
$subject = sprintf('[Website] %s request from %s', $type, $name);

$lines = [
    'A new enquiry was submitted on trgnetworking.com',
    '',
    'Request type : ' . $type,
    'Name         : ' . $name,
    'Email        : ' . $email,
    'Company      : ' . ($companyName !== '' ? $companyName : '—'),
    'Phone        : ' . ($phone !== '' ? $phone : '—'),
    'Service      : ' . ($service !== '' ? $service : '—'),
    '',
    'Message:',
    $message !== '' ? $message : '(no message provided)',
    '',
    '---',
    'Submitted : ' . date('Y-m-d H:i:s T'),
    'IP        : ' . $ip,
    'Page      : ' . ($get('page') !== '' ? $get('page') : ($_SERVER['HTTP_REFERER'] ?? 'unknown')),
];
$body = implode("\n", $lines);

$headers = implode("\r\n", [
    'From: TRG Networking Website <' . $FROM_ADDRESS . '>',
    'Reply-To: ' . $name . ' <' . $email . '>',
    'Content-Type: text/plain; charset=UTF-8',
    'MIME-Version: 1.0',
    'X-Mailer: PHP/' . phpversion(),
]);

// --- CSV backup, written before the mail attempt ---------------------------
// If SMTP is down or misconfigured, the lead is still on disk.
//
// This file holds names, emails and phone numbers, so it must never be
// downloadable. Preference order:
//   1. a private directory one level ABOVE the web root
//   2. this directory, which api/.htaccess denies access to
$privateDir = dirname(__DIR__, 2) . '/trg-private';
if (!is_dir($privateDir)) @mkdir($privateDir, 0750, true);
$csvPath = is_dir($privateDir) && is_writable($privateDir)
    ? $privateDir . '/enquiries.csv'
    : __DIR__ . '/enquiries.csv';
$isNew   = !file_exists($csvPath);
if ($fh = @fopen($csvPath, 'a')) {
    if (flock($fh, LOCK_EX)) {
        if ($isNew) {
            fputcsv($fh, ['timestamp', 'type', 'name', 'email', 'company', 'phone', 'service', 'message', 'ip']);
        }
        fputcsv($fh, [date('c'), $type, $name, $email, $companyName, $phone, $service, $message, $ip]);
        flock($fh, LOCK_UN);
    }
    fclose($fh);
    @chmod($csvPath, 0640);
}

$sent = @mail($NOTIFY_TO, $subject, $body, $headers, '-f' . $FROM_ADDRESS);

if (!$sent) {
    // The enquiry is safely in the CSV, but do not claim success — the front
    // end shows the visitor a mailto fallback when this happens.
    fail('Mail delivery failed on the server.', 502);
}

echo json_encode(['ok' => true]);
