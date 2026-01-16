<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Str;

class PdfSignatureService
{
    private const SIGNATURE_DIR = 'public/assets/images/finance';
    private const UPLOAD_DIR = 'uploads/pdf-sign';
    private const OUTPUT_DIR = 'signed';

    /**
     * Get list of available signature files (PNG and JPG)
     *
     * @return array
     */
    public function getAvailableSignatures(): array
    {
        $signaturePath = public_path('assets/images/finance');
        
        if (!File::exists($signaturePath)) {
            return [];
        }

        $files = File::files($signaturePath);
        $signatures = [];

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());
            if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                $signatures[] = $file->getFilename();
            }
        }

        sort($signatures);
        return $signatures;
    }

    /**
     * Validate signature filename (prevent path traversal)
     *
     * @param string $signatureName
     * @return bool
     */
    public function validateSignatureName(string $signatureName): bool
    {
        // Only allow basename (no path separators)
        if (strpos($signatureName, '/') !== false || strpos($signatureName, '\\') !== false) {
            return false;
        }

        // Check if file exists in signature directory
        $signaturePath = public_path('assets/images/finance/' . $signatureName);
        
        if (!File::exists($signaturePath)) {
            return false;
        }

        // Ensure it's a valid image extension
        $extension = strtolower(pathinfo($signatureName, PATHINFO_EXTENSION));
        return in_array($extension, ['png', 'jpg', 'jpeg']);
    }

    /**
     * Get full path to signature file
     *
     * @param string $signatureName
     * @return string
     */
    public function getSignaturePath(string $signatureName): string
    {
        return public_path('assets/images/finance/' . $signatureName);
    }

    /**
     * Store uploaded PDF temporarily
     *
     * @param UploadedFile $file
     * @return string Path to stored file
     */
    public function storeUploadedPdf(UploadedFile $file): string
    {
        // Ensure upload directory exists
        $uploadDir = storage_path('app/' . self::UPLOAD_DIR);
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $fullPath = $uploadDir . '/' . $filename;
        
        // Move uploaded file directly
        if (!$file->move($uploadDir, $filename)) {
            throw new \Exception('PDF faylı yadda saxlanıla bilmədi: ' . $fullPath);
        }
        
        // Verify file was actually stored
        if (!File::exists($fullPath)) {
            throw new \Exception('PDF faylı yadda saxlanıla bilmədi: ' . $fullPath);
        }
        
        return $fullPath;
    }

    /**
     * Generate output filename for signed PDF
     *
     * @param string $originalFilename
     * @return string
     */
    public function generateOutputFilename(string $originalFilename): string
    {
        $nameWithoutExt = pathinfo($originalFilename, PATHINFO_FILENAME);
        $timestamp = time();
        return "{$nameWithoutExt}_signed_{$timestamp}.pdf";
    }

    /**
     * Get output path for signed PDF
     *
     * @param string $filename
     * @return string
     */
    public function getOutputPath(string $filename): string
    {
        $outputDir = storage_path('app/' . self::OUTPUT_DIR);
        
        // Ensure output directory exists
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        $fullPath = $outputDir . '/' . $filename;
        
        // Ensure parent directory exists
        $parentDir = dirname($fullPath);
        if (!File::exists($parentDir)) {
            File::makeDirectory($parentDir, 0755, true);
        }

        return $fullPath;
    }

    /**
     * Detect Python command (python3 or python)
     *
     * @return string|null
     */
    private function detectPythonCommand(): ?string
    {
        // Try python3 first
        $python3Check = new Process(['python3', '--version']);
        $python3Check->run();
        
        if ($python3Check->isSuccessful()) {
            return 'python3';
        }
        
        // Fallback to python
        $pythonCheck = new Process(['python', '--version']);
        $pythonCheck->run();
        
        if ($pythonCheck->isSuccessful()) {
            return 'python';
        }
        
        return null;
    }

    /**
     * Check if Python and required packages are available
     *
     * @return array ['available' => bool, 'message' => string, 'python_cmd' => string|null]
     */
    public function checkPythonRequirements(): array
    {
        $pythonCmd = $this->detectPythonCommand();
        
        if (!$pythonCmd) {
            return [
                'available' => false,
                'message' => 'Python quraşdırılmayıb. Zəhmət olmasa Python3 quraşdırın.',
                'python_cmd' => null
            ];
        }

        // Check if PyMuPDF is installed
        $checkScript = "import sys; import fitz; sys.exit(0)";
        $pymupdfCheck = new Process([$pythonCmd, '-c', $checkScript]);
        $pymupdfCheck->run();
        
        if (!$pymupdfCheck->isSuccessful()) {
            return [
                'available' => false,
                'message' => 'PyMuPDF paketi quraşdırılmayıb. Zəhmət olmasa quraşdırın: pip install pymupdf',
                'python_cmd' => $pythonCmd
            ];
        }

        // Check if Pillow is installed
        $pillowCheck = new Process([$pythonCmd, '-c', "import sys; from PIL import Image; sys.exit(0)"]);
        $pillowCheck->run();
        
        if (!$pillowCheck->isSuccessful()) {
            return [
                'available' => false,
                'message' => 'Pillow paketi quraşdırılmayıb. Zəhmət olmasa quraşdırın: pip install pillow',
                'python_cmd' => $pythonCmd
            ];
        }

        return [
            'available' => true,
            'message' => 'Bütün tələblər yerinə yetirilib.',
            'python_cmd' => $pythonCmd
        ];
    }

    /**
     * Sign PDF using Python script
     *
     * @param string $inputPdfPath
     * @param string $signaturePath
     * @param string $outputPdfPath
     * @return void
     * @throws \Exception
     */
    public function signPdf(string $inputPdfPath, string $signaturePath, string $outputPdfPath): void
    {
        $pythonScript = app_path('Services/PdfSignature/sign_pdf.py');

        if (!File::exists($pythonScript)) {
            throw new \Exception('Python script not found: ' . $pythonScript);
        }

        if (!File::exists($inputPdfPath)) {
            throw new \Exception('Input PDF not found: ' . $inputPdfPath);
        }

        if (!File::exists($signaturePath)) {
            throw new \Exception('Signature file not found: ' . $signaturePath);
        }

        // Check Python requirements before proceeding
        $requirements = $this->checkPythonRequirements();
        if (!$requirements['available']) {
            throw new \Exception($requirements['message']);
        }

        $pythonCmd = $requirements['python_cmd'] ?? 'python3';

        // Ensure output directory exists
        $outputDir = dirname($outputPdfPath);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Build command (Process handles escaping automatically)
        $command = [
            $pythonCmd,
            $pythonScript,
            '--input', $inputPdfPath,
            '--sig', $signaturePath,
            '--output', $outputPdfPath,
        ];

        $process = new Process($command);
        $process->setTimeout(60); // 60 seconds timeout
        $process->run();

        if (!$process->isSuccessful()) {
            $error = $process->getErrorOutput();
            $output = $process->getOutput();
            
            // Provide more helpful error messages
            if (strpos($error, 'PyMuPDF') !== false || strpos($error, 'fitz') !== false) {
                throw new \Exception('PyMuPDF paketi quraşdırılmayıb. Zəhmət olmasa quraşdırın: pip install pymupdf');
            }
            
            if (strpos($error, 'Pillow') !== false || strpos($error, 'PIL') !== false) {
                throw new \Exception('Pillow paketi quraşdırılmayıb. Zəhmət olmasa quraşdırın: pip install pillow');
            }
            
            // Combine error and output for debugging
            $fullError = trim($error . "\n" . $output);
            throw new \Exception('PDF imzalama zamanı xəta baş verdi: ' . $fullError);
        }

        if (!File::exists($outputPdfPath)) {
            throw new \Exception('Signed PDF was not created at: ' . $outputPdfPath);
        }
    }

    /**
     * Clean up temporary files
     *
     * @param string $filePath
     * @return void
     */
    public function cleanup(string $filePath): void
    {
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }
}
