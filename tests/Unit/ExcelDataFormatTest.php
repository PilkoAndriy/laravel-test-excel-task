<?php

namespace Tests\Unit;

use App\Services\Excel\ExcelDataFormat;
use Tests\TestCase;

class ExcelDataFormatTest extends TestCase
{
    protected $excelDataFormat;

    protected function setUp(): void
    {
        parent::setUp();
        $this->excelDataFormat = new ExcelDataFormat();
    }


    public function testFormat()
    {
        $testArray = $this->generateTestArray();

        $this->excelDataFormat->format($testArray);
        $resultWithoutAllBroken = $this->excelDataFormat->getFormattedData();
        $this->excelDataFormat->fixedBrokenData();
        $resultWithFixedBroken = $this->excelDataFormat->getFormattedData();
        $this->excelDataFormat->setSessionFailedRowInfo();
        $sessionValue = session()->get('excel.export.error');

        $this->assertCount(16,$testArray);
        $this->assertCount(3, $resultWithoutAllBroken['brands']);
        $this->assertCount(5, $resultWithoutAllBroken['categories']);
        $this->assertCount(12, $resultWithoutAllBroken['products']);
        $this->assertCount(4, $resultWithFixedBroken['brands']);
        $this->assertCount(6, $resultWithFixedBroken['categories']);
        $this->assertCount(14, $resultWithFixedBroken['products']);
        $this->withSession(['excel.export.error']);
        $this->assertIsArray($sessionValue);
        $this->assertEquals($sessionValue['category'], 2);
        $this->assertEquals($sessionValue['category_child'], 0);
    }

    protected function generateTestArray()
    {
        return [
            ['test1','test2','test3','test4','test5','test6','test7','test8','test9','test10'],
            ['test1','test2','test3','test4','test5','test6','test7','test8','test9','test10'],
            ['test1','test2','test3','test4','test5','test6','test7','test8','test9','test10'],
            ['test1','test2','test3','test4','test5','test6','test7','test8','test9','test10'],
            [null,'broken','broken','broken','broken','broken','broken','broken','broken','broken'],
            [null,'broken','broken','broken','broken','broken','broken','broken','broken','broken'],
            [null,'fixed','fixed','fixed','fixed','fixed','fixed','fixed','fixed','fixed','fixed'],
            [null,'fixed','fixed','fixed','fixed','fixed','fixed','fixed','fixed','fixed','fixed'],
            ['first','first','first','first','first','first','first','first','first','first'],
            ['first','first','first','first','first','first','first','first','first','first'],
            ['first','first','first','first','first','first','first','first','first','first'],
            ['first','first','first','first','first','first','first','first','first','first'],
            ['second','second','second','second','second','second','second','second','second','second'],
            ['second','second','second','second','second','second','second','second','second','second'],
            ['second','second','second','second','second','second','second','second','second','second'],
            ['second','second','second','second','second','second','second','second','second','second'],
        ];
    }
}
