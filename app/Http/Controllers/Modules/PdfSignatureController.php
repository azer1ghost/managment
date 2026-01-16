<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\PdfSignatureRequest;
use App\Services\PdfSignatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfSignatureController extends Controller
{
    protected $pdfSignatureService;

    public function __construct(PdfSignatureService $pdfSignatureService)
    {
        $this->pdfSignatureService = $pdfSignatureService;
    }

    /**
     * Show PDF signature upload form
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $signatures = $this->pdfSignatureService->getAvailableSignatures();
        $pythonCheck = $this->pdfSignatureService->checkPythonRequirements();

        return view('pages.finance.pdf-signature', compact('signatures', 'pythonCheck'));
    }

    /**
     * Handle PDF upload and signing
     *
     * @param PdfSignatureRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(PdfSignatureRequest $request)
    {
        try {
            $pdfFile = $request->file('pdf');
            $signatureName = $request->input('signature_name');

            // Validate signature name
            if (!$this->pdfSignatureService->validateSignatureName($signatureName)) {
                return redirect()
                    ->route('pdf-signature.index')
                    ->withErrors(['signature_name' => 'Seçilmiş imza faylı tapılmadı və ya etibarsızdır.'])
                    ->withInput();
            }

            // Store uploaded PDF
            $inputPdfPath = $this->pdfSignatureService->storeUploadedPdf($pdfFile);

            // Get signature path
            $signaturePath = $this->pdfSignatureService->getSignaturePath($signatureName);

            // Generate output filename and path
            $outputFilename = $this->pdfSignatureService->generateOutputFilename($pdfFile->getClientOriginalName());
            $outputPdfPath = $this->pdfSignatureService->getOutputPath($outputFilename);

            // Sign the PDF
            $this->pdfSignatureService->signPdf($inputPdfPath, $signaturePath, $outputPdfPath);

            // Clean up uploaded file
            $this->pdfSignatureService->cleanup($inputPdfPath);

            // Return download response
            return response()->download($outputPdfPath, $outputFilename, [
                'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Clean up on error
            if (isset($inputPdfPath)) {
                $this->pdfSignatureService->cleanup($inputPdfPath);
            }
            if (isset($outputPdfPath) && file_exists($outputPdfPath)) {
                $this->pdfSignatureService->cleanup($outputPdfPath);
            }

            return redirect()
                ->route('pdf-signature.index')
                ->withErrors(['error' => 'PDF imzalama zamanı xəta baş verdi: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
