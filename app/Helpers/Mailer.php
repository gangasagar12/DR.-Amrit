<?php
namespace App\Helpers;

use App\Models\Setting;

class Mailer {
    public static function send(string $to, string $subject, string $htmlBody): bool {
        $doctorName = Setting::get('doctor_name', 'Dr. Amrit Jaishi');
        $doctorEmail = Setting::get('contact_email', 'amritjaishi0@gmail.com');
        $smtpEnabled = (bool)Setting::get('smtp_enabled', '0');
        $fromEmail = Setting::get('smtp_from_email', $doctorEmail);
        $fromName = Setting::get('smtp_from_name', "{$doctorName} Dental Practice");

        // Always log outgoing email to storage/logs/email.log for audit and local testing
        try {
            $logDir = dirname(__DIR__, 2) . '/storage/logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0777, true);
            }
            $logEntry = "[" . date('Y-m-d H:i:s') . "] TO: {$to} | FROM: {$fromName} <{$fromEmail}> | SUBJECT: {$subject}\nBODY:\n{$htmlBody}\n" . str_repeat('=', 60) . "\n\n";
            @file_put_contents($logDir . '/email.log', $logEntry, FILE_APPEND);
        } catch (\Throwable $e) {
            error_log("Email log error: " . $e->getMessage());
        }

        if (!$smtpEnabled) {
            // Native PHP mailer fallback
            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                "From: {$fromName} <{$fromEmail}>",
                "Reply-To: {$fromEmail}",
                'X-Mailer: PHP/' . phpversion()
            ];
            @mail($to, $subject, $htmlBody, implode("\r\n", $headers));
            return true;
        }

        // SMTP socket transmission
        $host = Setting::get('smtp_host', '');
        $port = (int)Setting::get('smtp_port', '587');
        $username = Setting::get('smtp_username', '');
        $password = Setting::get('smtp_password', '');
        $encryption = Setting::get('smtp_encryption', 'tls'); // 'tls', 'ssl', or 'none'

        if (empty($host)) {
            return false;
        }

        try {
            return self::smtpSend($host, $port, $username, $password, $encryption, $fromEmail, $fromName, $to, $subject, $htmlBody);
        } catch (\Throwable $e) {
            error_log("SMTP Error: " . $e->getMessage());
            return false;
        }
    }

    private static function smtpSend(
        string $host, int $port, string $username, string $password,
        string $encryption, string $fromEmail, string $fromName,
        string $to, string $subject, string $body
    ): bool {
        $prefix = ($encryption === 'ssl') ? 'ssl://' : '';
        $socket = @fsockopen($prefix . $host, $port, $errno, $errstr, 15);
        if (!$socket) {
            return false;
        }

        $read = function() use ($socket) {
            $data = '';
            while ($str = fgets($socket, 515)) {
                $data .= $str;
                if (substr($str, 3, 1) === ' ') break;
            }
            return $data;
        };

        $write = function(string $cmd) use ($socket) {
            fputs($socket, $cmd . "\r\n");
        };

        $read(); // initial greeting
        $write("EHLO " . gethostname());
        $read();

        if ($encryption === 'tls') {
            $write("STARTTLS");
            $res = $read();
            if (strpos($res, '220') !== 0) {
                fclose($socket);
                return false;
            }
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
            $write("EHLO " . gethostname());
            $read();
        }

        if (!empty($username) && !empty($password)) {
            $write("AUTH LOGIN");
            $read();
            $write(base64_encode($username));
            $read();
            $write(base64_encode($password));
            $authRes = $read();
            if (strpos($authRes, '235') !== 0) {
                fclose($socket);
                return false;
            }
        }

        $write("MAIL FROM: <{$fromEmail}>");
        $read();
        $write("RCPT TO: <{$to}>");
        $read();
        $write("DATA");
        $read();

        $headers = [
            "MIME-Version: 1.0",
            "Content-type: text/html; charset=UTF-8",
            "From: {$fromName} <{$fromEmail}>",
            "To: <{$to}>",
            "Subject: {$subject}",
            "Date: " . date('r'),
            "X-Mailer: DentalDoctorMailer/1.0"
        ];

        $message = implode("\r\n", $headers) . "\r\n\r\n" . $body . "\r\n.";
        $write($message);
        $res = $read();

        $write("QUIT");
        fclose($socket);

        return (strpos($res, '250') === 0);
    }
}
