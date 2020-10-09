<?php

namespace Tests\Unit;

use App\Services\Excel\ExcelFileStoreService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExcelFileStoreServiceTest extends TestCase
{
    protected $excelFileStore;

    public function setUp(): void
    {
        parent::setUp();
        $this->excelFileStore = new ExcelFileStoreService();

    }

    public function testStore()
    {
        Storage::fake('local');
        // Assert storage is empty.
        $this->assertEquals([], Storage::allFiles());
        $uploadedFile = new UploadedFile(
            base_path('tests/files/myfile.xlsx'),
            'myfile.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $this->excelFileStore->store($uploadedFile);

        // Assert the file was stored...
        $this->assertNotEquals([], Storage::allFiles());
    }
}
