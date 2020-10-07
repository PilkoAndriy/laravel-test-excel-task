<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUpload()
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

        $this->json('POST', route('upload.excel'),
            ['file' => $uploadedFile]
        )->assertStatus(302);

        // Assert the file was stored...
        $this->assertNotEquals([], Storage::allFiles());
    }
}
