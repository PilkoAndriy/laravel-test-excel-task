<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUploadedFile;
use App\Imports\ProductImport;
use App\Services\ExcelDataFormat;
use App\Services\ExcelDataStore;
use App\Services\ExcelService;
use Maatwebsite\Excel\Facades\Excel;

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
            Excel::import(new ProductImport(new ExcelDataFormat() , new ExcelDataStore()), $filename);
            //TODO Remove local file

        }
        return back()->with('success', __('File uploaded'));
    }
}
