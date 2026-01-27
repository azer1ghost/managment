<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class LogReaderService
{
    protected const SINGLE_FILE = 'laravel.log';
    protected const DAILY_PREFIX = 'laravel-';
    // Some servers/apps use capitalized prefix in logging path (Laravel.log → Laravel-YYYY-MM-DD.log)
    protected const DAILY_PREFIX_ALT = 'Laravel-';
    protected const PATTERN = '/^\[(?<date>.*)\]\s(?<env>\w+)\.(?<type>\w+):(?<message>.*)/m';

    /**
     * List log file identifiers for "Select Date" dropdown.
     *
     * Primary behaviour:
     * - Prefer Laravel defaults: laravel.log (single) and laravel-YYYY-MM-DD.log (daily)
     * - If none found, fall back to ANY *.log file in storage/logs (prod environments
     *   sometimes use custom names/channels).
     *
     * @return array<string>
     */
    public function getAvailableLogIdentifiers(): array
    {
        $identifiers = [];
        $dir = storage_path('logs');

        // 1) Default single file
        if (File::exists($dir . '/' . self::SINGLE_FILE)) {
            $identifiers[] = self::SINGLE_FILE;
        }

        // 2) Daily files (laravel-YYYY-MM-DD.log or Laravel-YYYY-MM-DD.log)
        $dailyLower = glob($dir . '/' . self::DAILY_PREFIX . '*.log') ?: [];
        $dailyUpper = glob($dir . '/' . self::DAILY_PREFIX_ALT . '*.log') ?: [];
        $dailyFiles = array_merge($dailyLower, $dailyUpper);

        if ($dailyFiles) {
            rsort($dailyFiles);
            foreach ($dailyFiles as $path) {
                $name = basename($path);
                // Case-insensitive: matches both laravel- and Laravel-
                if (preg_match('/^laravel-(.+)\.log$/i', $name, $m)) {
                    $identifiers[] = $m[1]; // just the date part
                }
            }
        }

        // 3) Fallback: if nothing found, include any *.log files (custom log channels)
        if ($identifiers === []) {
            $all = glob($dir . '/*.log') ?: [];
            sort($all);
            foreach ($all as $path) {
                $identifiers[] = basename($path);
            }
        }

        // Remove duplicates and normalize keys
        return array_values(array_unique($identifiers));
    }

    /**
     * Resolve identifier to log file path.
     *
     * Supported identifiers:
     * - 'laravel.log'          → storage/logs/laravel.log
     * - 'YYYY-MM-DD'           → storage/logs/laravel-YYYY-MM-DD.log
     * - any other string (e.g. 'custom.log') → storage/logs/{identifier}
     */
    protected function resolvePath(string $identifier): string
    {
        if ($identifier === self::SINGLE_FILE) {
            return storage_path('logs/' . self::SINGLE_FILE);
        }

        // Identifier is a date value (from laravel-YYYY-MM-DD.log / Laravel-YYYY-MM-DD.log)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $identifier)) {
            $lower = storage_path('logs/' . self::DAILY_PREFIX . $identifier . '.log');
            if (File::exists($lower)) {
                return $lower;
            }
            // Fallback to capitalized prefix
            return storage_path('logs/' . self::DAILY_PREFIX_ALT . $identifier . '.log');
        }

        // Fallback: treat identifier as full filename (e.g. custom.log)
        return storage_path('logs/' . $identifier);
    }

    /**
     * Resolve identifier to display filename.
     */
    protected function resolveFilename(string $identifier): string
    {
        if ($identifier === self::SINGLE_FILE) {
            return self::SINGLE_FILE;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $identifier)) {
            // Prefer actual existing filename if possible
            $lower = self::DAILY_PREFIX . $identifier . '.log';
            if (File::exists(storage_path('logs/' . $lower))) {
                return $lower;
            }

            $upper = self::DAILY_PREFIX_ALT . $identifier . '.log';
            if (File::exists(storage_path('logs/' . $upper))) {
                return $upper;
            }

            // Fallback to default lowercase naming
            return $lower;
        }

        // For custom names we keep identifier as-is
        return $identifier;
    }

    /**
     * Read and parse log file by identifier. Returns API-shaped array.
     *
     * @param string|null $identifier  'laravel.log', 'YYYY-MM-DD', or null for first available
     * @return array{success: bool, message?: string, data?: array}
     */
    public function getLogs(?string $identifier = null): array
    {
        $available = $this->getAvailableLogIdentifiers();

        if ($available === []) {
            return [
                'success' => false,
                'message' => 'No log available. Ensure LOG_CHANNEL writes to storage/logs (e.g. stack → single or daily).',
            ];
        }

        if ($identifier === null || $identifier === '') {
            $identifier = $available[0];
        }

        if (!in_array($identifier, $available, true)) {
            return [
                'success' => false,
                'message' => 'Log file not found for selected date: ' . $identifier,
            ];
        }

        $path = $this->resolvePath($identifier);

        if (!File::exists($path)) {
            return [
                'success' => false,
                'message' => 'Log file not found: ' . basename($path),
            ];
        }

        if (!is_readable($path)) {
            return [
                'success' => false,
                'message' => 'Log file not readable (check permissions): ' . basename($path),
            ];
        }

        $content = @file_get_contents($path);
        if ($content === false) {
            return [
                'success' => false,
                'message' => 'Could not read log file: ' . basename($path),
            ];
        }

        $logs = $this->parseContent($content);
        $filename = $this->resolveFilename($identifier);

        return [
            'success' => true,
            'data' => [
                'available_log_dates' => $available,
                'date' => $identifier,
                'filename' => $filename,
                'logs' => $logs,
            ],
        ];
    }

    /**
     * Parse log content. Monolog-style lines become structured entries; other lines become raw fallback.
     *
     * @return array<int, array{timestamp: string, env: string, type: string, message: string}>
     */
    protected function parseContent(string $content): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $content);
        $logs = [];
        $pattern = self::PATTERN;

        foreach ($lines as $line) {
            $line = rtrim($line);
            if ($line === '') {
                continue;
            }
            if (preg_match($pattern, $line, $m)) {
                $logs[] = [
                    'timestamp' => $m['date'],
                    'env' => $m['env'],
                    'type' => $m['type'],
                    'message' => trim($m['message']),
                ];
            } else {
                // Stacktrace və əlavə xətlər: ayrı sətir kimi yox, əvvəlki logun içində göstər
                if (!empty($logs) && (
                    strpos($line, '#') === 0 ||
                    $line === '[stacktrace]' ||
                    $line === '{main}' ||
                    strpos($line, 'Stack trace:') === 0
                )) {
                    $lastIndex = count($logs) - 1;
                    $logs[$lastIndex]['message'] .= "\n" . $line;
                    continue;
                }

                $logs[] = [
                    'timestamp' => '-',
                    'env' => '-',
                    'type' => 'RAW',
                    'message' => $line,
                ];
            }
        }

        return $logs;
    }

    /**
     * Delete a specific log file or clear all .log files (for POST delete/clear).
     */
    public function deleteFile(string $filename): bool
    {
        $path = storage_path('logs/' . $filename);
        if (!File::exists($path) || substr($filename, -4) !== '.log') {
            return false;
        }
        return File::delete($path);
    }

    /**
     * Delete all .log files in storage/logs.
     */
    public function clearAll(): bool
    {
        $files = glob(storage_path('logs/*.log'));
        foreach ($files as $path) {
            @unlink($path);
        }
        return true;
    }
}
