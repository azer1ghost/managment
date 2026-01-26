<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class LogReaderService
{
    protected const SINGLE_FILE = 'laravel.log';
    protected const DAILY_PREFIX = 'laravel-';
    protected const PATTERN = '/^\[(?<date>.*)\]\s(?<env>\w+)\.(?<type>\w+):(?<message>.*)/m';

    /**
     * List log file identifiers for "Select Date" dropdown.
     * Includes laravel.log (single) and laravel-YYYY-MM-DD.log (daily), most recent first.
     *
     * @return array<string>
     */
    public function getAvailableLogIdentifiers(): array
    {
        $identifiers = [];
        $dir = storage_path('logs');

        if (File::exists($dir . '/' . self::SINGLE_FILE)) {
            $identifiers[] = self::SINGLE_FILE;
        }

        $daily = glob($dir . '/' . self::DAILY_PREFIX . '*.log');
        if ($daily !== false) {
            rsort($daily);
            foreach ($daily as $path) {
                $name = basename($path);
                if (preg_match('/^laravel-(.+)\.log$/', $name, $m)) {
                    $identifiers[] = $m[1];
                }
            }
        }

        return $identifiers;
    }

    /**
     * Resolve identifier (laravel.log or YYYY-MM-DD) to log file path.
     */
    protected function resolvePath(string $identifier): string
    {
        if ($identifier === self::SINGLE_FILE) {
            return storage_path('logs/' . self::SINGLE_FILE);
        }
        return storage_path('logs/' . self::DAILY_PREFIX . $identifier . '.log');
    }

    /**
     * Resolve identifier to display filename.
     */
    protected function resolveFilename(string $identifier): string
    {
        if ($identifier === self::SINGLE_FILE) {
            return self::SINGLE_FILE;
        }
        return self::DAILY_PREFIX . $identifier . '.log';
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
