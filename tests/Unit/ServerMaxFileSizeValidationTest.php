<?php

namespace Tests\Unit;

use App\Rules\ServerMaxFileSize;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\TestCase;

class ServerMaxFileSizeValidationTest extends TestCase
{
    function testServerMaxFileSizeValidationPass()
    {
        $rule = new ServerMaxFileSize;
        $file = UploadedFile::fake()->create('fake.png',1000);
        $this->assertTrue($rule->passes('attribute', $file));
    }

    function testServerMaxFileSizeValidationFail()
    {
        $rule = new ServerMaxFileSize;
        $file = UploadedFile::fake()->create('fake.png',1000000);
        $this->assertFalse($rule->passes('attribute', $file));
    }
}
