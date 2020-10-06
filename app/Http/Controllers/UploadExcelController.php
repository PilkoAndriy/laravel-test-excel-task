<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUploadedFile;
use App\Services\ExcelService;

class UploadExcelController extends Controller
{
    /**
     * @var ExcelService
     */
    protected $excelService;

    /**
     * UploadExcelController constructor.
     * @param ExcelService $excelService
     */
    public function __construct(ExcelService $excelService)
    {
        $this->excelService = $excelService;
    }

    /**
     * @param StoreUploadedFile $request
     * @return mixed
     */
    public function store(StoreUploadedFile $request)
    {
        if($request->hasFile('file')){
            $filename = $this->excelService->store($request->file);
            //TODO Excel compiled
        }
        return back()->with('success', __('File uploaded'));
    }
}
