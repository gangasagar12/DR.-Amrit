<?php
namespace App\Helpers;

class Uploader {
    public static function uploadImage(array $file, string $subfolder = 'general'): array {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $maxSize = $config['upload_max_size'] ?? 5242880; // 5MB
        $allowedTypes = $config['allowed_image_types'] ?? ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $allowedExtensions = $config['allowed_image_extensions'] ?? ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        // 1. Check upload error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => self::getErrorMessage($file['error'])];
        }

        // 2. Check file size
        if ($file['size'] > $maxSize) {
            $mb = round($maxSize / (1024 * 1024));
            return ['success' => false, 'message' => "File exceeds maximum allowed size of {$mb}MB."];
        }

        // 3. Verify extension
        $origName = $file['name'];
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions, true)) {
            return ['success' => false, 'message' => 'Invalid file extension. Allowed: ' . implode(', ', $allowedExtensions)];
        }

        // 4. Verify MIME type using finfo
        $tmpPath = $file['tmp_name'];
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmpPath);
            finfo_close($finfo);
        } else {
            $mime = mime_content_type($tmpPath);
        }

        if (!in_array($mime, $allowedTypes, true)) {
            return ['success' => false, 'message' => 'Invalid image MIME type: ' . htmlspecialchars($mime)];
        }

        // 5. Check if it's a valid image using getimagesize
        $imageInfo = @getimagesize($tmpPath);
        if ($imageInfo === false) {
            return ['success' => false, 'message' => 'The uploaded file is not a valid image.'];
        }

        // 6. Security scan: reject any embedded PHP code
        $contentSample = file_get_contents($tmpPath, false, null, 0, 1024);
        if (stripos($contentSample, '<?php') !== false || stripos($contentSample, '<?=') !== false || stripos($contentSample, '<script') !== false) {
            return ['success' => false, 'message' => 'Malicious file signature detected. Upload rejected.'];
        }

        // 7. Prepare target directory
        $safeSubfolder = preg_replace('/[^a-zA-Z0-9_\-]/', '', $subfolder);
        $targetDir = $config['public_dir'] . '/uploads/' . $safeSubfolder;

        if (!is_dir($targetDir)) {
            if (!@mkdir($targetDir, 0755, true)) {
                return ['success' => false, 'message' => 'Failed to create upload destination directory.'];
            }
        }

        // 8. Generate unique random filename
        $newFilename = bin2hex(random_bytes(16)) . '.' . $ext;
        $destination = $targetDir . '/' . $newFilename;

        if (!move_uploaded_file($tmpPath, $destination)) {
            return ['success' => false, 'message' => 'Failed to save uploaded file.'];
        }

        // Return public relative path
        $relativePath = 'uploads/' . $safeSubfolder . '/' . $newFilename;
        return [
            'success' => true,
            'path' => $relativePath,
            'filename' => $newFilename
        ];
    }

    public static function uploadPdf(array $file, string $subfolder = 'documents'): array {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $maxSize = 10485760; // 10MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => self::getErrorMessage($file['error'])];
        }

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => "Document exceeds maximum allowed size of 10MB."];
        }

        $origName = $file['name'];
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            return ['success' => false, 'message' => 'Only PDF files are allowed for publication documents.'];
        }

        $tmpPath = $file['tmp_name'];
        $safeSubfolder = preg_replace('/[^a-zA-Z0-9_\-]/', '', $subfolder);
        $targetDir = $config['public_dir'] . '/uploads/' . $safeSubfolder;

        if (!is_dir($targetDir)) {
            if (!@mkdir($targetDir, 0755, true)) {
                return ['success' => false, 'message' => 'Failed to create upload destination directory.'];
            }
        }

        $newFilename = bin2hex(random_bytes(16)) . '.pdf';
        $destination = $targetDir . '/' . $newFilename;

        if (!move_uploaded_file($tmpPath, $destination)) {
            return ['success' => false, 'message' => 'Failed to save uploaded document.'];
        }

        return [
            'success' => true,
            'path' => 'uploads/' . $safeSubfolder . '/' . $newFilename,
            'filename' => $newFilename
        ];
    }

    private static function getErrorMessage(int $code): string {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize limit in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE specified in HTML form.',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
            default => 'Unknown upload error.'
        };
    }
}
