<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaperUpdateService;
use Illuminate\Support\Facades\Log;

class UpdatePaperController extends Controller
{
    protected $paperUpdateService;

    public function __construct(PaperUpdateService $paperUpdateService)
    {
        $this->paperUpdateService = $paperUpdateService;
    }

    public function updatePaperData()
    {
        Log::info('Starting paper update process via controller.');
        
        // เรียกใช้ service เพื่ออัปเดตข้อมูล paper
        $this->paperUpdateService->updatePaperData();

        Log::info('Paper update process completed via controller.');

        return response()->json(['message' => 'Paper data update completed']);
    }
}
