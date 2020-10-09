<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUploadedFile;
use App\Imports\ProductImport;
use App\Services\Excel\ExcelDataFormat;
use App\Services\Excel\ExcelDataStore;
use App\Services\Excel\ExcelFileStoreService;
use Maatwebsite\Excel\Facades\Excel;

class UploadExcelController extends Controller
{
    /**
     * @var ExcelFileStoreService
     */
    protected $excelFileStore;
    /**
     * @var ExcelDataFormat
     */
    protected $excelDataFormat;
    /**
     * @var ExcelDataStore
     */
    protected $excelDataStorage;

    /**
     * UploadExcelController constructor.
     * @param ExcelFileStoreService $excelFileStore
     * @param ExcelDataFormat $excelDataFormat
     * @param ExcelDataStore $excelDataStore
     */
    public function __construct(ExcelFileStoreService $excelFileStore, ExcelDataFormat  $excelDataFormat, ExcelDataStore $excelDataStore)
    {
        $this->excelFileStore = $excelFileStore;
        $this->excelDataFormat = $excelDataFormat;
        $this->excelDataStorage = $excelDataStore;
    }

    /**
     * @param StoreUploadedFile $request
     * @return mixed
     */
    public function store(StoreUploadedFile $request)
    {
        if($request->hasFile('file')){
            $filename = $this->excelFileStore->store($request->file);
            Excel::import(new ProductImport($this->excelDataFormat, $this->excelDataStorage), $filename);
            //TODO Remove local file

        }
        return back()->with('success', __('File uploaded'));
    }
}
