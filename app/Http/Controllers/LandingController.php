<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentPeriod;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page of the recruitment system.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch the currently active recruitment period
        $activePeriod = RecruitmentPeriod::where('is_active', true)->first();

        return view('landing', compact('activePeriod'));
    }

    /**
     * Download template files.
     *
     * @param string $type
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadTemplate($type)
    {
        $templates = [
            'kesediaan-docx' => [
                'path' => public_path('templates/Template_Surat_Kesediaan_Mengajar.docx'),
                'name' => 'Template Surat Kesediaan Mengajar UPT PJJ.docx',
                'headers' => ['Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            ],
            'kesediaan-pdf' => [
                'path' => public_path('templates/Template_Surat_Kesediaan_Mengajar.pdf'),
                'name' => 'Template Surat Kesediaan Mengajar UPT PJJ.pdf',
                'headers' => ['Content-Type' => 'application/pdf'],
            ],
            'pakta-pdf' => [
                'path' => public_path('templates/Template_Pakta_Integritas.pdf'),
                'name' => 'Template Pakta Integritas Calon Tutor.pdf',
                'headers' => ['Content-Type' => 'application/pdf'],
            ],
        ];

        if (!isset($templates[$type]) || !file_exists($templates[$type]['path'])) {
            abort(404, 'File template tidak ditemukan.');
        }

        $item = $templates[$type];
        return response()->download($item['path'], $item['name'], $item['headers'] ?? []);
    }
}
